<?php
session_start();
error_reporting(0);
include('includes/config.php');

// Fetch the necessary data from the database
$searchQuery = isset($_GET['searchQuery']) ? $_GET['searchQuery'] : '';
$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 3;

// SQL query to search and limit the results
$sql = "SELECT tblproducts.ProductName, tblproducts.availableQty, tblcategory.CategoryName, tblbrands.BrandName, tblproducts.SNumber, tblproducts.ProductPrice, tblproducts.id as productid, tblproducts.productImage, tblproducts.isIssued, tblproducts.productQty,  
        COUNT(tblissuedproducts.id) AS issuedProducts,
        COUNT(tblissuedproducts.ReturnStatus) AS returnedproduct
        FROM tblproducts
        INNER JOIN tblcategory ON tblcategory.CategoryName = tblproducts.CategoryName
        INNER JOIN tblbrands ON tblbrands.BrandName = tblproducts.BrandName
        LEFT JOIN tblissuedproducts ON tblissuedproducts.ProductId = tblproducts.id
        WHERE (tblproducts.ProductName LIKE :searchQuery
            OR tblbrands.BrandName LIKE :searchQuery
            OR tblcategory.CategoryName LIKE :searchQuery)
        GROUP BY tblproducts.id
        LIMIT :offset, :limit"; // Use limit and offset for pagination

$query = $dbh->prepare($sql);
$query->bindValue(':searchQuery', '%' . $searchQuery . '%');
$query->bindValue(':offset', $offset, PDO::PARAM_INT);
$query->bindValue(':limit', $limit, PDO::PARAM_INT);
$query->execute();
$results = $query->fetchAll(PDO::FETCH_OBJ);

// Query to count the total number of products
$countSql = "SELECT COUNT(DISTINCT tblproducts.id) as total
             FROM tblproducts
             INNER JOIN tblcategory ON tblcategory.CategoryName = tblproducts.CategoryName
             INNER JOIN tblbrands ON tblbrands.BrandName = tblproducts.BrandName
             LEFT JOIN tblissuedproducts ON tblissuedproducts.ProductId = tblproducts.id
             WHERE (tblproducts.ProductName LIKE :searchQuery
                OR tblbrands.BrandName LIKE :searchQuery
                OR tblcategory.CategoryName LIKE :searchQuery)";

$countQuery = $dbh->prepare($countSql);
$countQuery->bindValue(':searchQuery', '%' . $searchQuery . '%');
$countQuery->execute();
$totalProducts = $countQuery->fetch(PDO::FETCH_OBJ)->total;

if ($query->rowCount() > 0) {
    foreach ($results as $result) {
        // Output product data
        ?>
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
        <?php
    }

    // If fewer products are returned than the limit, it means there are no more products
    if ($query->rowCount() < $limit) {
        echo "<p class='col-md-12' style='text-align: center;'>No more Equipments</p>"; // Display below the products
    }
} else {
    echo "<p style='text-align: center; margin-top: 20px;'>No Equipments found</p>"; // Display if no products are found
}

// Pass the total number of products to JavaScript
echo "<input type='hidden' id='total-products' value='$totalProducts'>";
?>