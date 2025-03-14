<?php
session_start();
error_reporting(E_ALL);  // Enable full error reporting for debugging
include('includes/config.php');

// Function to log activities
function action_made($dbh, $user_id, $action_made) {
    $sql = "INSERT INTO logs (user_id, timelog, action_made) VALUES (:user_id, NOW(), :action_made)";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':action_made', $action_made, PDO::PARAM_STR);
  
    if (!$stmt->execute()) {
        echo "Error executing action_made statement: " . $stmt->errorInfo()[2];
    }
}

if (strlen($_SESSION['alogin']) == 0) {   
    header('location:index.php');
} else { 
    if (isset($_POST['issue'])) {
        $userid = strtoupper($_POST['userid']);
        $productid = $_POST['productid'];
        $aremark = $_POST['aremark'];
        $expReturn = $_POST['expReturn']; // Get the expected return date
        $sku = $_POST['sku']; // Get the selected SKU from the form

        // Debugging: Check product ID, SKU, and expected return date
        error_log("Product ID from form: " . $productid);
        error_log("SKU from form: " . $sku);
        error_log("Expected Return Date: " . $expReturn);

        // Fetch total product quantity
        $sqlProductQty = "SELECT ProductQty, ProductName 
                          FROM tblproducts 
                          WHERE id = :productid";
        $queryProductQty = $dbh->prepare($sqlProductQty);

        // Check if the query preparation was successful
        if (!$queryProductQty) {
            $errorInfo = $dbh->errorInfo();
            error_log("Database error during query preparation: " . $errorInfo[2]);
            $_SESSION['error'] = "Database error during query preparation. Please try again.";
            header('location:manage-issued-equipments.php');
            exit();
        }

        // Bind the parameter and execute the query
        $queryProductQty->bindParam(':productid', $productid, PDO::PARAM_STR);
        $queryExecuted = $queryProductQty->execute();

        // Check if the query execution was successful
        if (!$queryExecuted) {
            $errorInfo = $queryProductQty->errorInfo();
            error_log("Database error during query execution: " . $errorInfo[2]);
            $_SESSION['error'] = "Database error during query execution. Please try again.";
            header('location:manage-issued-equipments.php');
            exit();
        }

        // Fetch the product details
        $product = $queryProductQty->fetch(PDO::FETCH_OBJ);

        if (!$product) {
            $_SESSION['error'] = "Product not found.";
            header('location:manage-issued-equipments.php');
            exit();
        }

        // Fetch total borrowed quantity for the product
        $sqlBorrowedQty = "SELECT SUM(quantity) AS totalBorrowed 
                           FROM tblissuedproducts 
                           WHERE ProductId = :productid 
                           AND ReturnDate IS NULL";
        $queryBorrowedQty = $dbh->prepare($sqlBorrowedQty);
        $queryBorrowedQty->bindParam(':productid', $productid, PDO::PARAM_STR);
        $queryBorrowedQty->execute();
        $borrowed = $queryBorrowedQty->fetch(PDO::FETCH_OBJ);

        $totalBorrowed = $borrowed->totalBorrowed ?? 0;
        $availableQty = $product->ProductQty - $totalBorrowed;

        // Check if the requested quantity is available (assume 1 is being borrowed)
        if ($availableQty >= 1) {
            // Check if the user has already borrowed the same product
            $sqlCheckBorrowed = "SELECT id, quantity, borrowedqty 
                                 FROM tblissuedproducts 
                                 WHERE UserID = :userid 
                                 AND ProductId = :productid 
                                 AND ReturnDate IS NULL";
            $queryCheckBorrowed = $dbh->prepare($sqlCheckBorrowed);
            $queryCheckBorrowed->bindParam(':userid', $userid, PDO::PARAM_STR);
            $queryCheckBorrowed->bindParam(':productid', $productid, PDO::PARAM_STR);
            $queryCheckBorrowed->execute();
            $existingBorrow = $queryCheckBorrowed->fetch(PDO::FETCH_OBJ);

            if ($existingBorrow) {
                // If the user has already borrowed the same product, update the quantity and borrowedqty
                $newQuantity = $existingBorrow->quantity + 1; // Assume 1 is being borrowed
                $newBorrowedQty = $existingBorrow->borrowedqty + 1; // Assume 1 is being borrowed

                $sqlUpdate = "UPDATE tblissuedproducts 
                              SET quantity = :newQuantity, borrowedqty = :newBorrowedQty 
                              WHERE id = :id";
                $queryUpdate = $dbh->prepare($sqlUpdate);
                $queryUpdate->bindParam(':newQuantity', $newQuantity, PDO::PARAM_INT);
                $queryUpdate->bindParam(':newBorrowedQty', $newBorrowedQty, PDO::PARAM_INT);
                $queryUpdate->bindParam(':id', $existingBorrow->id, PDO::PARAM_INT);
                $queryUpdate->execute();

                $rid = $existingBorrow->id; // Use the existing record ID
            } else {
                // If the user has not borrowed the product before, insert a new record
                $sqlInsert = "INSERT INTO tblissuedproducts(UserID, ProductId, remark, quantity, borrowedqty, ExpReturn, SNumber) 
                              VALUES(:userid, :productid, :aremark, 1, 1, :expReturn, :sku)"; // Assume 1 is being borrowed
                $queryInsert = $dbh->prepare($sqlInsert);
                $queryInsert->bindParam(':userid', $userid, PDO::PARAM_STR);
                $queryInsert->bindParam(':productid', $productid, PDO::PARAM_STR);
                $queryInsert->bindParam(':aremark', $aremark, PDO::PARAM_STR);
                $queryInsert->bindParam(':expReturn', $expReturn, PDO::PARAM_STR);
                $queryInsert->bindParam(':sku', $sku, PDO::PARAM_STR);
                $queryInsert->execute();

                $rid = $dbh->lastInsertId(); // Capture the new record ID
            }

            // Update the available quantity in the products table
            $newAvailableQty = $availableQty - 1; // Assume 1 is being borrowed
            $sqlUpdateProduct = "UPDATE tblproducts 
                                 SET availableQty = :newAvailableQty 
                                 WHERE id = :productid";
            $queryUpdateProduct = $dbh->prepare($sqlUpdateProduct);
            $queryUpdateProduct->bindParam(':newAvailableQty', $newAvailableQty, PDO::PARAM_INT);
            $queryUpdateProduct->bindParam(':productid', $productid, PDO::PARAM_STR);
            $queryUpdateProduct->execute();

            // Update the isIssued field in tblsku to 1 for the issued SKU
            $sqlUpdateSKU = "UPDATE tblsku 
                             SET isIssued = 1 
                             WHERE id = :sku";
            $queryUpdateSKU = $dbh->prepare($sqlUpdateSKU);
            $queryUpdateSKU->bindParam(':sku', $sku, PDO::PARAM_STR);
            $queryUpdateSKU->execute();

            // Log the action
            $action_made = "Admin Issued New Product for User ID $userid: " . $product->ProductName;
            action_made($dbh, $_SESSION['alogin'], $action_made);

            $_SESSION['msg'] = "Product issued successfully";
            header('location:print/print-issued.php?rid=' . $rid);
            exit();
        } else {
            // If the requested quantity is not available
            $_SESSION['error'] = "Insufficient stock for the requested quantity.";
            header('location:manage-issued-equipments.php');
        }
    }
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="equipment" content="" />
    <title>Equipment Management System | Issue a new Equipments</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet"   />
    <!-- GOOGLE FONT -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <link rel="icon" href="assets/img/src.png">
    <script>
        // Function for get user name
        function getuser() {
            $("#loaderIcon").show();
            jQuery.ajax({
                url: "get_user.php",
                data: 'userid=' + $("#userid").val(),
                type: "POST",
                success: function (data) {
                    $("#get_user_name").html(data);
                    $("#loaderIcon").hide();
                },
                error: function () {}
            });
        }

        // Function for product details
        function getproduct() {
            $("#loaderIcon").show();
            jQuery.ajax({
                url: "get_product.php",
                data: 'productid=' + $("#productid").val(),
                type: "POST",
                success: function (data) {
                    $("#get_product_name").html(data);
                    $("#loaderIcon").hide();
                },
                error: function () {}
            });
        }
    </script> 
    <style type="text/css">
        .others{
            color:red;
        }
    </style>
</head>
<body>
    <!-- MENU SECTION START-->
    <?php include('includes/header.php');?>
    <!-- MENU SECTION END-->
    <div class="container">
        <div class="row pad-botm">
            <div class="col-md-12">
                <h4 class="header-line" style="position: relative; top: 30px;">Issue New Equipment</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-10 col-sm-6 col-xs-12 col-md-offset-1">
                <div class="panel panel-info">
                    <div class="panel-heading" style="display: flex; justify-content: space-between; align-items: center; background-color: #1B4D3E; color: #fff;">
                        <!-- X button aligned to the right -->
                        <span>Issue Equipment Details</span>
                        <a href="manage-issued-equipments.php" style="font-size: 30px; text-decoration: none; color: #fff;">&times;</a> 
                    </div>
                    <div class="panel-body">
                        <form role="form" method="post" id="issueProductForm">
                            <div class="form-group">
                                <label>User ID<span style="color:red;">*</span></label>
                                <input class="form-control" type="text" name="userid" id="userid" onBlur="getuser()" autocomplete="off" required />
                            </div>

                            <div class="form-group">
                                <span id="get_user_name" style="font-size:16px;"></span> 
                            </div>

                            <div class="form-group">
                                <label>Equipment Name<span style="color:red;">*</span></label>
                                <select class="form-control" name="productid" id="productid" onchange="getproduct()" required>
                                    <option value="" style='display: none;'>Select Equipment</option>
                                    <?php
                                    // Fetch all products from the database
                                    $sql = "SELECT id, ProductName FROM tblproducts";
                                    $query = $dbh->prepare($sql);
                                    $query->execute();
                                    $products = $query->fetchAll(PDO::FETCH_OBJ);

                                    if ($query->rowCount() > 0) {
                                        foreach ($products as $product) {
                                            echo '<option value="' . htmlentities($product->id) . '">' . htmlentities($product->ProductName) . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group" id="get_product_name"></div>

                            <div class="form-group">
                                <label>Expected Return Date<span style="color:red;">*</span></label>
                                <input class="form-control" type="date" name="expReturn" id="expReturn" required />
                            </div>

                            <div class="form-group">
                                <label>
                                    <input type="checkbox" id="remarkTypeY" name="aremark" value="Borrow" required> Borrow
                                    <span style="color:red;">*</span>
                                </label>
                            </div>

                            <button type="submit" name="issue" id="submit" class="btn btn-info" style="background-color: #1B4D3E; color: #fff;">Issue Equipment </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('includes/footer.php');?>
    <!-- FOOTER SECTION END-->
    <!-- JAVASCRIPT FILES PLACED AT THE BOTTOM TO REDUCE THE LOADING TIME -->
    <!-- CORE JQUERY  -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS  -->
    <script src="assets/js/bootstrap.js"></script>
    <!-- CUSTOM SCRIPTS  -->
    <script src="assets/js/custom.js"></script>
</body>
</html>