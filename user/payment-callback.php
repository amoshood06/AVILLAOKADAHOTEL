<?php
session_start();
require_once '../config/functions.php';

if (!isset($_SESSION['user']) || !isset($_GET['transaction_id']) || !isset($_GET['tx_ref'])) {
    // Missing critical info, or user not logged in
    header('Location: my-bookings.php');
    exit;
}

$user = $_SESSION['user'];
$settings = getSiteSettings();
// Initialize with default values
$site_name = "My Hotel";
$favicon = "favicon.ico";
$logo = "logo.png";
$site_description = "Welcome to our hotel.";
$address = "123 Hotel St, City";
$phone = "123-456-7890";
$email = "info@hotel.com";
$facebook_link = "";
$instagram_link = "";
$twitter_link = "";
$whatsapp_link = "";


if (is_array($settings)) {
    $site_name = $settings['site_name'] ?? $site_name;
    $favicon = $settings['favicon'] ?? $favicon;
    $logo = $settings['logo'] ?? $logo;
    $site_description = $settings['site_description'] ?? $site_description;
    $address = $settings['address'] ?? $address;
    $phone = $settings['phone'] ?? $phone;
    $email = $settings['email'] ?? $email;
    $facebook_link = $settings['facebook_link'] ?? $facebook_link;
    $instagram_link = $settings['instagram_link'] ?? $instagram_link;
    $twitter_link = $settings['twitter_link'] ?? $twitter_link;
    $whatsapp_link = $settings['whatsapp_link'] ?? $whatsapp_link;
}
$transactionId = $_GET['transaction_id'];
$txRef = $_GET['tx_ref'];

// --- Verify Transaction with Flutterwave ---
$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.flutterwave.com/v3/transactions/{$transactionId}/verify",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        "Content-Type: application/json",
        "Authorization: Bearer " . $settings['flutterwave_secret_key']
    ),
));

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);

if ($err) {
    // cURL error
    header('Location: payment-failed.php?reason=curl_error');
    exit;
}

$result = json_decode($response);

if ($result && $result->status === 'success') {
    // --- Verification Checks ---
    $bookingId = $result->data->meta->booking_id;
    $chargedAmount = $result->data->charged_amount;
    $booking = select("SELECT total_amount FROM bookings WHERE id = ? AND user_id = ?", [$bookingId, $user['id']], true);

    if (!$booking || $chargedAmount < $booking['total_amount']) {
        // Amount mismatch or booking not found for this user
        header('Location: payment-failed.php?reason=invalid_amount');
        exit;
    }

    // --- Process Successful Payment ---
    // 1. Update booking status
    execute("UPDATE bookings SET payment_status = 'paid' WHERE id = ?", [$bookingId]);

    // 2. Log payment in payments table
    $paymentSql = "INSERT INTO payments (booking_id, amount, method, status, reference) VALUES (?, ?, ?, ?, ?)";
    execute($paymentSql, [$bookingId, $chargedAmount, 'flutterwave', 'success', $txRef]);
    
    // 3. Award reward points (e.g., 1 point per $10)
    $pointsToAdd = floor($chargedAmount / 10);
    if ($pointsToAdd > 0) {
        execute("UPDATE users SET reward_points = reward_points + ? WHERE id = ?", [$pointsToAdd, $user['id']]);
        $_SESSION['user']['reward_points'] += $pointsToAdd; // Update session
    }

    // Redirect to success page
    $_SESSION['success_booking_id'] = $bookingId;
    header('Location: payment-success.php');
    exit;

} else {
    // Transaction was not successful according to Flutterwave
    header('Location: payment-failed.php?reason=flutterwave_declined');
    exit;
}