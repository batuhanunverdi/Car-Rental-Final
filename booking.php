<?php
session_start();
if(!$_SESSION["isLoggedIn"]){
    header("Location:index.php");
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
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include "navigation.php";?>
<div class="container" style="margin-bottom: 500px">
    <div class="row">
        <div class="col-md mt-5 mb-5">
            <form class="row-g ms-5 ps-5" method="post" action="booking.php">
                <div class="row form-group">
                    <div class="col-lg-3 pb-2 pt-2">
                        <input type="text" class="form-control " placeholder="City">
                    </div>
                    <div class="col-lg-3 pb-2 pt-2">
                        <div class="input-group date">
                            <label for="pickUpDate"></label><input placeholder="Pick Up Date" class="form-control"
                                                                   type="text" onfocus="(this.type='date')"
                                                                   id="pickUpDate">
                        </div>
                    </div>
                    <div class="col-lg-3 pb-2 pt-2">
                        <div class="input-group date">
                            <label for="deliveryDate"></label><input placeholder="Delivery Date" class="form-control"
                                                                     type="text" onfocus="(this.type='date')"
                                                                     id="deliveryDate">
                        </div>
                    </div>
                    <div class="col-lg-3 pb-2 pt-2">
                        <button type="submit" class="btn btn-warning">Search</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="col-md mt-5 mb-5">
        <table class="table table-image">
            <thead>
            <tr>
                <th scope="col">Car</th>
                <th scope="col">Car Name</th>
                <th scope="col">Location</th>
                <th scope="col">Price</th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $sql = 'SELECT c.`ID`,c.`TYPE_ID`,c.`GEAR_ID`,c.`ENGINE_ID`,c.`CAR_NAME`,c.`COLOR_ID`,c.`CAR_YEAR`,
            c.`MILEAGE`,c.`PRICE`,l.`LOCATION`,c.`PLATE`,c.`IMAGE` FROM car c INNER JOIN location l 
            ON l.ID = c.LOCATION_ID WHERE NOT EXISTS(SELECT * FROM customer_car cc 
            WHERE c.ID = cc.CAR_ID AND '.$_SESSION['pickupDate'].' BETWEEN cc.PICK_UP AND cc.RETURN_DATE AND '.$_SESSION['deliveryDate'].' BETWEEN cc.PICK_UP AND cc.RETURN_DATE);';
            $cars = $connect->query($sql);
            if (!$cars) {
                die("Invalid Query: " . $connect->error);
            }
            while ($row = $cars->fetch_assoc()) {
                echo "<tr>
                                  <td class='w-25'> <img class='img-fluid img-thumbnail' src=../images/uploads/".$row['IMAGE']."></td> 
                                  <td>" . $row['CAR_NAME'] . "</td>
                                  <td>" . $row['LOCATION'] . "</td>
                                  <td>" . $row['PRICE'] . "</td>
                                  </tr>";
            }?>
            </tbody>
        </table>
    </div>
</div>

<footer class="bg-dark text-white pt-5 pb-4">
    <div class="container text-center text-md-left">
        <div class="row text-center text-md-left">

            <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mt-3">
                <h5 class="text-uppercase mb-4 font-weight-bold text-warning">
                    Contact Us
                </h5>
                <p> 0 123 456 78 90</p>
                <p>Antalya, Turkey</p>
            </div>
            <div class="col-md-3 col-lg-3 col-xl-3 mx-auto mt-3">
                <h5 class="text-uppercase mb-4 font-weight-bold text-warning">
                    MBU Car Rental
                </h5>
                <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Recusandae iste eius sequi soluta ea
                    rem!</p>
            </div>
            <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mt-3">
                <h5 class="text-uppercase mb-4 font-weight-bold text-warning">
                    Social Media
                </h5>
                <a href="#"><i class="bi bi-facebook"></i></a>
                <a href="#"><i class="bi bi-twitter"></i></a>
                <a href="#"><i class="bi bi-github"></i></a>
            </div>
        </div>
    </div>
</footer>

</body>
<script src="script.js"></script>


</html>

