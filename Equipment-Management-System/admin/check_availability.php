<?php 
require_once("includes/config.php");
//code check email
if(!empty($_POST["snumber"])) {
$snumber=$_POST["snumber"];
$sql ="SELECT id FROM tblproducts WHERE SNumber=:snumber";
$query= $dbh -> prepare($sql);
$query-> bindParam(':snumber', $isbn, PDO::PARAM_STR);
$query-> execute();
$results = $query -> fetchAll(PDO::FETCH_OBJ);
 
if($query -> rowCount() > 0){
echo "<span style='color:red'> Serial Number already exists with another Product. .</span>"; 
echo "<script>$('#add').prop('disabled',true);</script>";
} else { echo "<script>$('#add').prop('disabled',false);</script>";}
}