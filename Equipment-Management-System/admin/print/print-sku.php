<?php
session_start();
error_reporting(E_ALL);
include('../includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit();
}

// Fetch productid from the URL
$productid = isset($_GET['productid']) ? intval($_GET['productid']) : null;

if (!$productid) {
    echo "Product ID is missing in the URL.";
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

// Fetch products and SKUs for the specified productid
$sql = "SELECT 
            tblproducts.ProductName, 
            tblcategory.CategoryName, 
            tblbrands.BrandName, 
            tblproducts.SNumber, 
            tblproducts.RegDate, 
            tblproducts.ProductPrice, 
            tblproducts.productQty, 
            tblproducts.availableQty, 
            tblproducts.id as productid, 
            tblproducts.productImage,
            tblsku.SNumber as sku_SNumber,
            tblsku.RegDate as sku_RegDate,
            tblsku.remarks
        FROM tblproducts 
        JOIN tblcategory ON tblcategory.CategoryName = tblproducts.CategoryName 
        JOIN tblbrands ON tblbrands.BrandName = tblproducts.BrandName
        LEFT JOIN tblsku ON tblsku.productid = tblproducts.id
        WHERE tblproducts.id = :productid"; // Filter by productid

$query = $dbh->prepare($sql);
$query->bindParam(':productid', $productid, PDO::PARAM_INT);
$query->execute();
$products = $query->fetchAll(PDO::FETCH_OBJ);

if (empty($products)) {
    echo "No products found for the specified Product ID.";
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
    // Assign the fetched details to variables
    $AdName = $results['AdName'];
    $UserName = $results['UserName'];
    $AdminEmail = $results['AdminEmail'];
    $updationDate = $results['updationDate'];  // You may need to adjust this based on your database structure
} else {
    // Handle the case where no user is found
    echo "Admin details not found.";
    exit();
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

$admin_email = $_SESSION['alogin'];  // Get the logged-in admin's email
$sqlAdmin = "SELECT id FROM admin WHERE UserName = :username";
$queryAdmin = $dbh->prepare($sqlAdmin);
$queryAdmin->bindParam(':username', $admin_email, PDO::PARAM_STR);
$queryAdmin->execute();
$admin_result = $queryAdmin->fetch(PDO::FETCH_OBJ);
$user_id = $admin_result->id;  // Get the admin's ID

// Log the action of printing
$action_made = "Property Custodian Printed an Equipment";  // Log the printing in the action
action_made($dbh, $user_id, $action_made);  // Call the function to log the action

// Get the referer URL
$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'view-equipment.php?productid=' . $productid;
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="equipment" content="" />
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
        table td:nth-child(4), /* Quantity column */
        table td:nth-child(5), /* Price column */
        table td:nth-child(6)  /* Total column */
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
                text-align: left;
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
<a href="<?php echo htmlentities($referer); ?>" class="no-print" style="padding: 10px; background-color: #1B4D3E; color: white; text-decoration: none; display: inline-block; border-radius: 5px;">
    Back
</a>

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
        Please review the details of the equipments listed below, including their quantities, prices, and the total amount due. 
    </p>

    <br>
    <div class="table-wrapper">
    <table cellpadding="0" cellspacing="0" style="width: 100%; border-collapse: collapse;">
    <tr class="heading">
        <td style="text-align: center; font-weight: bold; background-color: #f2f2f2;">Date</td>
        <td style="font-weight: bold; background-color: #f2f2f2;">Equipments</td>
        <td style="text-align: center; font-weight: bold; background-color: #f2f2f2;">Serial Numbers</td> <!-- Keep Serial Numbers column -->
        <td style="text-align: center; font-weight: bold; background-color: #f2f2f2;">Remarks</td> <!-- Keep Remarks column -->
    </tr>
    <?php
    $totalAmount = 0;
    foreach ($products as $product) {
        // Calculate total amount, but only considering the necessary product info
        $itemTotal = $product->ProductPrice * $product->productQty;
        $totalAmount += $itemTotal;
    ?>
    <tr class="item">
        <td style="text-align: center;"><?php echo date("F j, Y", strtotime($product->sku_RegDate)); ?></td> <!-- Use sku_RegDate for Date -->
        <td style="text-align: left;"><?php echo htmlentities($product->ProductName); ?></td>

        <!-- Display SNumber from tblsku -->
        <td style="text-align: center;">
            <?php
                if (!empty($product->sku_SNumber)) {
                    echo htmlentities($product->sku_SNumber); // Display the SKU Serial Number
                } else {
                    echo "N/A"; // If no SKU SNumber, display N/A
                }
            ?>
        </td>

        <!-- Display Remarks from tblsku -->
        <td style="text-align: center;">
            <?php
                if (!empty($product->remarks)) {
                    echo htmlentities($product->remarks); // Display the Remarks
                } else {
                    echo "N/A"; // If no remarks, display N/A
                }
            ?>
        </td>
    </tr>
    <?php } ?>
    
</table>

    </div>
    <br><br>

    <!-- Signature Section -->
    <div class="signature-section" style="text-align: left; margin-left: 0;">
        <div class="signature-line" style="width: 250px; text-align: left; margin-left: 0;"></div>
        <div class="signature-name">Mr./Ms. <?php echo htmlentities($AdName ?? 'N/A'); ?></div>
        <div class="signature-title">Property Custodian</div>
    </div>
</div>
</body>
</html>