<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['login'])==0)
  { 
header('location:index.php');
}

else ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="equipment" content="" />
    <title>Equipment Management System | User Dashboard</title>
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
    <?php include('includes/header.php'); ?>
    <!-- MENU SECTION END-->

        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line" style="position: relative; top: 30px;">USER DASHBOARD</h4>
                </div>
            </div>

            <?php
        if (isset($error)) { ?>
            <div class="errorWrap"><strong>ERROR</strong>: <?php echo htmlentities($error); ?> </div>
        <?php } 
        if (isset($_SESSION['msg'])) { ?>
            <div class="succWrap"><strong>SUCCESS</strong>: <?php echo htmlentities($_SESSION['msg']); ?> </div>
            <?php unset($_SESSION['msg']); // Clear the success message after displaying it ?>
        <?php } ?>

            <div class="row">
                <a href="listed-equipments.php">
                    <div class="col-md-4 col-sm-4 col-xs-6">
                        <div class="alert alert-success back-widget-set text-center">
                            <i class="fa fa-folder fa-5x"></i>
                            <?php 
                            // SQL query to count only products with valid CategoryName and BrandName
                            $sql = "SELECT COUNT(tblproducts.id) as total
                                    FROM tblproducts
                                    INNER JOIN tblcategory ON tblcategory.CategoryName = tblproducts.CategoryName
                                    INNER JOIN tblbrands ON tblbrands.BrandName = tblproducts.BrandName";
                            $query = $dbh->prepare($sql);
                            $query->execute();
                            $result = $query->fetch(PDO::FETCH_OBJ);
                            $list = $result->total; // Get the total count
                            ?>
                            <h3><?php echo htmlentities($list); ?></h3>
                            Equipment Listed
                        </div>
                    </div>
                </a>

                <div class="col-md-4 col-sm-4 col-xs-6">
                    <div class="alert alert-warning back-widget-set text-center" onclick="window.location.href='not-returned.php';" style="cursor: pointer;">
                        <i class="fa fa-recycle fa-5x"></i>
                        <?php 
                        $rsts = 0;
                        $sid = $_SESSION['stdid'];
                        $sql2 = "SELECT id from tblissuedproducts where UserID=:sid and (ReturnStatus=:rsts || ReturnStatus is null || ReturnStatus='')";
                        $query2 = $dbh->prepare($sql2);
                        $query2->bindParam(':sid', $sid, PDO::PARAM_STR);
                        $query2->bindParam(':rsts', $rsts, PDO::PARAM_STR);
                        $query2->execute();
                        $results2 = $query2->fetchAll(PDO::FETCH_OBJ);
                        $returnedproducts = $query2->rowCount();
                        ?>
                        <h3><?php echo htmlentities($returnedproducts); ?></h3>
                        Equipments Not Returned Yet
                    </div>
                </div>

                <?php 
                $ret = $dbh->prepare("SELECT id from tblissuedproducts where UserID=:sid");
                $ret->bindParam(':sid', $sid, PDO::PARAM_STR);
                $ret->execute();
                $results22 = $ret->fetchAll(PDO::FETCH_OBJ);
                $totalissuedproducts = $ret->rowCount();
                ?>

                <a href="issued-equipments.php">
                    <div class="col-md-4 col-sm-4 col-xs-6">
                        <div class="alert alert-success back-widget-set text-center">
                            <i class="fa fa-book fa-5x"></i>
                            <h3><?php echo htmlentities($totalissuedproducts); ?></h3>
                            Total Issued Equipments
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
<br><br>
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