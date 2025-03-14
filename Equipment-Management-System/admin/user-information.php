<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit(); // Always exit after a header redirect
} else {
    // Retrieve the user id from the GET parameter
    $sid = $_GET['stdid'];  // Make sure the 'stdid' is passed via the URL

    // Fetch the Full Name for the user (not the admin)
    $sql_user = "SELECT FullName FROM tblusers WHERE UserId = :sid";  // Query to get FullName of the user based on 'UserId'
    $query_user = $dbh->prepare($sql_user);
    $query_user->bindParam(':sid', $sid, PDO::PARAM_STR);
    $query_user->execute();
    $result_user = $query_user->fetch(PDO::FETCH_ASSOC);

    // Debugging: Check if the user was found
    if ($result_user) {
        $FullName = $result_user['FullName']; // Assign the Full Name of the user to the variable
    } else {
        $FullName = 'Unknown User'; // Default value if user not found
    }

    // Define the $show_password variable
    $show_password = false; // Set to false by default to hide the password

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

    // Security code to allow password reset
    $security_code = "admin123"; // Change this to a more secure code

    // Handle password reset
    if (isset($_POST['reset_password'])) {
        $entered_security_code = $_POST['security_code']; // Get the entered security code

        // Verify the security code
        if ($entered_security_code == $security_code) {
            // Security code is correct, proceed with password reset
            $new_password = bin2hex(random_bytes(8)); // Generate a random password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT); // Hash the new password

            // Update the password in the database
            $sql = "UPDATE tblusers SET Password = :password WHERE UserId = :sid";
            $query = $dbh->prepare($sql);
            $query->bindParam(':password', $hashed_password, PDO::PARAM_STR);
            $query->bindParam(':sid', $sid, PDO::PARAM_STR);

            // Execute the query and check for errors
            if ($query->execute()) {
                $_SESSION['password_reset_message'] = "Password has been reset. New password: " . $new_password;
            } else {
                $_SESSION['password_reset_message'] = "Error resetting password: " . implode(" ", $query->errorInfo());
            }
        } else {
            // Security code is incorrect
            $_SESSION['password_reset_message'] = "Incorrect security code. Please try again.";
        }

        // Fetch the logged-in admin's ID
        $admin_email = $_SESSION['alogin'];  // Get the logged-in admin's email
        $sqlAdmin = "SELECT id FROM admin WHERE UserName = :username";
        $queryAdmin = $dbh->prepare($sqlAdmin);
        $queryAdmin->bindParam(':username', $admin_email, PDO::PARAM_STR);
        $queryAdmin->execute();
        $admin_result = $queryAdmin->fetch(PDO::FETCH_OBJ);
        $user_id = $admin_result->id;  // Get the admin's ID
        
        // Log the action of block user
        $action_made = "Admin Reset Password of $FullName";  // Log the block user in the action
        action_made($dbh, $user_id, $action_made);  // Call the function to log the action

        // Redirect to the same page to prevent form resubmission
        header("Location: " . $_SERVER['PHP_SELF'] . "?stdid=" . $sid);
        exit(); // Always exit after a header redirect
    }

    // Handle role update
    if (isset($_POST['update_role'])) {
        $new_role = $_POST['role']; // Get the selected role
        $sid = $_GET['stdid']; // Ensure $sid is defined (user ID from the URL)

        // Update the role in the database
        $sql = "UPDATE tblusers SET Usertype = :role WHERE UserId = :sid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':role', $new_role, PDO::PARAM_STR);
        $query->bindParam(':sid', $sid, PDO::PARAM_STR);

        // Execute the query and check for errors
        if ($query->execute()) {
            // Success message (no alert, just a variable for display)
            $_SESSION['role_update_message'] = "$FullName Role has been updated successfully to $new_role";
        } else {
            // Error message (no alert, just a variable for display)
            $role_update_message = "Error updating role: " . implode(" ", $query->errorInfo());
        }

        // Fetch the logged-in admin's ID
        $admin_email = $_SESSION['alogin'];  // Get the logged-in admin's email
        $sqlAdmin = "SELECT id FROM admin WHERE UserName = :username";
        $queryAdmin = $dbh->prepare($sqlAdmin);
        $queryAdmin->bindParam(':username', $admin_email, PDO::PARAM_STR);
        $queryAdmin->execute();
        $admin_result = $queryAdmin->fetch(PDO::FETCH_OBJ);
        $user_id = $admin_result->id;  // Get the admin's ID
        
        // Fetch FullName from tblusers
        $sqlUserName = "SELECT FullName FROM tblusers WHERE UserId = :sid";
        $queryUserName = $dbh->prepare($sqlUserName);
        $queryUserName->bindParam(':sid', $sid, PDO::PARAM_STR);
        $queryUserName->execute();
        $user = $queryUserName->fetch(PDO::FETCH_OBJ);

        if ($user) {
            $FullName = $user->FullName;
        } else {
            $FullName = 'Unknown User'; // Default value if user not found
        }

        // Log the action of updating the role
        $action_made = "Admin Updated the Role of $FullName to $new_role";  // Log the role update
        action_made($dbh, $user_id, $action_made);  // Call the function to log the action

        // Redirect to the same page to prevent form resubmission
        header("Location: " . $_SERVER['PHP_SELF'] . "?stdid=" . $sid);
        exit(); // Always exit after a header redirect
    }

    // Code for block users    
    if (isset($_GET['inid'])) {
        $userId = $_GET['inid']; // Get the UserId from the URL
        $status = 0;

        // Update the user status to blocked
        $sql = "UPDATE tblusers SET Status=:status WHERE UserId=:userId";
        $query = $dbh->prepare($sql);
        $query->bindParam(':userId', $userId, PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->execute();

        // Fetch FullName from tblusers using the UserId
        $sqlUserName = "SELECT FullName FROM tblusers WHERE UserId = :userId";
        $queryUserName = $dbh->prepare($sqlUserName);
        $queryUserName->bindParam(':userId', $userId, PDO::PARAM_STR);
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
        
        // Log the action of block user
        $action_made = "Admin Blocked $FullName";  // Log the block user in the action
        action_made($dbh, $user_id, $action_made);  // Call the function to log the action

        $_SESSION['success'] = "Blocked Successfully";
        header('location:user-information.php?stdid=' . $userId); // Redirect to the same user's page
        exit(); // Ensure no further code is executed after redirect
    }

    // Code for active users
    if (isset($_GET['id'])) {
        $userId = $_GET['id']; // Get the UserId from the URL
        $status = 1;

        // Update the user status to active
        $sql = "UPDATE tblusers SET Status=:status WHERE UserId=:userId";
        $query = $dbh->prepare($sql);
        $query->bindParam(':userId', $userId, PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->execute();

        // Fetch FullName from tblusers using the UserId
        $sqlUserName = "SELECT FullName FROM tblusers WHERE UserId = :userId";
        $queryUserName = $dbh->prepare($sqlUserName);
        $queryUserName->bindParam(':userId', $userId, PDO::PARAM_STR);
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
        
        // Log the action of unblock user
        $action_made = "Admin Unblocked $FullName";  // Log the unblock user in the action
        action_made($dbh, $user_id, $action_made);  // Call the function to log the action

        $_SESSION['msg'] = "Unblocked Successfully";
        header('location:user-information.php?stdid=' . $userId); // Redirect to the same user's page
        exit(); // Ensure no further code is executed after redirect
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
        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <!------MENU SECTION START-->
    <?php include('includes/header.php'); ?>
    <!-- MENU SECTION END-->
    <div class="container">
        <div class="row pad-botm">
            <h4 class="header-line" style="position: relative; top: 30px;">
                <?php echo htmlentities($FullName); ?> Information
            </h4>
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
                    <div class="alert alert-danger">
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

            <?php if (isset($_SESSION['password_reset_message'])) { ?>
                <div class="alert <?php echo (strpos($_SESSION['password_reset_message'], 'Error') === false) ? 'alert-success' : 'alert-danger'; ?>">
                    <?php echo htmlentities($_SESSION['password_reset_message']); ?>
                </div>
                <?php unset($_SESSION['password_reset_message']); ?>
            <?php } ?>

            <?php if (isset($_SESSION['role_update_message'])) { ?>
                <div class="alert <?php echo (strpos($_SESSION['role_update_message'], 'Error') === false) ? 'alert-success' : 'alert-danger'; ?>">
                    <?php echo htmlentities($_SESSION['role_update_message']); ?>
                </div>
                <?php unset($_SESSION['role_update_message']); ?>
            <?php } ?>

            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading" style="display: flex; justify-content: space-between; align-items: center; background-color: #1B4D3E; color: #fff;">
                            <span>DETAILS</span>
                            <a href="reg-users.php" style="font-size: 30px; text-decoration: none; color: #fff;">&times;</a>
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
                                            <label>Password:</label>
                                            <p>
                                                <?php
                                                if ($show_password) {
                                                    echo htmlentities($result->Password);
                                                } else {
                                                    echo "********";
                                                }
                                                ?>
                                            </p>
                                        </div>
                                        <div class="form-group">
                                            <label>Registration Date:</label>
                                            <p><?php echo date("F j, Y", strtotime($result->RegDate)); ?></p>
                                        </div>
                                        <hr>
                                <?php
                                        $cnt++;
                                    }
                                }
                                ?>
                            </div>

                            <style>
                                /* Shorter dropdown */
                                .short-select {
                                    width: 150px; /* Adjust width to make it shorter */
                                    height: 30px; /* Adjust height */
                                    font-size: 12px; /* Adjust font size */
                                    padding: 3px; /* Adjust padding */
                                }

                                /* Optional: Smaller dropdown options */
                                .short-select option {
                                    font-size: 12px; /* Adjust font size */
                                    padding: 3px; /* Adjust padding */
                                }

                                /* Container for buttons and forms */
                                .button-container {
                                    display: flex;
                                    align-items: center;
                                    gap: 15px;
                                }

                                /* Container for forms (reset password and update role) */
                                .form-container {
                                    display: flex;
                                    gap: 20px;
                                    align-items: flex-end;
                                }

                                /* Form styling for Reset Password and Update Role */
                                .form-group {
                                    margin-bottom: 0;
                                }

                                /* Style for buttons */
                                .btn {
                                    font-size: 14px;
                                    padding: 10px 20px;
                                    margin-top: 10px;
                                }
                            </style>

                            <!-- Button Container -->
                            <div class="button-container">
                                <!-- Edit Button -->
                                <button id="editButton" class="btn btn-primary" onclick="toggleForms()">Edit</button>

                                <!-- Block/Unblock Button -->
                                <div class="form-group">
                                    <?php if ($result->Status == 1) { ?>
                                        <button class="btn btn-danger" onclick="confirmBlock(event, '<?php echo htmlentities($result->UserId); ?>', '<?php echo htmlentities($result->FullName); ?>')">
                                            <i class="fa fa-times"></i> Block
                                        </button>
                                    <?php } else { ?>
                                        <button class="btn btn-primary" onclick="confirmUnblock(event, '<?php echo htmlentities($result->UserId); ?>', '<?php echo htmlentities($result->FullName); ?>')">
                                            <i class="fa fa-unlock"></i> Unblock
                                        </button>
                                    <?php } ?>
                                </div>

                                <!-- History Button (now visible) -->
                                <a href="user-history.php?stdid=<?php echo htmlentities($result->UserId); ?>">
                                    <button class="btn btn-primary" style="background-color: #143D60; color: #fff;">
                                        <i class="fa-solid fa-circle-info"></i> History
                                    </button>
                                </a>
                            </div>

                            <!-- Form Container for Reset Password and Update Role -->
                            <div class="form-container">
                                <!-- Reset Password Form -->
                                <form method="post" id="resetPasswordForm" style="flex: 0;" class="hidden">
                                    <div class="form-group">
                                        <label for="security_code">Security Code:</label>
                                        <input type="password" name="security_code" id="security_code" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" name="reset_password" class="btn btn-warning"><i class="fa-solid fa-redo"></i> Reset Password</button>
                                    </div>
                                </form>

                                <!-- Update Role Form -->
                                <form method="post" id="updateRoleForm" style="flex: 1;" class="hidden">
                                    <div class="form-group">
                                        <label for="role">Update Role:</label>
                                        <select name="role" id="role" class="form-control short-select" style="cursor: pointer;" required>
                                            <option value="" style="display: none; width: 30px;">Select Role</option>
                                            <option value="Instructor" <?= $result->Usertype == 'Instructor' ? 'selected' : ''; ?>>Instructor</option>
                                            <option value="Student" <?= $result->Usertype == 'Student' ? 'selected' : ''; ?>>Student</option>
                                            <option value="School Utilities" <?= $result->Usertype == 'School Utilities' ? 'selected' : ''; ?>>School Utilities</option>
        
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" name="update_role" class="btn btn-info"><i class="fa-solid fa-edit"></i> Update Role</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!--End Advanced Tables -->
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript to Toggle Forms -->
    <script>
        function toggleForms() {
            const resetPasswordForm = document.getElementById('resetPasswordForm');
            const updateRoleForm = document.getElementById('updateRoleForm');
            const editButton = document.getElementById('editButton');

            if (resetPasswordForm.classList.contains('hidden') && updateRoleForm.classList.contains('hidden')) {
                resetPasswordForm.classList.remove('hidden');
                updateRoleForm.classList.remove('hidden');
                editButton.textContent = 'Cancel';
            } else {
                resetPasswordForm.classList.add('hidden');
                updateRoleForm.classList.add('hidden');
                editButton.textContent = 'Edit';
            }
        }
    </script>

    <!---------------------------BLOCK/JS----------------------------->
    <script>
        function confirmBlock(event, userId, FullName) {
            event.preventDefault(); // Prevent the default anchor tag behavior

            Swal.fire({
                title: 'Are you sure you want to Block ' + FullName + '?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#135D66',
                confirmButtonText: 'Block',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Proceed with the block if user confirms
                    blockUser(userId, FullName);  
                } else {
                    Swal.fire({
                        title: 'Cancelled',
                        icon: 'info',
                        confirmButtonColor: '#135D66',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }

        function blockUser(userId, FullName) {
            // Send the request to block the user via a GET request
            fetch("user-information.php?inid=" + userId + "&stdid=" + userId, {
                method: "GET", // Use GET to trigger the block action
            })
            .then(response => {
                if (response.ok) {
                    return response.text();  // Get the response text (you can customize the response if needed)
                } else {
                    throw new Error('Network response was not ok');
                }
            })
            .then(data => {
                Swal.fire({
                    title: FullName + ' has been Blocked successfully!',
                    icon: 'success',
                    confirmButtonColor: '#135D66',
                    confirmButtonText: 'OK'
                }).then(() => {
                    // Redirect to the same user's page after blocking
                    window.location.href = 'user-information.php?stdid=' + userId;
                });
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error',
                    text: 'An error occurred while blocking the user.',
                    icon: 'error',
                    confirmButtonColor: '#135D66',
                    confirmButtonText: 'OK'
                });
            });
        }
    </script>
    <!------------------------------------------------------------------>

    <!---------------------------UNBLOCK/JS----------------------------->
    <script>
        function confirmUnblock(event, userId, FullName) {
            event.preventDefault(); // Prevent the default anchor tag behavior

            Swal.fire({
                title: 'Are you sure you want to Unblock ' + FullName + '?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#135D66',
                confirmButtonText: 'Unblock',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Proceed with the unblock if user confirms
                    unblockUser(userId, FullName);  
                } else {
                    Swal.fire({
                        title: 'Cancelled',
                        icon: 'info',
                        confirmButtonColor: '#135D66',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }

        function unblockUser(userId, FullName) {
            // Send the request to unblock the user via a GET request
            fetch("user-information.php?id=" + userId + "&stdid=" + userId, {
                method: "GET", // Use GET to trigger the unblock action
            })
            .then(response => {
                if (response.ok) {
                    return response.text();  // Get the response text (you can customize the response if needed)
                } else {
                    throw new Error('Network response was not ok');
                }
            })
            .then(data => {
                Swal.fire({
                    title: FullName + ' has been Unblocked successfully!',
                    icon: 'success',
                    confirmButtonColor: '#135D66',
                    confirmButtonText: 'OK'
                }).then(() => {
                    // Redirect to the same user's page after unblocking
                    window.location.href = 'user-information.php?stdid=' + userId;
                });
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error',
                    text: 'An error occurred while unblocking the user.',
                    icon: 'error',
                    confirmButtonColor: '#135D66',
                    confirmButtonText: 'OK'
                });
            });
        }
    </script>
    <!------------------------------------------------------------------>

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