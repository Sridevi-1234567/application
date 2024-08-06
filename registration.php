<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <title>Mobishop Registration</title>
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
        .my-form input[type="radio"] {
            display: none;
        }

        .my-form input[type="radio"] {
            display: inline-block;
            margin: 2px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border: 2px solid #f2f2f2;
            border-radius: 5px;
        }
    </style>
</head>
<body>
</head>
<body>
<header style="background-color:grey;color:white;text-align: center; height: 70px; padding: 20px; " >
    MOBISHOP
</header>
    <div class="container">     
    <form  method="post" class="my-form" action="<?php echo $_SERVER["PHP_SELF"];?>">
          <h2 class="text-center">Registration</h2>
    <div class="form-group">
        <label for="name" class="control-label">Name: </label>
        <input type="text" id="name" name="name" class="form-control" required placeholder="Enter your Name min 4 characters"><br>
    </div>
    <div class="form-group">
        <label for="username" class="control-label">Username: </label>
        <input type="text" id="username" name="username" required class="form-control" placeholder="Enter your Name min 4 characters"><br>
    </div>
    <div class="form-group">
        <label for="password" class="control-label">Password:</label>
        <input type="password" id="password" name="password" required class="form-control" placeholder="Enter Password start with capital and min 8 characters"><br>
    </div>
    <div class="form-group">
        <label for="confirm_password" class="control-label">Confirm Password:</label>
        <input type="password" id="cpassword" name="cpassword" required class="form-control" placeholder="Confirm your password"><br>
    </div>
    <div class="form-group">
        <label for="email" class="control-label">Email:</label>
        <input type="email" id="email" name="email" required class="form-control" placeholder="Enter your Email"><br>
    </div>
    <div class="form-group">
        <label for="dob" class="control-label">Date of Birth:</label>
        <input type="date" id="dob" name="dob" required class="form-control"><br>
    </div>
    <div class="form-group">
        <label for="gender" class="control-label">Gender:</label>
        <input type="radio" name= "gender" value="male" >Male &nbsp;&nbsp;
        <input type="radio" name="gender" value="female" >Female<br>
    </div>
            <div class="form-group">
                <label for="phone" class="control-label">Phone:</label>
                <input type="tel" id="phone" name="phone" required class="form-control" placeholder="Enter your 10 digit Mobile Number excluding 91 country code"><br>
            </div>
    <div class="form-group">
        <label for="address" class="control-label">Address: </label>
        <input type="text" id="address" name="address" required class="form-control" placeholder="Enter your Address"><br>
    </div>
    <div class="form-group">
        <label for="scode" class="control-label">Secret code: </label>
        <input type="password" id="scode" name="scode" class="form-control" required placeholder=" 4 digit code to reset password if you forgot your password"><br>
        
    </div>
    <div class="form-group">
                <input type="submit" name="submit" class="btn btn-success" value="Register">&nbsp;&nbsp;
                <input type="reset" class="btn btn-primary" value="Reset" name="reset"> 
    </div>
            <div>
                Already have an account?<a href="login.php" class="btn btn-danger">Login</a>
            </div>
        </form>
        </div>
        </body>
        </html>
</body>
</html>

