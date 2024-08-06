<?php
//error_reporting(0);
require('db.php');
session_start();
 
if (!isset($_SESSION['userId']) || !isset($_SESSION['usercategory']) || $_SESSION['usercategory'] != 'user') {
    header("Location: home.php");
    exit();
}
$userId = $_SESSION['userId'];
$conn=getcon(); 
$query = "SELECT * FROM items";
//$result = mysqli_query($conn, $query);
 if(isset($_GET["filters"])) {
       $filters = [];
       $query = "SELECT * FROM items WHERE 1=1";
    if (isset($_GET['query']) && $_GET['query'] != '') {
        $searchQuery = $_GET['query'];
        $query .= " AND name LIKE '%$searchQuery%'";
    }
    if (isset($_GET['category']) && $_GET['category'] != '') {
        $category = $_GET['category'];
        $query .= " AND category = '$category'";
    }
     
    if (isset($_GET['price_min']) && isset($_GET['price_max']) && $_GET['price_min'] != '' && $_GET['price_max'] != '') {
        $price_min = $_GET['price_min'];
        $price_max = $_GET['price_max'];
        $query .= " AND price BETWEEN $price_min AND $price_max";
    }
    if (isset($_GET['sort'])) {
        $sort = $_GET['sort'];
        switch ($sort) {
            case 'price_asc':
                $query .= " ORDER BY price ASC";
                break;
            case 'price_desc':
                $query .= " ORDER BY price DESC";
                break;
            case 'newest':
                $query .= " ORDER BY id DESC";
                break;
            case 'oldest':
                $query .= " ORDER BY id ASC";
                break;
        }
    }
 }
 
$result = mysqli_query($conn, $query);
if(mysqli_num_rows($result) == 0) {
    $error = "No items found for your search criteria.";
}
?>
 
<!DOCTYPE html>
<head>
<script>
window.onload = function() {
    document.getElementById('toggleFilters').addEventListener('click', function() {
        var form = document.getElementById('filterForm');
        if (form.style.display === 'none') {
            form.style.display = 'block';
            form.style.width = '30%';
        } else {
            form.style.display = 'none';
        }
    });

    document.getElementById('toggleUserDetails').addEventListener('click', function() {
        var form = document.getElementById('userDetails');
        if (form.style.display === 'none') {
            form.style.display = 'block';
            form.style.width = '30%';
        } else {
            form.style.display = 'none';
        }
    });
};

</script>
    <title>View Items</title>
<!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"> -->
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
    .users {
        width: 80%;
        margin: auto;
        position: relative;

    }
    .user-details {
        margin: auto;
        display: flex;
        justify-content: space-between;
    }
    .user-details form {
        display: flex;
        flex-direction: column;
    }
    .user-details form input[type="text"] {
        margin-bottom: 10px;
    }
    .user-details form input[type="submit"] {
        cursor: pointer;
    }
   
    .btn-left {
        position: relative;
        top: 10px;
        left: 45%;

    }
</style>
</head>
<body>
<header style="background-color:grey;color:white;text-align: center; height: 70px; padding: 20px; " >
    MOBISHOP
    <div style="position: absolute; top: 15px; left: 10px; width: 40%;">
        <form method="GET" action="searchitems.php" style="display: flex;">
            <input type="text" name="query" class="form-control" placeholder="Search items with name" style="width: 85%;" required>
            <button type="submit" class="btn btn-primary mt-2" style="width: 15%;">Search</button>
        </form>
    </div>
    <div style="position: absolute; top: 15px; right: 10px;">
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</header>
<div class="users">
<button id="toggleUserDetails" class="btn btn-secondary mb-3 btn-left">Your Details</button>
<div id="userDetails" style="display: none;" class="user-details">
    <h3>User Details</h3>
    <p>Name: <?php echo $_SESSION['username']; ?></p>
    <p>Email: <?php echo $_SESSION['email']; ?></p>
    <p>Mobile: <?php echo $_SESSION['mobile'];?></p>
    <p>Address: <?php echo $_SESSION['address'];?></p>
    <form method="POST" action="updateUserDetails.php">
        <label for="mobile">Name:</label><br>
        <input type="text" id="name" name="name" placeholder="update Name" pattern="[A-Za-z0-9\s.,'-]+" required class="form-control"><br>
        <input type="submit" value="Update Name" id="updateNameButton" name="updateNameButton" class="btn btn-primary">
    </form>
    <form method="POST" action="updateUserDetails.php">
        <label for="mobile">Email:</label><br>
        <input type="text" id="email" name="email" placeholder="update Email" pattern="@[a-zA-Z0-9.-]+" required class="form-control"><br>
        <input type="submit" value="Update Email" id="updateEmailButton" name="updateEmailButton" class="btn btn-primary">
    </form>
    <form method="POST" action="updateUserDetails.php">
        <label for="mobile">Mobile:</label><br>
        <input type="text" id="mobile" name="mobile" placeholder="update 10 digit mobile number" pattern="\d{10}" required class="form-control"><br>
        <input type="submit" value="Update Mobile" id="updateMobileButton" name="updateMobileButton" class="btn btn-primary">
    </form>
    <br>
    <form method="POST" action="updateUserDetails.php">
        <label for="address">Address:</label><br>
        <input type="text" id="address" name="address" placeholder="update address" pattern="[A-Za-z0-9\s.,'-]+" required class="form-control"><br>
        <input type="submit" value="Update Address" id="updateAddressButton" name="updateAddressButton" class="btn btn-primary">
    </form>
