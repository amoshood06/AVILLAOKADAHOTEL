<?php
session_start();

if (isset($_GET['food_id'])) {
    $food_id = filter_var($_GET['food_id'], FILTER_SANITIZE_NUMBER_INT);

    if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        if (isset($_SESSION['cart'][$food_id])) {
            unset($_SESSION['cart'][$food_id]);
            $_SESSION['success_message'] = "Food item removed from cart.";
        } else {
            $_SESSION['error_message'] = "Food item not found in cart.";
        }
    } else {
        $_SESSION['error_message'] = "Your cart is empty.";
    }
} else {
    $_SESSION['error_message'] = "No food item specified to remove.";
}

header('Location: my-foods.php');
exit();
