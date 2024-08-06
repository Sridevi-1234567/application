<?php
error_reporting(0);
require('db.php');
session_start();
 
if (!isset($_SESSION['userId']) || !isset($_SESSION['usercategory']) || $_SESSION['usercategory'] != 'user') {
    header("Location: home.php");
    exit();
}
 $conn=getcon();
$query = $_GET['query'];
$search_query = "SELECT * FROM items WHERE name LIKE '%$query%'";
$search_result = mysqli_query($conn, $search_query);
if(mysqli_num_rows($search_result) == 0) {
    $error = "No items found for your search ";
}
 
?>
 
<!DOCTYPE html>
<html>
<head>
    <title>Search Results</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<style>
    .card-img-top {
        width: 100%; 
        height: 200px; 
        object-fit:contain; 
    }
    .card {
        border: 1px solid lightgray;
        margin: 10px;
        padding: 10px;
        transition: transform .2s;
    }
    .card:hover {
        transform: scale(1.05); 
    }
</style>
</head>
<body>
<header style="background-color:grey;color:white;text-align: center; height: 70px; padding: 20px; " >
    MOBISHOP
    <div style="position: absolute; top: 15px; right: 10px;">
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</header>
<div class="container">
<div class="text-right"> 
    <a href="viewitems.php" class="btn btn-primary mt-4">Back to Items</a>
</div>
    <h2>Search Results</h2>
    <form method="GET" action="searchitems.php" class="mb-4">
        <input type="text" name="query" class="form-control" placeholder="Search items by name" value="<?php echo htmlspecialchars($query); ?>" style="width:50%">
        <button type="submit" class="btn btn-primary mt-2">Search</button>
    </form>
    <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
    <div class="row">
        <?php while ($item = mysqli_fetch_assoc($search_result)) { ?>
            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="<?php echo $item['image']; ?>" class="card-img-top" alt="<?php echo $item['name']; ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $item['name']; ?></h5>
                        <p class="card-text">Quantity: <?php echo $item['quantity']; ?></p>
                        <p class="card-text">Price: $<?php echo $item['price']; ?></p>
                        <a href="itemdetails.php?id=<?php echo $item['id']; ?>" class="btn btn-primary">View Details</a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
</body>
</html>