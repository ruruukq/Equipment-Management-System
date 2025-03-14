<?php
session_start();
error_reporting(E_ALL);
include('../includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit();
}
$rid = isset($_GET['rid']) ? intval($_GET['rid']) : 0;
$returnedQuantity = isset($_GET['quantity']) ? intval($_GET['quantity']) : 0; // Retrieve the returned quantity

if ($rid == 0) {
    echo "Invalid ID.";
    exit();
}

// Fetch product details from tblissuedproducts using the rid
$sql = "SELECT * FROM tblissuedproducts WHERE id = :rid";
$query = $dbh->prepare($sql);
$query->bindParam(':rid', $rid, PDO::PARAM_INT);
$query->execute();
$productDetails = $query->fetch(PDO::FETCH_ASSOC);

// Check if we have data for the product
if (!$productDetails) {
    echo "Product not found.";
    exit();
}
// Fetch last used invoice number from database
$sql = "SELECT last_invoice_number FROM invoice_settings WHERE id = 1";
$query = $dbh->prepare($sql);
if (!$query->execute()) {
    $errorInfo = $query->errorInfo();
    echo "Error executing query: " . $errorInfo[2];
    exit();
}
$result = $query->fetch(PDO::FETCH_ASSOC);

if (!$result) {
    $newInvoiceNumber = 1;
    $insertSQL = "INSERT INTO invoice_settings (id, last_invoice_number) VALUES (1, :newInvoiceNumber)";
    $insertQuery = $dbh->prepare($insertSQL);
    $insertQuery->bindParam(':newInvoiceNumber', $newInvoiceNumber, PDO::PARAM_INT);
    if (!$insertQuery->execute()) {
        $errorInfo = $insertQuery->errorInfo();
        echo "Error inserting initial invoice number: " . $errorInfo[2];
        exit();
    }
} else {
    $invoiceNumber = $result['last_invoice_number'];
    $newInvoiceNumber = $invoiceNumber + 1;
    $updateSQL = "UPDATE invoice_settings SET last_invoice_number = :newInvoiceNumber WHERE id = 1";
    $updateQuery = $dbh->prepare($updateSQL);
    $updateQuery->bindParam(':newInvoiceNumber', $newInvoiceNumber, PDO::PARAM_INT);
    if (!$updateQuery->execute()) {
        $errorInfo = $updateQuery->errorInfo();
        echo "Error updating invoice number: " . $errorInfo[2];
        exit();
    }
}

// Fetch the products and user details based on the rid
$sql = "SELECT 
            tblissuedproducts.ProductId, 
            tblissuedproducts.UserId, 
            tblissuedproducts.IssuesDate,
            tblissuedproducts.ReturnDate,
            tblissuedproducts.ExpReturn,
            tblissuedproducts.quantity,
            tblissuedproducts.borrowedqty, 
            tblissuedproducts.id as product_id, 
            tblproducts.ProductName,
            tblusers.FullName,
            tblusers.MobileNumber,
            tblusers.EmailId,
            tblusers.Usertype
        FROM tblissuedproducts
        JOIN tblproducts ON tblissuedproducts.ProductId = tblproducts.id
        JOIN tblusers ON tblissuedproducts.UserId = tblusers.UserId
        WHERE tblissuedproducts.id = :rid";  // Now using id to fetch product based on rid
$query = $dbh->prepare($sql);
$query->bindParam(':rid', $rid, PDO::PARAM_INT);
$query->execute();
$products = $query->fetchAll(PDO::FETCH_OBJ);

// Debug: Check if products were found
if (empty($products)) {
    echo "No products found for this record.";
    exit();
}

// Fetch the admin's details
$email = $_SESSION['alogin'];
$sql = "SELECT * FROM admin WHERE UserName=:username";
$query = $dbh->prepare($sql);
$query->bindParam(':username', $email, PDO::PARAM_STR);
$query->execute();
$results = $query->fetch(PDO::FETCH_ASSOC);

if ($results) {
    $AdName = $results['AdName'];
    $UserName = $results['UserName'];
    $AdminEmail = $results['AdminEmail'];
    $updationDate = $results['updationDate'];
} else {
    echo "Admin details not found.";
}
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
<a href="../manage-issued-equipments.php" class="no-print" style=" padding: 10px; background-color: #1B4D3E; color: white; text-decoration: none; display: inline-block; border-radius: 5px;">Back</a>
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
    This document serves as an official record for the equipment transactions made under the Equipment Management System. 
    Please review the details of the equipments listed below, including your issued quantities actual return dates, and the total quantity issued.
