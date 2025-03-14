<?php
session_start();
error_reporting(0);
include('includes/config.php');

// Fetching search input
$searchQuery = isset($_GET['searchQuery']) ? $_GET['searchQuery'] : '';

// SQL query to search products by Product Name, Brand, or Category
$sql = "SELECT 
            tblproducts.ProductName, 
            tblproducts.availableQty, 
            tblcategory.CategoryName, 
            tblbrands.BrandName, 
            tblproducts.SNumber, 
            tblproducts.ProductPrice, 
            tblproducts.id as productid, 
            tblproducts.productImage, 
            tblproducts.isIssued, 
            tblproducts.productQty,  
            COUNT(tblissuedproducts.id) AS issuedProducts,
            COUNT(CASE WHEN tblissuedproducts.ReturnDate IS NOT NULL THEN 1 END) AS returnedproduct
        FROM tblproducts
        INNER JOIN tblcategory ON tblcategory.CategoryName = tblproducts.CategoryName
        INNER JOIN tblbrands ON tblbrands.BrandName = tblproducts.BrandName
        LEFT JOIN tblissuedproducts ON tblissuedproducts.ProductId = tblproducts.id
        WHERE (tblproducts.ProductName LIKE :searchQuery
            OR tblbrands.BrandName LIKE :searchQuery
            OR tblcategory.CategoryName LIKE :searchQuery)
        GROUP BY tblproducts.id
        LIMIT 6"; // Limiting to 6 products

