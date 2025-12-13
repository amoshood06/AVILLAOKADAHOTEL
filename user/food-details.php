<?php
$pageTitle = "Food Details"; // Define page title for header_user.php
require_once 'partials/header_user.php'; // Use the new user-specific header
require_once '../config/db.php'; // Keep database connection specific to this page

$conn = getDbConnection();
$food_item = null;

if (isset($_GET['id'])) {
    $food_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    if ($food_id && $conn) {
        try {
            $stmt = $conn->prepare("SELECT id, food_name, price, description, image FROM food_menu WHERE id = :id");
            $stmt->bindParam(':id', $food_id, PDO::PARAM_INT);
            $stmt->execute();
            $food_item = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching food item details: " . $e->getMessage());
            echo "<div class='alert alert-danger' role='alert'>
                    Could not retrieve food item details at this time.
                  </div>";
        }
    }
}

?>

    <?php if ($food_item): ?>
        <div class="row">
            <div class="col-md-6">
                <?php if (!empty($food_item['image'])): ?>
                    <img src="../asset/image/<?php echo htmlspecialchars($food_item['image']); ?>" class="img-fluid rounded" alt="<?php echo htmlspecialchars($food_item['food_name']); ?>">
                <?php else: ?>
                    <img src="https://via.placeholder.com/600x400.png?text=No+Image" class="img-fluid rounded" alt="No Image">
                <?php endif; ?>
            </div>
            <div class="col-md-6">
                <h2><?php echo htmlspecialchars($food_item['food_name']); ?></h2>
                <p class="lead"><strong>Price: $<?php echo htmlspecialchars(number_format($food_item['price'], 2)); ?></strong></p>
                <p><?php echo nl2br(htmlspecialchars($food_item['description'])); ?></p>

                <form action="add-to-cart.php" method="POST" class="mt-4">
                    <input type="hidden" name="food_id" value="<?php echo htmlspecialchars($food_item['id']); ?>">
                    <input type="hidden" name="food_name" value="<?php echo htmlspecialchars($food_item['food_name']); ?>">
                    <input type="hidden" name="price" value="<?php echo htmlspecialchars($food_item['price']); ?>">
                    <div class="form-group row">
                        <label for="quantity" class="col-sm-2 col-form-label">Quantity:</label>
                        <div class="col-sm-4">
                            <input type="number" name="quantity" id="quantity" class="form-control" value="1" min="1" max="100" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success mt-3">Add to Cart</button>
                </form>

                <a href="my-foods.php" class="btn btn-secondary mt-3">Back to Menu</a>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-warning" role="alert">
            Food item not found or invalid ID.
        </div>
    <?php endif; ?>

<?php require_once 'partials/footer_user.php'; // Use the new user-specific footer
?>