<?php
session_start(); // Start the session
include('includes/config.php');  // Include the database connection

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

// Check if the admin is logged in
if (isset($_SESSION['alogin'])) {
    // Fetch the admin's email from the session
    $admin_email = $_SESSION['alogin'];

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

    // Log the logout activity **before** destroying the session
    action_made($dbh, $user_id, "Logged out from the System");
}

// Destroy the session
session_unset();  // Unset all session variables
session_destroy(); // Destroy the session

// Redirect to the login page
header("Location: index.php");
exit;
?>
