<?php
/**
 * QuickCut – Chapa Payment Verification
 * 
 * Chapa redirects here after the user completes (or cancels) payment.
 * We NEVER trust the redirect alone — we call the Chapa verification API.
 * 
 * GET ?tx_ref=QC-xxx-xxx-xxx
 */

session_start();
require_once 'db.php';
require_once 'config/chapa.php';
require_once 'create_notification.php';

// ── Get tx_ref from query string ──────────────────────────────
$tx_ref = trim($_GET['tx_ref'] ?? '');

if (empty($tx_ref)) {
    header('Location: payment_failed.php?reason=missing_ref');
    exit;
}

// ── Sanitise tx_ref — only allow alphanumerics and hyphens ────
if (!preg_match('/^[A-Za-z0-9\-]+$/', $tx_ref)) {
    header('Location: payment_failed.php?reason=invalid_ref');
    exit;
}

// ── Look up payment record ────────────────────────────────────
$stmt = $pdo->prepare("SELECT * FROM payments WHERE transaction_ref = ? LIMIT 1");
$stmt->execute([$tx_ref]);
$payment = $stmt->fetch();

if (!$payment) {
    header('Location: payment_failed.php?reason=not_found');
    exit;
}

// ── Already processed? (idempotency guard) ───────────────────
if ($payment['status'] === 'paid') {
    header('Location: payment_success.php?tx_ref=' . urlencode($tx_ref));
    exit;
}
if ($payment['status'] === 'failed') {
    header('Location: payment_failed.php?tx_ref=' . urlencode($tx_ref));
    exit;
}

// ── Call Chapa Verification API ───────────────────────────────
$ch = curl_init(CHAPA_BASE_URL . '/transaction/verify/' . urlencode($tx_ref));
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER     => [
        'Authorization: Bearer ' . CHAPA_SECRET_KEY,
    ],
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_TIMEOUT        => 30,
]);

$verify_raw  = curl_exec($ch);
$curl_error  = curl_error($ch);
$http_code   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($curl_error || $verify_raw === false) {
    header('Location: payment_failed.php?reason=gateway_error&tx_ref=' . urlencode($tx_ref));
    exit;
}

$verify_resp = json_decode($verify_raw, true);
$chapa_status = $verify_resp['data']['status'] ?? 'failed';

$appointment_id = (int) $payment['appointment_id'];
$user_id        = (int) $payment['user_id'];

try {
    if ($chapa_status === 'success') {
        // ── Payment verified as successful ──
        $pdo->beginTransaction();

        $pdo->prepare(
            "UPDATE payments SET status='paid', chapa_response=? WHERE transaction_ref=?"
        )->execute([$verify_raw, $tx_ref]);

        $pdo->prepare(
            "UPDATE appointments SET status='waiting' WHERE id=? AND status='pending'"
        )->execute([$appointment_id]);

        // Notify user
        $appt_stmt = $pdo->prepare(
            "SELECT appointment_date, appointment_time, s.name AS service_name 
             FROM appointments a 
             JOIN services s ON a.service_id = s.id 
             WHERE a.id = ?"
        );
        $appt_stmt->execute([$appointment_id]);
        $appt = $appt_stmt->fetch();

        if ($appt) {
            $msg = "✅ Payment confirmed! Your appointment for {$appt['service_name']} on {$appt['appointment_date']} at {$appt['appointment_time']} is now confirmed.";
            create_notification($pdo, $user_id, $msg, 'payment');
        }

        $pdo->commit();
        header('Location: payment_success.php?tx_ref=' . urlencode($tx_ref));
        exit;

    } else {
        // ── Payment failed / pending / other ──
        $pdo->beginTransaction();

        $pdo->prepare(
            "UPDATE payments SET status='failed', chapa_response=? WHERE transaction_ref=?"
        )->execute([$verify_raw, $tx_ref]);

        $pdo->prepare(
            "UPDATE appointments SET status='cancelled' WHERE id=? AND status='pending'"
        )->execute([$appointment_id]);

        create_notification($pdo, $user_id, "❌ Payment for your appointment was not completed. The slot has been released.", 'payment');

        $pdo->commit();
        header('Location: payment_failed.php?tx_ref=' . urlencode($tx_ref) . '&appt_id=' . $appointment_id);
        exit;
    }

} catch (PDOException $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    header('Location: payment_failed.php?reason=db_error&tx_ref=' . urlencode($tx_ref));
    exit;
}
?>
