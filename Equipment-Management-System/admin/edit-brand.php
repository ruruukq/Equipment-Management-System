<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
{   
    header('location:index.php');
}
else { 
    if(isset($_POST['update'])) {
        $athrid = intval($_GET['athrid']);  // Get the brand ID from the URL
        $brand = $_POST['brand'];  // Get the new brand name

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

        // Check if the brand already exists
        $sql_check = "SELECT * FROM tblbrands WHERE BrandName = :brand";
        $query_check = $dbh->prepare($sql_check);
        $query_check->bindParam(':brand', $brand, PDO::PARAM_STR);
        $query_check->execute();

        if($query_check->rowCount() > 0) {
            // Brand already exists
            $_SESSION['error'] = "$brand already exists!";
            header('location:manage-brands.php');
        } else {
            // Fetch the previous brand name before the update
            $sql_old_brand = "SELECT BrandName FROM tblbrands WHERE id = :athrid";
            $query_old_brand = $dbh->prepare($sql_old_brand);
            $query_old_brand->bindParam(':athrid', $athrid, PDO::PARAM_INT);
            $query_old_brand->execute();
            $old_brand = $query_old_brand->fetch(PDO::FETCH_OBJ);

            // Update the brand name in the tblbrands table
            $sql = "UPDATE tblbrands SET BrandName = :brand WHERE id = :athrid";
            $query = $dbh->prepare($sql);
            $query->bindParam(':brand', $brand, PDO::PARAM_STR);
            $query->bindParam(':athrid', $athrid, PDO::PARAM_INT);
            $query->execute();

            // Update the BrandName in the tblproducts table
            if ($old_brand) {
                $sql_update_products = "UPDATE tblproducts SET BrandName = :brand WHERE BrandName = :old_brand";
                $query_update_products = $dbh->prepare($sql_update_products);
                $query_update_products->bindParam(':brand', $brand, PDO::PARAM_STR);
                $query_update_products->bindParam(':old_brand', $old_brand->BrandName, PDO::PARAM_STR);
                $query_update_products->execute();
            }

            // Get the logged-in admin's ID
            $admin_email = $_SESSION['alogin'];
            $sql = "SELECT id FROM admin WHERE UserName = :username";
            $query = $dbh->prepare($sql);
            $query->bindParam(':username', $admin_email, PDO::PARAM_STR);
            $query->execute();
            $admin_result = $query->fetch(PDO::FETCH_OBJ);
            
            $user_id = $admin_result->id;  // Admin's ID

            // Log the action with the old and new brand name
            if ($old_brand) {
                $action_made = "Admin Updated the Brand from " . $old_brand->BrandName . " to " . $brand;
                action_made($dbh, $user_id, $action_made);
            }

            $_SESSION['msg'] = "Brand Updated successfully";
            header('location:manage-brands.php');
            exit();
        }
    }
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="brand" content="" />
    <title>Equipment Management System | Add Brand</title>
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
                <h4 class="header-line" style="position: relative; top: 30px;">Edit Brand</h4>
                
                            </div>

</div>
<div class="row">
<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
<div class="panel panel-info">
<div class="panel-heading" style="display: flex; justify-content: space-between; align-items: center; background-color: #1B4D3E; color: #fff;">
    <span>Brand Information</span>
    <a href="manage-brands.php" style="font-size: 30px; text-decoration: none; color: #fff;">&times;</a> 
</div>
<div class="panel-body">
<form role="form" method="post" id="editBrandForm">
<div class="form-group">
<label>Brand Name</label>
<?php 
$athrid=intval($_GET['athrid']);
$sql = "SELECT * from  tblbrands where id=:athrid";
$query = $dbh -> prepare($sql);
$query->bindParam(':athrid',$athrid,PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{               ?>   
<input class="form-control" type="text" name="brand" value="<?php echo htmlentities($result->BrandName);?>" required />
<?php }} ?>
</div>

<button type="submit" name="update" class="btn btn-info" style="background-color: #1B4D3E; color: #fff;">Update </button>

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
document.querySelector('#editBrandForm').addEventListener('submit', function() {
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