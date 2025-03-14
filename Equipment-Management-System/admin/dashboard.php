<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include('includes/config.php');

// Redirect to login if not authenticated
if (!isset($_SESSION['alogin'])) {
    header('location: index.php');
    exit();
}

// Fetch data from the database
try {
    // Total Products
    $sqlProducts = "SELECT COUNT(*) as total FROM tblproducts";
    $queryProducts = $dbh->prepare($sqlProducts);
    $queryProducts->execute();
    $resultProducts = $queryProducts->fetch(PDO::FETCH_ASSOC);
    $listproducts = $resultProducts['total'];

    // Products Not Returned
    $sqlNotReturned = "SELECT COUNT(*) as total FROM tblissuedproducts WHERE ReturnStatus = 0";
    $queryNotReturned = $dbh->prepare($sqlNotReturned);
    $queryNotReturned->execute();
    $resultNotReturned = $queryNotReturned->fetch(PDO::FETCH_ASSOC);
    $returnedproducts = $resultNotReturned['total'];

    // Registered Users
    $sqlUsers = "SELECT COUNT(*) as total FROM tblusers";
    $queryUsers = $dbh->prepare($sqlUsers);
    $queryUsers->execute();
    $resultUsers = $queryUsers->fetch(PDO::FETCH_ASSOC);
    $regstds = $resultUsers['total'];

    // Total Brands
    $sqlBrands = "SELECT COUNT(*) as total FROM tblbrands";
    $queryBrands = $dbh->prepare($sqlBrands);
    $queryBrands->execute();
    $resultBrands = $queryBrands->fetch(PDO::FETCH_ASSOC);
    $listbrands = $resultBrands['total'];

    // Total Categories
    $sqlCategories = "SELECT COUNT(*) as total FROM tblcategory";
    $queryCategories = $dbh->prepare($sqlCategories);
    $queryCategories->execute();
    $resultCategories = $queryCategories->fetch(PDO::FETCH_ASSOC);
    $listdcats = $resultCategories['total'];
} catch (PDOException $e) {
    // Handle database errors
    echo "Error: " . $e->getMessage();
    exit();
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Equipment Management System | Admin Dash Board</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <link rel="icon" href="assets/img/src.png">
    <style>
        body {
            margin-bottom: 0;
        }

        .content-wrapper {
            padding-bottom: 0;
        }
    </style>
</head>
<body>
    <?php include('includes/header.php'); ?>
    <div class="container">
        <div class="row pad-botm">
            <div class="col-md-12">
                <h4 class="header-line" style="position: relative; top: 30px;">ADMIN DASHBOARD</h4>
            </div>
        </div>

        <!-- Dashboard Widgets -->
        <div class="row">
            <a href="manage-equimpments.php">
                <div class="col-md-3 col-sm-3 col-xs-6">
                    <div class="alert alert-success back-widget-set text-center">
                        <i class="fa fa-box fa-5x"></i>
                        <h3><?php echo htmlentities($listproducts); ?></h3>
                        Equipments Listed
                    </div>
                </div>
            </a>
            <a href="manage-not-returned.php">
                <div class="col-md-3 col-sm-3 col-xs-6">
                    <div class="alert alert-warning back-widget-set text-center">
                        <i class="fa fa-recycle fa-5x"></i>
                        <h3><?php echo htmlentities($returnedproducts); ?></h3>
                        Equipments Not Returned Yet
                    </div>
                </div>
            </a>
            <a href="reg-users.php">
                <div class="col-md-3 col-sm-3 col-xs-6">
                    <div class="alert alert-danger back-widget-set text-center">
                        <i class="fa fa-users fa-5x"></i>
                        <h3><?php echo htmlentities($regstds); ?></h3>
                        Registered Users
                    </div>
                </div>
            </a>
            <a href="manage-brands.php">
                <div class="col-md-3 col-sm-3 col-xs-6">
                    <div class="alert alert-success back-widget-set text-center">
                        <i class="fa fa-tags fa-5x"></i>
                        <h3><?php echo htmlentities($listbrands); ?></h3>
                        Brands Listed
                    </div>
                </div>
            </a>
        </div>
        <div class="row">
            <a href="manage-categories.php">            
                <div class="col-md-3 col-sm-3 rscol-xs-6">
                    <div class="alert alert-info back-widget-set text-center">
                        <i class="fa fa-file-archive-o fa-5x"></i>
                        <h3><?php echo htmlentities($listdcats); ?></h3>
                        Listed Categories
                    </div>
                </div>
            </a>
        </div>
    </div>

    <script src="assets/js/jquery-1.10.2.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/custom.js"></script>

</body>
</html>