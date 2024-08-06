<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        body{
            background-color: lightgrey;
        }
        .my-form {
            margin: 0 auto;
            width: 50%; 
            padding: 20px;
            border: 2px solid darkgrey;
            border-radius: 10px;
            margin-top: 50px;
            background-color:darkgray;

        }
        .my-form .form-control {
            background-color: #f2f2f2;
            border: 1px solid black;
            border-radius: 5px;
            
        }
        .my-form .form-group {
            margin-bottom: 10px;
        }
        </style>
</head>
<body>
<header style="background-color:grey;color:white;text-align: center; height: 70px; padding: 20px; " >
    MOBISHOP
</header>
    <div class="container">
    <form  method="post" class="my-form">
        <h2 class="text-center">Login</h2>
    <div class="form-group">
        <label for="email" class="control-label">Email:</label>
        <input type="email" id="email" name="email" required class="form-control" placeholder="Enter your Email"><br>
    </div>
    <div class="form-group">
        <label for="password" class="control-label">Password:</label>
        <input type="password" id="password" name="password" required class="form-control" placeholder="Enter your Password"><br>
    </div>
    <div class="form-group">
        <input type="submit" class="btn btn-success" value="Login" name="submit">&nbsp;&nbsp;
         <input type="reset" class="btn btn-primary" value="Reset" name="fp">&nbsp;&nbsp; 
        <a href="forgot.php" class="btn btn-danger">Forgot Password</a>&nbsp;&nbsp;
        <a href="registration.php" class="btn btn-warning">Register</a>&nbsp;&nbsp;
    
    
    </div>
    </form>
    </div>
    
</body>
</html>

<?php 
error_reporting(0);
session_start();
require 'db.php';
if(isset($_POST['submit'])){
    $email=trim($_POST['email']);
    $password=trim($_POST['password']);
    $password=crypt($password,PASSWORD_DEFAULT);
    $con=getcon();
    $query="select * from user_details where Email='$email' and Password='$password'";
    $result=mysqli_query($con,$query);
    $row=mysqli_fetch_assoc($result);
    if($row){
        echo "<script>alert('Login Successful')</script>";
        $_SESSION['userId']=$row['User_Id'];
        $_SESSION['username']=$row['Username'];
        $_SESSION['usercategory']=$row['category'];
        $_SESSION['email']=$row['Email'];
        $_SESSION['gender']=$row['Gender'];
        $_SESSION['mobile']=$row['Mobile'];
        $_SESSION['address']=$row['Address'];
        if($row['category']== "admin"){
            header("Refresh:0; url=admindashboard.php");
        }
        else{
            header("Refresh:0; url=viewitems.php");
        }
        
    }
    else{
        echo "<script>alert('Login Failed')</script>";
        header("Refresh:0; url=home.php");
    }

}
















?>