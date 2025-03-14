<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include('includes/config.php');

if (isset($_SESSION['alogin'])) {
    header('location: dashboard.php');
    exit();
}

if (isset($_SESSION['error1'])) {
    $error1 = $_SESSION['error1'];
    unset($_SESSION['error1']); // Clear after displaying
}
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

if (isset($_SESSION['alogin']) && $_SESSION['alogin']) {
    $_SESSION['alogin'] = '';
}


// Initialize the failed login attempts counter if it doesn't exist
if (!isset($_SESSION['failed_login_attempts'])) {
    $_SESSION['failed_login_attempts'] = 0;
}

if (isset($_POST['login'])) {
    $login_input = $_POST['username']; // This can be either UserName or AdminEmail
    $password = $_POST['password']; // Do not hash the password here
    
    // SQL query to check both UserName or AdminEmail
    $sql = "SELECT UserName, AdminEmail, Password, id FROM admin WHERE (UserName = :login_input OR AdminEmail = :login_input)";
    
    $query = $dbh->prepare($sql);
    $query->bindParam(':login_input', $login_input, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if ($query->rowCount() > 0) {
        $user = $results[0];

        // If password is correct
        if (password_verify($password, $user->Password)) {
            $user_id = $user->id;
            $_SESSION['failed_login_attempts'] = 0;  // Reset failed attempts
            $_SESSION['alogin'] = $login_input; // Set session for admin login
            action_made($dbh, $user_id, "Logged in from the System");

            // Redirect to the dashboard
            header('Location: dashboard.php');
            exit;
        } else {
            // Password is wrong, increment failed attempts
            $_SESSION['failed_login_attempts']++;
            
            // Check if failed login attempts reach 3
            if ($_SESSION['failed_login_attempts'] >= 3) {
                // Log the activity for multiple failed login attempts
                $admin_email = isset($_SESSION['alogin']) ? $_SESSION['alogin'] : '';  // Get the logged-in admin's email

                // Fetch the admin's ID
                $sqlAdmin = "SELECT id FROM admin WHERE UserName = :username";
                $queryAdmin = $dbh->prepare($sqlAdmin);
                $queryAdmin->bindParam(':username', $admin_email, PDO::PARAM_STR);
                $queryAdmin->execute();
                $admin_result = $queryAdmin->fetch(PDO::FETCH_OBJ);

                if ($admin_result) {
                    $user_id = $admin_result->id;  // Get the admin's ID
                } else {
                    // If no admin is found, use a valid default user ID (e.g., 1 for a system user)
                    $user_id = 1;  // Replace with a valid default user ID
                }

                // Log the activity
                action_made($dbh, $user_id, "Someone was trying to access your account");

                // Reset the failed login attempts counter
                $_SESSION['failed_login_attempts'] = 0;

                // Set the error message in the session
                $_SESSION['error1'] = "Multiple failed login attempts detected. Please try again later.";

                // Redirect to the login page
                header("Location: index.php#alogin");
                exit;
            } else {
                // Set the error message for invalid password
                $_SESSION['error'] = "Invalid Password";
                header("Location: index.php#alogin");
                exit;
            }
        }
    } else {
        // Check if the login input is an email or username and respond accordingly
        if (filter_var($login_input, FILTER_VALIDATE_EMAIL)) {
            // If it's an email, set the error message for invalid email
            $_SESSION['error'] = "Invalid Email";
        } else {
            // Otherwise, set the error message for invalid username
            $_SESSION['error'] = "Invalid Username";
        }

        // If both email and password are incorrect
        $_SESSION['error'] = "Invalid details, please try again";

        header("Location: index.php#alogin");
        exit;
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <title>Equipment Management System | Login</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link href="assets/css/font-awesome.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link rel="icon" href="assets/img/src.png">

    <style>

.navbar {
    position: fixed; /* Fix the header at the top */
    top: 0;
    width: 100%;
    z-index: 1000; /* Ensure it stays above other content */
    background-color: #fff; /* Add background color if needed */
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Optional: Add shadow */
}
        /* Prevent horizontal overflow */
html, body {
    overflow-x: hidden; /* Hide horizontal scrollbar */
    width: 100%; /* Ensure the body takes full width */
    max-width: 100%; /* Prevent exceeding the viewport width */
}

/* Ensure all elements stay within the viewport */
.container, .row, .col-md-12, .col-md-6, .col-md-3, .col-sm-6, .col-xs-12 {
    max-width: 100%; /* Prevent exceeding the viewport width */
    margin: 0; /* Remove default margins */
    padding: 0; /* Remove default padding */
}

/* Ensure images and other content do not overflow */
img, .hero-image, .about-box, .panel {
    max-width: 100%; /* Ensure images and containers do not exceed the viewport width */
    height: auto; /* Maintain aspect ratio */
}
    /* Full-screen image below the navbar */
    .hero-image {
    position: relative; /* Use relative positioning */
    width: 100%;
    height: 100vh; /* Make the hero image fill the viewport height */
    background-image: url('assets/img/img.jpg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    z-index: -1;
}

.hero-image img {
    width: 100%;
    height: 100%; /* Ensure the image fills the container */
    object-fit: cover; /* Maintain aspect ratio */
    display: block;
}
#home {
    padding-top: 0; /* Remove padding at the top */
    padding-bottom: 0; /* Remove padding at the bottom */
    margin: 0; /* Remove any default margins */
}

#about {
    padding-top: 70px; /* Adjust based on header height */
}
/* Add a brown overlay */
.hero-image::before {
    content: ''; /* Required for the pseudo-element */
    position: absolute; /* Position the overlay */
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(24, 35, 24, 0.6); /* RGBA for a brown color with opacity */
    z-index: 1; /* Ensure the overlay stays on top of the background image */
}
.hero-text {
    position: absolute;
    top: 0; /* Align to the top */
    right: 0; /* Align to the right */
    height: 100%; /* Same height as the image */
    width: 25%; /* Adjust width as needed */
    color: #fff;
    background-color:rgba(27, 77, 62, 0.7); /* Solid blue background */
    padding: 20px; /* Add padding */
    transition: transform 1.5s ease-out; /* Animation for sliding */
    transform: translateX(100%); /* Start from the right */
    display: flex;
    flex-direction: column;
    justify-content: center; /* Center content vertically */
    align-items: flex-start; /* Align text to the left */
    box-sizing: border-box; /* Include padding in width/height */
    font-family: 'Poppins';
}
.hero-text.slide-in {
    transform: translateX(0); /* Slide to the left */
}

.hero-text h1 {
    font-size: 3rem;
    margin-bottom: 10px;
    color: #fff; /* White text color */
   
}

.hero-text p {
    font-size: 1.5rem;
    color: #fff; /* White text color */
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .hero-text {
        width: 60%; /* Wider on smaller screens */
    }

    .hero-text h1 {
        font-size: 2rem;
    }

    .hero-text p {
        font-size: 1rem;
    }
}
.about-section {
    display: flex; /* This will make the two boxes align side by side */
    justify-content: space-between; /* Optional: Add space between the boxes */
    flex-wrap: wrap; /* Allows wrapping of elements if the screen size is smaller */
    gap: 20px; /* Add some gap between the boxes */
    font-family: 'Poppins';
}

/* Ensure all buttons are aligned in the center within the about-box */
.about-box {
    display: flex;
    flex-direction: column;
    justify-content: space-between; /* Distribute the content evenly */
    align-items: center; /* Center align all content (including the button) */
    background-color: #f8f9fa; /* Light gray background */
    border: 2px solid #ddd;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    text-align: center;
    transition: all 0.3s ease-in-out;
    cursor: pointer;
}

.about-box .learn-more-btn {
    margin-top: 15px; /* Add space above the button */
    align-self: center; /* Center the button horizontally */
}

/* Styling for the learn more button */
.learn-more-btn {
    width: 100%; /* Ensure the button spans the full width */
    text-align: center; /* Center the text in the button */
    padding: 10px;
    font-size: 16px;
    border-radius: 5px; /* Optional: Add rounded corners */
    transition: background-color 0.3s ease;
    background-color: #1B4D3E;
}

.learn-more-btn:hover {
    background-color: #1B4D3E; /* Darker blue when hovered */
}

.about-box:hover {
    transform: scale(1.1); /* Increase the size of the box */
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3); /* Add shadow to make it pop */
    z-index: 1; /* Ensure the box is above other elements */
}

/* Optionally, change the color or background of the h1 inside the about-box when hovered */
.about-box:hover h1 {
    color: #ffffff; /* Change text color when hovered */
}

/* Optional: Change the background of the box when hovered */
.about-box:hover {
    background-color: #f1f1f1; /* Lighter background when hovered */
}
/* Styling for the h1 */

/* Styling for the p */
.about-box p {
    font-size: 1.125rem; /* Set the font size for readability */
    color: #495057; /* Slightly lighter text color */
    line-height: 1.6; /* Increase line height for better spacing */
}

.about-box h1 {
    font-size: 2.5rem; /* Adjust the font size */
    color: #343a40; /* Dark text color */
    margin-bottom: 10px; /* Space below the heading */
    padding: 5px; /* Add some padding to create space around the text */
    background-color: #1B4D3E; /* Set background color */
    color: white; /* Text color to make it stand out */
    border-radius: 5px; /* Optional: Rounded corners for the background color */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Optional: Add a shadow effect for depth */
    display: flex; /* Use flexbox to align items */
    align-items: center; /* Vertically center the items */
    gap: 10px; /* Add space between the image and text */
    margin-top: -70px; /* Move the h1 element slightly up */
    position: relative; /* Make the h1 a positioning context for the image */
    padding-left: 100px; /* Add padding to the left to make space for the image */
    height: fixed;
    width: fixed;
}
.about-box-img {
    height: 130px; /* Adjust the size of the image */
    width: 130px; /* Adjust the size of the image */
    border-radius: 50%; /* Optional: Make the image circular */
    object-fit: cover; /* Ensure the image covers the area */
    position: absolute; /* Position the image absolutely */
    left: -40px; /* Move the image outside the h1 background */
    top: 40%; /* Center the image vertically */
    transform: translateY(-50%); /* Adjust vertical centering */
    z-index: 2; /* Ensure the image is above the h1 background */
}


/* Ensure responsiveness: In smaller screens, stack the boxes vertically */
@media (max-width: 768px) {
    .about-section {
        flex-direction: column; /* Stack the boxes vertically in portrait mode */
    }
}

</style>
</head>
<body>
    
<div id="home">



    <?php include('includes/header.php'); ?>
    
            <div class="hero-text">
                <h1>EQUIPMENT MANAGEMENT SYSTEM</h1>
                <p>Streamline your school's resource management with ease.</p>
            </div>
            <div class="hero-image">
        <img src="assets/img/img2.jpg" alt="">
    </div>
    </div>
    

<div id="about" style="padding-top: 80px;">
    <h1 style="text-align: center;">ABOUT</h1>
    <!-- Rest of your about content -->

<br>
<br><br><br>
<div class="row about-section">
    <!-- Equipment Management System Box -->
    <div class="col-md-3 col-sm-6 col-xs-12 about-box">
        <h1><img src="assets/img/img3.png" alt="" class="about-box-img"><br>VISION&nbsp;&nbsp;&nbsp;&nbsp;<br><br></h1>
        <p style="font-size: 15px; flex-grow: 1;">St. Rose College Educational Foundation, Inc. in Samput, Paniqui, Tarlac strives to provide quality education rooted in strong values, ensuring every student is empowered to realize their full potential and contribute meaningfully to society.</p>
     <!--   <a href="support-details.html" class="btn btn-primary learn-more-btn" style="background-color: #1B4D3E;">Learn More</a> -->
    </div>

    <!-- Support Box -->
    <div class="col-md-3 col-sm-6 col-xs-12 about-box">
        <h1><img src="assets/img/img4.png" alt="Support Icon" class="about-box-img"><br>SUPPORT&nbsp;&nbsp;&nbsp;&nbsp;<br><br></h1>
        <p style="font-size: 15px; flex-grow: 1;">
            Our Equipment Management System (EMS) supports schools in maintaining optimal inventory levels and improving overall operational efficiency. 
            It helps you track, maintain, and manage assets effectively to ensure smooth operations and better resource utilization.
        </p>
       <!--   <a href="support-details.html" class="btn btn-primary learn-more-btn" style="background-color: #1B4D3E;">Learn More</a> -->
    </div>

    <!-- Goals Box -->
    <div class="col-md-3 col-sm-6 col-xs-12 about-box">
        <h1><img src="assets/img/img1.png" alt="" class="about-box-img"><br>AMBITIONS &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br><br></h1>
        <p style="font-size: 15px; flex-grow: 1;">The Equipment Management System ensures that necessary materials are always available <br> when needed while minimizing waste and inefficiencies.</p>
       <!--   <a href="support-details.html" class="btn btn-primary learn-more-btn" style="background-color: #1B4D3E;">Learn More</a> -->
    </div>
</div>

</div>
</div>
   

<br>

        <div id="alogin" style="padding-top: 50px;">
        <hr />

        <!-- User Login Form -->
        <div class="row">
    <?php if(isset($_SESSION['error1']) && $_SESSION['error1'] != "") { ?>
        <div class="col-md-6 col-md-offset-3">
            <div class="alert alert-danger">
                <strong>Failed to Login:</strong>
                <?php echo htmlentities($_SESSION['error1']); ?>
                <?php $_SESSION['error1'] = ""; ?>
            </div>
        </div>
    <?php } ?>


    <?php if(isset($_SESSION['error']) && $_SESSION['error'] != "") { ?>
        <div class="col-md-6 col-md-offset-3">
            <div class="alert alert-danger">
                <strong>Failed to Login:</strong>
                <?php echo htmlentities($_SESSION['error']); ?>
                <?php $_SESSION['error'] = ""; ?>
            </div>
        </div>
    <?php } ?>
</div>
            <div class="container">
                <div class="row pad-botm">
                    <div class="col-12">
                        <h4 class="header-line">ADMIN LOGIN FORM</h4>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3" >
                        <div class="panel panel-info" >
                            <div class="panel-heading" style="background-color: #1B4D3E; color: #fff;">LOGIN FORM</div>
                            <div class="panel-body">
                                <form method="post">
                                    <div class="form-group">
                                        <label>Enter Email ID</label>
                                        <input class="form-control" type="text" name="username" required autocomplete="off" />
                                    </div>
                                    <div class="form-group">
                                        <label>Password</label>
                                        <input class="form-control" type="password" name="password" required autocomplete="off" />
                                        <p class="help-block"><a href="reset-password.php" style="text-decoration: none;">Forgot Password?</a></p>
                                    </div>
                                    <button type="submit" name="login" class="btn btn-info" style="background-color: #1B4D3E; color: #fff;">LOGIN</button>
                                    
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
    // Function to trigger the slide-in animation
    function triggerSlideIn() {
        const heroText = document.querySelector('.hero-text');
        heroText.classList.add('slide-in');
    }

    // Intersection Observer to detect when the home section is visible
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                triggerSlideIn(); // Trigger the slide-in animation
            } else {
                // Reset the animation when the section is not visible
                const heroText = document.querySelector('.hero-text');
                heroText.classList.remove('slide-in');
            }
        });
    }, {
        threshold: 0.5 // Trigger when 50% of the section is visible
    });

    // Observe the home section
    const homeSection = document.getElementById('home');
    observer.observe(homeSection);
</script>

        <?php include('includes/footer.php'); ?>

        <script src="assets/js/jquery-1.10.2.js"></script>
        <script src="assets/js/bootstrap.js"></script>
        <script src="assets/js/custom.js"></script>
    </body>
</html>
