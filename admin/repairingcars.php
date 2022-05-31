<?php
session_start();
if (!$_SESSION["isAdminLoggedIn"]) {
    header("Location:login.php");
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
//$brandQuery = "SELECT `ID`,`BRAND_NAME` FROM brand";
//$colorQuery = "SELECT `ID`,`COLOR` FROM color";
//$typeQuery = "SELECT `ID`,`TYPE_NAME` FROM cartype";
//$gearQuery = "SELECT `ID`,`GEAR_NAME` FROM gear";
//$engineQuery = "SELECT `ID`,`ENGINE_NAME` FROM engine";
//$locationQuery = "SELECT `ID`,`LOCATION` FROM location";
//
//
//
//
//$brandResult = mysqli_query($connect, $brandQuery);
//$colorResult = mysqli_query($connect, $colorQuery);
//$typeResult = mysqli_query($connect, $typeQuery);
//$gearResult = mysqli_query($connect, $gearQuery);
//$engineResult = mysqli_query($connect, $engineQuery);
//$locationResult = mysqli_query($connect, $locationQuery);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="admin.css">
    <title>Admin Car</title>

</head>

<body>
<?php include('siderbar.php'); ?>

<div class="content-container">

    <div class="container-fluid">
        <div class="jumbotron">
            <div class="row">
                <div class="col-md mt-5 mb-5">
                    <table class="table table-image table-striped">
                        <thead>
                        <tr>
                            <th scope="col">Car NAME</th>
                            <th scope="col">Repair Status</th>
                            <th scope="col">Repair it</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $connect = new mysqli($hostname, $username, $password, $databaseName);
                        $sql = "SELECT cc.CAR_ID,c.CAR_NAME,cc.isRepaired FROM servicecars cc INNER JOIN car c ON c.ID = cc.CAR_ID;";
                        $cars = $connect->query($sql);
                        if (!$cars) {
                            die("Invalid Query: " . $connect->error);
                        }
                        while ($row = $cars->fetch_assoc()) {
                            echo '<tr>
                        <td>' . $row['CAR_NAME'] . '</td>
                        <td>' . $row['isRepaired'] . '</td>
                        <td><a class="btn btn-warning" href=/admin/repaircar.php?id=' . $row['CAR_ID'] .">Repair</a></td>
                        </tr>";
                        } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
function showError($err){
    echo "<script type='text/javascript'> alert('$err');
           window.location='cars.php';
          </script>";
}
?>
</body>


</html>
