<?php
error_reporting(0);
require 'db.php';
session_start();
if (!isset($_SESSION['userId']) || !isset($_SESSION['usercategory']) || $_SESSION['usercategory'] != 'admin') {
    header("Location:home.php");
    exit();
}
if (isset($_GET["itemid"])) {
$id=$_GET["itemid"];   
 $oquantity=0;
 $con=getcon();
 $check="SELECT * FROM orders WHERE item_id='$id'";
$c=mysqli_query($con, $check);
while($row=mysqli_fetch_assoc($c)){
    $oquantity=$oquantity+$row['quantity'];
}
if(isset($_POST['updateitem'])){

$update_fields = array();

if (!empty($_POST['name'])) {
    $name = $_POST['name'];
    $update_fields[] = "name='$name'";
}

if (!empty($_POST['desc'])) {
    $desc = $_POST['desc'];
    $update_fields[] = "description='$desc'";
}

if (!empty($_POST['vendor'])) {
    $vendor = $_POST['vendor'];
    $update_fields[] = "vendor='$vendor'";
}

if (!empty($_POST['price'])) {
    $price = $_POST['price'];
    $update_fields[] = "price='$price'";
}

if (isset($_POST['quantity'])) {
    $quantity = $_POST['quantity'];
    // if($quantity<0){
    //     echo "<script>alert('Quantity cannot be less than zero')</script>";
    //     header("Refresh:0; url=admindashboard.php");
    //     exit();
    // }
    $update_fields[] = "quantity='$quantity'";
}

if (isset($_FILES['newimage']) && $_FILES['newimage']['error'] == 0) {
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    $filename = $_FILES['newimage']['name'];
    $filetype = $_FILES['newimage']['type'];
    $filesize = $_FILES['newimage']['size'];
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    if (!in_array(strtolower($ext), $allowed)) {
        die("Error: Please select a valid file format.");
    }
    if ($filesize > 5 * 1024 * 1024) {
        die("Error: File size is larger than the allowed limit.");
    }
    $newimage = $_FILES['newimage']['name'];
    $target = "images/" . basename($newimage);
    move_uploaded_file($_FILES['newimage']['tmp_name'], $target);
    $update_fields[] = "image='$target'";
}

if (!empty($update_fields)) {
    $update_query = "UPDATE items SET " . implode(", ", $update_fields) . " WHERE id='$id'";
    $result = mysqli_query($con, $update_query);
    if($result){
        echo "<script>alert('Item updated successfully')</script>";
        header("Refresh:0; url=admindashboard.php");
    }else{
        echo "Error: " . mysqli_error($con);
        echo "<script>alert('Error updating item')</script>";
        header("Refresh:0; url=admindashboard.php");
    }
} else {
    echo "<script>alert('No fields to update')</script>";
    header("Refresh:0; url=admindashboard.php");
}
}
}
else{
    echo "<script>alert('Error updating item no item id found')</script>";
    header("Refresh:0; url=admindashboard.php");
}
//hi

?>








<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update items</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</head>
<body>
<header style="background-color:grey;color:white;text-align: center; height: 70px; padding: 20px; " >
    MOBISHOP ADMIN DASHBOARD
    <div style="position: absolute; top: 15px; right: 10px;">
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</header>
<h4>Give fields only which you want to update and leave remaining as blank</h4>
<form method="POST" enctype="multipart/form-data" action="<?php echo $_SERVER["PHP_SELF"] . '?itemid=' . $_GET['itemid']; ?>">
        <div class="form-group">
            <label for="name">Item Name:</label>
            <input type="text" class="form-control" name="name" >
        </div>
        <div class="form-group">
            <label for="desc">Item Description:</label>
            <input type="text" class="form-control" name="desc" >
        </div>
        <div class="form-group">
            <label for="image">Image:</label>
            <input type="file" class="form-control-file" name="newimage" placeholder="image is optional">
        </div>
        <div class="form-group">
            <label for="vendor">Vendor:</label>
            <input type="text" class="form-control" name="vendor">
        </div>
        <div class="form-group">
            <label for="quantity">Quantity:</label>
            <input type="number" class="form-control" name="quantity" min="0" placeholder=" Items sold till now: <?php echo $oquantity ?>">
        </div>
        <div class="form-group">
            <label for="price">Price:</label>
            <input type="number" step="0.01" min="1" class="form-control" name="price">
        </div>
        <button type="submit" name="updateitem" class="btn btn-success" value="update">Update Item</button>
        <a href="admindashboard.php" class="btn btn-primary">Back</a>
    </form>
    
</body>
</html>
<?php
