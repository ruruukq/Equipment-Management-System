<?php
session_start(); // Ensure this is at the very top
include('includes/config.php');
error_reporting(0);

if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit();
} else {
    if (isset($_POST['change'])) {
        $password = $_POST['password']; // Do not hash the password here
        $newpassword = $_POST['newpassword']; // Do not hash the new password here
        $email = $_SESSION['alogin'];

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

        // Check if the current password is correct
        $sql = "SELECT Password, id FROM admin WHERE UserName=:username";
        $query = $dbh->prepare($sql);
        $query->bindParam(':username', $email, PDO::PARAM_STR);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);

        if ($query->rowCount() > 0) {
            $user = $results[0];
            if (password_verify($password, $user->Password)) { // Verify the current password
                // Hash the new password
                $hashed_newpassword = password_hash($newpassword, PASSWORD_DEFAULT);

                // Update the password
                $con = "UPDATE admin SET Password = :newpassword WHERE UserName=:username";
                $chngpwd1 = $dbh->prepare($con);
                $chngpwd1->bindParam(':username', $email, PDO::PARAM_STR);
                $chngpwd1->bindParam(':newpassword', $hashed_newpassword, PDO::PARAM_STR);
                $chngpwd1->execute();

                $user_id = $user->id;

                // Ensure the session stays valid and you're still logged in as the same user
                $_SESSION['alogin'] = $email; // This should be the same session variable as before, not $_POST['username']

                action_made($dbh, $user_id, "You your changed Password");

                // Display success message and redirect to dashboard
                $_SESSION['msg'] = "Your password has been successfully changed.";
                header('Location: my-profile.php'); // Redirect to the correct page
                exit(); // Always call exit after a header redirection
            } else {
                $error = "Your current password is wrong.";
            }
        } else {
            $error = "Your current password is wrong.";
        }
    }
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Equipment Management System | Change Password</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <link rel="icon" href="assets/img/src.png">
    <style>
        .errorWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #fff;
            border-left: 4px solid #dd3d36;
            -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
            box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
        }
        .succWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #fff;
            border-left: 4px solid #5cb85c;
            -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
            box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
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
                <h4 class="header-line" style="position: relative; top: 30px;">Admin Change Password</h4>
            </div>
        </div>

        <!-- Display PHP error/success messages -->
        <?php
        if (isset($error)) { ?>
            <div class="errorWrap"><strong>ERROR</strong>: <?php echo htmlentities($error); ?> </div>
        <?php } 
        if (isset($_SESSION['msg'])) { ?>
            <div class="succWrap"><strong>SUCCESS</strong>: <?php echo htmlentities($_SESSION['msg']); ?> </div>
            <?php unset($_SESSION['msg']); // Clear the success message after displaying it ?>
        <?php } ?>

        <!-- Container for JavaScript validation error -->
        <div id="jsErrorWrap" class="errorWrap" style="display: none;">
            <strong>ERROR</strong>: <span id="jsErrorMsg"></span>
        </div>

        <!-- LOGIN PANEL START -->
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                <div class="panel panel-info">
                    <div class="panel-heading" style="display: flex; justify-content: space-between; align-items: center; background-color: #1B4D3E; color: #fff;">
                        <span>Change Password</span>
                        <a href="my-profile.php" style="font-size: 30px; text-decoration: none; color: #fff;">&times;</a>
                    </div>
                    <div class="panel-body">
                        <form role="form" method="post" onSubmit="return valid();" id="changepassForm" name="chngpwd">
                            <div class="form-group">
                                <label>Current Password</label>
                                <input class="form-control" type="password" name="password" autocomplete="off" required />
                            </div>
                            <div class="form-group">
                                <label>Enter New Password</label>
                                <input class="form-control" type="password" name="newpassword" autocomplete="off" required />
                            </div>
                            <div class="form-group">
                                <label>Confirm New Password</label>
                                <input class="form-control" type="password" name="confirmpassword" autocomplete="off" required />
                            </div>
                            <button type="submit" name="change" class="btn btn-info" style="background-color: #1B4D3E; color: #fff;">Change</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- LOGIN PANEL END -->
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
document.querySelector('#changepassForm').addEventListener('submit', function() {
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
    <?php include('includes/footer.php'); ?>
    <!-- FOOTER SECTION END-->

    <!-- JAVASCRIPT FILES PLACED AT THE BOTTOM TO REDUCE THE LOADING TIME  -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS  -->
    <script src="assets/js/bootstrap.js"></script>
    <!-- CUSTOM SCRIPTS  -->
    <script src="assets/js/custom.js"></script>

    <!-- JavaScript Validation -->
    <script type="text/javascript">
    function valid() {
        var newPassword = document.chngpwd.newpassword.value;
        var confirmPassword = document.chngpwd.confirmpassword.value;

        if (newPassword != confirmPassword) {
            // Display the error message in the designated container
            document.getElementById('jsErrorMsg').innerText = "New Password do not match! Please try again";
            document.getElementById('jsErrorWrap').style.display = 'block';
            document.chngpwd.confirmpassword.focus();
            return false;
        } else {
            // Hide the error message if passwords match
            document.getElementById('jsErrorWrap').style.display = 'none';
            return true;
        }
    }
    </script>
</body>
</html>