<?php
session_start();
require_once '../config/db.php'; // Include db.php for database connection
require_once '../config/functions.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit;
}

$conn = getDbConnection(); // Get database connection
if (!$conn) {
    echo "<div class='alert alert-danger' role='alert'>Database connection failed.</div>";
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

$allRooms = [];
try {
    $stmt = $conn->query("SELECT id, room_name, room_type, price, description, image, status, location_description FROM rooms WHERE status = 'available'");
    $allRooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error fetching all rooms: " . $e->getMessage());
    echo "<div class='alert alert-danger' role='alert'>Could not retrieve rooms at this time.</div>";
    exit;
}

if (empty($allRooms)) {
    $_SESSION['error_message'] = "No rooms available for booking.";
    header('Location: ../rooms.php');
    exit;
}

$selectedRoomId = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT) : $allRooms[0]['id'];

$room = null;
foreach ($allRooms as $r) {
    if ($r['id'] == $selectedRoomId) {
        $room = $r;
        break;
    }
}

// If selected room is not available or invalid, redirect to show all rooms or first available
if (!$room) {
    $_SESSION['error_message'] = "The selected room is not available.";
    header('Location: book-room.php?id=' . $allRooms[0]['id']); // Redirect to the first available room
    exit;
}

$foods = select("SELECT * FROM food_menu ORDER BY food_name ASC");
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $checkIn = $_POST['check_in'];
    $checkOut = $_POST['check_out'];
    $selectedFoods = $_POST['foods'] ?? [];
    $selectedRoomIdPost = filter_var($_POST['room_id'], FILTER_SANITIZE_NUMBER_INT); // Get room ID from form

    // Validate that the room selected in the form is still available
    $room = null;
    foreach ($allRooms as $r) {
        if ($r['id'] == $selectedRoomIdPost && $r['status'] == 'available') {
            $room = $r;
            break;
        }
    }

    if (!$room) {
        $message = "The room you selected is no longer available.";
    } elseif (empty($checkIn) || empty($checkOut)) {
        $message = "Please select check-in and check-out dates.";
    } elseif ($checkIn >= $checkOut) {
        $message = "Check-out date must be after the check-in date.";
    } else {
        // --- Calculation ---
        $checkinDate = new DateTime($checkIn);
        $checkoutDate = new DateTime($checkOut);
        $nights = $checkoutDate->diff($checkinDate)->days;
        $roomTotal = $nights * $room['price'];
        
        $foodTotal = 0;
        if (!empty($selectedFoods)) {
            foreach ($selectedFoods as $foodId => $quantity) {
                if ($quantity > 0) {
                    $foodItem = select("SELECT price FROM food_menu WHERE id = ?", [$foodId], true);
                    if ($foodItem) {
                        $foodTotal += $foodItem['price'] * $quantity;
                    }
                }
            }
        }
        
        $grandTotal = $roomTotal + $foodTotal;

        // --- Database Insertion ---
        // Assuming `execute` function from `functions.php` can handle prepared statements and return last insert ID
        $bookingSql = "INSERT INTO bookings (user_id, room_id, check_in, check_out, total_amount, payment_status) VALUES (?, ?, ?, ?, ?, 'pending')";
        $bookingId = execute($bookingSql, [$user['id'], $room['id'], $checkIn, $checkOut, $grandTotal], true); // Use $room['id'] from the validated room

        if ($bookingId) {
            if (!empty($selectedFoods)) {
                foreach ($selectedFoods as $foodId => $quantity) {
                     if ($quantity > 0) {
                        execute("INSERT INTO booking_foods (booking_id, food_id, quantity) VALUES (?, ?, ?)", [$bookingId, $foodId, $quantity]);
                    }
                }
            }
            // Change room status to booked
            execute("UPDATE rooms SET status = 'booked' WHERE id = ?", [$room['id']]); // Use $room['id'] from the validated room
            
            header("Location: payment.php?booking_id=" . $bookingId);
            exit;
        } else {
            $message = "Failed to create booking. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Room - <?php echo htmlspecialchars($site_name); ?></title>
    <link rel="shortcut icon" href="../asset/image/<?php echo htmlspecialchars($favicon); ?>" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-100">

<div class="flex h-screen bg-gray-100">
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-md">
        <div class="h-20 flex items-center justify-center">
            <h1 class="text-2xl font-bold text-blue-600"><?php echo htmlspecialchars($site_name); ?></h1>
        </div>
        <nav class="mt-5">
            <a href="dashboard.php" class="flex items-center mt-4 py-2 px-6 text-gray-600 hover:bg-gray-200">
                <i class="fas fa-th-large mr-3"></i> Dashboard
            </a>
            <a href="my-bookings.php" class="flex items-center mt-4 py-2 px-6 text-gray-600 hover:bg-gray-200">
                <i class="fas fa-calendar-alt mr-3"></i> My Bookings
            </a>
            <a href="profile.php" class="flex items-center mt-4 py-2 px-6 text-gray-600 hover:bg-gray-200">
                <i class="fas fa-user mr-3"></i> Profile
            </a>
             <a href="reward-points.php" class="flex items-center mt-4 py-2 px-6 text-gray-600 hover:bg-gray-200">
                <i class="fas fa-gift mr-3"></i> Reward Points
            </a>
            <a href="../logout.php" class="flex items-center mt-4 py-2 px-6 text-gray-600 hover:bg-gray-200">
                <i class="fas fa-sign-out-alt mr-3"></i> Logout
            </a>
        </nav>
    </aside>

    <!-- Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="flex justify-between items-center p-6 bg-white border-b-2 border-gray-200">
            <h2 class="text-2xl text-gray-700 font-semibold">Book Room: <?php echo htmlspecialchars($room['room_name']); ?></h2>
            <a href="../rooms.php" class="text-blue-500 hover:underline">&larr; Back to Rooms</a>
        </header>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
            <div class="max-w-4xl mx-auto">
                 <?php if ($message): ?>
                    <div class="mb-4 p-4 text-sm bg-red-100 text-red-700 rounded-lg">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>
                <form action="book-room.php?id=<?php echo $room['id']; ?>" method="POST">
                    <input type="hidden" name="room_id" value="<?php echo htmlspecialchars($room['id']); ?>">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Left Column: Booking Form -->
                        <div>
                             <div class="bg-white p-8 rounded-lg shadow-md">
                                <h3 class="text-xl font-semibold mb-6">Booking Details</h3>
                                <div class="mb-4">
                                    <label for="room_select" class="block text-gray-700 font-medium mb-2">Choose Your Room</label>
                                    <select id="room_select" name="room_select" class="w-full px-4 py-2 border rounded-lg" onchange="window.location.href='book-room.php?id=' + this.value">
                                        <?php foreach ($allRooms as $r): ?>
                                            <option value="<?php echo htmlspecialchars($r['id']); ?>" <?php echo ($r['id'] == $room['id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($r['room_name']); ?> (<?php echo htmlspecialchars($r['room_type']); ?>) - $<?php echo number_format($r['price'], 2); ?>/night
                                                <?php echo !empty($r['location_description']) ? ' (' . htmlspecialchars($r['location_description']) . ')' : ''; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label for="check_in" class="block text-gray-700 font-medium mb-2">Check-in Date</label>
                                    <input type="date" id="check_in" name="check_in" min="<?php echo date('Y-m-d'); ?>" class="w-full px-4 py-2 border rounded-lg" required>
                                </div>
                                <div class="mb-6">
                                    <label for="check_out" class="block text-gray-700 font-medium mb-2">Check-out Date</label>
                                    <input type="date" id="check_out" name="check_out" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" class="w-full px-4 py-2 border rounded-lg" required>
                                </div>
                                 <h3 class="text-xl font-semibold mb-4 mt-8">Add Food to Your Order</h3>
                                <div class="space-y-4 max-h-60 overflow-y-auto">
                                   <?php foreach ($foods as $food): ?>
                                       <div class="flex items-center justify-between">
                                           <div>
                                               <p class="font-semibold"><?php echo htmlspecialchars($food['food_name']); ?></p>
                                               <p class="text-sm text-gray-600">$<?php echo number_format($food['price'], 2); ?></p>
                                           </div>
                                           <input type="number" name="foods[<?php echo $food['id']; ?>]" min="0" value="0" class="w-20 px-2 py-1 border rounded-md">
                                       </div>
                                   <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <!-- Right Column: Room Details -->
                        <div class="bg-white p-8 rounded-lg shadow-md">
                             <img src="../asset/image/<?php echo htmlspecialchars($room['image']); ?>" alt="<?php echo htmlspecialchars($room['room_name']); ?>" class="w-full h-48 object-cover rounded-lg mb-6">
                            <h3 class="text-2xl font-bold text-gray-800"><?php echo htmlspecialchars($room['room_name']); ?></h3>
                            <p class="text-lg text-yellow-600 font-semibold my-2">$<?php echo number_format($room['price'], 2); ?> / night</p>
                            <p class="text-gray-600"><?php echo htmlspecialchars($room['description']); ?></p>
                            <?php if (!empty($room['location_description'])): ?>
                                <p class="text-gray-600 mt-2"><strong>Location:</strong> <?php echo htmlspecialchars($room['location_description']); ?></p>
                            <?php endif; ?>

                             <div class="mt-8">
                                <button type="submit" class="w-full bg-green-500 text-white font-bold py-3 px-6 rounded-lg hover:bg-green-600 transition shadow-lg">
                                    Confirm & Proceed to Payment
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>

</body>
</html>