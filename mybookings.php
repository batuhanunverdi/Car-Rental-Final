<?php
session_start();
if(!$_SESSION["id"]){
    header("Location:index.php");
}
$id = $_SESSION["id"];
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
    <title>My Bookings</title>
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark p-md-3">
    <div class="container">
        <a class="navbar-brand" href="index.php">MBU CAR RENTAL </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#    navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <div class="mx-auto"></div>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link text-white" href="cars.php">Cars</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="about.php">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="contact.php">Contact Us</a>
                </li>
                <?php
                if(!$_SESSION["isLoggedIn"]){
                    ?>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#userForm" data-bs-toggle="modal" data-bs-target="#userForm">Login / Sign
                            Up</a>
                    </li>
                    <?php
                }
                else{
                    ?>
                    <li class="nav-item">
                        <a class="nav-link text-white" href=mybookings.php?id=<?php echo $_SESSION['id']?> >My Bookings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="index.php?logout">
                            <?php echo $_SESSION["name"]; ?> Logout </a>
                    </li>
                    <?php
                }
                ?>
            </ul>
        </div>
    </div>
</nav>
<div class="container">
    <div class="row">
        <div class="col-md mt-5 mb-5">

            <table class="table table-image table-striped">
                <thead>
                <tr>
                    <th scope="col">Car</th>
                    <th scope="col">Car Name</th>
                    <th scope="col">Delivered Location</th>
                    <th scope="col">Price</th>
                    <th scope="col">Starting Date</th>
                    <th scope="col">Ending Date</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $sql="SELECT c.IMAGE,c.CAR_NAME,l.LOCATION,cc.TOTAL_PRICE,cc.PICK_UP,cc.RETURN_DATE FROM customer_car cc 
                INNER JOIN car c ON c.ID = cc.CAR_ID INNER JOIN location l ON l.ID = cc.RETURN_LOCATION_ID WHERE cc.CUSTOMER_ID= $id";
                $cars = $connect->query($sql);
                if (!$cars) {
                    die("Invalid Query: " . $connect->error);
                }
                while ($row = $cars->fetch_assoc()) {
                    echo "<tr>
                                  <td class='w-25'> <img class='img-fluid img-thumbnail' src=../images/uploads/".$row['IMAGE']."></td> 
                                  <td>" . $row['CAR_NAME'] . "</td>
                                  <td>" . $row['LOCATION'] . "</td>
                                  <td>" . $row['TOTAL_PRICE'] . "</td>
                                  <td>" . $row['PICK_UP'] . "</td>
                                  <td>" . $row['RETURN_DATE'] . "</td>
                                  </tr>";
                }?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<footer class="bg-dark text-white pt-5 pb-4" style="margin-top: 200px">
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
</html>
