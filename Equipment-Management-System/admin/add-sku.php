<?php
session_start();
error_reporting(E_ALL); // Enable error reporting
ini_set('display_errors', 1); // Display errors
include('includes/config.php');

if(strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit();
} else {
    // Check if productid is provided in the URL
    if (!isset($_GET['productid'])) {
        $_SESSION['error'] = "Product ID is missing.";
        header('location:manage-equipments.php');
        exit();
    }

    $productId = $_GET['productid']; // Get productid from the URL

    if(isset($_POST['add_sku'])) {
        $sNumber = $_POST['sNumber']; // New SKU to be added
        $remarks = $_POST['remarks']; // Remarks (Good Condition or Damaged)

        // Check if SNumber already exists in tblsku
        $sql_check = "SELECT COUNT(*) AS count FROM tblsku WHERE SNumber = :sNumber";
        $query_check = $dbh->prepare($sql_check);
        $query_check->bindParam(':sNumber', $sNumber, PDO::PARAM_STR);
        $query_check->execute();
        $result = $query_check->fetch(PDO::FETCH_ASSOC);

        if ($result['count'] > 0) {
            // SNumber already exists
            $_SESSION['error'] = "SKU already exists.";
            header('location:view-equipment.php?productid=' . $productId);
            exit();
        }

        // Fetch the product details from tblproducts
        $sql_product = "SELECT * FROM tblproducts WHERE id = :productId";
        $query_product = $dbh->prepare($sql_product);
        $query_product->bindParam(':productId', $productId, PDO::PARAM_INT);
        $query_product->execute();
        $product = $query_product->fetch(PDO::FETCH_ASSOC);

        if($product) {
            // Insert the new SKU into tblsku
            $sql_sku = "INSERT INTO tblsku (ProductId, SNumber, remarks) 
                        VALUES (:productId, :sNumber, :remarks)";
            $query_sku = $dbh->prepare($sql_sku);
            $query_sku->bindParam(':productId', $productId, PDO::PARAM_INT);
            $query_sku->bindParam(':sNumber', $sNumber, PDO::PARAM_STR);
            $query_sku->bindParam(':remarks', $remarks, PDO::PARAM_STR);
            $query_sku->execute();

            $lastInsertId = $dbh->lastInsertId();
            if($lastInsertId) {
                // Update productQty and availableQty in tblproducts
                $sql_update = "UPDATE tblproducts 
                               SET productQty = productQty + 1, 
                                   availableQty = availableQty + 1 
                               WHERE id = :productId";
                $query_update = $dbh->prepare($sql_update);
                $query_update->bindParam(':productId', $productId, PDO::PARAM_INT);
                $query_update->execute();

                $_SESSION['msg'] = "$sNumber added successfully for product: " . $product['ProductName'];
                header('location:view-equipment.php?productid=' . $productId);
                exit();
            } else {
                $_SESSION['error'] = "Something went wrong. Please try again";
                header('location:view-equipment.php?productid=' . $productId);
                exit();
            }
        } else {
            $_SESSION['error'] = "Product not found.";
            header('location:view-equipment.php?productid=' . $productId);
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
    <title>Equipment Management System | Add SKU</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <link rel="icon" href="assets/img/src.png">
</head>
<body>

<?php include('includes/header.php');?>

    <div class="container">
        <div class="row pad-botm">
            <div class="col-md-12">
                <h4 class="header-line">Add STOCK KEEPING UNIT</h4>
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
    <span>Stock Keeping Unit Information</span>
    <a href="view-equipment.php?productid=<?php echo htmlentities($productId); ?>" style="font-size: 30px; text-decoration: none; color: #fff;">&times;</a>
</div>
                    <div class="panel-body">
                        <form role="form" method="post" enctype="multipart/form-data">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>SKU<span style="color:red;">*</span></label>
                                    <input class="form-control" type="text" name="sNumber" autocomplete="off" required />
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Remarks<span style="color:red;">*</span></label><br>
                                    <label>
                                        <input type="radio" name="remarks" value="Good Condition" required /> Good Condition
                                    </label><br>
                                    <label>
                                        <input type="radio" name="remarks" value="Damaged" required /> Damaged
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <button type="submit" name="add_sku" style="background-color: #1B4D3E; color: #fff;" class="btn btn-info">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
             </div>
        </div>
    </div>

    <?php include('includes/footer.php');?>
<script src="assets/js/jquery-1.10.2.js"></script>
<script src="assets/js/bootstrap.js"></script>
<script src="assets/js/custom.js"></script>
</body>
</html>