</p>

<br>
<div class="user-details" style="font-size: 15px; font-weight: bold; margin-bottom: 10px;">
    <div style="display: flex; justify-content: space-between;">
        <!-- Left Side: FullName, UserId, and UserType -->
        <div style="flex: 1; padding-right: 10px;">
        <div style="margin-bottom: 10px;">
                <span style="font-weight: bold;">Full Name:</span> <?php echo htmlentities($products[0]->FullName); ?>
            </div>
            <div style="margin-bottom: 10px;">
                <span style="font-weight: bold;">User ID:</span> <?php echo htmlentities($products[0]->UserId); ?>
            </div>
            <div style="margin-bottom: 10px;">
                <span style="font-weight: bold;">Role:</span> <?php echo htmlentities($products[0]->Usertype); ?>
            </div>
        </div>

        <!-- Right Side: MobileNumber and EmailId -->
        <div style="flex: 1; padding-left: 10px; text-align: right;">
            <div style="margin-bottom: 10px;">
                <span style="font-weight: bold;">Mobile Number:</span> <?php echo htmlentities($products[0]->MobileNumber); ?>
            </div>
            <div style="margin-bottom: 10px;">
                <span style="font-weight: bold;">Email ID:</span> <?php echo htmlentities($products[0]->EmailId); ?>
            </div>
        </div>
    </div>
</div>
        <br>
        <div class="table-wrapper">
            <table cellpadding="0" cellspacing="0" style="width: 100%; border-collapse: collapse;">
                <tr class="heading">
                    <td style="text-align: center; font-weight: bold; background-color: #f2f2f2;">Issued Date</td>
                    <td style="text-align: center; font-weight: bold; background-color: #f2f2f2;">Issued Equipment</td>
                    <td style="text-align: center; font-weight: bold; background-color: #f2f2f2;">Return Date</td>
                    <td style="text-align: center; font-weight: bold; background-color: #f2f2f2;">Issued Quantity</td>
                    <td style="text-align: center; font-weight: bold; background-color: #f2f2f2;">Current Quantity</td>
                </tr>
                <?php foreach ($products as $product): ?>
                    <tr class="item">
                        <td style="text-align: center;"><?php echo htmlentities(date('F j, Y', strtotime($product->IssuesDate))); ?></td>
                        <td style="text-align: center;"><?php echo htmlentities($product->ProductName); ?></td>
                        <td style="text-align: center; <?php echo empty($product->ReturnDate); ?>">
                            <?php 
                                if (empty($product->ReturnDate)) {
                                    echo "Not Yet Returned";
                                } else {
                                    echo htmlentities(date('F j, Y', strtotime($product->ReturnDate)));
                                }
                            ?>
                        </td>
                        <td style="text-align: center;"><?php echo htmlentities($product->borrowedqty); ?></td>
                        <td style="text-align: center;"><?php echo htmlentities($product->quantity); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <br>
        <!-- Display a message for issued products -->
  <!----      <?php
foreach ($products as $product):
    // Check if the product is borrowed (borrowedqty > 0 and no return date)
    if ($product->borrowedqty > 0 && empty($product->ReturnDate)) {
        echo "<p style='text-align: left; font-size: 15px;'>{$product->borrowedqty} Quantities of a {$product->ProductName} was Issued to you.</p>";
    }
    
    // Check if the product has been returned (ReturnDate is not empty)
    if (!empty($product->ReturnDate)) {
        // Use the returned quantity passed from the form
        echo "<p style='text-align: left; font-size: 15px; font-weight: bold;'>You Returned {$returnedQuantity} Quantities of a {$product->ProductName}.</p>";
    }
endforeach;
?> ---->



        <br>
        <div class="signature-section" style="text-align: left; margin-left: 0;">
    <div class="signature-line" style="width: 250px; text-align: left; margin-left: 0;"></div>
    <div class="signature-name">Mr./Ms. <?php echo htmlentities($AdName); ?></div>
    <div class="signature-title">Property Custodian</div>
</div>

    </div>
</body>
</html>
