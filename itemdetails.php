<?php
//error_reporting(0);
require('db.php');
session_start();
 
if (!isset($_SESSION['userId']) || !isset($_SESSION['usercategory']) || $_SESSION['usercategory'] != 'user') {
    header("Location: home.php");
    exit();
}
$conn=getcon();
 
$id = $_GET['id'];
$user_id = $_SESSION['userId'];
$query = "SELECT * FROM items WHERE id='$id'";
$result = mysqli_query($conn, $query);
$item = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) == 0) {
    echo "<script>alert('Item not found')</script>";
    header("Refresh:0; url=viewitems.php");
    exit();
}
 
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['buy'])) {
    $quantity = $_POST['quantity'];
 
    if ($quantity <= $item['quantity']) {
        $new_quantity = $item['quantity'] - $quantity;
        $query = "UPDATE items SET quantity=$new_quantity WHERE id='$id'";
        mysqli_query($conn, $query);
        $total_price = $quantity * $item['price'];
        $query = "INSERT INTO cart (user_id, item_id, quantity, total_price) VALUES ($user_id, $id, $quantity, $total_price)";
        mysqli_query($conn, $query);
        echo "<script>alert('Item added to cart')</script>";
        header("Refresh:0; url=viewitems.php");
        exit();
    } else {
        $error = "Not enough quantity available, total quantity available";
        $error .= ", total available  is " . $item['quantity'];
    }
}
$similar_items_query = "SELECT * FROM items WHERE id !='$id' AND category = '".$item['category']."' LIMIT 3";
$similar_items_result = mysqli_query($conn, $similar_items_query);
if (mysqli_num_rows($similar_items_result) == 0) {
    $serror = "No similar items found";
}
$orderBy = 'r.created_at DESC';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['filter'])){
$filter = $_POST['filter'] ?? 'most_recent';

switch ($filter) {
    case 'high_rating':
        $orderBy = 'r.rating DESC';
        break;
    case 'low_rating':
        $orderBy = 'r.rating ASC';
        break;
    case 'most_recent':
        $orderBy = 'r.created_at DESC';
        break;
    case 'most_oldest':
        $orderBy = 'r.created_at ASC';
        break;
    default:
        $orderBy = 'r.created_at DESC';
        break;
}
}
$reviews_query = "SELECT r.*, u.Username FROM reviews r JOIN user_details u ON r.user_id = u.User_Id WHERE r.item_id = '$id' ORDER BY $orderBy";
$reviews_result = mysqli_query($conn, $reviews_query);
if (mysqli_num_rows($reviews_result) == 0) {
    $rerror = "No reviews found,";
    $rerror.="Be the first one to add review";
}
$average_rating_query = "SELECT AVG(rating) as average_rating FROM reviews WHERE item_id = '$id'";
$average_rating_result = mysqli_query($conn, $average_rating_query);
$average_rating_row = mysqli_fetch_assoc($average_rating_result);
$average_rating = round($average_rating_row['average_rating'], 1);
?>
 
