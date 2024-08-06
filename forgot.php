<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
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
<div class="container">
<form method="post" class="my-form" action="">
       <h2 class="text-center">Forgot Password</h2>
      <div class="form-group"> 
       <label for="email" class="control-label">Email: </label> 
        <input type="email" name="email" required class="form-control" placeholder="Enter your Email"><br>   </div>
      <div class="form-group">
        <label for="secretcode" class="control-label">Secret Code:</label> 
        <input type="password" name="secretcode" required class="form-control" placeholder="Enter your 4 digit secret code"><br></div>  
      <div class="form-group"> 
        <label for="newpassword" class="control-label">New Password:</label> 
        <input type="password" name="newpassword" required class="form-control" placeholder="Enter new Password you want to set"><br>
    </div> <div class="form-group">
    <input type="submit" name="submit" value="UpdatePassword" class="btn btn-success">&nbsp;&nbsp;
    <!-- <input type="reset" name="reset" value="Reset" class="btn btn-primary">&nbsp;&nbsp; -->
    <a href="login.php" class="btn btn-danger">Login</a>&nbsp;&nbsp;
    <a href="registration.php" class="btn btn-warning">Register</a>
       </div>

    </form>
</div>
    
</body>
</html>
<?php
//error_reporting(0);
require 'db.php';
if(isset($_POST['submit'])){
     $con=getcon();
     if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
      }
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = trim($_POST["email"]);
        $secretcode = trim($_POST["secretcode"]);
        $scode=crypt($secretcode, PASSWORD_DEFAULT);
        $sc = substr($scode,0,-3);
        $newpassword = trim($_POST["newpassword"]);
        $newpassword=crypt($newpassword, PASSWORD_DEFAULT);
      
        //$sql = "SELECT * FROM user_details WHERE Email='$email' AND secretcode='$secretcode'";
        //$result = $con->query($sql);
        //$result = mysqli_query($con, $sql);
        //$val = mysqli_fetch_assoc($result);
        
        
        $sql = "SELECT * FROM user_details WHERE Email='$email' AND secretcode='$sc'";
        $result = mysqli_query($con, $sql);
        
        if (mysqli_num_rows($result) > 0) {
          $sql = "UPDATE USER_DETAILS SET Password='$newpassword' WHERE Email='$email'";
          if (mysqli_query($con, $sql)) {
            echo "Password updated successfully";
            echo "<script>alert('Password updated successfully')</script>";
            header("Refresh:0; url=login.php");
          } else {
            echo "<script>alert('Error updating password')</script>";
            header("Refresh:0; url=forgot.php");
          }
        } else {
            echo "<script>alert('Invalid email or secret code')</script>";
            header("Refresh:0; url=forgot.php");
        }


}
}

?>