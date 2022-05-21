<?php
session_start();
if(!$_SESSION["isLoggedIn"]){
    header("Location:index.php");
}
if(!$_GET['customer'] && !$_GET['car'] && !$_GET['pickUp'] && !$_GET['returnDate']){
    header("Location:mybookings.php");

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
$pickUp = $_GET["pickUp"];
$returnDate = $_GET['returnDate'];
$sql = "UPDATE customer_car SET isActive = 1 WHERE CUSTOMER_ID='" . $customer . "'AND CAR_ID='".$car.
    "'AND PICK_UP='".$pickUp."'";
mysqli_query($connect, $sql);
mysqli_close($connect);
header("Location: index.php");
?>

