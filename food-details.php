<?php
session_start();
include 'header.php';
include 'config/db.php'; // Assuming db.php contains database connection

// Check if user is logged in, redirect to login if not
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Handle food order and room selection
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $food_item_id = $_POST['food_item_id'] ?? null; // Assuming food items have IDs
    $room_type = $_POST['room_type'] ?? '';
    $room_number = $_POST['room_number'] ?? '';
    $quantity = $_POST['quantity'] ?? 1;

    // Basic validation
    if (empty($food_item_id) || empty($room_type) || empty($room_number)) {
        $error = "Please select a food item, room type, and room number.";
    } else {
        // Here you would typically insert the order into a 'food_orders' table
        // For demonstration, let's just show a success message
        $success = "Order for Food Item ID: {$food_item_id}, Quantity: {$quantity} placed for Room Type: {$room_type}, Room Number: {$room_number}.";

        // Example database insertion (uncomment and adapt to your schema)
        /*
        $stmt = $conn->prepare("INSERT INTO food_orders (user_id, food_item_id, quantity, room_type, room_number, order_date) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("iiiss", $user_id, $food_item_id, $quantity, $room_type, $room_number);
        if ($stmt->execute()) {
            $success = "Your food order has been placed successfully!";
        } else {
            $error = "Failed to place order: " . $stmt->error;
        }
        $stmt->close();
        */
    }
}

// Fetch food items from the database (example)
$food_items = [];
// Assuming a 'food_menu' table with 'id' and 'name'
$result = $conn->query("SELECT id, name, price FROM food_menu");
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $food_items[] = $row;
    }
}

// Fetch room types/numbers (example - you might fetch available rooms or booking details)
$room_options = [
    ['type' => 'Standard', 'numbers' => ['101', '102', '103']],
    ['type' => 'Deluxe', 'numbers' => ['201', '202', '203']],
    ['type' => 'Suite', 'numbers' => ['301', '302', '303']],
];

?>

<div class="container mt-5">
    <h2>Order Food and Select Room</h2>

    <?php if (isset($success)): ?>
        <div class="alert alert-success" role="alert">
            <?php echo $success; ?>
        </div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="food_item_id" class="form-label">Select Food Item</label>
            <select class="form-select" id="food_item_id" name="food_item_id" required>
                <option value="">-- Choose Food --</option>
                <?php foreach ($food_items as $item): ?>
                    <option value="<?php echo htmlspecialchars($item['id']); ?>">
                        <?php echo htmlspecialchars($item['name']) . " - $" . htmlspecialchars($item['price']); ?>
                    </option>
                <?php endforeach; ?>
                <?php if (empty($food_items)): ?>
                    <option value="" disabled>No food items available. Please add some to the menu.</option>
                <?php endif; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1" required>
        </div>

        <div class="mb-3">
            <label for="room_type" class="form-label">Room Type</label>
            <select class="form-select" id="room_type" name="room_type" required>
                <option value="">-- Choose Room Type --</option>
                <?php foreach ($room_options as $room_type_option): ?>
                    <option value="<?php echo htmlspecialchars($room_type_option['type']); ?>">
                        <?php echo htmlspecialchars($room_type_option['type']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="room_number" class="form-label">Room Number</label>
            <input type="text" class="form-control" id="room_number" name="room_number" placeholder="e.g., 101" required>
            <small class="form-text text-muted">Enter the specific room number.</small>
        </div>

        <button type="submit" class="btn btn-primary">Place Order</button>
    </form>
</div>

<?php include 'footer.php'; ?>