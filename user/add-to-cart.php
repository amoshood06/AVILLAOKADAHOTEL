<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['food_id'], $_POST['food_name'], $_POST['price'])) {
        $food_id = filter_var($_POST['food_id'], FILTER_SANITIZE_NUMBER_INT);
        $food_name = filter_var($_POST['food_name'], FILTER_SANITIZE_STRING);
        $price = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $quantity = isset($_POST['quantity']) ? filter_var($_POST['quantity'], FILTER_SANITIZE_NUMBER_INT) : 1;

        if ($food_id && $food_name && $price !== false && $quantity > 0) {
            // Initialize cart if it doesn't exist
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            // Check if food item already in cart
            if (isset($_SESSION['cart'][$food_id])) {
                $_SESSION['cart'][$food_id]['quantity'] += $quantity;
            } else {
                $_SESSION['cart'][$food_id] = [
                    'id' => $food_id,
                    'name' => $food_name,
                    'price' => $price,
                    'quantity' => $quantity
                ];
            }
            $_SESSION['success_message'] = "Food item(s) added to cart!";
        } else {
            $_SESSION['error_message'] = "Invalid food item data or quantity.";
        }
    } else {
        $_SESSION['error_message'] = "Missing food item data.";
    }
} else {
    $_SESSION['error_message'] = "Invalid request method.";
}

// Redirect back to the food menu or a cart page
header('Location: my-foods.php'); // Or a dedicated cart view page
exit();
?>