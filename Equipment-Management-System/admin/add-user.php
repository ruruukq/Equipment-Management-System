<?php 
session_start();
include('includes/config.php');
error_reporting(0);

if(isset($_POST['signup']))
{
    // Get form inputs
    $fname = $_POST['fullanme'];
    $mobileno = $_POST['mobileno'];
    $email = $_POST['email']; 
    $password = md5($_POST['password']); 
    $status = 1;
    $usertype = $_POST['usertype'];

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

    // Check if the email or mobile number already exists
    $sql = "SELECT id FROM tblusers WHERE EmailId = :email OR MobileNumber = :mobileno";
    $query = $dbh->prepare($sql);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':mobileno', $mobileno, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);

    if($result)
    {
        // If email or mobile number already exists, display an error
        $_SESSION['error'] = "Email or Mobile Number already exists. Please use a different one.";
        header('location:reg-users.php');
        exit();
    }
    else
    {
        // Code for user ID generation
        $count_my_page = "userid.txt";
        $hits = file($count_my_page);
        $hits[0]++;

        // Update UserId to be in the format SID011001, SID011002, etc.
        $UserId = 'SID0' . str_pad($hits[0], 2, '0', STR_PAD_LEFT); // Format the UserId with 3 digits padding

        // Update the file with the new user ID count
        $fp = fopen($count_my_page , "w");
        fputs($fp , "$hits[0]");
        fclose($fp); 

        // Insert user into the database with the formatted UserId
        $sql = "INSERT INTO tblusers(UserId, FullName, MobileNumber, EmailId, Password, Status, Usertype) 
                VALUES(:UserId, :fname, :mobileno, :email, :password, :status, :usertype)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':UserId', $UserId, PDO::PARAM_STR);
        $query->bindParam(':fname', $fname, PDO::PARAM_STR);
        $query->bindParam(':mobileno', $mobileno, PDO::PARAM_STR);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':password', $password, PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->bindParam(':usertype', $usertype, PDO::PARAM_STR);
        $query->execute();
        
        $lastInsertId = $dbh->lastInsertId();
        
        if ($lastInsertId) {

             // Fetch FullName from tblusers
        $sqlUserName = "SELECT FullName, Usertype FROM tblusers WHERE UserId = :UserId";
        $queryUserName = $dbh->prepare($sqlUserName);
        $queryUserName->bindParam(':UserId', $UserId, PDO::PARAM_STR);
        $queryUserName->execute();
        $user = $queryUserName->fetch(PDO::FETCH_OBJ);

        if ($user) {
            $FullName = $user->FullName;
            $Usertype = $user->Usertype;
        } else {
            $FullName = 'Unknown User'; // Default value if user not found
        }
        
            // Fetch the admin's ID from the admin table using the logged-in admin's email
            $admin_email = $_SESSION['alogin'];  // Get the logged-in admin's email
            $sql = "SELECT id FROM admin WHERE UserName = :username";
            $query = $dbh->prepare($sql);
            $query->bindParam(':username', $admin_email, PDO::PARAM_STR);
            $query->execute();
            $admin_result = $query->fetch(PDO::FETCH_OBJ);
            
            if ($admin_result) {
                $user_id = $admin_result->id;  // Get the admin's ID

                // Log the action of adding the brand
                $action_made = "Admin Registered $FullName as $Usertype ";  // Log the brand name in the action
                action_made($dbh, $user_id, $action_made);  // Call the function to log the action

                $_SESSION['updatemsg'] = "Adding $FullName was successful, User ID is $UserId";
                header('Location: reg-users.php');  // Redirect to the landing page (e.g., reg-users.php)
                exit();
            } else {
                // In case no admin ID is found, handle accordingly (optional)
                $_SESSION['error'] = "Admin not found in the database.";
                header('Location: reg-users.php');
                exit();
            }
        } else {
            $_SESSION['error'] = "Something went wrong. Please try again.";
            header('Location: reg-users.php');
            exit();
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
    <meta name="user" content="" />
    <!--[if IE]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <![endif]-->
    <title>Equipment Management System | Add User</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <link rel="icon" href="assets/img/src.png">
<script type="text/javascript">
function valid()
{
    if(document.signup.password.value != document.signup.confirmpassword.value)
    {
        alert("Password and Confirm Password Field do not match  !!");
        document.signup.confirmpassword.focus();
        return false;
    }
    return true;
}
</script>

</head>
<body>
    <!------MENU SECTION START-->
<?php include('includes/header.php');?>
<!-- MENU SECTION END-->
         <div class="container">
        <div class="row pad-botm">
            <div class="col-md-12">
                <h4 class="header-line" style="position: relative; top: 30px;">Add User</h4>
                
                            </div>

        </div>
             <div class="row">
           
<div class="col-md-9 col-md-offset-1">
               <div class="panel panel-info">
               <div class="panel-heading" style="display: flex; justify-content: space-between; align-items: center; background-color: #1B4D3E; color: #fff;">
    <!-- X button aligned to the right -->
    <span>SIGNUP FORM</span>
    <a href="reg-users.php" style="font-size: 30px; text-decoration: none; color: #fff;">&times;</a> 
    <!-- Category Info text aligned to the left -->
</div>
                        <div class="panel-body">
                            <form name="signup" method="post" onSubmit="return valid();" id="addUserForm">
<div class="form-group">
<label>Enter Full Name</label>
<input class="form-control" type="text" name="fullanme" autocomplete="off" required />
</div>

<div class="form-group">
    <label for="role">Role</label>
    <select name="usertype" id="usertype" class="form-control" style="cursor: pointer;" required>
        <option value="" style="display: none;">Select Role</option>
        <option value="Instructor">Instructor</option>
        <option value="Student">Student</option>
        <option value="School Staff">School Utilities</option>
    </select>
</div>



<div class="form-group">
<label>Mobile Number :</label>
<input class="form-control" type="text" oninput="this.value=this.value.replace(/[^0-9]/g,'');" name="mobileno" maxlength="12" autocomplete="off" required />
</div>
                                      
<div class="form-group">
<label>Enter Email</label>
<input class="form-control" type="email" name="email" id="emailid" autocomplete="off" required  />
</div>

<div class="form-group">
<label>Enter Password</label>
<input class="form-control" type="password" name="password" autocomplete="off" required  />
</div>

<div class="form-group">
<label>Confirm Password </label>
<input class="form-control"  type="password" name="confirmpassword" autocomplete="off" required  />
</div>
                             
<button type="submit" name="signup" class="btn btn-primary" id="submit" style="background-color: #1B4D3E; color: #fff;">Add</button>

                                    </form>
                            </div>
                        </div>
                            </div>
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
document.querySelector('#addUserForm').addEventListener('submit', function() {
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
    <?php include('includes/footer.php');?>
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS  -->
    <script src="assets/js/bootstrap.js"></script>
      <!-- CUSTOM SCRIPTS  -->
    <script src="assets/js/custom.js"></script>
</body>
</html> 