</div>
</div>
<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <?php if($_SESSION['gender']=='male'){ ?>
        <h2>Welcome Mr.<?php echo $_SESSION['username']; ?></h2>
        <?php }else{ ?>
        <h2>Welcome Ms.<?php echo $_SESSION['username']; ?></h2>
        <?php } ?>
        <a href="viewcart.php" class="btn btn-primary mb-3">View Cart Items</a>
    </div>
    <button id="toggleFilters" class="btn btn-secondary mb-3">Use filters</button>

    <a href="viewitems.php" class="btn btn-secondary mb-3" style="text-decoration: none; color: inherit;">Clear filters</a>
    <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
    <div id="filterForm" style="display: none;">
    <form method="GET" action="viewitems.php" class="mb-4">
        <div class="form-row">
            <div class="col">
                <input type="text" name="query" class="form-control" placeholder="Filter items by name" value="<?php echo isset($_GET['query']) ? htmlspecialchars($_GET['query']) : ''; ?>">
            </div>
            <div class="col">
                <select name="category" class="form-control">
                    <option value="">All Categories</option>
                    <option value="phones" <?php echo (isset($_GET['category']) && $_GET['category'] == 'phones') ? 'selected' : ''; ?>>phones</option>
                    <option value="smartphones" <?php echo (isset($_GET['category']) && $_GET['category'] == 'smartphones') ? 'selected' : ''; ?>>smartphones</option>
                </select>
            </div>
            <div class="col">
                <input type="number" name="price_min" class="form-control" placeholder="Min Price" value="<?php echo isset($_GET['price_min']) ? htmlspecialchars($_GET['price_min']) : ''; ?>">
            </div>
            <div class="col">
                <input type="number" name="price_max" class="form-control" placeholder="Max Price" value="<?php echo isset($_GET['price_max']) ? htmlspecialchars($_GET['price_max']) : ''; ?>">
            </div>
            <div class="col">
                <select name="sort" class="form-control">
                    <option value="">Sort By</option>
                    <option value="price_asc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'price_asc') ? 'selected' : ''; ?>>Price (Low to High)</option>
                    <option value="price_desc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'price_desc') ? 'selected' : ''; ?>>Price (High to Low)</option>
                    <!--<option value="newest" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'newest') ? 'selected' : ''; ?>>Newest First</option>
                    <option value="oldest" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'oldest') ? 'selected' : ''; ?>>Oldest First</option>-->
                </select>
            </div>
            <div class="col">
                <button type="submit" class="btn btn-primary" name="filters">Apply Filters</button>
                <button type="reset" class="btn btn-danger" name="clear_filters">Clear Filters</button>
            </div>
        </div>
    </form>

    </div>
    <div class="row">
        <?php while ($item = mysqli_fetch_assoc($result)) { ?>
            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="<?php echo $item['image']; ?>" class="card-img-top" alt="image not loaded">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $item['name']; ?></h5>
                        <p class="card-text">Quantity: <?php echo $item['quantity']; ?></p>
                        <p class="card-text">Price: Rs.<?php echo $item['price']; ?></p>
                        <a href="itemdetails.php?id=<?php echo $item['id']; ?>" class="btn btn-primary">View Details</a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
</body>
</html>