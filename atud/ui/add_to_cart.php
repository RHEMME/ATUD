<?php
session_start();
if (isset($_POST['id']) && isset($_POST['qty'])) {
    $id = $_POST['id'];
    $qty = (int)$_POST['qty'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // If item exists, add to quantity, otherwise create new entry
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id] += $qty;
    } else {
        $_SESSION['cart'][$id] = $qty;
    }
    echo "Success";
}
?>