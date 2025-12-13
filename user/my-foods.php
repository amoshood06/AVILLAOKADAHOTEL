<?php
$pageTitle = "Food Menu"; // Define page title for header_user.php
require_once 'partials/header_user.php'; // Use the new user-specific header
require_once '../config/db.php'; // Keep database connection specific to this page

$conn = getDbConnection();

$food_items = [];
if ($conn) {
    try {
        $stmt = $conn->query("SELECT id, food_name, price, description, image FROM food_menu");
        $food_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching food items: " . $e->getMessage());
        // Display a user-friendly message
        echo "<div class='alert alert-danger' role='alert'>
                Could not retrieve food items at this time. Please try again later.
              </div>";
    }
} else {
    echo "<div class='alert alert-danger' role='alert'>
            Database connection failed.
          </div>";
}
?>

    <h2 class="mb-4">Our Food Menu</h2>
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="mb-4 p-4 text-sm bg-green-100 text-green-700 rounded-lg">
            <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="mb-4 p-4 text-sm bg-red-100 text-red-700 rounded-lg">
            <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($food_items)): ?>
        <div class="row">
            <?php foreach ($food_items as $food): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <?php if (!empty($food['image'])): ?>
                            <img src="../asset/image/<?php echo htmlspecialchars($food['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($food['food_name']); ?>">
                        <?php else: ?>
                            <img src="https://via.placeholder.com/300x200.png?text=No+Image" class="card-img-top" alt="No Image">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($food['food_name']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars(substr($food['description'], 0, 100)); ?>...</p>
                            <p class="card-text"><strong>Price: $<?php echo htmlspecialchars(number_format($food['price'], 2)); ?></strong></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="food-details.php?id=<?php echo htmlspecialchars($food['id']); ?>" class="btn btn-info btn-sm">View Details</a>
                                <!-- Placeholder for Add to Cart functionality -->
                                <form action="add-to-cart.php" method="POST">
                                    <input type="hidden" name="food_id" value="<?php echo htmlspecialchars($food['id']); ?>">
                                    <input type="hidden" name="food_name" value="<?php echo htmlspecialchars($food['food_name']); ?>">
                                    <input type="hidden" name="price" value="<?php echo htmlspecialchars($food['price']); ?>">
                                    <button type="submit" class="btn btn-success btn-sm">Add to Cart</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-warning" role="alert">
            No food items found in the menu.
        </div>
    <?php endif; ?>

<?php require_once 'partials/footer_user.php'; // Use the new user-specific footer
?>