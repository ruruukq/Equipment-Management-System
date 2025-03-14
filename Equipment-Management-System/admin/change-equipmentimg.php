<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin'])==0) {   
    header('location:index.php');
} else { 
    if(isset($_POST['update'])) {
        $productid = intval($_GET['productid']);
        $productImg = $_FILES["productpic"]["name"];
        $extension = substr($productImg, strlen($productImg) - 4, strlen($productImg));
        $allowed_extensions = array(".jpg", ".jpeg", ".png", ".gif");

        // Generate a unique name for the uploaded image
        $imgnewname = md5($productImg . time()) . $extension;

        // Validate the image format
        if (!in_array($extension, $allowed_extensions)) {
            echo "<script>alert('Invalid format. Only jpg / jpeg / png / gif format allowed');</script>";
        } else {
            // Define the upload directory
            $upload_dir = "productimg/";
            $upload_file = $upload_dir . $imgnewname;

            // Check if the image already exists
            if (file_exists($upload_file)) {
                // Image already exists, inform the user
                $_SESSION['error'] = "Image already exists!";
                header('location:manage-equipments.php');
                exit();
            }

            // Move the uploaded file to the server directory
            if (move_uploaded_file($_FILES["productpic"]["tmp_name"], $upload_file)) {
                // Retrieve the current image for deletion
                $sql = "SELECT productImage, ProductName FROM tblproducts WHERE id = :productid";
                $query = $dbh->prepare($sql);
                $query->bindParam(':productid', $productid, PDO::PARAM_INT);
                $query->execute();
                $result = $query->fetch(PDO::FETCH_OBJ);

                // Get the Product Name
                $productname = $result->ProductName;

                // Delete the current image if it exists
                if ($result && file_exists("productimg/" . $result->productImage)) {
                    unlink("productimg/" . $result->productImage);
                }

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

                // Update the product with the new image name
                $sql_update = "UPDATE tblproducts SET productImage = :imgnewname WHERE id = :productid";
                $query_update = $dbh->prepare($sql_update);
                $query_update->bindParam(':imgnewname', $imgnewname, PDO::PARAM_STR);
                $query_update->bindParam(':productid', $productid, PDO::PARAM_INT);
                $query_update->execute();

                // Get the admin's email and ID to log the action
                $admin_email = $_SESSION['alogin'];  // Get the logged-in admin's email
                $sql = "SELECT id FROM admin WHERE UserName = :username";
                $query = $dbh->prepare($sql);
                $query->bindParam(':username', $admin_email, PDO::PARAM_STR);
                $query->execute();
                $admin_result = $query->fetch(PDO::FETCH_OBJ);
                
                $user_id = $admin_result->id;  // Get the admin's ID
                // Log the action, including the product name in the log message
                $action_made = "Admin Updated $productname's Image";
                action_made($dbh, $user_id, $action_made);     
                $_SESSION['msg'] = "Image Updated successfully";
                header('location:manage-equipments.php');
                exit();
            } else {
                $_SESSION['error'] = "Something went wrong. Please try again";
                header('location:manage-equipments.php');
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
    <title>Equipment Management System | Edit Image</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <link rel="icon" href="assets/img/src.png">

    <style>
        /* Apply flex layout to the parent div */
.col-md-6 {
    display: flex;
    flex-direction: column;
    justify-content: flex-start;  /* Align elements to the top */
    height: 100%; /* Ensure that it takes the full height of the container */
}

.form-group {
    flex: 0; /* Prevent form groups from stretching to full height */
}

/* Optional: Adjust space between elements */
.form-group + .form-group {
    margin-top: 20px; /* Add spacing between form groups */
}

    </style>
</head>
<body>
    <?php include('includes/header.php'); ?>
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line" style="position: relative; top: 30px;">Edit Image</h4>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-info">
                    <div class="panel-heading" style="display: flex; justify-content: space-between; align-items: center; background-color: #1B4D3E; color: #fff;">
    <!-- X button aligned to the right -->
    <span>Equipment Information</span>
    <a href="<?php echo $_SERVER['HTTP_REFERER']; ?>" style="font-size: 30px; text-decoration: none; color: #fff;">&times;</a>

    <!-- Category Info text aligned to the left -->
</div>
                        <div class="panel-body">
                            <form role="form" method="post" enctype="multipart/form-data" id="editImageForm">
                                <?php 
                                    $productid = intval($_GET['productid']);
                                    $sql = "SELECT ProductName, productImage FROM tblproducts WHERE id = :productid";
                                    $query = $dbh->prepare($sql);
                                    $query->bindParam(':productid', $productid, PDO::PARAM_INT);
                                    $query->execute();
                                    $result = $query->fetch(PDO::FETCH_OBJ);

                                    if ($result) {
                                ?>
                                <input type="hidden" name="curremtimage" value="<?php echo htmlentities($result->productImage);?>">
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Product Image</label>
                                        <img src="productimg/<?php echo htmlentities($result->productImage);?>" width="100">
                                    </div>
                                </div>

                                <div class="col-md-6">
    <div class="form-group">
        <label>Equipment Name<span style="color:red;">*</span></label>
        <input class="form-control" type="text" name="productname" value="<?php echo htmlentities($result->ProductName);?>" readonly />
    </div>

    <!-- Added Flexbox Wrapper -->
    <div class="form-group" style="margin-top: auto;">
        <label>Equipment Picture<span style="color:red;">*</span></label>
        <input class="form-control" type="file" name="productpic" required="required" />
    </div>
</div>


                                <?php } ?>
                                <div class="col-md-12">
                                    <button type="submit" name="update" class="btn btn-info" style="background-color: #1B4D3E; color: #fff;">Update</button>
                                </div>
                            </form>
                        </div>
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
document.querySelector('#editImageForm').addEventListener('submit', function() {
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
    <?php include('includes/footer.php'); ?>

    <script src="assets/js/jquery-1.10.2.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/custom.js"></script>
</body>
</html>

<?php } ?>
