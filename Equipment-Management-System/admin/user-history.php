<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0) {   
    header('location:index.php');
} else {

    // Retrieve the user id from the GET parameter
    $sid = $_GET['stdid'];

    // Debug: Check the value of $sid
    error_log("User ID from URL: " . $sid);

    // Fetch the Full Name for the user
    $sql_user = "SELECT FullName FROM tblusers WHERE UserId = :sid";
    $query_user = $dbh->prepare($sql_user);
    $query_user->bindParam(':sid', $sid, PDO::PARAM_STR);
    $query_user->execute();
    $result_user = $query_user->fetch(PDO::FETCH_ASSOC);

    // Debug: Check the result of the query
    error_log("User Query Result: " . print_r($result_user, true));

    // Check if the user exists
    if ($result_user) {
        $FullName = $result_user['FullName']; // Assign the Full Name to the variable
    } else {
        $FullName = "Unknown User"; // Fallback if the user is not found
    }

    // Debug: Check the value of $FullName
    error_log("FullName: " . $FullName);

    // Fetch the admin's details (if needed)
    if (isset($_SESSION['alogin'])) {
        $email = $_SESSION['alogin'];
        $sql = "SELECT * FROM admin WHERE UserName=:username";
        $query = $dbh->prepare($sql);
        $query->bindParam(':username', $email, PDO::PARAM_STR);
        $query->execute();
        $results = $query->fetch(PDO::FETCH_ASSOC);

        if ($results) {
            // Use a different variable for the admin's full name
            $adminFullName = $results['FullName'];
            $UserName = $results['UserName'];
            $AdminEmail = $results['AdminEmail'];
            $updationDate = $results['updationDate'];
        } else {
            echo "Admin details not found.";
        }
    }
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="user" content="" />
    <title>Equipment Management System | User History</title>
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
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            .print-row, .print-row * {
                visibility: visible;
            }
            .print-row {
                position: absolute;
                left: 0;
                top: 0;
            }
        }
    </style>
</head>
<body>
    <!------MENU SECTION START-->
    <?php include('includes/header.php'); ?>
    <!-- MENU SECTION END-->
    <div class="container">
        <div class="row pad-botm">
            <div class="col-md-12">
                <h4 class="header-line" style="position: relative; top: 30px;"><?php echo htmlentities($FullName); ?> Issued History</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <!-- Advanced Tables -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Details
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th style="text-align: center;">User ID</th>
                                        <th style="text-align: center;">Name</th>
                                        <th style="text-align: center;">Role</th>
                                        <th style="text-align: center;">Issued Products</th>
                                        <th style="text-align: center;">Issued Date</th>
                                        <th style="text-align: center;">Expected Return Date</th>
                                        <th style="text-align: center;">Returned Date</th>
                                        <th style="text-align: center;">Issued Quantity</th>
                                    </tr>
                                </thead>
                                <tbody style="text-align: center;">
                                    <?php 
                                    $sql = "SELECT tblusers.UserId, tblusers.FullName, tblusers.Usertype, tblproducts.ProductName, tblissuedproducts.IssuesDate, tblissuedproducts.ReturnDate, tblissuedproducts.ExpReturn, tblissuedproducts.id as rid, tblissuedproducts.ReturnDate, tblissuedproducts.fine, tblissuedproducts.ReturnStatus, tblissuedproducts.borrowedqty 
                                            FROM tblissuedproducts 
                                            JOIN tblusers ON tblusers.UserId = tblissuedproducts.UserId 
                                            JOIN tblproducts ON tblproducts.id = tblissuedproducts.ProductId 
                                            WHERE tblusers.UserId = :sid 
                                            ORDER BY IssuesDate DESC";
                                    $query = $dbh->prepare($sql);
                                    $query->bindParam(':sid', $sid, PDO::PARAM_STR);
                                    $query->execute(); 
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                    $cnt = 1;
                                    if($query->rowCount() > 0) {
                                        foreach($results as $result) { ?>
                                            <tr class="odd gradeX" id="row-<?php echo htmlentities($result->rid); ?>">
                                                <td class="center"><?php echo htmlentities($cnt); ?></td>
                                                <td class="center"><?php echo htmlentities($result->UserId); ?></td>
                                                <td class="user-name" style="text-align: left;"><?php echo htmlentities(ucwords($result->FullName)); ?></td>
                                                <td><?php echo htmlentities($result->Usertype); ?></td>
                                                <td style="text-align: left;"><?php echo htmlentities($result->ProductName); ?></td>
                                                <td class="center" style="text-align: center;"><?php echo date("F j, Y", strtotime($result->IssuesDate));?></p></td>
                                                <td class="center" style="text-align: center;"><?php echo date("F j, Y", strtotime($result->ExpReturn));?></p></td>
                                                <td class="center" style="text-align: center;"><?php if($result->ReturnDate=='') { echo "Not returned yet"; } else { echo date("F j, Y", strtotime($result->ReturnDate)); } ?></td>
                                                <td class="center" style="text-align: center;"><?php echo htmlentities($result->borrowedqty); ?></td>
                                            </tr>
                                    <?php 
                                            $cnt++;
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
    <script>
        function printRow(rowId) {
            // Get the row content
            var rowContent = document.getElementById(rowId).innerHTML;

            // Create a new window for printing
            var printWindow = window.open('', '', 'height=500,width=800');
            printWindow.document.write('<html><head><title>Print Row</title>');
            printWindow.document.write('<style>table { width: 100%; border-collapse: collapse; } th, td { border: 1px solid black; padding: 8px; text-align: center; }</style>');
            printWindow.document.write('</head><body>');
            printWindow.document.write('<table>');
            printWindow.document.write(rowContent);
            printWindow.document.write('</table>');
            printWindow.document.write('</body></html>');
            printWindow.document.close();

            // Print the row
            printWindow.print();
        }
    </script>
</body>
</html>
<?php } ?>