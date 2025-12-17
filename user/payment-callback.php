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
    if ($result->data->status === 'successful') {
        $meta = $result->data->meta;
        $order_type = $meta->order_type ?? 'room'; // Default to 'room' if not specified
        $chargedAmount = $result->data->charged_amount;
        $tx_ref_from_fw = $result->data->tx_ref; // Transaction reference from Flutterwave

        // Update session user info in case reward points were awarded
        $_SESSION['user'] = select("SELECT * FROM users WHERE id = ?", [$user['id']], true);

        if ($order_type === 'room') {
            $bookingId = $meta->booking_id;
            $booking = select("SELECT total_amount FROM bookings WHERE id = ? AND user_id = ?", [$bookingId, $user['id']], true);

            if (!$booking || $chargedAmount < $booking['total_amount']) {
                error_log("Payment verification failed: Amount mismatch or booking not found for room booking ID: {$bookingId}. Charged: {$chargedAmount}, Expected: {$booking['total_amount']}");
                header('Location: payment-failed.php?reason=invalid_amount_room');
                exit;
            }

            execute("UPDATE bookings SET payment_status = 'paid', transaction_ref = ? WHERE id = ?", [$tx_ref_from_fw, $bookingId]);

            $paymentSql = "INSERT INTO payments (booking_id, amount, method, status, reference, user_id, order_type) VALUES (?, ?, ?, ?, ?, ?, ?)";
            execute($paymentSql, [$bookingId, $chargedAmount, 'flutterwave', 'success', $tx_ref_from_fw, $user['id'], 'room']);
            
            $pointsToAdd = floor($chargedAmount / 10);
            if ($pointsToAdd > 0) {
                execute("UPDATE users SET reward_points = reward_points + ? WHERE id = ?", [$pointsToAdd, $user['id']]);
            }

            $_SESSION['success_booking_id'] = $bookingId;
            $_SESSION['success_message'] = "Your room booking payment was successful!";
            header('Location: payment-success.php');
            exit;

        } elseif ($order_type === 'food') {
            $bookingIdForDelivery = $meta->booking_id; // The room booking ID where food will be delivered

            if (!isset($_SESSION['food_cart']) || empty($_SESSION['food_cart'])) {
                error_log("Payment verification failed for food order: Empty food cart session for user ID: {$user['id']}.");
                header('Location: payment-failed.php?reason=empty_food_cart');
                exit;
            }

            $total_food_amount_calculated = 0;
            foreach ($_SESSION['food_cart'] as $item) {
                $total_food_amount_calculated += ($item['price'] * $item['quantity']);
            }

            if ($chargedAmount < $total_food_amount_calculated) {
                error_log("Payment verification failed: Amount mismatch for food order. Charged: {$chargedAmount}, Expected: {$total_food_amount_calculated}. User ID: {$user['id']}");
                header('Location: payment-failed.php?reason=invalid_amount_food');
                exit;
            }

            foreach ($_SESSION['food_cart'] as $item) {
                $sql_food = "INSERT INTO booking_foods (booking_id, food_id, quantity, payment_status, transaction_ref, user_id) VALUES (?, ?, ?, ?, ?, ?)";
                execute($sql_food, [$bookingIdForDelivery, $item['food_id'], $item['quantity'], 'paid', $tx_ref_from_fw, $user['id']]);
            }

            $paymentSql = "INSERT INTO payments (booking_id, amount, method, status, reference, user_id, order_type) VALUES (?, ?, ?, ?, ?, ?, ?)";
            execute($paymentSql, [$bookingIdForDelivery, $chargedAmount, 'flutterwave', 'success', $tx_ref_from_fw, $user['id'], 'food_order']);

            $pointsToAdd = floor($chargedAmount / 10);
            if ($pointsToAdd > 0) {
                execute("UPDATE users SET reward_points = reward_points + ? WHERE id = ?", [$pointsToAdd, $user['id']]);
            }

            unset($_SESSION['food_cart']);
            $_SESSION['success_message'] = "Your food order has been placed successfully and paid!";
            header('Location: payment-success.php');
            exit;
        }

    } else {
        error_log("Flutterwave transaction not successful or status is not 'successful'. Transaction ID: {$transactionId}");
        header('Location: payment-failed.php?reason=flutterwave_declined_or_failed');
        exit;
    }
} else {
    error_log("Flutterwave API response error or status is not 'success'. Error: {$err}, Response: {$response}");
    header('Location: payment-failed.php?reason=flutterwave_api_error');
    exit;
}
