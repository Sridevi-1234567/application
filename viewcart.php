<?php
//error_reporting(0);
require('db.php');
session_start();
 
if (!isset($_SESSION['userId']) || !isset($_SESSION['usercategory']) || $_SESSION['usercategory'] != 'user') {
    header("Location: home.php");
    exit();
}
 
$user_id = $_SESSION['userId'];
 $conn=getcon();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $itemId= $_POST['item_id'];
    $order_id = $_POST['order_id'];
    $currentquantity= $_POST['quantity'];
    $getitem="select * from items where id='".$itemId."'";
       $items=mysqli_query($conn, $getitem);
       $item=mysqli_fetch_assoc($items);
    if (isset($_POST['increase'])) {
        $isincreased = false;
        $currentquantity=$currentquantity+1;
        if( $item['quantity'] <= 0 ){
            echo "<script>alert('Not enough quantity available')</script>";
            header("Refresh:0; url=viewcart.php");
        }
        else{
        $total_price = $currentquantity * $item['price'];
        $query = "UPDATE cart SET quantity = '$currentquantity', total_price = '$total_price' WHERE id = '$order_id' AND user_id = '$user_id'";
        $result= mysqli_query($conn, $query);

        if($result==true){
            $isincreased = true;
        }
        $isincreased = false;
        $new_quantity = $item['quantity']-1;
        $iquery = "UPDATE items SET quantity='$new_quantity' WHERE id='".$itemId."' AND $new_quantity >= 0";
        $u=mysqli_query($conn, $iquery);
        if($u==true){
            $isincreased = true;
        }
        if($isincreased==true){
            echo "<script>alert('quantity increased by 1')</script>";
            header("Refresh:0; url=viewcart.php");
        }else{
            echo "<script>alert('Error in increasing quantity')</script>";
            header("Refresh:0; url=viewcart.php");
        }
    }

    } elseif (isset($_POST['decrease'])) {
        $isdecreased = false;  
        if($currentquantity==1){
            $new_q = $item['quantity']+1;
            $cquery = "UPDATE items SET quantity='$new_q' WHERE id='".$itemId."'";
             mysqli_query($conn, $cquery);
            $query = "DELETE FROM cart WHERE user_id = '$user_id' AND id = '$order_id' AND item_id = '$itemId'";
             mysqli_query($conn, $query);
             echo "<script>alert('Order cleared successfully');</script>";
             header("Refresh:0; url=viewcart.php");

        }else{
        $currentquantity=$currentquantity- 1;
        $total_price = $currentquantity * $item['price'];
        $query = "UPDATE cart SET quantity = '$currentquantity',total_price = '$total_price' WHERE id = '$order_id' AND user_id = '$user_id' AND '$currentquantity' >0";
        $res=mysqli_query($conn, $query);
        if($res==true){
            $isdecreased = true;
        }
        $isdecreased = false;
        $new_quantity = $item['quantity']+1;
        $iquery = "UPDATE items SET quantity='$new_quantity' WHERE id='".$itemId."'";
        $d=mysqli_query($conn, $iquery);
        if($d==true){
            $isdecreased = true;
        }
        if($isdecreased==true){
            echo "<script>alert('quantity decreased by 1')</script>";
            header("Refresh:0; url=viewcart.php");
        }else{
            echo "<script>alert('Error in decreasing quantity')</script>";
            header("Refresh:0; url=viewcart.php");
        }
    }
 } elseif (isset($_POST['clear_all'])) {
         $orders = "SELECT * FROM cart WHERE user_id = $user_id";
         $o=mysqli_query($conn, $orders);
         while($orders=mysqli_fetch_assoc($o)){
            $item_id = $orders['item_id'];
            $quantity = $orders['quantity'];
            $getitems = "SELECT * FROM items WHERE id = $item_id";
            $items = mysqli_query($conn, $getitems);
            $item = mysqli_fetch_assoc($items);
            $new_quantity = $item['quantity'] + $quantity;
            $query = "UPDATE items SET quantity = $new_quantity WHERE id = $item_id";
            mysqli_query($conn, $query);
         }
        $query = "DELETE FROM cart WHERE user_id = $user_id";
        mysqli_query($conn, $query);
        echo "<script>alert('Items list cleared successfully');</script>";
        header("Refresh:0; url=viewcart.php");
    }
}
$query = "SELECT o.id, o.quantity, o.total_price,o.item_id, i.name, i.price FROM cart o JOIN items i ON o.item_id = i.id WHERE o.user_id = $user_id";
$result = mysqli_query($conn, $query);
$query = "SELECT SUM(total_price) AS grand_total FROM cart WHERE user_id = '$user_id'";
$total = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($total);
$grand_total = $row['grand_total'];
?>
 
<!DOCTYPE html>
<html>
<head>
    <title>View Orders</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</head>
<body>
<header style="background-color:grey;color:white;text-align: center; height: 70px; padding: 20px; " >
    MOBISHOP
    <div style="position: absolute; top: 15px; right: 10px;">
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</header>
<div class="container">
    <?php if(mysqli_num_rows($result) > 0) { ?>
        <h3>Grand Total Price: RS.<?php echo $grand_total; ?></h3>
    <h2>Your Items</h2>

    <form method="POST">
        <button type="submit" name="clear_all" class="btn btn-danger mb-3" value="clearall">Clear All</button>
    </form>
    <table class="table">
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($order = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $order['name']; ?></td>
                    <td><?php echo $order['quantity']; ?></td>
                    <td>Rs.<?php echo $order['price']; ?></td>
                    <td>Rs.<?php echo $order['total_price']; ?></td>
                    <td>
                        <form method="POST" class="d-inline">
                            <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                            <input type="hidden" name="item_id" value="<?php echo $order['item_id']; ?>">
                            <input type="hidden" name="quantity" value="<?php echo $order['quantity']; ?>">
                            <button type="submit" name="increase" class="btn btn-success btn-sm">+</button>
                            <button type="submit" name="decrease" class="btn btn-warning btn-sm">-</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table><?php } else { ?>
        <h2 style="color:red">No Items found,Start shopping now! </h2>
    <?php } ?>
    <a href="viewitems.php" class="btn btn-primary mt-3">Back to Items</a> 
    <a href="checkout.php" class="btn btn-primary mt-3">Checkout</a>
</div>
</body>
</html>