<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit();
} else {
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="product" content="" />
    <title>Equipment Management System | Manage Issued Products</title>
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
    <?php include('includes/header.php'); ?>
    <!-- MENU SECTION END-->
    <div class="container">
        <div class="row pad-botm">
            <div class="col-md-12">
                <h4 class="header-line" style="position: relative; top: 30px;">Manage Issued Products</h4>
            </div>
        </div>
        <div class="row">
            <?php if (isset($_SESSION['error']) && $_SESSION['error'] != "") { ?>
                <div class="col-md-6">
                    <div class="alert alert-danger">
                        <strong>Error :</strong>
                        <?php echo htmlentities($_SESSION['error']); ?>
                        <?php unset($_SESSION['error']); ?>
                    </div>
                </div>
            <?php } ?>
            <?php if (isset($_SESSION['msg']) && $_SESSION['msg'] != "") { ?>
                <div class="col-md-6">
                    <div class="alert alert-success">
                        <strong>Success :</strong>
                        <?php echo htmlentities($_SESSION['msg']); ?>
                        <?php unset($_SESSION['msg']); ?>
                    </div>
                </div>
            <?php } ?>
            <?php if (isset($_SESSION['delmsg']) && $_SESSION['delmsg'] != "") { ?>
                <div class="col-md-6">
                    <div class="alert alert-success">
                        <strong>Success :</strong>
                        <?php echo htmlentities($_SESSION['delmsg']); ?>
                        <?php unset($_SESSION['delmsg']); ?>
                    </div>
                </div>
            <?php } ?>
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
                                        <th style="text-align: center;">Equipment Name</th>
                                        <th style="text-align: center;">Issued Date</th>
                                        <th style="text-align: center;">Expected Return Date</th>
                                        <th style="text-align: center;">Return Date</th>
                                        <th style="text-align: center;">Remark</th>
                                        <th style="text-align: center;">Issued Quantity</th>
                                        <th style="text-align: center;">Current Quantity</th>
                                        <th style="text-align: center;">Action</th>
                                    </tr>
                                </thead>
                                <tbody style="text-align: center;">
                                    <?php
                                    // Fetch all issued products grouped by Product Name and User
                                    $sql = "SELECT tblusers.FullName, tblusers.Usertype, tblproducts.ProductName, tblproducts.SNumber, tblissuedproducts.IssuesDate, 
                                            tblissuedproducts.ExpReturn, tblissuedproducts.ReturnDate, tblissuedproducts.fine, tblissuedproducts.quantity, tblissuedproducts.borrowedqty, 
                                            tblissuedproducts.id as rid 
                                            FROM tblissuedproducts 
                                            JOIN tblusers ON tblusers.UserId = tblissuedproducts.UserId
                                            JOIN tblproducts ON tblproducts.id = tblissuedproducts.ProductId
                                            ORDER BY 
                                                CASE 
                                                    WHEN tblissuedproducts.ReturnDate = '' OR tblissuedproducts.ReturnDate IS NULL THEN 1 
                                                    WHEN tblissuedproducts.quantity > 0 THEN 2 
                                                    ELSE 3 
                                                END, tblissuedproducts.id DESC";

                                    $query = $dbh->prepare($sql);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                    $cnt = 1;
                                    if ($query->rowCount() > 0) {
                                        foreach ($results as $result) {
                                    ?>
                                            <tr class="odd gradeX">
                                                <td class="center"><?php echo htmlentities($cnt); ?></td>
                                                <td style="text-align: left;"><strong><?php echo strtoupper(htmlentities($result->FullName)); ?></strong></td>
                                                <td><strong><?php echo strtoupper(htmlentities($result->Usertype)); ?></strong></td>
                                                <td style="text-align: left;"><strong><?php echo htmlentities(strlen($result->ProductName) > 20 ? substr($result->ProductName, 0, 30) . '...' : $result->ProductName); ?></strong></td>
                                               
                                                <td class="center" style="text-align: center;"><?php echo date("F j, Y", strtotime($result->IssuesDate)) . "<br>"; echo date("g:i A", strtotime($result->IssuesDate)); ?></td>
                                                <td class="center" style="text-align: center;">
                                                    <?php
                                                    // Convert the ExpReturn to a date format like "February 23, 2025"
                                                    echo date("F j, Y", strtotime($result->ExpReturn));
                                                    ?>
                                                </td>
                                                <td class="center" style="text-align: center;">
                                                    <?php
                                                    if ($result->ReturnDate != "") {
                                                        echo date("F j, Y", strtotime($result->ReturnDate)) . "<br>";
                                                        echo date("g:i A", strtotime($result->ReturnDate));
                                                    } else {
                                                        echo "Pending";
                                                    }
                                                    ?>
                                                </td>
                                                <td class="center" style="text-align: center;">
                                                    <?php
                                                    // Check if ReturnDate is empty or there is remaining quantity
                                                    if ($result->ReturnDate == "" || $result->quantity > 0) {
                                                        echo '<span style="color:red"><strong>Not Yet Returned</strong></span>';
                                                    } else {
                                                        echo '<span style="color:green"><strong>Returned</strong></span>';
                                                    }
                                                    ?>
                                                </td>
                                                <td class="center" style="text-align: center;"><?php echo htmlentities($result->borrowedqty); ?></td>
                                                <td class="center" style="text-align: center;"><?php echo htmlentities($result->quantity); ?></td>
                                                <td class="center" style="text-align: center;">
                                                    <div style="display: flex; gap: 10px; justify-content: center;">
                                                        <?php
                                                        // Check if the product has been returned
                                                        if ($result->ReturnDate == "" || $result->quantity > 0) {
                                                            // If not returned yet, show the "Return" button
                                                            echo '<a href="update-issue-equipment.php?rid=' . htmlentities($result->rid) . '" style="text-decoration: none;">
                                                                    <button class="btn btn-primary" style="min-width: 80px;"><i class="fa fa-edit"></i> Return</button>
                                                                  </a>';
                                                            echo '<a href="print/print-issued.php?rid=' . htmlentities($result->rid) . '" style="text-decoration: none;">
                                                                    <button class="btn btn-primary" style="background-color: #1B4D3E; color: #fff; min-width: 80px;"><i class="fa fa-print"></i> Print</button>
                                                                  </a>';
                                                        } else {
                                                            // If returned, show the default "View" button
                                                            echo '<a href="update-issue-equipment.php?rid=' . htmlentities($result->rid) . '" style="text-decoration: none;">
                                                                    <button class="btn btn-success" style="min-width: 80px;"><i class="fa fa-eye"></i> View</button>
                                                                  </a>';
                                                            echo '<a href="print/print-issued.php?rid=' . htmlentities($result->rid) . '" style="text-decoration: none;">
                                                                    <button class="btn btn-primary" style="background-color: #1B4D3E; color: #fff; min-width: 80px;"><i class="fa fa-print"></i> Print</button>
                                                                  </a>';
                                                        }
                                                        ?>
                                                    </div>
                                                </td>
                                            </tr>
                                    <?php
                                            $cnt = $cnt + 1;
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!--End Advanced Tables -->
            </div>
        </div>
    </div>

    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('includes/footer.php'); ?>
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