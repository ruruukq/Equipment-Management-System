<?php
session_start();
error_reporting(E_ALL); // Enable full error reporting for debugging
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

if(strlen($_SESSION['alogin'])==0) {   
    header('location:index.php');
} else { 
    // Check if the form is for returning products
    if (isset($_POST['return'])) {
        $rid = intval($_GET['rid']);
        $fine = $_POST['fine'];
        $productid = $_POST['productid'];
        $returnDate = date('Y-m-d'); // Get the current date as return date
        $skuToReturn = $_POST['skuToReturn']; // The SKU selected for return
        $remarks = $_POST['remarks']; // The selected remark (Good Condition or Damaged)

        // First, get the current quantity of the product in tblissuedproducts
        $sql = "SELECT Quantity, ReturnStatus, UserId FROM tblissuedproducts WHERE id=:rid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':rid', $rid, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);
        $quantityIssued = $result->Quantity;
        $returnStatus = $result->ReturnStatus;
        $UserId = $result->UserId; // Fetch the UserId from tblissuedproducts

        // Validate if the SKU to return is valid
        if ($skuToReturn) {
            // Update the SKU status in tblsku to mark it as returned and add remarks
            $sqlUpdateSKU = "UPDATE tblsku SET isIssued = 0, remarks = :remarks WHERE id = :skuToReturn";
            $queryUpdateSKU = $dbh->prepare($sqlUpdateSKU);
            $queryUpdateSKU->bindParam(':skuToReturn', $skuToReturn, PDO::PARAM_INT);
            $queryUpdateSKU->bindParam(':remarks', $remarks, PDO::PARAM_STR);
            $queryUpdateSKU->execute();

            // Calculate the remaining quantity to be returned
            $remainingQuantity = $quantityIssued - 1; // Assume 1 SKU is being returned

            // If the remaining quantity is greater than 0, don't mark as fully returned
            if ($remainingQuantity > 0) {
                $rstatus = 0;  // Keep the ReturnStatus as not returned
            } else {
                $rstatus = 1;  // Mark as fully returned
            }

            $returnDate = date('Y-m-d H:i:s');  // Include both date and time
            // Update the issued product table (reduce quantity)
            $sqlUpdateIssued = "UPDATE tblissuedproducts SET fine=:fine, ReturnStatus=:rstatus, 
                        ReturnDate=IF(ReturnDate IS NULL, CURRENT_TIMESTAMP, :returnDate), 
                        Quantity=:remainingQuantity WHERE id=:rid";
            $query = $dbh->prepare($sqlUpdateIssued);
            $query->bindParam(':rid', $rid, PDO::PARAM_STR);
            $query->bindParam(':fine', $fine, PDO::PARAM_STR);
            $query->bindParam(':rstatus', $rstatus, PDO::PARAM_STR);
            $query->bindParam(':returnDate', $returnDate, PDO::PARAM_STR);
            $query->bindParam(':remainingQuantity', $remainingQuantity, PDO::PARAM_INT);
            $query->execute();

            // Update the available quantity in the tblproducts table (increase by returned quantity)
            $sqlUpdateProduct = "UPDATE tblproducts SET availableQty = availableQty + 1 WHERE id=:productid";
            $query = $dbh->prepare($sqlUpdateProduct);
            $query->bindParam(':productid', $productid, PDO::PARAM_STR);
            $query->execute();
            
            // Fetch FullName from tblusers table using the UserId from tblissuedproducts
            $sqlUserName = "SELECT FullName FROM tblusers WHERE UserId = :UserId";
            $queryUserName = $dbh->prepare($sqlUserName);
            $queryUserName->bindParam(':UserId', $UserId, PDO::PARAM_STR);
            $queryUserName->execute();
            $user = $queryUserName->fetch(PDO::FETCH_OBJ);

            if ($user) {
                $FullName = $user->FullName;
            } else {
                echo "No user found with UserId: " . $UserId . "<br>";
                $FullName = 'Unknown User'; // Default value if user not found
            }

            // Fetch ProductName from tblproducts table
            $sqlProductName = "SELECT ProductName FROM tblproducts WHERE id = :productid";
            $queryProductName = $dbh->prepare($sqlProductName);
            $queryProductName->bindParam(':productid', $productid, PDO::PARAM_STR);
            $queryProductName->execute();
            $product = $queryProductName->fetch(PDO::FETCH_OBJ);
            $ProductName = $product->ProductName;

            // Fetch the logged-in admin's ID
            $admin_email = $_SESSION['alogin'];  // Get the logged-in admin's email
            $sqlAdmin = "SELECT id FROM admin WHERE UserName = :username";
            $queryAdmin = $dbh->prepare($sqlAdmin);
            $queryAdmin->bindParam(':username', $admin_email, PDO::PARAM_STR);
            $queryAdmin->execute();
            $admin_result = $queryAdmin->fetch(PDO::FETCH_OBJ);
            $user_id = $admin_result->id;  // Get the admin's ID

            $action_made = "$FullName Has Returned 1 Product of $ProductName (SKU: $skuToReturn) with Remarks: $remarks";
            action_made($dbh, $user_id, $action_made);

            // Redirect after success
            $_SESSION['msg'] = "Product Returned Successfully";
            header('location:print/print-issued.php?rid=' . $rid);

        } else {
            $_SESSION['error'] = "Invalid SKU Selected.";
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
    <meta name="product" content="" />
    <title>Equipment Management System | Issued Equipment Details</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <link rel="icon" href="assets/img/src.png">
    <style type="text/css">
        .others{ color:red; }

        .product-name {
            text-transform: uppercase; /* Force all letters to be uppercase */
            white-space: nowrap; /* Prevent wrapping of the product name */
            overflow: hidden; /* Hide overflow text if needed */
            text-overflow: ellipsis; /* Use ellipsis for overflowing text */
        }

        .form-group {
            margin-bottom: 15px; /* Ensure consistent spacing between fields */
        }

        .form-footer {
            margin-top: 20px; /* Give space for the button */
            clear: both; /* Clear any floating elements to avoid overlap */
        }

        .panel-body {
            padding-bottom: 30px; /* Add some space at the bottom of the form */
        }

        #fine {
            display: block;
            width: 30%;
            margin-top: 5px;
            padding: 8px;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>
    <!-- MENU SECTION START -->
    <?php include('includes/header.php'); ?>
    <!-- MENU SECTION END -->
    <div class="container">
        <div class="row pad-botm">
            <div class="col-md-12">
                <h4 class="header-line" style="position: relative; top: 30px;">Issued Equipment Details</h4>
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
                        <form role="form" method="post" id="returnProductForm">
                            <?php 
                                $rid = intval($_GET['rid']);
                                $sql = "SELECT tblusers.UserId, tblusers.FullName, tblusers.EmailId, tblusers.MobileNumber, 
                                        tblproducts.ProductName, tblproducts.SNumber, tblissuedproducts.IssuesDate, 
                                        tblissuedproducts.ReturnDate,tblissuedproducts.ExpReturn,tblissuedproducts.quantity,tblissuedproducts.quantity, tblissuedproducts.id AS rid, 
                                        tblissuedproducts.fine, tblissuedproducts.ReturnStatus, tblproducts.id AS bid, 
                                        tblproducts.productImage 
                                        FROM tblissuedproducts 
                                        JOIN tblusers ON tblusers.UserId = tblissuedproducts.UserId 
                                        JOIN tblproducts ON tblproducts.id = tblissuedproducts.ProductId 
                                        WHERE tblissuedProducts.id = :rid";

                                $query = $dbh->prepare($sql);
                                $query->bindParam(':rid', $rid, PDO::PARAM_STR);
                                $query->execute();
                                $results = $query->fetchAll(PDO::FETCH_OBJ);

                                // Check if data is returned
                                if($query->rowCount() > 0) {
                                    foreach($results as $result) { 
                            ?>
                            <input type="hidden" name="productid" value="<?php echo htmlentities($result->bid); ?>">

                            <h4><strong>USER DETAILS:</strong></h4>
                            <hr/>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>User Name:</label>
                                    <?php echo htmlentities($result->FullName); ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>User ID:</label>
                                    <?php echo htmlentities($result->UserId); ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>User Email ID:</label>
                                    <?php echo htmlentities($result->EmailId); ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>User Contact No:</label>
                                    <?php echo htmlentities($result->MobileNumber); ?>
                                </div>
                            </div>

                            <!-- Add a divider with extra spacing between USER and PRODUCT details -->
                            <div style="margin: 30px 0;">
                                <hr />
                            </div>

                            <h4><strong>EQUIPMENT DETAILS:</strong></h4>
                            <hr />

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Equipment Image:</label>
                                    <img src="productimg/<?php echo htmlentities($result->productImage); ?>" width="200">
                                </div>
                            </div>

                            <div class="row">
                                <!-- Left Column: Product Details -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Equipment Name:</label>
                                        <span><?php echo htmlentities($result->ProductName); ?></span> <!-- Removed the 'product-name' class -->
                                    </div>

                                    <div class="form-group">
                                        <label>Equipment Issued Date:</label>
                                        <?php echo date("F j, Y at g:i A", strtotime($result->IssuesDate)); ?>
                                    </div>

                                    <div class="form-group">
                                        <label>Expected Return Date:</label>
                                        <?php 
                                            // Convert the ExpReturn to a date format like "February 23, 2025"
                                            echo date("F j, Y", strtotime($result->ExpReturn));
                                        ?>
                                    </div>

                                    <div class="form-group">
                                        <label>Equipment Returned Date:</label>
                                        <?php 
                                            // Check if there's a return date
                                            if ($result->ReturnDate != "") {
                                                // If there's a return date, check if there are remaining quantities
                                                if ($result->quantity > 0) {
                                                    echo "Not Complete"; // If there are remaining quantities, display "Not Complete"
                                                } else {
                                                    echo date("F j, Y g:i A", strtotime($result->ReturnDate)); // If no remaining quantities, display "Returned" with date and time
                                                }
                                            } else {
                                                // If there's no return date, check if there are remaining quantities
                                                if ($result->quantity > 0) {
                                                    echo "Not Return Yet"; // If there are remaining quantities, display "Not Return Yet"
                                                } else {
                                                    echo "Returned on " . date("F j, Y g:i A", strtotime($result->ReturnDate)); // If no remaining quantities, display "Returned" with date and time
                                                }
                                            }
                                        ?>
                                    </div>

                                    <div class="form-group">
                                        <label>Remaining Quantity:</label>
                                        <?php echo htmlentities($result->quantity); ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Fetch SKUs for the issued product -->
                            <?php
                                $sqlSKU = "SELECT id, SNumber FROM tblsku WHERE ProductId = :productid AND isIssued = 1";
                                $querySKU = $dbh->prepare($sqlSKU);
                                $querySKU->bindParam(':productid', $result->bid, PDO::PARAM_INT);
                                $querySKU->execute();
                                $skus = $querySKU->fetchAll(PDO::FETCH_OBJ);
                            ?>

                            <!-- Check if Remaining Borrowed Quantity is greater than 0 -->
                            <?php if ($result->quantity > 0) { ?>
                                <div class="form-group">
                                    <label>Select SKU to Return<span style="color:red;">*</span></label>
                                    <select class="form-control" name="skuToReturn" id="skuToReturn" required style="width: 30%;">
                                        <option value="" style='display: none;'>Select SKU</option>
                                        <?php
                                        if ($querySKU->rowCount() > 0) {
                                            foreach ($skus as $sku) {
                                                echo '<option value="' . htmlentities($sku->id) . '">' . htmlentities($sku->SNumber) . '</option>';
                                            }
                                        } else {
                                            echo '<option value="" disabled>No SKUs available</option>';
                                        }
                                        ?>
                                    </select>
                                </div>

                                <!-- Add Remarks Section -->
                                <div class="form-group">
                                    <label>Remarks<span style="color:red;">*</span></label>
                                    <div>
                                        <label>
                                            <input type="radio" name="remarks" value="Good Condition" required> Good Condition
                                        </label>
                                        <label>
                                            <input type="radio" name="remarks" value="Damaged" required> Damaged
                                        </label>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <div class="form-group">
                                    <label>No Remaining Borrowed Quantity</label>
                                    <input class="form-control" type="text" value="No Quantity to Return" disabled style="width: 30%;" />
                                </div>
                            <?php } ?>

                            <?php 
                                // Allow admin to edit the fine even if the product is returned
                                if($result->ReturnStatus == 0) {
                                    // If product is not yet returned, show the "Return Product" button
                                    if ($result->quantity > 0) {
                                        // If there are still products to be returned, show the "Return Product" button
                                        echo '<button type="submit" name="return" id="submit" class="btn btn-info" style="background-color: #1B4D3E; color: #fff;">Update</button>';
                                    } else {
                                        // If all quantities have been returned, disable the "Return Product" button but still allow updating the fine
                                        echo '<button type="submit" name="return" id="submit" class="btn btn-info" style="background-color: #1B4D3E; color: #fff;" disabled>All quantities returned</button>';
                                    }
                                } else {
                                    // If product is already returned, show the "Update Fine" button instead
                                    echo '<button type="submit" name="editfine" id="submit" class="btn btn-info" style="background-color: #1B4D3E; color: #fff;">Update</button>';
                                }
                            }
                        }
                            ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('includes/footer.php');?>
    <!-- FOOTER SECTION END-->
    <!-- JAVASCRIPT FILES PLACED AT THE BOTTOM TO REDUCE THE LOADING TIME  -->
    <!-- CORE JQUERY  -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS  -->
    <script src="assets/js/bootstrap.js"></script>
    <!-- CUSTOM SCRIPTS  -->
    <script src="assets/js/custom.js"></script>
</body>
</html>