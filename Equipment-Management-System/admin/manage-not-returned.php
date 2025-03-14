<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
{   
    header('location:index.php');
}
else{ 
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="product" content="" />
    <title>Equipment Management System | Manage Issued Product</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- DATATABLE STYLE  -->
    <link href="assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
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
    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">Not Returned Products</h4>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Issued Products 
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th style="text-align: center;">Names</th>
                                            <th style="text-align: center;">Role</th>
                                            <th style="text-align: center;">Product Name</th>
                                            <th style="text-align: center;">SKU</th>
                                            <th style="text-align: center;">Issued Date</th>
                                            <th style="text-align: center;">Expected Return Date</th>
                                            <th style="text-align: center;">Return Date</th>
                                            <th style="text-align: center;">Current Quantity</th>
                                            <th style="text-align: center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody style="text-align: center;">
                                        <?php 
                                        $sql = "SELECT tblusers.FullName, tblusers.Usertype, tblproducts.ProductName, tblproducts.SNumber, tblproducts.productImage, tblissuedproducts.IssuesDate, tblissuedproducts.ReturnDate, tblissuedproducts.ExpReturn, tblissuedproducts.quantity, tblissuedproducts.id as rid 
                                                FROM tblissuedproducts 
                                                JOIN tblusers ON tblusers.UserId = tblissuedproducts.UserId 
                                                JOIN tblproducts ON tblproducts.id = tblissuedproducts.ProductId 
                                                WHERE (tblissuedproducts.ReturnDate IS NULL OR tblissuedproducts.ReturnDate = '') 
                                                OR tblissuedproducts.quantity > 0 
                                                ORDER BY tblissuedproducts.id DESC";
                                        $query = $dbh->prepare($sql);
                                        $query->execute();
                                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                                        $cnt = 1;
                                        if($query->rowCount() > 0) {
                                            foreach($results as $result) { 
                                        ?>
                                        <tr class="odd gradeX">
                                            <td class="center"><?php echo htmlentities($cnt);?></td>
                                            <td style="text-align: left;"><?php echo htmlentities($result->FullName);?></td>
                                            <td><?php echo htmlentities($result->Usertype);?></td>
                                            <td style="text-align: left;" width="300">
                                                <img src="productimg/<?php echo htmlentities($result->productImage); ?>" width="100"><br />
                                                <b><?php echo htmlentities($result->ProductName);?></b></td>
                                            <td class="center" style="text-align: center;"><?php echo htmlentities($result->SNumber);?></td>
                                            <td class="center" style="text-align: center;"><?php echo date("F j, Y", strtotime($result->IssuesDate)) . " " . "at" . " ";echo date("g:i A", strtotime($result->IssuesDate)); ?></td>
                                            <td class="center" style="text-align: center;">
                                                <?php echo date("F j, Y", strtotime($result->ExpReturn)); ?>
                                            </td>
                                            <td class="center" style="text-align: center;">
                                                <?php if($result->ReturnDate == "") { ?>
                                                    <span style="color:red">Not Returned Yet</span>
                                                <?php } else {
                                                    echo htmlentities($result->ReturnDate);
                                                } ?>
                                            </td>
                                            <td class="center" style="text-align: center;"><?php echo htmlentities($result->quantity);?></td>
                                            <td class="center" style="text-align: center;">
                                                <a href="update-issue-product.php?rid=<?php echo htmlentities($result->rid);?>"><button class="btn btn-primary"><i class="fa fa-edit "></i> Return</button></a>
                                            </td>
                                        </tr>
                                        <?php $cnt = $cnt + 1; } } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!--End Advanced Tables -->
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
    <!-- DATATABLE SCRIPTS  -->
    <script src="assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="assets/js/dataTables/dataTables.bootstrap.js"></script>
    <!-- CUSTOM SCRIPTS  -->
    <script src="assets/js/custom.js"></script>
</body>
</html>
<?php } ?>