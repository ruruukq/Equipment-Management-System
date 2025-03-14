<?php
session_start();
error_reporting(E_ALL);
include('../includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit();
}

// Fetch last used invoice number from database
$sql = "SELECT last_invoice_number FROM invoice_settings WHERE id = 1"; // Assuming there's only one row for invoice settings
$query = $dbh->prepare($sql);
if (!$query->execute()) {
    $errorInfo = $query->errorInfo();
    echo "Error executing query: " . $errorInfo[2];
    exit();
}
$result = $query->fetch(PDO::FETCH_ASSOC);

if (!$result) {
    // Handle the case where no result is returned
    // Initialize the invoice number with a default value if the table is empty
    $newInvoiceNumber = 1;

    // Insert a new row into invoice_settings table to start the invoice numbering
    $insertSQL = "INSERT INTO invoice_settings (id, last_invoice_number) VALUES (1, :newInvoiceNumber)";
    $insertQuery = $dbh->prepare($insertSQL);
    $insertQuery->bindParam(':newInvoiceNumber', $newInvoiceNumber, PDO::PARAM_INT);
    if (!$insertQuery->execute()) {
        $errorInfo = $insertQuery->errorInfo();
        echo "Error inserting initial invoice number: " . $errorInfo[2];
        exit();
    }
} else {
    // Get the current invoice number and increment it for the new invoice
    $invoiceNumber = $result['last_invoice_number'];
    $newInvoiceNumber = $invoiceNumber + 1;

    // Update the invoice number in the database for the next invoice
    $updateSQL = "UPDATE invoice_settings SET last_invoice_number = :newInvoiceNumber WHERE id = 1";
    $updateQuery = $dbh->prepare($updateSQL);
    $updateQuery->bindParam(':newInvoiceNumber', $newInvoiceNumber, PDO::PARAM_INT);
    if (!$updateQuery->execute()) {
        $errorInfo = $updateQuery->errorInfo();
        echo "Error updating invoice number: " . $errorInfo[2];
        exit();
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

// Fetch all users from the database
$sqlUsers = "SELECT * FROM tblusers ORDER BY FullName ASC";
$queryUsers = $dbh->prepare($sqlUsers);
$queryUsers->execute();
$users = $queryUsers->fetchAll(PDO::FETCH_OBJ);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="product" content="" />
    <title>Equipment Management System | Print</title>
    <!-- BOOTSTRAP CORE STYLE -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <link rel="icon" href="../assets/img/src.png">
    <!-- GOOGLE FONT -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <style>
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            font-size: 16px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        }
        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
            border-collapse: collapse; /* Ensure borders are merged */
        }
        .invoice-box table td, .invoice-box table th {
            padding: 10px;
            vertical-align: top;
            border: 2px solid #000; /* Bold borders for all cells */
        }
        .invoice-box table tr td:nth-child(2) {
            text-align: right;
        }
        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }
        .invoice-box table tr.top table td.title {
            font-size: 45px;
            line-height: 45px;
            color: #333;
        }
        .invoice-box table tr.information table td {
            padding-bottom: 40px;
        }
        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 3px solid #000; /* Extra bold border for header */
            font-weight: bold;
            padding: 10px 15px;
            text-align: center;
        }
        .invoice-box table tr.item td {
            border-bottom: 2px solid #000; /* Bold borders for item rows */
        }
        .invoice-box table tr.total td:nth-child(2) {
            border-top: 3px solid #000; /* Extra bold border for total row */
            font-weight: bold;
        }
        .invoice-box table tr.total td {
            border-top: 3px solid #000; /* Extra bold border for the total row */
            font-weight: bold;
            text-align: right;
            border-left: none;  /* Remove the left border */
            border-right: none; /* Remove the right border */
            padding: 10px 15px;
        }

     /* Signature Section */