<?php
require ("db.php");
//error_reporting(0);
if(isset($_POST['submit'])){
if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //validations
        $invalid = false;
        $name = $_POST['name'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $cpassword = $_POST['cpassword'];
        $mobile= $_POST['phone'];
        $gender= $_POST['gender'];
        $loc = $_POST['address'];
        $scode= trim($_POST['scode']);
        $dob = $_POST['dob'];
        if($name=="" || $username=="" || $email=="" || $password=="" || $cpassword=="" || $mobile=="" || $scode=="" || $gender=="" || $loc=="" ||  $dob== ""){
            echo "<script>alert('All fields are mandatory');</script>";
            $invalid = true;
        }
        else{
            $namepattern = "/^[a-zA-Z ]{4,15}$/";
            if(!$invalid  && (!preg_match($namepattern, $name) || !preg_match($namepattern, $username))) {
                //echo "First name and last name should start with an alphabet, contain only alphabets, and be between 5 to 15 characters long.";
                echo "<script>alert('First name and last name should start with an alphabet, contain only alphabets, and be between 5 to 15 characters long');</script>";
                $invalid = true;
            }
            $usernamePattern = "/^[a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/";
             if(!$invalid  && !preg_match($usernamePattern, $email)) {
               echo "<script>alert('Email is invalid');</script>";
                $invalid = true;
        }   
            }
            $passwordPattern = "/^[A-Z][a-zA-Z0-9@#&]{7,}$/";
            $issize=strlen($password)==strlen($cpassword);
            $issame=strcmp($cpassword, $password)==0;
            if(!$invalid  && (!$issize || !$issame || !preg_match($passwordPattern, $password))) {
                echo "<script>alert('New password and confirm password should be the same, start with an uppercase letter, and not be less than 8 characters.');</script>";
                //echo "New password and confirm password should be the same, start with an uppercase letter, and not be less than 8 characters.";
                $invalid = true;
        }
        $mobilePattern = "/^91\d{10}$/";
          if (!$invalid  && !preg_match($mobilePattern, "91".$mobile)) {
                echo "<script>alert('Mobile number should contain exactly 10 digits');</script>";
                $invalid = true;
                echo "<br>";
                //echo '<button onclick="location.href=\'registration.html\'" type="button">Retry</button>';
                  }
            if (!$invalid && !DateTime::createFromFormat('Y-m-d', $dob)) {
                    echo "<script>alert('Date of birth is invalid');</script>";
                    $invalid = true;
                }
                $dob = DateTime::createFromFormat('Y-m-d', $dob);
            if(!$invalid){
                $now = new DateTime();
                $diff = $now->diff($dob);
                if ($diff->y < 15) {
                    echo "<script>alert('You must be at least 15 years old.');</script>";
                    $invalid = true;
                }
            }
            if (!$invalid && !preg_match('/^[a-zA-Z0-9 .,#-]+$/', $loc)) {
                    echo "<script>alert('Address is invalid');</script>";
                    $invalid = true;
                }
            if (!$invalid && (!preg_match('/^\d{4}$/', $scode))) {
                    echo "<script>alert('Security code is invalid');</script>";
                    $invalid = true;
                }
       

    if($invalid == true){
        echo "Invalid data";
        echo "<br>";
        echo '<button onclick="location.href=\'registration.php\'" type="button" class="btn btn-warning">Retry</button>';
        $invalid = false;
    }
    else{
        $con=getcon();
        if(!$con) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $password = crypt($password,PASSWORD_DEFAULT);
        $code= crypt($scode,PASSWORD_DEFAULT);
        $dob = $dob->format('Y-m-d');
        //$dob = DateTime::createFromFormat('mm/dd/YYYY', $dob)->format('YYYY-mm-dd');
        $sql_check = "SELECT * FROM user_details WHERE Email='$email'";
        $result_check = mysqli_query($con, $sql_check);
        if (mysqli_num_rows($result_check) > 0) {
            echo "<script>alert('Email id already exists')</script>";
            //header("Refresh:0; url=registration.php");
        } else {
        $sql = "INSERT INTO USER_DETAILS (Name, Username, Password, DOB,Email, Gender, Mobile, Address,secretcode )
         VALUES ('$name', '$username', '$password', '$dob', '$email', '$gender', '$mobile', '$loc', '$code')";
        $result = mysqli_query($con,$sql);
        if(!$result) {
            die("". mysqli_error($con));
        
        }
        else{
            echo "<script>alert('Registration Successful');</script>";
           // header("Refresh:0; url=home.php");
        }
        
     mysqli_close($con);
        

            
    }
}
}



}
?>