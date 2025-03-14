<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['login']) == 0) {
    header('location:index.php');
    exit();
} else {
    // Retrieve the user id from the GET parameter
    $sid = $_GET['stdid'];

    // Fetch the Full Name for the user
    $sql_user = "SELECT FullName FROM tblusers WHERE UserId = :sid";
    $query_user = $dbh->prepare($sql_user);
    $query_user->bindParam(':sid', $sid, PDO::PARAM_STR);
    $query_user->execute();
    $result_user = $query_user->fetch(PDO::FETCH_ASSOC);
    $FullName = $result_user['FullName']; // Assign the Full Name to the variable

    if (isset($_POST['update_info'])) {
        $stdid = $_POST['stdid'];  // Retrieve stdid from the form
        $fullname = $_POST['fullname'];
        $mobilenumber = $_POST['mobilenumber'];
        $email = $_POST['email'];
    
        // Update user information
        $sql = "UPDATE tblusers SET FullName = :fullname, MobileNumber = :mobilenumber, EmailId = :email WHERE UserId = :sid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':fullname', $fullname, PDO::PARAM_STR);
        $query->bindParam(':mobilenumber', $mobilenumber, PDO::PARAM_STR);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':sid', $stdid, PDO::PARAM_STR);  // Use stdid here instead of the $_GET parameter
    
        if ($query->execute()) {
            $_SESSION['update_message'] = "Information updated successfully.";
        } else {
            $_SESSION['update_message'] = "Error updating information: " . implode(" ", $query->errorInfo());
        }
    
        // Redirect to the same page to prevent form resubmission
        header("Location: my-profile.php");
        exit();
    }
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="user" content="" />
    <title>Equipment Management System | User Profile</title>
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
        .user-details {
            margin-bottom: 20px;
        }
        .user-details label {
            font-weight: bold;
        }
        .print-button {
            margin-top: 20px;
        }
        .edit-mode {
            display: none; /* Hide edit mode by default */
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
                <h4 class="header-line" style="position: relative; top: 30px;"><?php echo htmlentities($FullName); ?> Information </h4>
            </div>
        </div>

        <?php if (isset($_SESSION['update_message'])) { ?>
            <div class="alert <?php echo (strpos($_SESSION['update_message'], 'Error') === false) ? 'alert-success' : 'alert-danger'; ?>">
                <?php echo htmlentities($_SESSION['update_message']); ?>
            </div>
            <?php unset($_SESSION['update_message']); ?>
        <?php } ?>

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading" style="display: flex; justify-content: space-between; align-items: center; background-color: #1B4D3E; color: #fff;">
                        <span>DETAILS</span>
                        <a href="dashboard.php" style="font-size: 30px; text-decoration: none; color: #fff;" id="close-button">&times;</a>
                    </div>

                    <div class="panel-body">
                        <div class="user-details">
                            <?php
                            $sql = "SELECT * FROM tblusers WHERE tblusers.UserId = :sid";
                            $query = $dbh->prepare($sql);
                            $query->bindParam(':sid', $sid, PDO::PARAM_STR);
                            $query->execute();
                            $results = $query->fetchAll(PDO::FETCH_OBJ);
                            $cnt = 1;
                            if ($query->rowCount() > 0) {
                                foreach ($results as $result) { ?>
                                    <!-- View Mode -->
                                    <div id="view-mode">
                                        <div class="form-group">
                                            <label>User ID:</label>
                                            <p><?php echo htmlentities($result->UserId); ?></p>
                                        </div>
                                        <div class="form-group">
                                            <label>Name:</label>
                                            <p><?php echo htmlentities(ucwords($result->FullName)); ?></p>
                                        </div>
                                        <div class="form-group">
                                            <label>Role:</label>
                                            <p><?php echo htmlentities($result->Usertype); ?></p>
                                        </div>
                                        <div class="form-group">
                                            <label>Mobile Number:</label>
                                            <p><?php echo htmlentities(ucwords($result->MobileNumber)); ?></p>
                                        </div>
                                        <div class="form-group">
                                            <label>Email:</label>
                                            <p><?php echo htmlentities(ucwords($result->EmailId)); ?></p>
                                        </div>
                                        <div class="form-group">
                                            <label>Registration Date:</label>
                                            <p><?php echo date("F j, Y", strtotime($result->RegDate)); ?></p>
                                        </div>
                                        <button id="edit-button" class="btn btn-primary"><i class="fa fa-edit"></i> Edit Information</button>
                                        <form method="post" action="change-password.php" style="display:inline;">
                                <button type="submit" style="background-color: #1B4D3E; color: #fff;" class="btn">
                                    <i class="fa fa-eye"></i> Change Password
                                </button>
                            </form>
                                    </div>

                                    <!-- Edit Mode -->
                                    <div id="edit-mode" class="edit-mode">
                                        <form method="post" action="" id="editProfileForm">

                                        <input type="hidden" name="stdid" value="<?php echo htmlentities($result->UserId); ?>">

                                            <div class="form-group">
                                                <label>User ID:</label>
                                                <input type="text" class="form-control" value="<?php echo htmlentities($result->UserId); ?>" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label>Name:</label>
                                                <input type="text" name="fullname" class="form-control" value="<?php echo htmlentities(ucwords($result->FullName)); ?>">
                                            </div>
                                            <div class="form-group">
                                                <label>Role:</label>
                                                <input type="text" class="form-control" value="<?php echo htmlentities($result->Usertype); ?>" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label>Mobile Number:</label>
                                                <input type="text" name="mobilenumber" class="form-control" value="<?php echo htmlentities(ucwords($result->MobileNumber)); ?>">
                                            </div>
                                            <div class="form-group">
                                                <label>Email:</label>
                                                <input type="email" name="email" class="form-control" value="<?php echo htmlentities(ucwords($result->EmailId)); ?>">
                                            </div>
                                            <div class="form-group">
                                                <label>Registration Date:</label>
                                                <input type="text" class="form-control" value="<?php echo date("F j, Y", strtotime($result->RegDate)); ?>" readonly>
                                            </div>
                                            <button type="submit" name="update_info" class="btn btn-warning"><i class="fa fa-save"></i> Save Changes</button>
                                            <button type="button" id="cancel-button" class="btn btn-danger"><i class="fa fa-times"></i> Cancel</button>
                                        </form>
                                    </div>
                            <?php
                                    $cnt++;
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <!--End Advanced Tables -->
            </div>
        </div>
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
document.querySelector('#editProfileForm').addEventListener('submit', function() {
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

    
    <!-- JavaScript to Toggle Edit Mode -->
    <script>
    // Function to toggle the close button's href based on edit mode
    function toggleCloseButtonHref(isEditMode) {
        const closeButton = document.getElementById('close-button');
        if (isEditMode) {
            closeButton.href = "my-profile.php"; // Redirect to my-profile.php in edit mode
        } else {
            closeButton.href = "dashboard.php"; // Redirect to dashboard.php in view mode
        }
    }

    // When edit button is clicked, change to edit mode and update close button href
    document.getElementById('edit-button').addEventListener('click', function() {
        document.getElementById('view-mode').style.display = 'none';
        document.getElementById('edit-mode').style.display = 'block';
        toggleCloseButtonHref(true); // Set close button href to my-profile.php
    });

    // Cancel button will revert to view mode and update close button href
    document.getElementById('cancel-button').addEventListener('click', function() {
        document.getElementById('edit-mode').style.display = 'none';
        document.getElementById('view-mode').style.display = 'block';
        toggleCloseButtonHref(false); // Set close button href to dashboard.php
    });

    // Initialize the close button href based on the current mode
    document.addEventListener('DOMContentLoaded', function() {
        const isEditMode = document.getElementById('edit-mode').style.display === 'block';
        toggleCloseButtonHref(isEditMode);
    });
</script>

    
    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('includes/footer.php'); ?>
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