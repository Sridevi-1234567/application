<?php
error_reporting(0);
session_start();
if (isset($_SESSION['userId'])) {
    session_unset();
    session_destroy();
}
header("Location: home.php");
exit();
?>
