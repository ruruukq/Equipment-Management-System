<?php
session_start(); // Ensure this is at the very top
include('includes/config.php');
error_reporting(0);

// Ensure that the user is logged in
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit();
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

    // Fetch the admin's details
    $email = $_SESSION['alogin'];
    $sql = "SELECT * FROM admin WHERE UserName=:username";
    $query = $dbh->prepare($sql);
    $query->bindParam(':username', $email, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetch(PDO::FETCH_ASSOC);

    if ($results) {
        // Assign the fetched details to variables
        $AdName = $results['AdName'];
        $UserName = $results['UserName'];
        $AdminEmail = $results['AdminEmail'];
        $updationDate = $results['updationDate'];  // You may need to adjust this based on your database structure
    } else {
        // Handle the case where no user is found
        echo "Admin details not found.";
    }
}

if (isset($_POST['update'])) {
    $adname = $_POST['adname'];
    $newUsername = $_POST['username'];
    $newAdminEmail = $_POST['ademail']; // Change this to store the new email

    // Initialize a variable to store the action log message
    $action_made = "You Updated ";

    // Initialize a variable to track if any change occurred
    $changes = [];

    // Check for changes in FullName
    if ($adname != $AdName) {
        $changes[] = "Your Name to $adname"; // Log FullName change
    }

    // Check for changes in UserName
    if ($newUsername != $UserName) {
        $changes[] = "Your Username to $newUsername"; // Log UserName change
    }

    // Check for changes in AdminEmail
    if ($newAdminEmail != $AdminEmail) {
        $changes[] = "Your Email to $newAdminEmail"; // Log AdminEmail change
    }

    // If there are any changes, build the action_made message
    if (count($changes) > 0) {
        // Handle the case where there are more than one change
        if (count($changes) == 1) {
            // If only one change, no need for a comma or "and"
            $action_made .= $changes[0];
        } else {
            // If more than one change, separate the first two with commas, and the last with "and"
            $action_made .= implode(", ", array_slice($changes, 0, -1));  // All except the last change
            if (count($changes) > 1) {
                $action_made .= " and " . $changes[count($changes) - 1];  // Add the last change with "and"
            }
        }
    } else {
        // If no changes, set a default message or take any necessary action
        $_SESSION['error'] = "No changes were made.";
        header("Location: my-profile.php");
        exit();
    }

    // Update the admin's details in the database
    $sql = "UPDATE admin SET AdName=:adname, UserName=:newUsername, AdminEmail=:ademail WHERE UserName=:oldUsername";
    $query = $dbh->prepare($sql);
    $query->bindParam(':adname', $adname, PDO::PARAM_STR);
    $query->bindParam(':newUsername', $newUsername, PDO::PARAM_STR);
    $query->bindParam(':oldUsername', $UserName, PDO::PARAM_STR);
    $query->bindParam(':ademail', $newAdminEmail, PDO::PARAM_STR); // Use new email

    // Execute the query
    if ($query->execute()) {
        // Update the session with the new username
        $_SESSION['alogin'] = $newUsername;  // Update the session variable

        // Get the admin's ID after update (to log the action)
        $admin_email = $_SESSION['alogin'];  // Get the logged-in admin's email
        $sql = "SELECT id FROM admin WHERE UserName = :newUsername";
        $query = $dbh->prepare($sql);
        $query->bindParam(':newUsername', $admin_email, PDO::PARAM_STR);
        $query->execute();
        $admin_result = $query->fetch(PDO::FETCH_OBJ);
        
        // If admin exists, log the action
        if ($admin_result) {
            $user_id = $admin_result->id;  // Get the admin's ID
            
            // Log the action of updating the information
            action_made($dbh, $user_id, $action_made);  // Call the function to log the action
        }
        // Set a success message and redirect
        $_SESSION['msg'] = "Information updated successfully.";
        header("Location: my-profile.php");  // Redirect to the updated profile page
        exit();
    } else {
        // If update failed, show error
        $error = implode(" ", $query->errorInfo());
        $_SESSION['error'] = "Error updating information: " . $error;
        echo $error;  // Debugging line
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
    <title>Equipment Management System | Admin Profile</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <link rel="icon" href="assets/img/src.png">
</head>
<body>
    <?php include('includes/header.php'); ?>

    <div class="container">
        <div class="row pad-botm">
            <div class="col-md-12">
                <h4 class="header-line" style="position: relative; top: 30px;"><?php echo htmlentities($AdName); ?> Information </h4>
            </div>
        </div>

        <?php
                if (isset($_SESSION['msg'])) {
                    echo '<div class="alert alert-success">' . $_SESSION['msg'] . '</div>';
                    unset($_SESSION['msg']);
                }

                if (isset($_SESSION['error'])) {
                    echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
                    unset($_SESSION['error']);
                }
                        ?>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading" style="display: flex; justify-content: space-between; align-items: center; background-color: #1B4D3E; color: #fff;">
                        <span>DETAILS</span>
                        <a href="dashboard.php" style="font-size: 30px; text-decoration: none; color: #fff;" id="close-button">&times;</a>
                    </div>

                    <div class="panel-body">
                        <div class="user-details">
                            <!-- View Mode -->
                            <div id="view-mode">
                            <div class="form-group">
                                <label>Property Custodian Name:</label>
                                <p><?php echo htmlentities($AdName); ?></p>
                            </div>
                            <div class="form-group">
                                <label>Username:</label>
                                <p><?php echo htmlentities($UserName); ?></p>
                            </div>
                            <div class="form-group">
                                <label>Email:</label>
                                <p><?php echo htmlentities($AdminEmail); ?></p>
                            </div>
                            <div class="form-group">
                                <label>Updation Date:</label>
                                <p><?php echo htmlentities($updationDate); ?></p>
                            </div>
                            
                            <button id="edit-button" class="btn btn-primary"><i class="fa fa-edit"></i> Edit Information</button>
                            <form method="post" action="change-password.php" style="display:inline;">
                                <button type="submit" style="background-color: #1B4D3E; color: #fff;" class="btn">
                                    <i class="fa fa-eye"></i> Change Password
                                </button>
                            </form>
                            </div>

                            <!-- Edit Mode (Hidden by Default) -->
                            <div id="edit-mode" style="display: none;">
                                <form method="post" action="" id="editAdminForm">
                                    <div class="form-group">
                                        <label>Property Custodian Name:</label>
                                        <input type="text" name="adname" class="form-control" value="<?php echo htmlentities($AdName); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Username:</label>
                                        <input type="text" name="username" class="form-control" value="<?php echo htmlentities($UserName); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Email:</label>
                                        <input type="email" name="ademail" class="form-control" value="<?php echo htmlentities($AdminEmail); ?>">
                                    </div>
                                    <button type="submit" name="update" class="btn btn-warning"><i class="fa fa-save"></i> Save Changes</button>
                                    <button type="button" id="cancel-button" class="btn btn-danger"><i class="fa fa-times"></i> Cancel</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
// Show edit mode when the "Edit Information" button is clicked
document.getElementById('edit-button').addEventListener('click', function() {
    document.getElementById('view-mode').style.display = 'none';
    document.getElementById('edit-mode').style.display = 'block';
});

// Hide edit mode when the "Cancel" button is clicked
document.getElementById('cancel-button').addEventListener('click', function() {
    document.getElementById('edit-mode').style.display = 'none';
    document.getElementById('view-mode').style.display = 'block';
});

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
document.querySelector('#editAdminForm').addEventListener('submit', function() {
    formSubmitting = true;
});

// Custom confirmation dialog using JavaScript
document.getElementById('close-button').addEventListener('click', function(event) {
    if (formModified && !formSubmitting) {
        // Show custom pop-up to the user
        let userConfirmed = confirm("You have unsaved changes. Do you really want to leave?");
        if (userConfirmed) {
            // If user clicks "OK", allow the page to unload
            window.location.href = 'my-profile.php';  // Redirect to my-profile or handle leaving as needed
        } else {
            // If user clicks "Cancel", prevent the page from leaving
            event.preventDefault();  // Prevent the default action (closing the modal or navigating away)
        }
    } else {
        // If no changes were made, just redirect to the my-profile
        window.location.href = 'my-profile.php';
    }
});
    </script>
    <?php include('includes/footer.php'); ?>

    <script src="assets/js/jquery-1.10.2.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/custom.js"></script>
</body>
</html>