<!DOCTYPE html>
<html>
<head>
    <title>Item Details</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<style>
    .filter-form {
        margin: 20px 0;
        display: flex;
        justify-content: left;
        align-items: left;
    }

    .filter-form select {
        margin-right: 10px;
        padding: 5px;
        border-radius: 5px;
        border: 1px solid #ddd;
    }

    .filter-form input[type="submit"] {
        padding: 5px 10px;
        border: none;
        border-radius: 5px;
        background-color: #007bff;
        color: white;
        cursor: pointer;
    }
    .filter-form input[type="submit"]:hover {
        background-color: #0056b3;
    }
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
    .quantity-input {
        max-width: 100px !important;
    }
    .review {
        margin: 20px 0;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .review:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        transform: translateY(-10px);
    }
    body{
        margin:auto;
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
    <h2>Item Details</h2>
    <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
    <div class="card">
        <img src="<?php echo $item['image']; ?>" class="card-img-top" alt="Image not loaded">
        <div class="card-body">
            <h5 class="card-title"><?php echo $item['name']; ?></h5>
            <p class="card-text">Vendor: <?php echo $item['vendor']; ?></p>
            <p class="card-text">Category: <?php echo $item['category']; ?></p>
            <p class="card-text">Description: <?php echo $item['description']; ?></p>
            <p class="card-text">Quantity: <?php echo $item['quantity']; ?></p>
            <p class="card-text">Price: Rs.<?php echo $item['price']; ?></p>
            <p class="card-text">Average Rating: <?php echo $average_rating; ?> / 5 &#11088;</p>
            <form method="POST">
                <div class="form-group">
                    <label for="quantity">Quantity:</label>
                    <div style="width: 100px;" >
                    <input type="number" class="form-control" name="quantity" min="1" max="<?php echo $item['quantity']; ?>" required placeholder="max <?php echo $item['quantity']; ?> ">
                    </div>
                </div>
                <?php if($item['quantity'] > 0){ ?>
                <button type="submit" name="buy" class="btn btn-success" value="Buy">Add to Cart</button>
                <?php } else { ?>
                <button type="button" class="btn btn-danger" disabled>Out of Stock</button>
                <?php } ?>
            </form>
        </div>
    </div>
    <a href="viewitems.php" class="btn btn-primary mt-3">Back to Items</a>
</div>
<?php
$query = "SELECT * FROM cart WHERE user_id = '$user_id' AND item_id = '$id'";
$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) > 0) {
    $check_query = "SELECT * FROM reviews WHERE user_id = '$user_id' AND item_id = '$id'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        echo "<p>You have already submitted a review for this item.<p>";
    } else {
?>
<h3>Submit a Review</h3>
   <div>
    <form method="POST" action="submitreview.php">
        <div class="form-group">
            <label for="rating">Rating:</label>
            <select class="form-control" name="rating" required>
                <option value="">Select Rating</option>
                <option value="1">1 Star</option>
                <option value="2">2 Stars</option>
                <option value="3">3 Stars</option>
                <option value="4">4 Stars</option>
                <option value="5">5 Stars</option>
            </select>
        </div>
        <div class="form-group">
            <label for="comment">Comment:</label>
            <textarea class="form-control" name="comment" rows="3"></textarea>
        </div>
        <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
        <button type="submit" class="btn btn-primary">Submit Review</button>
    </form>
    </div>
    <?php }} else {
echo "<p>You can't submit a review because you haven't purchased this item yet.</p>";
}
?>
    <h3>Reviews</h3>
    <form method="POST" class="filter-form">
    <select name="filter">
        <option value="high_rating">High Rating</option>
        <option value="low_rating">Low Rating</option>
        <!--<option value="most_recent">Most Recent</option>
        //<option value="most_oldest">Most Oldest</option>-->
    </select>
    <input type="submit" value="Filter" name="filter">
</form>
    <?php if (isset($rerror)) { echo "<div class='alert alert-danger'>$rerror</div>"; } ?>
    
    <div class="list-group">
        <?php while ($review = mysqli_fetch_assoc($reviews_result)) { ?>
            <div class="list-group-item review">
                <h5><?php echo $review['Username']; ?> <small class="text-muted"><?php echo $review['created_at']; ?></small></h5>
                <p>Rating: <?php echo $review['rating']; ?> / 5</p>
                <p><?php echo $review['comment']; ?></p>
            </div>
        <?php } ?>
    </div>

<h3>Similar Items</h3>
   <?php if (isset($serror)) { echo "<div class='alert alert-danger'>$serror</div>"; } ?>
    <div class="row">
        <?php while ($similar_item = mysqli_fetch_assoc($similar_items_result)) { ?>
            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="<?php echo $similar_item['image']; ?>" class="card-img-top" alt="<?php echo $similar_item['name']; ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $similar_item['name']; ?></h5>
                        <p class="card-text">Quantity: <?php echo $similar_item['quantity']; ?></p>
                        <p class="card-text">Price: Rs.<?php echo $similar_item['price']; ?></p>
                        <a href="itemdetails.php?id=<?php echo $similar_item['id']; ?>" class="btn btn-primary">View Details</a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</body>
</html>