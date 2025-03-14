<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$active_page = isset($active_page) ? $active_page : ''; // Default to empty if not set

// Fetch the admin's details only if the admin is logged in
if (isset($_SESSION['alogin'])) {
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equipment Management System</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Navbar Styles */
        .navbar {
            background-color: #1B4D3E; /* Changed to #1B4D3E */
            width: 100%;
            padding: 10px 0;
            margin: 0;
            box-shadow: none; /* Remove any box-shadow */
            border: none; /* Remove any border */
            position: fixed; /* Fix the navbar at the top */
            top: 0; /* Position it at the top */
            left: 0;
            z-index: 1000; /* Ensure the navbar stays on top of other elements */
        }

        /* Add some padding at the top to prevent content from being hidden behind the fixed navbar */
        body {
            padding-top: 0; /* Adjust this value if needed to match navbar height */
        }

        html {
            scroll-behavior: smooth;
        }

        .navbar-brand img {
            height: 70px;
            width: 70px;
            margin-left: 10px;
            margin-top: -15px;
        }

        .navbar h4 {
            color: white;
            margin-top: 30px;
            display: inline-block;
            vertical-align: middle;
        }

        /* Menu Section Styles */
       .menu-section {
            background-color: #f8f9fa; /* Light background */
            width: 100%;
            padding: 10px 0;
            margin: 0;
            border-top: 1px solid #ddd;
        }

        .menu-section ul {
            margin: 0;
            padding: 0;
            justify-content: center; /* Center the menu items */
            align-items: center; /* Vertically align the items */
        }

        .menu-section ul li {
            list-style-type: none;
            margin-right: 20px; /* Space between menu items */
            display: block; /* Ensure the li takes up full width */
        }

        .menu-section ul li a {
            color: white !important; /* Changed to white */
            padding: 10px 20px;
            display: block; /* Ensure the anchor takes full width */
            text-decoration: none; /* Remove underline */
            width: 100%; /* Ensure full width */
        }

        .menu-section ul li a:hover {
            background-color: rgba(255, 255, 255, 0.1); /* Light hover effect */
            width: 100%; /* Ensure it takes full width */
            display: block; /* Make sure it's block-level for full-width effect */
        }

        .dropdown-toggle:focus, .dropdown-toggle:active {
            background-color: transparent !important;
            box-shadow: none !important; /* Remove any box-shadow as well */
        }

        /* Make the text color white for non-logged-in user links */
        .menu-section1 ul li a {
            color: white !important; /* Set text color to white */
        }

        /* Ensure the dropdown text color is white */
        .menu-section1 .dropdown-toggle {
            color: white !important; /* White color for the Admin dropdown */
        }

        /* Make the hover effect on the Admin dropdown white as well */
        .menu-section1 .dropdown-menu a {
            color: white !important; /* Ensure dropdown items are white */
        }

        .menu-section1 .dropdown-menu li a:hover {
            background-color: rgba(255, 255, 255, 0.1); /* Light hover effect */
        }

        /* For the Admin menu section (dropdown items) */
        .menu-section1 .dropdown-menu li a {
            color: white !important; /* Make dropdown links white */
        }

        <?php if (isset($_SESSION['alogin'])) { ?>
            body {
                padding-top: 80px; /* Adjusted padding for admin */
            }
        <?php } ?>

        /* Active Page Highlight */
        .active {
            background-color: #007bff !important; /* Blue background for active page */
            color: white !important; /* White text for active page */
        }

        .dropdown-menu .active {
            background-color: #007bff !important; /* Blue background for active dropdown item */
            color: white !important; /* White text for active dropdown item */
        }

        /* Dropdown Menu Styles */
        .dropdown-menu {
            min-width: auto; /* Allow the dropdown to adjust width based on content */
            width: max-content; /* Set width to fit the longest text */
            white-space: nowrap; /* Prevent text from wrapping */
            padding: 0; /* Remove default padding */
            margin: 0; /* Remove default margin */
            background-color: #1B4D3E; /* Changed to #1B4D3E */
        }

        .dropdown-menu li {
            width: 100%;
        }

        .dropdown-menu li a {
            display: block;
            padding: 8px 15px;
            color: white !important; /* Changed to white */
            text-decoration: none;
            white-space: nowrap; /* Prevent text from wrapping */
        }

        .dropdown-menu li a:hover {
            background-color: rgba(255, 255, 255, 0.1); /* Light hover effect */
        }

         /* Your existing styles */

        /* Ensure the modal is not blurred and text is visible */
        .modal {
           
            filter: none !important;
            opacity: 1 !important;
        }

        .modal-content {
            background-color: #fff; /* Ensure the modal background is white */
            color: #000; /* Ensure the text color is black */
            border-radius: 5px; /* Add rounded corners */
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5); /* Add a shadow for better visibility */
            pointer-events: auto !important;
        }
        /* Ensure the modal content is visible */
        .modal-content {
            background-color: #fff; /* Ensure the modal background is white */
            color: #000; /* Ensure the text color is black */
            border-radius: 5px; /* Add rounded corners */
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5); /* Add a shadow for better visibility */
        }

        .modal-header, .modal-body, .modal-footer {
            padding: 15px; /* Add padding for better spacing */
        }

        .modal-header {
            border-bottom: 1px solid #e5e5e5; /* Add a border below the header */
        }

        .modal-footer {
            border-top: 1px solid #e5e5e5; /* Add a border above the footer */
        }

        /* Ensure the modal backdrop is above other elements but below the modal */
        .modal-backdrop {
            z-index: 100; /* Bootstrap backdrops typically use 1040 */
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .menu-section ul {
                text-align: center;
            }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<div class="navbar navbar-inverse set-radius-zero" style="background-color: #1B4D3E; color: white;">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand">
                <img src="assets/img/src.png" style="vertical-align: middle;" /> <!-- Align image to middle -->
            </a>
            <h4 style="color: white; margin-top: 10px; display: inline-block; vertical-align: middle; font-family: 'Montserrat', sans-serif; text-transform: uppercase;">
    <?php echo !isset($_SESSION['alogin']) ? '<span style="font-size: 1.3em; font-weight: bold;">St. Rose College Educational Foundation, Inc.</span> <br> <span>Samput, Paniqui, Tarlac</span>' : '<span style="display: inline-block; font-weight: bold; margin-top: 10px;">St. Rose College Educational Foundation, Inc. <br> Equipment Management System</span>'; ?>
</h4>
    </div>

   
     <!-- Security Code Modal -->
<div class="modal fade" id="securityCodeModal" tabindex="-1" role="dialog" aria-labelledby="securityCodeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" >
            <div class="modal-header" style="background-color: #1B4D3E; color: #fff;">
                <h5 class="modal-title" id="securityCodeModalLabel">Enter Security Code    <a type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true" style="font-size: 30px; text-decoration: none; color: #fff !important;">&times;</span>
    </a></h5>
            
            </div>
            <div class="modal-body">
                <form id="securityCodeForm">
                    <div class="form-group">
                        <label for="securityCode">Security Code</label>
                        <input type="password" class="form-control" id="securityCode" placeholder="Enter Security Code" required>
                    </div>
                    <button type="submit" class="btn btn-primary" style="background-color: #1B4D3E; color: #fff;">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

        <!-- Show logout button when admin is logged in -->
        <?php if (isset($_SESSION['alogin'])) { ?>
            <section class="menu-section1">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="navbar-collapse collapse">
                                <ul id="menu-top" class="nav navbar-nav navbar-right">
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" id="ddlmenuItem" data-toggle="dropdown" aria-expanded="false">
                                            <i class="fa fa-user"></i> &nbsp; <?php echo htmlentities($AdName); ?> <i class="fa fa-angle-down"></i>
                                        </a>
                                        <ul class="dropdown-menu" role="menu" aria-labelledby="ddlmenuItem" style="background-color: #1B4D3E;">
                                        <li>
                                            <a href="my-profile.php" style="color: #fff;" data-security="true">My Profile</a>
                                        </li>
                                        <li><a href="activity-logs.php" style="color: #fff;">Activity Logs</a></li>      
                                            <li><a href="#" onclick="SweetAlert4(event)" style="color: #fff;">LOG OUT</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        <?php } else { ?>

            
                <!-- For non-logged-in users, show the "Home" and "Admin Login" links -->
                <section class="menu-section1" style="background-color: #1B4D3E; color: white;">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="navbar-collapse collapse">
                                    <ul id="menu-top" class="nav navbar-nav navbar-right">
                                        <li><a href="index.php#home">Home</a></li>
                                        <li><a href="index.php#about">About</a></li>
                                        <li><a href="index.php#contact">Contacts</a></li>
                                        <li><a href="index.php#alogin">Login</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            <?php } ?>
        </div>
    </div>

<?php if (isset($_SESSION['alogin'])) { ?>
    <section class="menu-section" style="margin-top: 20px; border-bottom: 5px solid #1B4D3E;">
        <div class="container-fluid" >
            <div class="row">
                <div class="col-md-12" >
                    <div class="navbar-collapse collapse">
                        <ul id="menu-top" class="nav navbar-nav navbar-center" style="display: flex; justify-content: center; width: 100%; padding: 0;">
                            <!-- Dashboard Link -->
                            <li style="margin: 0 10px; list-style: none;">
                                <a href="dashboard.php" class="<?php echo ($active_page == 'dashboard') ? 'active' : ''; ?>" style="display: block; padding: 10px 15px; border: 1px solid #ddd; border-radius: 5px; text-decoration: none; color: black !important;">
                                    <i class="fa fa-tachometer-alt"></i> DASHBOARD
                                </a>
                            </li>

                            <!-- Categories Dropdown -->
                            <li style="margin: 0 10px; list-style: none;">
                                <a href="#" class="dropdown-toggle <?php echo (in_array($active_page, ['add-category', 'manage-categories'])) ? 'active' : ''; ?>" id="ddlmenuItem" data-toggle="dropdown" style="display: block; padding: 10px 15px; border: 1px solid #ddd; border-radius: 5px; text-decoration: none; color: black !important;">
                                    <i class="fa fa-th"></i> Categories <i class="fa fa-angle-down"></i>
                                </a>
                                <ul class="dropdown-menu" role="menu" aria-labelledby="ddlmenuItem">
                                    <li style="list-style: none; padding: 0;">
                                        <a href="add-category.php" class="<?php echo ($active_page == 'add-category') ? 'active' : ''; ?>" style="display: block; padding: 8px 15px; width: 100%; color: #fff !important;">Add Category</a>
                                    </li>
                                    <li style="list-style: none; padding: 0;">
                                        <a href="manage-categories.php" class="<?php echo ($active_page == 'manage-categories') ? 'active' : ''; ?>" style="display: block; padding: 8px 15px; width: 100%; color: #fff !important;">Category Lists</a>
                                    </li>
                                </ul>
                            </li>

                            <!-- Brands Dropdown -->
                            <li style="margin: 0 10px; list-style: none;">
                                <a href="#" class="dropdown-toggle <?php echo (in_array($active_page, ['add-brand', 'manage-brands'])) ? 'active' : ''; ?>" id="ddlmenuItem" data-toggle="dropdown" style="display: block; padding: 10px 15px; border: 1px solid #ddd; border-radius: 5px; text-decoration: none; color: black !important;">
                                    <i class="fa fa-tags"></i> Brands <i class="fa fa-angle-down"></i>
                                </a>
                                <ul class="dropdown-menu" role="menu" aria-labelledby="ddlmenuItem">
                                    <li style="list-style: none; padding: 0;">
                                        <a href="add-brand.php" class="<?php echo ($active_page == 'add-brand') ? 'active' : ''; ?>" style="display: block; padding: 8px 15px; width: 100%; color: #fff !important;">Add Brand</a>
                                    </li>
                                    <li style="list-style: none; padding: 0;">
                                        <a href="manage-brands.php" class="<?php echo ($active_page == 'manage-brands') ? 'active' : ''; ?>" style="display: block; padding: 8px 15px; width: 100%; color: #fff !important;">Brand Lists</a>
                                    </li>
                                </ul>
                            </li>

                            <!-- Equipments Dropdown -->
                            <li style="margin: 0 10px; list-style: none;">
                                <a href="#" class="dropdown-toggle <?php echo (in_array($active_page, ['add-product', 'manage-products'])) ? 'active' : ''; ?>" id="ddlmenuItem" data-toggle="dropdown" style="display: block; padding: 10px 15px; border: 1px solid #ddd; border-radius: 5px; text-decoration: none; color: black !important;">
                                    <i class="fa fa-box"></i> Equimpments <i class="fa fa-angle-down"></i>
                                </a>
                                <ul class="dropdown-menu" role="menu" aria-labelledby="ddlmenuItem">
                                    <li style="list-style: none; padding: 0;">
                                        <a href="add-equipment.php" class="<?php echo ($active_page == 'add-product') ? 'active' : ''; ?>" style="display: block; padding: 8px 15px; width: 100%; color: #fff !important;">Add Equimpment</a>
                                    </li>
                                    <li style="list-style: none; padding: 0;">
                                        <a href="manage-equipments.php" class="<?php echo ($active_page == 'manage-products') ? 'active' : ''; ?>" style="display: block; padding: 8px 15px; width: 100%; color: #fff !important;">Equipment Lists</a>
                                    </li>
                                </ul>
                            </li>

                            <!-- Issue Equipment Dropdown -->
                            <li style="margin: 0 10px; list-style: none;">
                                <a href="#" class="dropdown-toggle <?php echo (in_array($active_page, ['issue-equipment', 'manage-issued-products'])) ? 'active' : ''; ?>" id="ddlmenuItem" data-toggle="dropdown" style="display: block; padding: 10px 15px; border: 1px solid #ddd; border-radius: 5px; text-decoration: none; color: black !important;">
                                    <i class="fa fa-cogs"></i> Issue Equipments <i class="fa fa-angle-down"></i>
                                </a>
                                <ul class="dropdown-menu" role="menu" aria-labelledby="ddlmenuItem">
                                    <li style="list-style: none; padding: 0;">
                                        <a href="issue-equipment.php" class="<?php echo ($active_page == 'issue-equipment') ? 'active' : ''; ?>" style="display: block; padding: 8px 15px; width: 100%; color: #fff !important;">Issue New Equipment</a>
                                    </li>
                                    <li style="list-style: none; padding: 0;">
                                        <a href="manage-issued-equipments.php" class="<?php echo ($active_page == 'manage-issued-products') ? 'active' : ''; ?>" style="display: block; padding: 8px 15px; width: 100%; color: #fff !important;">Issued Equipments Lists</a>
                                    </li>
                                </ul>
                            </li>

                            <!-- Registered Students Link -->
                            <li style="margin: 0 10px; list-style: none;">
                                <a href="#" class="dropdown-toggle <?php echo (in_array($active_page, ['add-user', 'reg-users'])) ? 'active' : ''; ?>" id="ddlmenuItem" data-toggle="dropdown" style="display: block; padding: 10px 15px; border: 1px solid #ddd; border-radius: 5px; text-decoration: none; color: black !important;">
                                    <i class="fa fa-users"></i> Manage Users <i class="fa fa-angle-down"></i>
                                </a>
                                <ul class="dropdown-menu" role="menu" aria-labelledby="ddlmenuItem">
                                    <li style="list-style: none; padding: 0;">
                                        <a href="add-user.php" class="<?php echo ($active_page == 'add-user') ? 'active' : ''; ?>" style="display: block; padding: 8px 15px; width: 100%; color: #fff !important;">Add User</a>
                                    </li>
                                    <li style="list-style: none; padding: 0;">
                                        <a href="reg-users.php" class="<?php echo ($active_page == 'reg-users') ? 'active' : ''; ?>" style="display: block; padding: 8px 15px; width: 100%; color: #fff !important;">Registered Users</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php } ?>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script>
    // SweetAlert for Logout
    function SweetAlert4(event) {
        event.preventDefault(); // Prevent the default anchor tag behavior

        Swal.fire({
            title: 'Are you sure you want to log out?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#135D66',
            confirmButtonText: 'Yes',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect to the logout page if user confirms
                window.location.href = 'logout.php';
            }
        });
    }
</script>

 <!-- Scripts -->
 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function () {
    // Define the correct security code
    const correctSecurityCode = "admin123"; // Replace with your actual security code

    // Function to handle the security code submission
    $('#securityCodeForm').on('submit', function (e) {
        e.preventDefault();
        const enteredCode = $('#securityCode').val();

        if (enteredCode === correctSecurityCode) {
            // Redirect to the target page stored in the data attribute
            const targetPage = $('#securityCodeModal').data('target');
            window.location.href = targetPage;
        } else {
            alert('Incorrect security code. Please try again.');
        }
    });

    // Function to show the security code modal and set the target page
    $('a[data-security="true"]').on('click', function (e) {
        e.preventDefault();
        const targetPage = $(this).attr('href');
        $('#securityCodeModal').data('target', targetPage).modal('show');
    });

    // Ensure the modal is fully interactive
    $('#securityCodeModal').on('shown.bs.modal', function () {
        $('#securityCode').focus(); // Focus on the input field when modal is shown
    });
});
    </script>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function () {
        $('.dropdown-toggle').dropdown();
    });
</script>
</body>
</html>