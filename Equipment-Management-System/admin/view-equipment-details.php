<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
{   
    header('location:index.php');
}
else { 
    // Handle product deletion
    if(isset($_GET['del'])) {
        $id = $_GET['del'];

        // Fetch the product's name before deleting
        $sql_products = "SELECT ProductName FROM tblproducts WHERE id = :id";
        $query_products = $dbh->prepare($sql_products);
        $query_products->bindParam(':id', $id, PDO::PARAM_INT);
        $query_products->execute();
        $products = $query_products->fetch(PDO::FETCH_OBJ);  // Fetch product name

        // Prepare delete query 
        $sql = "DELETE FROM tblproducts WHERE id=:id";

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

        // Prepare and execute the delete statement
        try {
            $query = $dbh->prepare($sql);
            $query->bindParam(':id', $id, PDO::PARAM_STR);

            // Execute the query
            if ($query->execute()) {
                $admin_email = $_SESSION['alogin'];  // Get the logged-in admin's email
                $sql = "SELECT id FROM admin WHERE UserName = :username";
                $query = $dbh->prepare($sql);
                $query->bindParam(':username', $admin_email, PDO::PARAM_STR);
                $query->execute();
                $admin_result = $query->fetch(PDO::FETCH_OBJ);
          
                $user_id = $admin_result->id;  // Get the admin's ID

                // Log the action with the product name
                $action_made = "Admin Equipment Product: " . $products->ProductName;
                action_made($dbh, $user_id, $action_made);  // Call the function to log the action

                $_SESSION['delmsg'] = "Equipment deleted successfully"; // Set the success message
            } else {
                // Handle any errors if query fails
                $_SESSION['delmsg'] = "Error deleting equipment. Please try again."; // Set error message
            }
        } catch (Exception $e) {
            // Catch any exceptions and display error
            $_SESSION['delmsg'] = "Error: " . $e->getMessage();
        }

        // Redirect to manage-products.php after deletion
        header('location:manage-equipments.php');
        exit(); // Ensure no further code is executed after redirect
    }

    // Fetch product details for viewing
    $productid = intval($_GET['productid']);
    $sql = "SELECT ProductName, CategoryName, BrandName, SNumber, ProductPrice, id as productid, productImage, productQty 
            FROM tblproducts 
            WHERE id = :productid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':productid', $productid, PDO::PARAM_INT);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if($query->rowCount() > 0) {
        $result = $results[0]; // Assign the first result to $result
    } else {
        // Handle the case where no product is found
        echo "Product not found.";
        exit();
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
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <link rel="icon" href="assets/img/src.png">
</head>
<body>
      <!------MENU SECTION START-->
<?php include('includes/header.php');?>
<!-- MENU SECTION END-->
         <div class="container">
        <div class="row pad-botm">
            <div class="col-md-12">
                <h4 class="header-line" style="position: relative; top: 30px;">View Equipment</h4>
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
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="panel panel-info">
                    <div class="panel-heading" style="display: flex; justify-content: space-between; align-items: center; background-color: #1B4D3E; color: #fff;">
                        <span>Equipment Information</span>
                        <a href="manage-equipments.php" style="font-size: 30px; text-decoration: none; color: #fff;">&times;</a> 
                    </div>
                    <div class="panel-body">
                        <form role="form" method="post" id="viewProductForm">
                            <?php 
                                $productid = intval($_GET['productid']);
                                $sql = "SELECT ProductName, CategoryName, BrandName, SNumber, ProductPrice, id as productid, productImage, productQty 
                                        FROM tblproducts 
                                        WHERE id = :productid";
                                $query = $dbh->prepare($sql);
                                $query->bindParam(':productid', $productid, PDO::PARAM_INT);
                                $query->execute();
                                $results = $query->fetchAll(PDO::FETCH_OBJ);

                                if($query->rowCount() > 0) {
                                    foreach($results as $result) {   
                            ?>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Equipment Image</label>
                                    <img src="productimg/<?php echo htmlentities($result->productImage); ?>" width="100">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Equipment Name<span style="color:red;">*</span></label>
                                    <input class="form-control" type="text" name="productname" value="<?php echo htmlentities($result->ProductName);?>" readonly />
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label> Category <span style="color:red;">*</span></label>
                                    <input class="form-control" type="text" name="category" value="<?php echo htmlentities($result->CategoryName); ?>" readonly />
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label> Brand <span style="color:red;">*</span></label>
                                    <input class="form-control" type="text" name="brand" value="<?php echo htmlentities($result->BrandName); ?>" readonly />
                                </div>
                            </div>


                            <div class="col-md-6">
                            <div class="form-group">
                                <label>Price in PHP<span style="color:red;">*</span></label>
                                <input class="form-control" type="text" name="productprice" value="<?php echo htmlentities(number_format($result->ProductPrice, 2));?>" readonly />
                            </div>
                        </div>


                            <div class="col-md-6">  
                                <div class="form-group">
                                    <label>Equipment Quantity<span style="color:red;">*</span></label>
                                    <div class="input-group">
                                        <input class="form-control" type="number" name="productqty" id="productqty" autocomplete="off" value="<?php echo htmlentities($result->productQty);?>" readonly />
                                    </div>
                                </div>
                            </div>

                            <?php }} ?>

                            <div class="col-md-12">
                                <a href="edit-equipment.php?productid=<?php echo $productid; ?>" class="btn btn-info" style="background-color: #1B4D3E; color: #fff;">Edit</a>
                                <button class="btn btn-danger" onclick="confirmDelete(event, <?php echo htmlentities($result->productid); ?>, '<?php echo htmlentities($result->ProductName); ?>')"><i class="fa fa-trash"></i> Delete</button>
                            </div>

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

    <!---------------------------DELETE/JS----------------------------->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    // Confirm deletion with SweetAlert
    function confirmDelete(event, id, product) {
        event.preventDefault(); // Prevent the default anchor tag behavior

        Swal.fire({
            title: 'Are you sure you want to delete ' + product + '?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#135D66',
            confirmButtonText: 'Delete',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Proceed with the deletion if user confirms
                deleteProduct(id, product);  
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

    // Function to handle product deletion
    function deleteProduct(id, product) {
    fetch("view-equipment-details.php?del=" + id, { // Corrected URL to target the current page
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
            title: product + ' has been deleted successfully!',
            icon: 'success',
            confirmButtonColor: '#135D66',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href = "manage-equipments.php"; // Redirect to manage-products.php after deletion
        });
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            title: 'Error',
            text: 'An error occurred while deleting the product.',
            icon: 'error',
            confirmButtonColor: '#135D66',
            confirmButtonText: 'OK'
        });
    });
}
    </script>
    <!------------------------------------------------------------------>
</body>
</html>
<?php } ?>