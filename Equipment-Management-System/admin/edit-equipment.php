<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
{   
    header('location:index.php');
}
else { 

    if (isset($_POST['update'])) {
        $productname = $_POST['productname'];
        $category = $_POST['category'];
        $brand = $_POST['brand'];
        $snumber = $_POST['snumber'];
        $productprice = $_POST['productprice'];
        $productid = intval($_GET['productid']);
        $productqty = $_POST['productqty'];
    
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
    
        // Check if Product Name already exists when it is being updated
        if ($productname != $_POST['old_productname']) { // Only check if Product Name is changed
            $sql_check_name = "SELECT id FROM tblproducts WHERE ProductName = :productname AND id != :productid";
            $query_check_name = $dbh->prepare($sql_check_name);
            $query_check_name->bindParam(':productname', $productname, PDO::PARAM_STR);
            $query_check_name->bindParam(':productid', $productid, PDO::PARAM_INT);
            $query_check_name->execute();
    
            if ($query_check_name->rowCount() > 0) {
                // Product Name already exists
                $_SESSION['error'] = "Error: Equipment Name ($productname) already exists!";
                header('Location: view-equipment.php?productid=' . urlencode($productid));
                exit();
            }
        }
    
        // Check if SKU (SNumber) already exists when it is being updated
        if ($snumber != $_POST['old_snumber']) { // Only check if SKU is changed
            $sql_check_sku = "SELECT id FROM tblproducts WHERE SNumber = :snumber AND id != :productid";
            $query_check_sku = $dbh->prepare($sql_check_sku);
            $query_check_sku->bindParam(':snumber', $snumber, PDO::PARAM_STR);
            $query_check_sku->bindParam(':productid', $productid, PDO::PARAM_INT);
            $query_check_sku->execute();
    
            if ($query_check_sku->rowCount() > 0) {
                // SKU already exists
                $_SESSION['error'] = "Error: SKU ($snumber) already exists!";
                header('Location: view-equipment-details.php?productid=' . urlencode($productid));
                exit();
            }
        }
    
        // Retrieve current availableQty from the database before updating
        $sql = "SELECT productQty, availableQty FROM tblproducts WHERE id = :productid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':productid', $productid, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);
    
        // Calculate new availableQty based on the updated productQty
        $currentAvailableQty = $result->availableQty;
        $newProductQty = $productqty;
    
        // Calculate the difference between the new and old product quantities
        $quantityDifference = $newProductQty - $result->productQty;
    
        // Update the availableQty based on the difference
        $newAvailableQty = $currentAvailableQty + $quantityDifference;
    
        // If no duplicates, proceed with the update query
        $sql_update = "UPDATE tblproducts 
                       SET ProductName = :productname, 
                           CategoryName = :category, 
                           BrandName = :brand, 
                           ProductPrice = :productprice, 
                           productQty = :productqty, 
                           availableQty = :availableQty, 
                           SNumber = :snumber 
                       WHERE id = :productid";
        $query_update = $dbh->prepare($sql_update);
        $query_update->bindParam(':productname', $productname, PDO::PARAM_STR);
        $query_update->bindParam(':category', $category, PDO::PARAM_STR);
        $query_update->bindParam(':brand', $brand, PDO::PARAM_STR);
        $query_update->bindParam(':snumber', $snumber, PDO::PARAM_STR);
        $query_update->bindParam(':productprice', $productprice, PDO::PARAM_STR);
        $query_update->bindParam(':productid', $productid, PDO::PARAM_INT);
        $query_update->bindParam(':productqty', $newProductQty, PDO::PARAM_INT);
        $query_update->bindParam(':availableQty', $newAvailableQty, PDO::PARAM_INT);
        $query_update->execute();
    
        $admin_email = $_SESSION['alogin'];  // Get the logged-in admin's email
        $sql_admin = "SELECT id FROM admin WHERE UserName = :username";
        $query_admin = $dbh->prepare($sql_admin);
        $query_admin->bindParam(':username', $admin_email, PDO::PARAM_STR);
        $query_admin->execute();
        $admin_result = $query_admin->fetch(PDO::FETCH_OBJ);
    
        $user_id = $admin_result->id;  // Get the admin's ID
        action_made($dbh, $user_id, "Admin Updated $productname Information");
        $_SESSION['msg'] = "Equipment Information Updated successfully";
        header('Location: view-equipment-details.php?productid=' . urlencode($productid));
        exit();
    }
    
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
    <title>Equipment Management System | Edit Equipment</title>
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
                <h4 class="header-line" style="position: relative; top: 30px;">Edit Equipment</h4>
                
                            </div>

</div>
<div class="row">
<div class="col-md12 col-sm-12 col-xs-12">
<div class="panel panel-info">
<div class="panel-heading" style="display: flex; justify-content: space-between; align-items: center; background-color: #1B4D3E; color: #fff;">
    <span>Equipment Information</span>
    <a href="view-equipment-details.php?productid=<?php echo htmlentities($result->productid); ?>" style="font-size: 30px; text-decoration: none; color: #fff;">&times;</a>
</div>
<div class="panel-body">
<form role="form" method="post" id="editProductForm">
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
        <a href="change-equipmentimg.php?productid=<?php echo htmlentities($result->productid); ?>&productname=<?php echo urlencode($result->ProductName); ?>&productprice=<?php echo urlencode($result->ProductPrice); ?>&productqty=<?php echo urlencode($result->productQty); ?>&return_url=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>">Change Equipment Image</a>
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label>Equipment Name</label>
        <input class="form-control" type="text" name="productname" value="<?php echo htmlentities($result->ProductName);?>" />
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label> Category</label>
        <select class="form-control" name="category" required="required">
            <option value="<?php echo htmlentities($result->CategoryName); ?>"><?php echo htmlentities($result->CategoryName); ?></option>
            <?php 
            $sql1 = "SELECT CategoryName FROM tblcategory"; // Removed status condition
            $query1 = $dbh->prepare($sql1);
            $query1->execute();
            $resultss = $query1->fetchAll(PDO::FETCH_OBJ);
            if ($query1->rowCount() > 0) {
                foreach ($resultss as $row) {
                    if ($result->CategoryName == $row->CategoryName) {
                        continue; // Skip the current category
                    } else {
                        ?>
                        <option value="<?php echo htmlentities($row->CategoryName); ?>"><?php echo htmlentities($row->CategoryName); ?></option>
                        <?php
                    }
                }
            }
            ?>
        </select>
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label> Brand</label>
        <select class="form-control" name="brand" required="required">
            <option value="<?php echo htmlentities($result->BrandName); ?>"><?php echo htmlentities($result->BrandName); ?></option>
            <?php 
            $sql2 = "SELECT BrandName FROM tblbrands";
            $query2 = $dbh->prepare($sql2);
            $query2->execute();
            $result2 = $query2->fetchAll(PDO::FETCH_OBJ);
            if ($query2->rowCount() > 0) {
                foreach ($result2 as $ret) {
                    if ($result->BrandName == $ret->BrandName) {
                        continue; // Skip the current brand
                    } else {
                        ?>
                        <option value="<?php echo htmlentities($ret->BrandName); ?>"><?php echo htmlentities($ret->BrandName); ?></option>
                        <?php
                    }
                }
            }
            ?>
        </select>
    </div>
</div>


<div class="col-md-6">
                            <div class="form-group">
                                <label>Price in PHP</label>
                                <input class="form-control" type="text" name="productprice" value="<?php echo htmlentities(number_format($result->ProductPrice, 2));?>"  />
                            </div>
                        </div>

<!-- <div class="col-md-6">  
    <div class="form-group">
        <label>Equipment Quantity</label>
        <div class="input-group">
            <input class="form-control" type="text" oninput="this.value=this.value.replace(/[^0-9]/g,'');" name="productqty" id="productqty" autocomplete="off" value="<?php echo htmlentities($result->productQty);?>" />
        </div>
    </div> 
</div> -->

<?php }} ?>

