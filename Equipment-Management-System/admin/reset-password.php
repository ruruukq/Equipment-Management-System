<?php
session_start();
include('includes/config.php'); // Include your PDO connection

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Ensure the PDO connection is valid
if (!$dbh) {
    die('Database connection failed');
}

if (isset($_POST['change'])) {
    $email = $_POST['email'];

    // Validate the email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format');</script>";
        exit;
    }

    // Generate a new password
    $new_password = bin2hex(random_bytes(8)); // Generates a random 16-character password

    // Hash the password before storing it in the database
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    try {
        // Prepare and execute the update query
        $stmt = $dbh->prepare("UPDATE admin SET password = :password WHERE AdminEmail = :email");
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Check if any row was affected (meaning the email was found and updated)
        if ($stmt->rowCount() > 0) {
            // Send the new password to the user's email
            $mail = new PHPMailer(true);

            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'srcequipmentmanagement@gmail.com';
                $mail->Password   = 'hovozqzmwpvcuptr'; // Use App Password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                // Recipients
                $mail->setFrom('srcequipmentmanagement@gmail.com', 'Equipment Management System');
                $mail->addAddress($email);

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Password Recovery';
                $mail->Body    = "Your new password is: <b>$new_password</b>. Please change it after logging in.";

                $mail->send();

                // Redirect to the login page after sending the email
                echo "<script>
                        alert('A new password has been sent to your email.');
                        window.location.href = 'index.php#alogin';
                      </script>";
            } catch (Exception $e) {
                echo "<script>alert('Message could not be sent. Mailer Error: {$mail->ErrorInfo}');</script>";
            }
        } else {
            echo "<script>alert('Email not found in our database.');</script>";
        }
    } catch (PDOException $e) {
        echo "<script>alert('Error updating password: {$e->getMessage()}');</script>";
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
    <title>Equipment Management System | Password Recovery </title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
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
<h4 class="header-line" style="position: relative; top: 30px;">Password Recovery</h4>
</div>
</div>
             
<!--LOGIN PANEL START-->           
<div class="row">
<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3" >
<div class="panel panel-info">
<div class="panel-heading" style="display: flex; justify-content: space-between; align-items: center; background-color: #1B4D3E; color: #fff;">
    <!-- X button aligned to the right -->
    <span>EMAIL FORM</span>
    <a href="index.php#alogin" style="font-size: 30px; text-decoration: none; color: #fff;">&times;</a> 
    <!-- Category Info text aligned to the left -->
</div>
<div class="panel-body">
<form role="form" name="chngpwd" method="post" onSubmit="return valid();">

<div class="form-group">
<label>Enter Registered Email</label>
<input class="form-control" type="email" name="email" required autocomplete="off" />
</div>

 <button type="submit" name="change" class="btn btn-info" style="background-color: #1B4D3E; color: #fff;">SUBMIT</button> | <a href="index.php#alogin">Login</a>
</form>
 </div>
</div>
</div>
</div>  
<!---LOGIN PABNEL END-->            
             
 
    </div>
    </div>
      <!-- FOOTER SECTION END-->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS  -->
    <script src="assets/js/bootstrap.js"></script>
      <!-- CUSTOM SCRIPTS  -->
    <script src="assets/js/custom.js"></script>

</body>
</html>
