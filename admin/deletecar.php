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
$sql = "SELECT `IMAGE` FROM car WHERE ID='" . $id . "'";
$result = $connect->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        unlink('../images/uploads/' . $row['IMAGE']);
    }
}
$sql = "DELETE FROM car WHERE ID='" . $id . "'";
mysqli_query($connect, $sql);
mysqli_close($connect);
header("Location: cars.php");
?>
