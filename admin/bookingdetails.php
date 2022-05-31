<?php
session_start();
if (!$_SESSION["isAdminLoggedIn"]) {
    header("Location:login.php");
}
unset($_SESSION["selectLocation"]);
$bookingId = $_GET["id"];
$isBooking = "SELECT * FROM customer_car where ID =$bookingId";


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

$isBookingResult = mysqli_query($connect,$isBooking);
$count = mysqli_num_rows($isBookingResult);

if($count==0){
    $err="Booking Not Found";
    showError($err);

}


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
                            <th scope="col">Customer Name</th>
                            <th scope="col">Pick Up Location</th>
                            <th scope="col">Car NAME</th>
                            <th scope="col">Pick-up Date</th>
                            <th scope="col">Drop Date</th>
                            <th scope="col">Price </th>
                            <th scope="col">Leased On</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $connect = new mysqli($hostname, $username, $password, $databaseName);
                        $sql = "SELECT cc.CUSTOMER_ID,cc.CAR_ID,cu.CUSTOMER_NAME,l.location,c.CAR_NAME,
                                cc.PICK_UP,cc.RETURN_DATE,cc.TOTAL_PRICE,cc.created_date
                                FROM customer_car cc INNER JOIN customer cu ON cu.ID= cc.CUSTOMER_ID 
                                INNER JOIN car c ON c.ID =cc.CAR_ID INNER JOIN location l ON l.ID = c.LOCATION_ID WHERE cc.ID=$bookingId;";
                        $cars = $connect->query($sql);
                        if (!$cars) {
                            die("Invalid Query: " . $connect->error);
                        }
                        while ($row = $cars->fetch_assoc()) {
                            echo '<tr>
                        <td>' . $row['CUSTOMER_NAME'] . '</td>
                        <td>' . $row['location'] . '</td>
                        <td>' . $row['CAR_NAME'] . '</td>
                        <td>' . $row['PICK_UP'] . '</td>
                        <td>' . $row['RETURN_DATE'] . '</td>
                        <td>' . $row['TOTAL_PRICE'] . ' TL </td>
                        <td>' . $row['created_date'] . '</td>
                        </tr>';
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
           window.location='bookings.php';
          </script>";
}
?>

</body>

</html>