$query = $dbh->prepare($sql);
$query->bindValue(':searchQuery', '%' . $searchQuery . '%');
$query->execute();
$results = $query->fetchAll(PDO::FETCH_OBJ);
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="product" content="" />
    <title>Equipment Management System | Issued Products</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- DATATABLE STYLE  -->
    <link href="assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <link rel="icon" href="assets/img/src.png">
    <style>
        /* Custom CSS to ensure products fit properly */
        .product-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px; /* Adjust the gap between products */
            justify-content: flex-start;
        }
        .product-item {
            flex: 1 1 calc(33.333% - 20px); /* Three products per row */
            box-sizing: border-box;
            max-width: calc(33.333% - 20px); /* Ensure equal width */
        }
        @media (max-width: 992px) {
            .product-item {
                flex: 1 1 calc(50% - 20px); /* Two products per row on smaller screens */
                max-width: calc(50% - 20px);
            }
        }
        @media (max-width: 768px) {
            .product-item {
                flex: 1 1 100%; /* One product per row on mobile */
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <!------MENU SECTION START-->
    <?php include('includes/header.php'); ?>
    <br>
    <!-- MENU SECTION END-->
    <div class="container">
        <div class="row pad-botm">
            <div class="col-md-12">
                <h4 class="header-line" style="position: relative; top: 30px;">Equipments Lists</h4>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="search-bar">
            <form id="search-form" method="GET">
                <div class="form-group">
                    <input type="text" id="search-query" class="form-control" placeholder="Search by Product Name, Brand, or Category" name="searchQuery">
                </div>
            </form>
        </div>

        <div class="row">
            <div class="col-md-12">
                <!-- Advanced Tables -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                    Equipments Information
                    </div>
                    <div class="panel-body">
                        <div class="product-container" id="product-container">
                            <?php if ($query->rowCount() > 0) { ?>
                                <?php foreach ($results as $result) { ?>
                                    <div class="product-item">
                                        <table class="table table-bordered">
                                            <tr>
                                                <td rowspan="2"><img src="admin/productimg/<?php echo htmlentities($result->productImage); ?>" width="120"></td>
                                                <th>Equipment Name</th>
                                                <td><?php echo htmlentities($result->ProductName); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Brand</th>
                                                <td><?php echo htmlentities($result->BrandName); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Category</th>
                                                <td colspan="2"><?php echo htmlentities($result->CategoryName); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Available Equipment</th>
                                                <td colspan="2"><?php echo htmlentities($result->availableQty); ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                <?php } ?>
                            <?php } else { ?>
                                <p class="col-md-12" style="text-align: center;">No Equipments found matching your search.</p>
                            <?php } ?>
                        </div>

                        <!-- "Back" and "More Products" buttons -->
                        <button id="back-button" class="btn btn-primary" style="display:none;">Back</button>
                        <button id="load-more" class="btn btn-primary" style="background-color: #1B4D3E; color: #fff;">More Equipments</button>
                    </div>
                </div>
                <!--End Advanced Tables -->
            </div>
        </div>
    </div>
    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('includes/footer.php'); ?>
    <!-- FOOTER SECTION END-->
    <!-- JAVASCRIPT FILES PLACED AT THE BOTTOM TO REDUCE THE LOADING TIME  -->
    <!-- CORE JQUERY  -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS  -->
    <script src="assets/js/bootstrap.js"></script>
    <!-- DATATABLE SCRIPTS  -->
    <script src="assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="assets/js/dataTables/dataTables.bootstrap.js"></script>
    <!-- CUSTOM SCRIPTS  -->
    <script src="assets/js/custom.js"></script>
    <!-- JS for loading more products -->
    <script>
        $(document).ready(function() {
    let offset = 6; // Start loading from the 4th product
    let limit = 6; // Number of products to load per request
    let previousOffset = 0; // To store the offset for the previous state
    let currentSearchQuery = ''; // To store the current search query

    // Get the total number of products from the hidden input
    let totalProducts = parseInt($("#total-products").val());

    // Disable "More Products" button if there are no more products to load
    if (totalProducts <= limit) {
        $("#load-more").text('No more products').attr('disabled', true);
    }

    // "Load More" button click event
    $("#load-more").click(function() {
        $.ajax({
            url: 'load-equipments.php', // PHP file where products are fetched
            type: 'GET',
            data: {
                offset: offset,
                limit: limit,
                searchQuery: currentSearchQuery // Pass the search query
            },
            success: function(response) {
                if (response) {
                    previousOffset = offset; // Store the current offset before updating it
                    $("#product-container").append(response); // Append more products to the container
                    offset += limit; // Increase the offset for the next request

                    // Check if the response includes "No more products"
                    if (response.includes("No more Equipments") || response.trim() === '') {
                        // When no more products are found, change button text to "No more products"
                        $("#load-more").text('No more Equipments').attr('disabled', true); // Disable the button
                    } else {
                        $("#load-more").text('More Equipments'); // Reset to "More Products" if there are more
                    }

                    $("#back-button").show(); // Show back button after loading more products
                }
            }
        });
    });

    // "Back" button click event
    $("#back-button").click(function() {
        if (previousOffset > 0) {
            $.ajax({
                url: 'load-equipments.php', // PHP file where products are fetched
                type: 'GET',
                data: {
                    offset: previousOffset - limit,
                    limit: limit,
                    searchQuery: currentSearchQuery // Pass the search query to go back
                },
                success: function(response) {
                    if (response) {
                        $("#product-container").html(response); // Replace the current product container content with previous results
                        offset = previousOffset; // Reset the offset to the previous offset
                        previousOffset -= limit; // Adjust the previous offset for the next "Back" action

                        // Hide back button if there is no previous page
                        if (previousOffset <= 0) {
                            $("#back-button").hide();
                        }

                        // Ensure the load-more button displays correctly
                        if (response.includes("No more Equipments") || response.trim() === '') {
                            $("#load-more").text('No more Equipments').attr('disabled', true); // Disable button if no products
                        } else {
                            $("#load-more").text('More Equipments');
                            $("#load-more").attr('disabled', false); // Enable "More Products" button again
                        }
                    }
                }
            });
        }
    });

    // Detect when the user types or deletes in the search bar
    $("#search-query").keyup(function() {
        currentSearchQuery = $("#search-query").val(); // Update the current search query

        if (currentSearchQuery === '') {
            // If search is empty, reset the query and fetch all products
            $.ajax({
                url: 'load-equipments.php', // PHP file where products are fetched
                type: 'GET',
                data: { searchQuery: '', offset: 0, limit: 3 },
                success: function(response) {
                    if (response) {
                        $("#product-container").html(response); // Update the product container with all products
                        offset = 3; // Reset the offset after clearing the search query
                        previousOffset = 0; // Reset the previous offset
                        $("#back-button").hide(); // Hide the back button
                        $("#load-more").text('More Equipments').attr('disabled', false); // Reset the "More Products" button
                    }
                }
            });
        } else {
            // Perform search based on the user's input
            $.ajax({
                url: 'load-equipments.php', // PHP file where products are fetched
                type: 'GET',
                data: { searchQuery: currentSearchQuery, offset: 0, limit: 3 },
                success: function(response) {
                    if (response) {
                        $("#product-container").html(response); // Update the product container with search results
                        offset = 3; // Reset the offset after search query changes
                        previousOffset = 0; // Reset the previous offset
                        $("#back-button").hide(); // Hide the back button

                        // Check if the number of products returned is less than the limit
                        if ($(".product-item").length < 3) {
                            $("#load-more").text('No More Equipments').attr('disabled', true); // Disable the button and change text
                        } else {
                            $("#load-more").text('More Equipments').attr('disabled', false); // Reset the "More Products" button
                        }
                    }
                }
            });
        }
    });
});
    </script>
</body>
</html>