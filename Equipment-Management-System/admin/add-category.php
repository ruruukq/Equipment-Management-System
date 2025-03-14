<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
{   
    header('location:index.php');
}
else{ 
    if(isset($_POST['create']))
    {
        $category = $_POST['category'];

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
 
        // Check if the category already exists
        $sql_check = "SELECT * FROM tblcategory WHERE CategoryName = :category";
        $query_check = $dbh->prepare($sql_check);
        $query_check->bindParam(':category', $category, PDO::PARAM_STR);
        $query_check->execute();

        if($query_check->rowCount() > 0) {
            // Category already exists
            $_SESSION['error'] = "$category already exists!";
            header('location:manage-categories.php');
        } else {
            // Insert the new category
            $sql = "INSERT INTO tblcategory(CategoryName) VALUES(:category)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':category', $category, PDO::PARAM_STR);
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
                    $action_made = "Admin Added Category: " . $category;  // Log the category name in the action
                    action_made($dbh, $user_id, $action_made);  // Call the function to log the action
            
                    $_SESSION['msg'] = "$category Listed successfully";
                    header('location:manage-categories.php');
                    exit();
                } else {
                    $_SESSION['error'] = "Session expired. Please log in again.";
                    header('location:index.php');
                    exit();
                }
            } else {
                $_SESSION['error'] = "Something went wrong. Please try again";
                header('location:manage-categories.php');
                exit();
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
    <meta name="category" content="" />
    <title>Equipment Management System | Add Categories</title>
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
                <h4 class="header-line" style="position: relative; top: 30px;">Add category</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                <div class="panel panel-info">
                    <div class="panel-heading" style="display: flex; justify-content: space-between; align-items: center; background-color: #1B4D3E; color: #fff;">
                        <!-- X button aligned to the right -->
                        <span>Category Information</span>
                        <a href="manage-categories.php" style="font-size: 30px; text-decoration: none; color: #fff;">&times;</a> 
                        <!-- Category Info text aligned to the left -->
                    </div>
                    <div class="panel-body">
                    <form role="form" method="post" id="addCategoryForm">
                        <div class="form-group">
                            <label>Category Name</label>
                            <input class="form-control" type="text" name="category" autocomplete="off" required />
                        </div>
                        <button type="submit" name="create" class="btn btn-info" style="background-color: #1B4D3E; color: #fff;">Create</button>
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
document.querySelector('#addCategoryForm').addEventListener('submit', function() {
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
<?php } ?>