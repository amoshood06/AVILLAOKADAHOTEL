<?php
require_once '../config/functions.php';
initSessionConfig();
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$room_id = $_GET['id'] ?? null;
$room = null;
$bookingMessage = '';

if ($room_id) {
    $room = select("SELECT * FROM rooms WHERE id = ?", [$room_id], true);
    if (!$room) {
        header('Location: ../rooms.php');
        exit;
    }
} else {
    header('Location: ../rooms.php');
    exit;
}

// Handle booking submission
if (isset($_POST['book_room'])) {
    $check_in_date = $_POST['check_in_date'];
    $check_out_date = $_POST['check_out_date'];

    // Basic validation
    if (empty($check_in_date) || empty($check_out_date)) {
        $bookingMessage = "Please select both check-in and check-out dates.";
    } elseif (strtotime($check_in_date) >= strtotime($check_out_date)) {
        $bookingMessage = "Check-out date must be after check-in date.";
    } elseif (strtotime($check_in_date) < strtotime(date('Y-m-d'))) {
        $bookingMessage = "Check-in date cannot be in the past.";
    } else {
        // Calculate number of nights
        $datetime1 = new DateTime($check_in_date);
        $datetime2 = new DateTime($check_out_date);
        $interval = $datetime1->diff($datetime2);
        $num_nights = $interval->days;

        $total_amount = $num_nights * $room['price'];

        // Check room availability
        // This query checks if there are any existing bookings for this room that overlap with the requested dates
        $availability_check_sql = "
            SELECT COUNT(*) AS count
            FROM bookings
            WHERE room_id = ?
            AND (
                (? < check_out AND ? > check_in)
            )
        ";
        $conflicting_bookings = select(
            $availability_check_sql,
            [$room_id, $check_in_date, $check_out_date],
            true
        )['count'];

        if ($conflicting_bookings > 0) {
            $bookingMessage = "This room is not available for the selected dates. Please choose different dates.";
        } else {
            // Room is available, proceed with booking
            $insert_sql = "INSERT INTO bookings (user_id, room_id, check_in, check_out, total_amount, payment_status) VALUES (?, ?, ?, ?, ?, 'pending')";
            $new_booking_id = execute($insert_sql, [$user_id, $room_id, $check_in_date, $check_out_date, $total_amount], true); // Pass true to get lastInsertId

            if ($new_booking_id !== false) { // Check if insertion was successful
                // Update room status to 'booked' (consider changing this based on payment status)
                // For now, let's assume it becomes booked immediately
                execute("UPDATE rooms SET status = 'booked' WHERE id = ?", [$room_id]);
                
                $_SESSION['booking_id_for_payment'] = $new_booking_id; // Store for payment page
                header('Location: payment.php?booking_id=' . $new_booking_id); // Redirect to payment page
                exit;
            } else {
                $bookingMessage = "Failed to book room. Please try again.";
            }
        }
    }
}

$pageTitle = "Book " . htmlspecialchars($room['room_name']);
require_once 'partials/header_user.php';
?>

<div class="flex-1 flex flex-col overflow-hidden">
    <header class="flex justify-between items-center p-6 bg-white border-b-2 border-gray-200">
        <h2 class="text-2xl text-gray-700 font-semibold">Book <?php echo htmlspecialchars($room['room_name']); ?></h2>
    </header>

    <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
        <div class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow-md">
            <?php if ($bookingMessage): ?>
                <div class="mb-4 p-3 text-sm <?php echo strpos($bookingMessage, 'successfully') !== false ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?> rounded-lg">
                    <?php echo $bookingMessage; ?>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <img src="../asset/image/<?php echo htmlspecialchars($room['image']); ?>" alt="<?php echo htmlspecialchars($room['room_name']); ?>" class="w-full h-48 object-cover rounded-lg">
                </div>
                <div>
                    <h3 class="text-xl font-semibold mb-2"><?php echo htmlspecialchars($room['room_name']); ?></h3>
                    <p class="text-gray-600 mb-4"><?php echo nl2br(htmlspecialchars($room['description'])); ?></p>
                    <p class="text-2xl font-bold text-yellow-600">₦<span id="room-price"><?php echo number_format($room['price'], 2); ?></span> / night</p>
                </div>
            </div>

            <form action="book-room.php?id=<?php echo $room['id']; ?>" method="POST">
                <div class="mb-4">
                    <label for="check_in_date" class="block text-gray-700 font-medium mb-2">Check-in Date</label>
                    <input type="date" id="check_in_date" name="check_in_date" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required min="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="mb-4">
                    <label for="check_out_date" class="block text-gray-700 font-medium mb-2">Check-out Date</label>
                    <input type="date" id="check_out_date" name="check_out_date" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="mb-6">
                    <p class="text-lg font-semibold text-gray-800">Number of Nights: <span id="num_nights">0</span></p>
                    <p class="text-lg font-semibold text-gray-800">Total Amount: ₦<span id="total_amount">0.00</span></p>
                </div>
                <button type="submit" name="book_room" class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition font-semibold">Confirm Booking</button>
            </form>
        </div>
    </main>

<?php require_once 'partials/footer_user.php'; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkInInput = document.getElementById('check_in_date');
        const checkOutInput = document.getElementById('check_out_date');
        const numNightsSpan = document.getElementById('num_nights');
        const totalAmountSpan = document.getElementById('total_amount');
        const roomPrice = parseFloat(document.getElementById('room-price').textContent.replace(/,/g, ''));

        function calculateAmount() {
            const checkInDate = new Date(checkInInput.value);
            const checkOutDate = new Date(checkOutInput.value);

            if (checkInDate && checkOutDate && checkOutDate > checkInDate) {
                const diffTime = Math.abs(checkOutDate - checkInDate);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                numNightsSpan.textContent = diffDays;
                totalAmountSpan.textContent = (diffDays * roomPrice).toLocaleString('en-NG', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            } else {
                numNightsSpan.textContent = 0;
                totalAmountSpan.textContent = '0.00';
            }
        }

        checkInInput.addEventListener('change', calculateAmount);
        checkOutInput.addEventListener('change', calculateAmount);

        // Set min date for check-out
        checkInInput.addEventListener('change', function() {
            if (checkInInput.value) {
                checkOutInput.min = checkInInput.value;
                if (new Date(checkOutInput.value) <= new Date(checkInInput.value)) {
                    checkOutInput.value = ''; // Reset checkout if it's before or same as check-in
                }
            }
        });

        // Initialize calculation if dates are pre-filled (not the case here, but good practice)
        calculateAmount();
    });
</script>