<?php
if (!$_SESSION["isAdminLoggedIn"]) {
    header("Location:login.php");
}
if (!$_GET['id']) {
    header("Location:cars.php");
}
$hostname = "localhost";
$username = "root";
$password = "Sanane5885.";
$databaseName = "carrental";
$connect = new mysqli($hostname, $username, $password, $databaseName);
$err = null;
if ($connect->connect_error) {
    $connect->close();
    die("Connection failed: " . $connect->connect_error);
}
$id = $_GET['id'];

$sql = "DELETE FROM servicecars WHERE CAR_ID='" . $id . "'";
mysqli_query($connect, $sql);
mysqli_close($connect);
header("Location: repairingcars.php");
?>
