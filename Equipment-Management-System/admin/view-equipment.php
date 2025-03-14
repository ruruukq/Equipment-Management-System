<?php
session_start();
error_reporting(E_ALL);  // Set error reporting to all for debugging
include('includes/config.php');

if(isset($_GET['del'])) {
    $id = $_GET['del'];

    // Fetch the SKU details before deleting
    $sql_sku = "SELECT SNumber, ProductId FROM tblsku WHERE id = :id";
    $query_sku = $dbh->prepare($sql_sku);
    $query_sku->bindParam(':id', $id, PDO::PARAM_INT);
    $query_sku->execute();
    $sku = $query_sku->fetch(PDO::FETCH_OBJ);  // Fetch SKU details

    if ($sku) {
        $productId = $sku->ProductId; // Get the associated product ID

        // Prepare delete query for SKU
        $sql_delete_sku = "DELETE FROM tblsku WHERE id=:id";

        // Prepare update query for product quantities
        $sql_update_product = "UPDATE tblproducts 
                               SET productQty = productQty - 1, 
                                   availableQty = availableQty - 1 
                               WHERE id = :productId";

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

        // Begin a transaction to ensure atomicity
        $dbh->beginTransaction();

        try {
            // Step 1: Delete the SKU
            $query_delete = $dbh->prepare($sql_delete_sku);
            $query_delete->bindParam(':id', $id, PDO::PARAM_INT);
            $query_delete->execute();

            // Step 2: Update the product quantities
            $query_update = $dbh->prepare($sql_update_product);
            $query_update->bindParam(':productId', $productId, PDO::PARAM_INT);
            $query_update->execute();

            // Commit the transaction
            $dbh->commit();

            // Log the action
            $admin_email = $_SESSION['alogin'];  // Get the logged-in admin's email
            $sql = "SELECT id FROM admin WHERE UserName = :username";
            $query = $dbh->prepare($sql);
            $query->bindParam(':username', $admin_email, PDO::PARAM_STR);
            $query->execute();
            $admin_result = $query->fetch(PDO::FETCH_OBJ);
      
            $user_id = $admin_result->id;  // Get the admin's ID

            $action_made = "Admin Deleted SKU: " . $sku->SNumber;
            action_made($dbh, $user_id, $action_made);  // Log the action

            $_SESSION['delmsg'] = "SKU deleted successfully and product quantities updated."; // Success message
        } catch (Exception $e) {
            // Rollback the transaction in case of error
            $dbh->rollBack();
            $_SESSION['delmsg'] = "Error: " . $e->getMessage(); // Error message
        }
    } else {
        $_SESSION['delmsg'] = "SKU not found."; // Error message if SKU doesn't exist
    }

    // Redirect to manage-equipments.php after deletion
    header('location:manage-equipments.php');
    exit(); // Ensure no further code is executed after redirect
}
// Initialize $selectedProductId with a default value
$selectedProductId = null;

