<?php
include("data/database.php");
if (isset($_POST['id'])) {
    $id = intval($_POST['id']);

    if (!empty($_SESSION['basket_product_id'])) {
        $_SESSION['basket_product_id'] = array_filter(
            $_SESSION['basket_product_id'],
            function ($productId) use ($id) {
                return $productId != $id;
            }
        );
    }
}