<?php
session_start();
if(!$_SESSION["isAdminLoggedIn"]){
    header("Location:login.php");
}
if(!$_GET['customer'] && !$_GET['car']){
    header("Location:bookings.php");

}
$hostname = "localhost";
$username = "root";
$password = "Sanane5885.";
$databaseName = "carrental";
$connect = new mysqli($hostname, $username, $password, $databaseName);
if ($connect->connect_error) {
    $connect->close();
    die("Connection failed: " . $connect->connect_error);
}
$customer = $_GET['customer'];
$car = $_GET['car'];

$sql = "DELETE FROM customer_car WHERE CUSTOMER_ID='" . $customer . "'AND CAR_ID='".$car."'";
mysqli_query($connect, $sql);
mysqli_close($connect);
header("Location: bookings.php");
?>