.signature-section {
    margin-top: 30px;
    text-align: center;
    width: 300px; /* Adjust width to fit the name */
    margin-left: auto;
    margin-right: auto;
}
.signature-line {
    width: 200px; /* Signature line width */
    border-top: 1px solid #000;
    margin: 0 auto;
    margin-bottom: 10px;
}
.signature-name {
    font-weight: bold;
    font-size: 14px; /* Adjust font size */
}
.signature-title {
    font-style: italic;
    font-size: 12px; /* Adjust font size */
}

        .table {
    table-layout: fixed; /* Make table columns fixed to avoid content overflow */
    width: 100%; /* Ensure table fits inside the container */
}
/* Adjust the width of each table column */
table td:nth-child(1), /* Date column */
table td:nth-child(2), /* Products column */
table td:nth-child(3), /* Brand column */
table td:nth-child(4), /* SKU column */
table td:nth-child(5), /* Quantity column */
table td:nth-child(6), /* Price column */
table td:nth-child(7)  /* Total column */
{
    max-width: 150px; /* Adjust column widths as per content */
    word-wrap: break-word; /* Wrap text inside the cells */
}
@media print {
    .no-print {
        display: none !important;  /* Hide elements with the 'no-print' class */
    }
    .invoice-box {
        box-shadow: none;
        max-width: 100%;
        padding: 10px;
    }
    .no-print {
        display: none;
    }
    .header-section {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 20px;
    }
    .header-section img {
        width: 80px; /* Smaller logo for print */
        height: auto;
        margin-right: 10px;
    }
    .header-section .school-details {
        font-size: 14px; /* Smaller font for print */
        font-weight: bold;
        line-height: 1.2;
    }

    .header-section .invoice-details {
        flex-direction: column;
        align-items: flex-end;
        text-align: right;
        margin-left: 100px; /* Keep the margin for proper alignment */
        justify-content: flex-end;
        font-size: 14px; /* Smaller font for print */
        line-height: 1.2;
        position: absolute; /* Position it at the bottom of the page */
        right: 10px; /* Adjust the right margin */
        top: 45px; /* Set it to the bottom */
        margin-top: 7px;
        width: auto;
    }

    .invoice-box table {
        font-size: 14px; /* Smaller font for print */
    }
}


    </style>
</head>
<body>
<a href="../reg-users.php" class="no-print" style=" padding: 10px; background-color: #1B4D3E; color: white; text-decoration: none; display: inline-block; border-radius: 5px;">Back</a>
<div class="invoice-box">
    <div class="header-section">
        <!-- Image and School Details -->
        <div style="display: flex; align-items: center;">
            <img src="../assets/img/src.png" alt="School Logo" style="width: 100px; height: auto; margin-right: 10px;">
            <div class="school-details" style="flex-grow: 1;">
                <div style="display: flex; justify-content: space-between; align-items: baseline;">
                    <span>St. Rose College Educational Foundation, Inc.</span>
                    <span style="font-weight: normal;" class="invoice-details">Created: <?php echo date('F j, Y'); ?></span>
                </div>
                <span style="font-weight: normal; display: block; margin-top: 2px;">Samput, Paniqui, Tarlac</span>
            </div>
        </div>
    </div>

        <h1 style="font-size: 25px; text-align: center; font-weight: bold;">Equipment Management System</h1>
<p style="text-align: center; font-size: 16px; color: #555; margin-top: 10px;">
This document serves as an official record of all registered users under the Equipment Management System.
Please review the details of the users listed below, including their roles, contact information, and account status.
</p>

<br>

<div class="table-wrapper">
    <table cellpadding="0" cellspacing="0" style="width: 100%; border-collapse: collapse;">
        <tr class="heading">
            <td style="text-align: center; font-weight: bold; background-color: #f2f2f2;">#</td>
            <td style="font-weight: bold; background-color: #f2f2f2;">User ID</td>
            <td style="text-align: center; font-weight: bold; background-color: #f2f2f2;">Full Name</td>
            <td style="text-align: center; font-weight: bold; background-color: #f2f2f2;">Role</td>
            <td style="text-align: center; font-weight: bold; background-color: #f2f2f2;">Email</td>
            <td style="text-align: center; font-weight: bold; background-color: #f2f2f2;">Mobile Number</td>
            <td style="text-align: center; font-weight: bold; background-color: #f2f2f2;">Status</td>
        </tr>
        <?php
        $cnt = 1;
        if ($queryUsers->rowCount() > 0) {
            foreach ($users as $user) {
        ?>
        <tr class="item">
            <td style="text-align: center;"><?php echo $cnt; ?></td>
            <td style="text-align: center;"><?php echo htmlentities($user->UserId); ?></td>
            <td style="text-align: left;"><?php echo htmlentities($user->FullName); ?></td>
            <td style="text-align: center;"><?php echo htmlentities($user->Usertype); ?></td>
            <td style="text-align: center;"><?php echo htmlentities($user->EmailId); ?></td>
            <td style="text-align: center;"><?php echo htmlentities($user->MobileNumber); ?></td>
            <td style="text-align: center;">
                <?php echo ($user->Status == 1) ? '<span style="color: green;">Active</span>' : '<span style="color: red;">Blocked</span>'; ?>
            </td>
        </tr>
        <?php
                $cnt++;
            }
        } else {
        ?>
        <tr>
            <td colspan="7" style="text-align: center;">No users found.</td>
        </tr>
        <?php } ?>
    </table>
</div>

<br><br>

<!-- Signature Section -->
<div class="signature-section" style="text-align: left; margin-left: 0;">
    <div class="signature-line" style="width: 250px; text-align: left; margin-left: 0;"></div>
    <div class="signature-name">Mr./Ms. <?php echo htmlentities($AdName); ?></div>
    <div class="signature-title">Property Custodian</div>
</div>
    </div>
</body>
</html>