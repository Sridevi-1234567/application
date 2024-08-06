<?php
error_reporting(0);
require('db.php');
$conn=getcon();
session_start();
 
if (!isset($_SESSION['userId']) || !isset($_SESSION['usercategory']) || $_SESSION['usercategory'] != 'user') {
    header("Location: home.php");
    exit();
}
 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $item_id = $_POST['item_id'];
    $user_id = $_SESSION['userId'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];
    
    $review_query = "INSERT INTO reviews (item_id, user_id, rating, comment) VALUES ('$item_id', '$user_id', '$rating', '$comment')";
    mysqli_query($conn, $review_query);
    header("Location: itemdetails.php?id=$item_id");
    exit();
}
?>