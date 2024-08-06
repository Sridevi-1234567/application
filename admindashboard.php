<?php
require('db.php');
//error_reporting(0);
session_start();

 
if (!isset($_SESSION['userId']) || !isset($_SESSION['usercategory']) || $_SESSION['usercategory'] != 'admin') {
    header("Location:home.php");
    exit();
}
$conn=getcon();
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_item'])) {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $vendor = $_POST['vendor'];
    $desc = $_POST['desc'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['image']['name'];
        $filetype = $_FILES['image']['type'];
        $filesize = $_FILES['image']['size'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if (!in_array(strtolower($ext), $allowed)) {
            die("Error: Please select a valid file format.");
        }
        if ($filesize > 5 * 1024 * 1024) {
            die("Error: File size is larger than the allowed limit.");
        }
    }
    if(!is_dir("images/"))
    {
        mkdir("images/",0777, true);
    }
    $image = $_FILES['image']['name'];
    $target = "images/" . basename($image);
    move_uploaded_file($_FILES['image']['tmp_name'], $target);
    
    $query = "INSERT INTO items(name,category, image,description, vendor, quantity, price) VALUES ('$name','$category', '$target','$description', '$vendor', $quantity, $price)";
    mysqli_query($conn, $query);
    if(mysqli_affected_rows($conn) > 0) {
        echo "<script>alert('Item added successfully')</script>";
    } else {
        echo "<script>alert('Error in adding this item')</script>";
    }
    header("Refresh:0; url=admindashboard.php");
}
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $check="SELECT * FROM orders WHERE item_id='$id'";
    $c=mysqli_query($conn, $check);
    if(mysqli_num_rows($c) > 0) {
        echo "<script>alert('This item is already ordered by some users. You cannot delete this item')</script>";
        header("Refresh:0; url=admindashboard.php");
        exit();
    } else {
        $query = "DELETE FROM items WHERE id='$id'";
        $dresult=mysqli_query($conn, $query);
        if(mysqli_affected_rows($conn) > 0) {
            echo "<script>alert('Item deleted successfully')</script>";
        } else {
            echo "<script>alert('Error in deleting this item')</script>";
        }
        header("Refresh:0; url=admindashboard.php");
    }

}
 
$query = "SELECT * FROM items";
$result = mysqli_query($conn, $query);
?>
 
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
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
    
<div class="container">
    <h3>Add Item</h3>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Item Name:</label>
            <input type="text" class="form-control" name="name" required>
        </div>
        <div class="form-group">
            <label for="desc">Item Description:</label>
            <input type="text" class="form-control" name="desc" required>
        </div>
        <div class="form-group">
            <label for="category">Category:</label>
            <select name="category" class="form-control" required>
                <option value="phones">Featured keypad Mobile</option>
                <option value="smartphones">Smart Phones</option>
            </select>
        </div>
        <div class="form-group">
            <label for="image">Image:</label>
            <input type="file" class="form-control-file" name="image" required>
        </div>
        <div class="form-group">
            <label for="vendor">Vendor:</label>
            <input type="text" class="form-control" name="vendor" required>
        </div>
        <div class="form-group">
            <label for="quantity">Quantity:</label>
            <input type="number" class="form-control" name="quantity" required>
        </div>
        <div class="form-group">
            <label for="price">Price:</label>
            <input type="number" step="0.01" class="form-control" name="price" required>
        </div>
        <button type="submit" name="add_item" class="btn btn-success">Add Item</button>
    </form>
    <hr>
    <h3>Items</h3>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Image</th>
                <th>Description</th>
                <th>Vendor</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($item = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $item['id']; ?></td>
                    <td><?php echo $item['name']; ?></td>
                    <td><img src="<?php echo $item['image']; ?>" width="50" alt="img not found"></td>
                    <td><?php echo $item['description']; ?></td>
                    <td><?php echo $item['vendor']; ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td><?php echo $item['price']; ?></td>
                    <td>
                        <a href="adminedititem.php?itemid=<?php echo $item['id']; ?>" class="btn btn-primary">Edit</a>
                        <a href="admindashboard.php?delete=<?php echo $item['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>