<?php
$title = "Food Details";
require_once 'header.php';

// Check if user is logged in, redirect to login if not
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<div class='container mx-auto py-16 text-center'>Invalid food item.</div>";
    require_once 'footer.php';
    exit();
}

$food_id = $_GET['id'];
$food = select("SELECT * FROM food_menu WHERE id = ?", [$food_id], true);

if (!$food) {
    echo "<div class='container mx-auto py-16 text-center'>Food item not found.</div>";
    require_once 'footer.php';
    exit();
}

// Fetch user's active bookings
$bookings = select("SELECT b.id, r.room_name FROM bookings b JOIN rooms r ON b.room_id = r.id WHERE b.user_id = ? AND b.check_out >= CURDATE()", [$user_id]);

$message = '';
$error = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_id = filter_var($_POST['booking_id'] ?? '', FILTER_SANITIZE_NUMBER_INT);
    $quantity = filter_var($_POST['quantity'] ?? 1, FILTER_SANITIZE_NUMBER_INT);

    if (empty($booking_id) || $booking_id <= 0 || $quantity <= 0) {
        $message = "Please select a room and specify a valid quantity.";
        $error = true;
    } else {
        // Initialize cart if it doesn't exist
        if (!isset($_SESSION['food_cart'])) {
            $_SESSION['food_cart'] = [];
        }

        // Add item to cart
        $cart_item_key = $food_id . '_' . $booking_id; // Unique key for food item + booking
        if (isset($_SESSION['food_cart'][$cart_item_key])) {
            $_SESSION['food_cart'][$cart_item_key]['quantity'] += $quantity;
        } else {
            $_SESSION['food_cart'][$cart_item_key] = [
                'food_id' => $food_id,
                'food_name' => $food['food_name'],
                'price' => $food['price'],
                'quantity' => $quantity,
                'booking_id' => $booking_id // Store the selected booking ID
            ];
        }

        $_SESSION['food_cart_success'] = "Food item(s) added to cart. Proceed to checkout.";
        header('Location: user/food-checkout.php'); // Redirect to food checkout page
        exit();
    }
}

?>

<main class="py-16">
    <div class="container mx-auto px-6">

        <?php if ($message): ?>
            <div class="mb-8 p-4 text-center <?php echo $error ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'; ?> rounded-lg">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            <div>
                <img src="asset/image/<?php echo htmlspecialchars($food['image']); ?>" alt="<?php echo htmlspecialchars($food['food_name']); ?>" class="w-full h-auto rounded-lg shadow-lg">
            </div>
            <div>
                <h1 class="text-4xl font-serif mb-4"><?php echo htmlspecialchars($food['food_name']); ?></h1>
                <p class="text-2xl font-semibold text-yellow-600 mb-6">₦<?php echo number_format($food['price']); ?></p>
                <p class="text-gray-600 mb-8"><?php echo htmlspecialchars($food['description']); ?></p>

                <form action="food-details.php?id=<?php echo $food_id; ?>" method="POST">
                    <div class="mb-6">
                        <label for="booking_id" class="block text-gray-700 font-medium mb-2">Deliver to Room:</label>
                        <select id="booking_id" name="booking_id" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="">Select a booked room</option>
                            <?php if ($bookings && count($bookings) > 0): ?>
                                <?php foreach ($bookings as $booking): ?>
                                    <option value="<?php echo $booking['id']; ?>"><?php echo htmlspecialchars($booking['room_name']); ?> (Booking #<?php echo $booking['id']; ?>)</option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="" disabled>You have no active room bookings.</option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="mb-6">
                        <label for="quantity" class="block text-gray-700 font-medium mb-2">Quantity:</label>
                        <input type="number" id="quantity" name="quantity" value="1" min="1" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    
                    <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition font-semibold" <?php echo (empty($bookings)) ? 'disabled' : ''; ?>>
                        <?php echo (empty($bookings)) ? 'Book a room to order food' : 'Add to Cart'; ?>
                    </button>
                </form>
            </div>
        </div>
    </div>
</main>

<?php require_once 'footer.php'; ?>