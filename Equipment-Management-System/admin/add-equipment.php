<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {
    if(isset($_POST['add'])) {
        $productname = $_POST['productname'];
        $categoryname = $_POST['category'];
        $brandname = $_POST['brand'];
        $productprice = $_POST['productprice'];
        $productImg = $_FILES["productpic"]["name"];
        $extension = substr($productImg, strlen($productImg) - 4, strlen($productImg));
        $allowed_extensions = array(".jpg", ".jpeg", ".png", ".gif");

        $imgnewname = md5($productImg . time()) . $extension;

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

        // Check if the product with the same name already exists
        $sql_check = "SELECT * FROM tblproducts WHERE ProductName = :productname";
        $query_check = $dbh->prepare($sql_check);
        $query_check->bindParam(':productname', $productname, PDO::PARAM_STR);
        $query_check->execute();

        if($query_check->rowCount() > 0) {
            // Product with the same name exists
            $_SESSION['error'] = "Equipment name ($productname) already exists!";
            header('location:manage-equipments.php');
            exit();
        } else {
            if (!in_array($extension, $allowed_extensions)) {
                $_SESSION['error'] = "Invalid format. Only jpg / jpeg / png / gif format allowed";
                header('location:add-equipment.php');
                exit();
            } else {
                move_uploaded_file($_FILES["productpic"]["tmp_name"], "productimg/" . $imgnewname);

                // Insert new product
                $sql = "INSERT INTO tblproducts (ProductName, CategoryName, BrandName, ProductPrice, productImage) 
                        VALUES(:productname, :category, :brand, :productprice, :imgnewname)";
                $query = $dbh->prepare($sql);
                $query->bindParam(':productname', $productname, PDO::PARAM_STR);
                $query->bindParam(':category', $categoryname, PDO::PARAM_STR);
                $query->bindParam(':brand', $brandname, PDO::PARAM_STR);
                $query->bindParam(':productprice', $productprice, PDO::PARAM_STR);
                $query->bindParam(':imgnewname', $imgnewname, PDO::PARAM_STR);
                $query->execute();

                $lastInsertId = $dbh->lastInsertId();
                if($lastInsertId) {
                    $admin_email = $_SESSION['alogin'];  // Get the logged-in admin's email
                    $sql = "SELECT id FROM admin WHERE UserName = :username";
                    $query = $dbh->prepare($sql);
                    $query->bindParam(':username', $admin_email, PDO::PARAM_STR);
                    $query->execute();
                    $admin_result = $query->fetch(PDO::FETCH_OBJ);
                    
                    if ($admin_result) {
                        $user_id = $admin_result->id;  // Get the admin's ID
                        // Log the action of adding the product
                        $action_made = "Admin Added Equipment: " . $productname;  // Log the product name in the action
                        action_made($dbh, $user_id, $action_made);  // Call the function to log the action
                
                        $_SESSION['msg'] = "$productname Listed successfully";
                        header('location:manage-equipments.php');
                        exit();
                    } else {
                        $_SESSION['error'] = "Session expired. Please log in again.";
                        header('location:index.php');
                        exit();
                    }
                } else {
                    $_SESSION['error'] = "Something went wrong. Please try again";
                    header('location:manage-equipments.php');
                    exit();
                }
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
    <title>Equipment Management System | Add Equipments</title>
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
                <h4 class="header-line" style="position: relative; top: 30px;">Add Equipment</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="panel panel-info">
                    <div class="panel-heading" style="display: flex; justify-content: space-between; align-items: center; background-color: #1B4D3E; color: #fff;">
                        <span>Equipment Information</span>
                        <a href="manage-equipments.php" style="font-size: 30px; text-decoration: none; color: #fff;">&times;</a>
                    </div>
                    <div class="panel-body">
                        <form role="form" method="post" enctype="multipart/form-data" id="addProductForm">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Equipment Name<span style="color:red;">*</span></label>
                                    <input class="form-control" type="text" name="productname" autocomplete="off" required />
                                </div>
                            </div>

                            <!-- Category Dropdown -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label> Category<span style="color:red;">*</span></label>
                                    <select class="form-control" name="category" required="required">
                                        <option value="" style="display: none;"> Select Category</option>
                                        <?php 
                                            $sql = "SELECT CategoryName FROM tblcategory";
                                            $query = $dbh->prepare($sql);
                                            $query->execute();
                                            $results = $query->fetchAll(PDO::FETCH_OBJ);     
                                            if ($query->rowCount() > 0) {
                                                foreach ($results as $result) {
                                        ?>  
                                        <option value="<?php echo htmlentities($result->CategoryName); ?>"><?php echo htmlentities($result->CategoryName); ?></option>
                                        <?php }} ?> 
                                    </select>
                                </div>
                            </div>

                            <!-- Brand Dropdown -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label> Brand<span style="color:red;">*</span></label>
                                    <select class="form-control" name="brand" required="required">
                                        <option value="" style="display: none;"> Select Brand</option>
                                        <?php 
                                            $sql = "SELECT BrandName FROM tblbrands";
                                            $query = $dbh->prepare($sql);
                                            $query->execute();
                                            $results = $query->fetchAll(PDO::FETCH_OBJ);
                                            if ($query->rowCount() > 0) {
                                                foreach ($results as $result) {
                                        ?>  
                                        <option value="<?php echo htmlentities($result->BrandName); ?>"><?php echo htmlentities($result->BrandName); ?></option>
                                        <?php }} ?> 
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Price<span style="color:red;">*</span></label>
                                    <input class="form-control" type="text" name="productprice" autocomplete="off" required="required" oninput="formatPrice(this)" />
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Product Picture<span style="color:red;">*</span></label>
                                    <input class="form-control" type="file" name="productpic" autocomplete="off" required="required" />
                                </div>
                            </div>

                            <div class="col-md-12">
                                <button type="submit" name="add" id="add" class="btn btn-info" style="background-color: #1B4D3E; color: #fff;">Submit </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom JavaScript for pop-up alert -->
    <script>
        // Flag to track if form has been modified
        let formModified = false;

        // Listen for any input change to set formModified to true
        document.querySelectorAll('input, select, textarea').forEach(element => {
            element.addEventListener('change', function() {
                formModified = true;
            });
        });

        // Flag to prevent the confirmation dialog on form submission
        let formSubmitting = false;

        // Listen for form submission
        document.querySelector('#addProductForm').addEventListener('submit', function() {
            formSubmitting = true;
        });

        // Custom confirmation dialog using JavaScript
        window.addEventListener('beforeunload', function(event) {
            if (formModified && !formSubmitting) {
                event.preventDefault(); // Prevent page from unloading
                
                // Show custom pop-up to the user
                let userConfirmed = confirm("You have unsaved changes. Do you really want to leave?");
                if (userConfirmed) {
                    // If user clicks "OK", allow the page to unload
                    window.location.href = document.referrer;  // Redirect to previous page or handle leaving as needed
                } else {
                    // If user clicks "Cancel", prevent the page from leaving
                    event.returnValue = '';  // Necessary for Chrome/Firefox, prevents leaving page
                }
            }
        });

        // Reset the form submitting flag after the page has unloaded (optional)
        window.addEventListener('unload', function() {
            formSubmitting = false;
        });
    </script>
    
    <?php include('includes/footer.php');?>
    <script src="assets/js/jquery-1.10.2.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/custom.js"></script>
</body>
</html>
<?php } ?>
