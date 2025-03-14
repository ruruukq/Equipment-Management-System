<?php
session_start();
include('includes/config.php');

if (isset($_POST['productid'])) {
    $productid = $_POST['productid'];

    // Fetch product details
    $sqlProduct = "SELECT ProductName, ProductQty, availableQty, productImage, BrandName 
                   FROM tblproducts 
                   WHERE id = :productid";
    $queryProduct = $dbh->prepare($sqlProduct);
    $queryProduct->bindParam(':productid', $productid, PDO::PARAM_INT);
    $queryProduct->execute();
    $product = $queryProduct->fetch(PDO::FETCH_OBJ);

    if ($product) {
        // Fetch available SKUs for the product
        $sqlSKU = "SELECT tblsku.id, tblsku.SNumber 
                   FROM tblsku 
                   LEFT JOIN tblissuedproducts ON tblsku.id = tblissuedproducts.ProductId 
                   AND tblissuedproducts.ReturnDate IS NULL
                   WHERE tblsku.ProductId = :productid 
                   AND tblissuedproducts.id IS NULL 
                   AND tblsku.isIssued = 0"; // Only fetch SKUs that are not issued
        $querySKU = $dbh->prepare($sqlSKU);
        $querySKU->bindParam(':productid', $productid, PDO::PARAM_INT);
        $querySKU->execute();
        $skus = $querySKU->fetchAll(PDO::FETCH_OBJ);

        // Display product details
        echo "<p><strong>Product Name:</strong> " . htmlentities($product->ProductName) . "</p>";
        echo "<p><strong>Total Quantity:</strong> " . htmlentities($product->ProductQty) . "</p>";
        echo "<p><strong>Available Quantity:</strong> " . htmlentities($product->availableQty) . "</p>";

        // Display product image and brand name
        echo "<img src='productimg/" . htmlentities($product->productImage) . "' width='120'><br />";
        echo "<p><strong>Brand:</strong> " . htmlentities($product->BrandName) . "</p>";

        // Display available SKUs
            if ($querySKU->rowCount() > 0) {
                echo "<label>Available SKUs:</label>";
                echo "<select name='sku' id='sku' class='form-control' required>";
                echo "<option value='' style='display: none;'>Select SKU</option>"; // Add this line for the placeholder option
                foreach ($skus as $sku) {
                    echo "<option value='" . htmlentities($sku->id) . "'>" . htmlentities($sku->SNumber) . "</option>";
                }
                echo "</select>";
            } else {
                echo "<p style='color:red;'>No available SKUs for this product.</p>";
            }

        // If no available quantity, display the message
        if ($product->availableQty == 0) {
            echo "<p style='color:red;'>Product not available for issue.</p>";
        } else {
            echo "<input type='hidden' name='productid' value='" . htmlentities($productid) . "' required>";
            echo "<input type='hidden' name='aqty' value='" . htmlentities($product->availableQty) . "' required>";
        }
    } else {
        echo "<p>Product not found.</p>";
    }
} else {
    echo "<p>Invalid request. Please try again.</p>";
}
?>