if(strlen($_SESSION['alogin']) == 0) {   
    header('location:index.php');
} else { 
    // Check if a product is selected
    $selectedProductId = isset($_GET['productid']) ? intval($_GET['productid']) : null;

    // Fetch the product name (ProductName) from tblproducts
    $productName = "Manage Equipments"; // Default header text
    if ($selectedProductId) {
        $sql = "SELECT ProductName FROM tblproducts WHERE id = :productid"; // Use the correct column name
        $query = $dbh->prepare($sql);
        $query->bindParam(':productid', $selectedProductId, PDO::PARAM_INT);
        $query->execute();
        $product = $query->fetch(PDO::FETCH_OBJ);

        if ($product) {
            $productName = htmlentities($product->ProductName); // Use the correct column name
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
    <meta name="equipments" content="" />
    <title>Equipment Management System | View Equipment</title>
    <!-- BOOTSTRAP CORE STYLE -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- DATATABLE STYLE -->
    <link href="assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
    <!-- CUSTOM STYLE -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <link rel="icon" href="assets/img/src.png">
    <!-- SweetAlert CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body>
    <!-- MENU SECTION START-->
    <?php include('includes/header.php'); ?>
    <!-- MENU SECTION END-->
    <div class="container">
        <div class="row pad-botm">
            <div class="col-md-12">
                <h4 class="header-line" style="position: relative; top: 30px;"><?php echo $productName; ?></h4>
            </div>
        </div>

        <div class="row">
            <?php if(isset($_SESSION['error']) && $_SESSION['error'] != "") { ?>
                <div class="col-md-6">
                    <div class="alert alert-danger">
                        <strong>Error:</strong>
                        <?php echo htmlentities($_SESSION['error']); ?>
                        <?php $_SESSION['error'] = ""; ?>
                    </div>
                </div>
            <?php } ?>

            <?php if(isset($_SESSION['msg']) && $_SESSION['msg'] != "") { ?>
                <div class="col-md-6">
                    <div class="alert alert-success">
                        <strong>Success:</strong>
                        <?php echo htmlentities($_SESSION['msg']); ?>
                        <?php $_SESSION['msg'] = ""; ?>
                    </div>
                </div>
            <?php } ?>

            <?php if(isset($_SESSION['delmsg']) && $_SESSION['delmsg'] != "") { ?>
                <div class="col-md-6">
                    <div class="alert alert-danger">
                        <strong>Success:</strong>
                        <?php echo htmlentities($_SESSION['delmsg']); ?>
                        <?php $_SESSION['delmsg'] = ""; ?>
                    </div>
                </div>
            <?php } ?>

            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading" style="display: flex; justify-content: space-between; align-items: center;">
                            <span>Equipment Listing</span>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <!-- Add SKU Button -->
                                <a href="add-sku.php?productid=<?php echo htmlentities($selectedProductId); ?>" style="text-decoration: none;">
                                    <button class="btn btn-primary" style="background-color: #1B4D3E; color: #ddd; border: none; outline: none;">
                                        <i class="fa-solid fa-plus"></i> ADD
                                    </button>
                                </a>
                                <!-- Print Button -->
                                    <a href="print/print-sku.php?productid=<?php echo $selectedProductId; ?>" style="text-decoration: none;">
        <button class="btn btn-primary" style="background-color: #1B4D3E; color: #fff; border: none; outline: none;">
            <i class="fa fa-print"></i> Print
        </button>
    </a>

                                <!-- Close Button -->
                                <a href="manage-equipments.php" style="font-size: 30px; text-decoration: none; color: #000;">&times;</a>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th style="text-align: center;">SKU</th>
                                            <th style="text-align: center;">Conditions</th>
                                            <th style="text-align: center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($selectedProductId) {
                                            $sql = "SELECT 
                                                        tblproducts.ProductName, 
                                                        tblsku.SNumber, 
                                                        tblsku.remarks, 
                                                        tblsku.id as skuid 
                                                    FROM tblproducts 
                                                    JOIN tblsku ON tblproducts.id = tblsku.ProductId 
                                                    WHERE tblproducts.id = :productId";
                                            $query = $dbh->prepare($sql);
                                            $query->bindParam(':productId', $selectedProductId, PDO::PARAM_INT);
                                            $query->execute();
                                            $results = $query->fetchAll(PDO::FETCH_OBJ);
                                            $cnt = 1;
                                            if($query->rowCount() > 0) {
                                                foreach($results as $result) { ?>
                                                    <tr class="odd gradeX">
                                                        <td class="center"><?php echo htmlentities($cnt); ?></td>
                                                        <td class="center" style="text-align: center;"><?php echo htmlentities($result->SNumber); ?></td>
                                                        <td class="center" style="text-align: center;"><?php echo htmlentities($result->remarks); ?></td>                                               
                                                        <td class="center" style="text-align: center;">
                                                        <button class="btn btn-danger" onclick="confirmDelete(event, <?php echo htmlentities($result->skuid); ?>, '<?php echo htmlentities($result->SNumber); ?>')">
                                                        <i class="fa fa-trash"></i> Delete
                                                    </button>
                                                        </td>
                                                    </tr>
                                                <?php $cnt = $cnt + 1; 
                                                } 
                                            } else { ?>
                                                <tr>
                                                    <td colspan="4" style="text-align: center;">No SKUs found for this product.</td>
                                                </tr>
                                            <?php }
                                        } else { ?>
                                            <tr>
                                                <td colspan="4" style="text-align: center;">Please select a product to view SKUs.</td>
                                            </tr>
                                        <?php } ?>                          
                                    </tbody>
                                </table>
                            </div>                    
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

       <!---------------------------DELETE/JS----------------------------->
       <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    // Confirm deletion with SweetAlert
  // Confirm deletion with SweetAlert
function confirmDelete(event, id, SNumber) {
    event.preventDefault(); // Prevent the default anchor tag behavior

    Swal.fire({
        title: 'Are you sure you want to delete SKU ' + SNumber + '?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#135D66',
        confirmButtonText: 'Delete',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Proceed with the deletion if user confirms
            deleteSNumber(id, SNumber);  
        } else {
            Swal.fire({
                title: 'Cancelled',
                icon: 'info',
                confirmButtonColor: '#135D66',
                confirmButtonText: 'OK'
            });
        }
    });
}

// Function to handle SKU deletion
function deleteSNumber(id, SNumber) {
    fetch("view-equipment.php?del=" + id, {
        method: "GET", // Use GET to trigger the delete process
    })
    .then(response => {
        if (response.ok) {
            return response.text();  // Get the response text (you can customize the response if needed)
        } else {
            throw new Error('Network response was not ok');
        }
    })
    .then(data => {
        Swal.fire({
            title: 'SKU ' + SNumber + ' has been deleted successfully!',
            icon: 'success',
            confirmButtonColor: '#135D66',
            confirmButtonText: 'OK'
        }).then(() => {
            location.reload(); // Reload the page after deletion
        });
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            title: 'Error',
            text: 'An error occurred while deleting the SKU.',
            icon: 'error',
            confirmButtonColor: '#135D66',
            confirmButtonText: 'OK'
        });
    });
}
    </script>
    <!------------------------------------------------------------------>


    <!-- FOOTER SECTION END-->
    <?php include('includes/footer.php'); ?>
    <!-- FOOTER SECTION END-->
    <!-- JAVASCRIPT FILES PLACED AT THE BOTTOM TO REDUCE THE LOADING TIME -->
    <!-- CORE JQUERY -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS -->
    <script src="assets/js/bootstrap.js"></script>
    <!-- DATATABLE SCRIPTS -->
    <script src="assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="assets/js/dataTables/dataTables.bootstrap.js"></script>
    <!-- CUSTOM SCRIPTS -->
    <script src="assets/js/custom.js"></script>
    <!-- SweetAlert JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>
</html>