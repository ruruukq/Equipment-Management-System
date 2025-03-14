<?php
session_start();
error_reporting(0);

include('includes/config.php');
if(strlen($_SESSION['alogin']) == 0) {   
    header('location:index.php');
} else { 
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

    // Code for block users    
    if(isset($_GET['inid'])) {
        $id = $_GET['inid'];
        $status = 0;
        $sql = "UPDATE tblusers SET Status=:status WHERE id=:id";

        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->execute();

        $admin_email = $_SESSION['alogin'];  // Get the logged-in admin's email
        
        $sql = "SELECT id FROM admin WHERE UserName = :username";
        $query = $dbh->prepare($sql);
        $query->bindParam(':username', $admin_email, PDO::PARAM_STR);
        $query->execute();
        $admin_result = $query->fetch(PDO::FETCH_OBJ);

        $user_id = $admin_result->id;  // Get the admin's ID
        action_made($dbh, $user_id, "Admin Blocked User");
        $_SESSION['success'] = "Blocked Successfully";
        header('location:reg-users.php');
        exit(); // Ensure no further code is executed after redirect
    }

    // Code for active users
    if(isset($_GET['id'])) {
        $id = $_GET['id'];
        $status = 1;

        $sql = "UPDATE tblusers SET Status=:status WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->execute();

        $admin_email = $_SESSION['alogin'];  // Get the logged-in admin's email

        $sql = "SELECT id FROM admin WHERE UserName = :username";
        $query = $dbh->prepare($sql);
        $query->bindParam(':username', $admin_email, PDO::PARAM_STR);
        $query->execute();
        $admin_result = $query->fetch(PDO::FETCH_OBJ);
   
        $user_id = $admin_result->id;  // Get the admin's ID
        action_made($dbh, $user_id, "Admin Unblocked User");
        $_SESSION['msg'] = "Unblocked Successfully";
        header('location:reg-users.php');
        exit(); // Ensure no further code is executed after redirect
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
    <title>Equipment Management System | Activity Logs</title>
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
    <div class="container">
        <div class="row pad-botm">
            <div class="col-md-12">
                <h4 class="header-line" style="position: relative; top: 30px;">Activity Logs</h4>
            </div>
        </div>

            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Activities
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center;">#</th>
                                            <th style="text-align: center;">Activities</th>
                                            <th style="text-align: center;">Timelog</th>
                                        </tr>
                                    </thead>
                                    <tbody style="text-align: center;">
                                        <?php 
                                        $sql = "SELECT * FROM logs";
                                        $query = $dbh->prepare($sql);
                                        $query->execute();
                                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                                        $cnt = 1;
                                        if($query->rowCount() > 0) {
                                            foreach($results as $result) { ?>                                      
                                                <tr class="odd gradeX" >
                                                    <td class="center"><?php echo htmlentities($cnt); ?></td>
                                                    <td style="text-align: left;"><?php echo htmlentities($result->action_made); ?></td>
                                                    <td class="center" style="text-align: center;"><?php echo date("F j, Y", strtotime($result->timelog)) . " " . "at" . " ";echo date("g:i A", strtotime($result->timelog)); ?></td>
                                                </tr>
                                            <?php $cnt = $cnt + 1; 
                                            } 
                                        } ?>                                      
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