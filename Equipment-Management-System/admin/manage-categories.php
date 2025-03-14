<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit();
} else {
    if (isset($_GET['del'])) {
        $id = $_GET['del'];

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

        // Fetch the category name before deleting
        $sql_category = "SELECT CategoryName FROM tblcategory WHERE id = :id";
        $query_category = $dbh->prepare($sql_category);
        $query_category->bindParam(':id', $id, PDO::PARAM_INT);
        $query_category->execute();
        $category = $query_category->fetch(PDO::FETCH_OBJ);  // Fetch category name

        $sql = "DELETE FROM tblcategory WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_STR);
        $query->execute();

        $admin_email = $_SESSION['alogin'];  // Get the logged-in admin's email

        $sql = "SELECT id FROM admin WHERE UserName = :username";
        $query = $dbh->prepare($sql);
        $query->bindParam(':username', $admin_email, PDO::PARAM_STR);
        $query->execute();
        $admin_result = $query->fetch(PDO::FETCH_OBJ);

        $user_id = $admin_result->id;  // Get the admin's ID
        // Log the action with the category name
        $action_made = "Admin Deleted Category: " . $category->CategoryName;
        action_made($dbh, $user_id, $action_made);  // Call the function to log the action
        $_SESSION['delmsg'] = "Category Deleted successfully";
        header('location:manage-categories.php');
        exit();
    }
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="category" content="" />
    <title>Equipment Management System | Manage Categories</title>
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
</head>
<body>
    <!------MENU SECTION START-->
    <?php include('includes/header.php'); ?>
    <!-- MENU SECTION END-->
    <div class="container">
        <div class="row pad-botm">
            <div class="col-md-12">
                <h4 class="header-line" style="position: relative; top: 30px;">Manage Categories</h4>
            </div>
        </div>
        <div class="row">
            <?php if (isset($_SESSION['error']) && $_SESSION['error'] != "") { ?>
                <div class="col-md-6">
                    <div class="alert alert-danger">
                        <strong>Error :</strong>
                        <?php echo htmlentities($_SESSION['error']); ?>
                        <?php unset($_SESSION['error']); ?>
                    </div>
                </div>
            <?php } ?>
            <?php if (isset($_SESSION['msg']) && $_SESSION['msg'] != "") { ?>
                <div class="col-md-6">
                    <div class="alert alert-success">
                        <strong>Success :</strong>
                        <?php echo htmlentities($_SESSION['msg']); ?>
                        <?php unset($_SESSION['msg']); ?>
                    </div>
                </div>
            <?php } ?>
            <?php if (isset($_SESSION['updatemsg']) && $_SESSION['updatemsg'] != "") { ?>
                <div class="col-md-6">
                    <div class="alert alert-success">
                        <strong>Success :</strong>
                        <?php echo htmlentities($_SESSION['updatemsg']); ?>
                        <?php unset($_SESSION['updatemsg']); ?>
                    </div>
                </div>
            <?php } ?>
            <?php if (isset($_SESSION['delmsg']) && $_SESSION['delmsg'] != "") { ?>
                <div class="col-md-6">
                    <div class="alert alert-danger">
                        <strong>Success :</strong>
                        <?php echo htmlentities($_SESSION['delmsg']); ?>
                        <?php unset($_SESSION['delmsg']); ?>
                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="row">
            <div class="col-md-12">
                <!-- Advanced Tables -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Categories Listing
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">#</th>
                                        <th>Category</th>
                                        <th style="text-align: center;">Creation Date</th>
                                        <th style="text-align: center;">Updation Date</th>
                                        <th style="text-align: center;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT * FROM tblcategory";
                                    $query = $dbh->prepare($sql);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                    $cnt = 1;
                                    if ($query->rowCount() > 0) {
                                        foreach ($results as $result) {
                                    ?>
                                            <tr class="odd gradeX">
                                                <td class="center"><?php echo htmlentities($cnt); ?></td>
                                                <td class="center"><?php echo htmlentities($result->CategoryName); ?></td>
                                                <td class="center" style="text-align: center;"><?php echo date("F j, Y", strtotime($result->CreationDate)) . " " . "at" . " "; echo date("g:i A", strtotime($result->CreationDate)); ?></td>
                                                <td class="center" style="text-align: center;">
                                                    <?php
                                                    // Check if UpdationDate is set, not empty, and valid
                                                    if (!empty($result->UpdationDate) && $result->UpdationDate != '0000-00-00 00:00:00') {
                                                        $date = new DateTime($result->UpdationDate);
                                                        echo $date->format('F y, j \a\t g:i A');
                                                    } else {
                                                        echo ""; // No date available, can be customized as needed
                                                    }
                                                    ?>
                                                </td>
                                                <td class="center" style="text-align: center;">
                                                    <a href="edit-category.php?catid=<?php echo htmlentities($result->id); ?>"><button class="btn btn-primary"><i class="fa fa-edit "></i> Edit</button></a>
                                                    <a href="manage-categories.php?del=<?php echo htmlentities($result->id); ?>" onclick="confirmDelete(event, <?php echo htmlentities($result->id); ?>, '<?php echo htmlentities($result->CategoryName); ?>');"><button class="btn btn-danger"><i class="fa fa-trash"></i> Delete</button></a>
                                                </td>
                                            </tr>
                                    <?php
                                            $cnt = $cnt + 1;
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!--End Advanced Tables -->
            </div>
        </div>
    </div>

    <!---------------------------DELETE/JS----------------------------->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(event, id, category) {
            event.preventDefault();
            Swal.fire({
                title: 'Delete ' + category + '?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#135D66',
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteCategory(id, category);  // Call delete category only when the user confirms
                } else {
                    Swal.fire({
                        title: 'Cancelled',
                        icon: 'info',
                        confirmButtonColor: '#135D66',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }

        function deleteCategory(id, category) {
            fetch("manage-categories.php?del=" + id)  // Send the 'id' in the URL for deletion
                .then(response => {
                    if (response.ok) {
                        return response.text();  // Get the response text (you can customize the response if needed)
                    } else {
                        throw new Error('Network response was not ok');
                    }
                })
                .then(data => {
                    Swal.fire({
                        title: 'Successfully Deleted ' + category,
                        icon: 'success',
                        confirmButtonColor: '#135D66',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        location.reload(); // Optionally reload the page after deletion to update the UI
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Error',
                        text: 'An error occurred while deleting the category.',
                        icon: 'error',
                        confirmButtonColor: '#135D66',
                        confirmButtonText: 'OK'
                    });
                });
        }
    </script>
    <!------------------------------------------------------------------>

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
</body>
</html>
<?php } ?>