<div class="col-md-12">
    <button type="submit" name="update" class="btn btn-info" style="background-color: #1B4D3E; color: #fff;">Update </button>
</div>

</form>
</div>
</div>
</div>

</div>
</div>
</div>


<!-- Hidden Field to Store the Original Available Quantity -->
<input type="hidden" id="availableQty" value="<?php echo htmlentities($result->productQty); ?>">

<script>
    document.getElementById("increaseQty").addEventListener("click", function() {
        let currentQty = parseInt(document.getElementById("productqty").value);
        let availableQty = parseInt(document.getElementById("availableQty").value);

        // Update the productQty by adding 1 (or change this to any value you want)
        currentQty += 1;
        availableQty += 1;

        // Update both fields
        document.getElementById("productqty").value = currentQty;
        document.getElementById("availableQty").value = availableQty;
    });

    document.getElementById("decreaseQty").addEventListener("click", function() {
        let currentQty = parseInt(document.getElementById("productqty").value);
        let availableQty = parseInt(document.getElementById("availableQty").value);

        if (currentQty > 0) {
            // Update the productQty by subtracting 1
            currentQty -= 1;
            availableQty -= 1;

            // Update both fields
            document.getElementById("productqty").value = currentQty;
            document.getElementById("availableQty").value = availableQty;
        }
    });
</script>

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
document.querySelector('#editProductForm').addEventListener('submit', function() {
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