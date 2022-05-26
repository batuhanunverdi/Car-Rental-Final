<?php
session_start();
if (!$_SESSION["isLoggedIn"]) {
    header("Location:index.php");
}
$userId = $_SESSION['id'];
if ($_SESSION["id"] != $_GET["id"]) {
    header("Location:myprofile.php?id=$userId");
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
$sql = "UPDATE customer SET IS_ACTIVE=0 WHERE ID=?";
$stmt = $connect->prepare($sql);
$stmt->bind_param("i",$userId);
$stmt->execute();
$connect->close();
unset($_SESSION["isLoggedIn"]);
unset($_SESSION["id"]);
unset($_SESSION["isLoggedIn"]);header("Location:index.php");
?>

