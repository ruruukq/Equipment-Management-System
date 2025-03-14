<?php
session_start();
error_reporting(0);

include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {
    // Code for active users
    if (isset($_GET['id'])) {
        $id = $_GET['id']; // Get the user ID from the URL

        // Fetch FullName from tblusers using the user ID
        $sqlUserName = "SELECT FullName FROM tblusers WHERE id = :id";
        $queryUserName = $dbh->prepare($sqlUserName);
        $queryUserName->bindParam(':id', $id, PDO::PARAM_STR);
        $queryUserName->execute();
        $user = $queryUserName->fetch(PDO::FETCH_OBJ);

        if ($user) {
            $FullName = $user->FullName;
        } else {
            $FullName = 'Unknown User'; // Default value if user not found
        }

        // Fetch the logged-in admin's ID
        $admin_email = $_SESSION['alogin'];  // Get the logged-in admin's email
        $sqlAdmin = "SELECT id FROM admin WHERE UserName = :username";
        $queryAdmin = $dbh->prepare($sqlAdmin);
        $queryAdmin->bindParam(':username', $admin_email, PDO::PARAM_STR);
        $queryAdmin->execute();
        $admin_result = $queryAdmin->fetch(PDO::FETCH_OBJ);
        $user_id = $admin_result->id;  // Get the admin's ID

        // Log the action (adjusting log message since no block/unblock action exists anymore)
        $action_made = "Admin viewed $FullName's details";  // Log the view action
        action_made($dbh, $user_id, $action_made);  // Call the function to log the action
    }
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="users" content="" />
    <title>Equipment Management System | Manage Registered Users</title>
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
                <h4 class="header-line" style="position: relative; top: 30px;">Manage Registered Users</h4>
            </div>
        </div>

        <div class="row">
            <?php if (isset($_SESSION['error']) && $_SESSION['error'] != "") { ?>
                <div class="col-md-6">
                    <div class="alert alert-danger">
                        <strong>Error:</strong> 
                        <?php echo htmlentities($_SESSION['error']); ?>
                        <?php $_SESSION['error'] = ""; ?>
                    </div>
                </div>
            <?php } ?>
            <?php if (isset($_SESSION['success']) && $_SESSION['success'] != "") { ?>
                <div class="col-md-6">
                    <div class="alert alert-success">
                        <strong>Completed:</strong> 
                        <?php echo htmlentities($_SESSION['success']); ?>
                        <?php $_SESSION['success'] = ""; ?>
                    </div>
                </div>
            <?php } ?>
            <?php if (isset($_SESSION['msg']) && $_SESSION['msg'] != "") { ?>
                <div class="col-md-6">
                    <div class="alert alert-success">
                        <strong>Success:</strong> 
                        <?php echo htmlentities($_SESSION['msg']); ?>
                        <?php $_SESSION['msg'] = ""; ?>
                    </div>
                </div>
            <?php } ?>
            <?php if (isset($_SESSION['updatemsg']) && $_SESSION['updatemsg'] != "") { ?>
                <div class="col-md-6">
                    <div class="alert alert-success">
                        <strong>Success:</strong> 
                        <?php echo htmlentities($_SESSION['updatemsg']); ?>
                        <?php $_SESSION['updatemsg'] = ""; ?>
                    </div>
                </div>
            <?php } ?>

            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading" style="display: flex; justify-content: space-between; align-items: center;">
                            <span>Registered Users</span>
                            <a href="print/print-users.php">
                                <button class="btn btn-primary" style="background-color: #1B4D3E; color: #fff;"><i class="fa fa-print"></i> Print</button>
                            </a>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th style="text-align: center;">User ID</th>
                                            <th style="text-align: center;">Names</th>
                                            <th style="text-align: center;">Role</th>
                                            <th style="text-align: center;">Email ID</th>
                                            <th style="text-align: center;">Mobile Number</th>
                                            <th style="text-align: center;">Status</th>
                                            <th style="text-align: center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody style="text-align: center;">
                                        <?php
                                        $sql = "SELECT * FROM tblusers ORDER BY `FullName` ASC";
                                        $query = $dbh->prepare($sql);
                                        $query->execute();
                                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                                        $cnt = 1;
                                        if ($query->rowCount() > 0) {
                                            foreach ($results as $result) {
                                        ?>
                                                <tr class="odd gradeX">
                                                    <td class="center"><?php echo htmlentities($cnt); ?></td>
                                                    <td class="center"><?php echo htmlentities($result->UserId ?? ''); ?></td>
                                                    <td class="user-name" style="text-align: left;"><?php echo htmlentities($result->FullName ?? ''); ?></td>
                                                    <td><?php echo htmlentities($result->Usertype ?? ''); ?></td>
                                                    <td class="center" style="text-align: center;"><?php echo htmlentities($result->EmailId ?? ''); ?></td>
                                                    <td class="center" style="text-align: center;"><?php echo htmlentities($result->MobileNumber ?? ''); ?></td>
                                                    <td class="center" style="text-align: center;">
                                                        <?php if ($result->Status == 1) {
                                                            echo '<span style="color: green;">' . htmlentities("Active") . '</span>';
                                                        } else {
                                                            echo '<span style="color: red;">' . htmlentities("Blocked") . '</span>';
                                                        } ?>
                                                    </td>
                                                    <td class="center" style="text-align: center;">
                                                        <a href="user-information.php?stdid=<?php echo htmlentities($result->UserId ?? ''); ?>">
                                                            <button class="btn btn-primary"> <i class="fa fa-eye "></i> View</button>
                                                        </a>
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