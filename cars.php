<?php
session_start();
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
$suvCarQuery = "SELECT c.IMAGE,c.CAR_NAME,c.PRICE,ct.TYPE_NAME,e.ENGINE_NAME 
FROM car c INNER JOIN cartype ct ON ct.ID= c.TYPE_ID INNER JOIN engine e ON e.ID = c.ENGINE_ID WHERE TYPE_ID=1 GROUP BY c.CAR_NAME;";
$sedanCarQuery = "SELECT c.IMAGE,c.CAR_NAME,c.PRICE,ct.TYPE_NAME,e.ENGINE_NAME 
FROM car c INNER JOIN cartype ct ON ct.ID= c.TYPE_ID INNER JOIN engine e ON e.ID = c.ENGINE_ID WHERE TYPE_ID=2 GROUP BY c.CAR_NAME;";
$hatchbackQuery = "SELECT c.IMAGE,c.CAR_NAME,c.PRICE,ct.TYPE_NAME,e.ENGINE_NAME 
FROM car c INNER JOIN cartype ct ON ct.ID= c.TYPE_ID INNER JOIN engine e ON e.ID = c.ENGINE_ID WHERE TYPE_ID=3 GROUP BY c.CAR_NAME;";

$suvCarResut = $connect->query($suvCarQuery);
$sedanCarResult  =  $connect->query($sedanCarQuery);
$hatchbackResult =  $connect->query($hatchbackQuery);
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
<!--Navbar-->
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
<div class="modal fade login-register-form pe-4" id="userForm" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header" style="height: 50px;">
                <ul class="nav nav-tabs mx-auto">
                    <li class="active"><button class="btn btn-outline-warning active" data-toggle="tab"
                                               href="#login-form">
                            Login <span class="glyphicon glyphicon-user"></span></button></li>
                    &nbsp;
                    <li><button class="btn btn-outline-warning" data-toggle="tab" href="#registration-form">
                            Register <span class="glyphicon glyphicon-pencil"></span></a></button></li>
                </ul>
            </div>
            <div class="modal-body">
                <div class="tab-content">
                    <div id="login-form" class="tab-pane fade in show active">
                        <form method="post" action="index.php">
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" class="form-control" id="email" placeholder="Enter email"
                                       name="email">
                            </div>
                            <div class="form-group">
                                <label for="pwd">Password:</label>
                                <input type="password" class="form-control" id="pwd" placeholder="Enter password"
                                       name="pwd">
                            </div>
                            <div class="checkbox">
                                <label><input type="checkbox" name="remember"> Remember me</label>
                            </div>
                            <button type="submit" class="btn btn-warning">Login</button>
                        </form>
                    </div>
                    <div id="registration-form" class="tab-pane fade">
                        <form action="index.php" method="post">
                            <div class="form-group">
                                <label for="name">Your Name:</label>
                                <input type="text" class="form-control" id="name" placeholder="Enter your name"
                                       name="name">
                            </div>
                            <div class="form-group">
                                <label for="newemail">Email:</label>
                                <input type="email" class="form-control" id="newemail" placeholder="Enter email"
                                       name="newemail">
                            </div>
                            <div class="form-group">
                                <label for="newemail">TC:</label>
                                <input type="text" class="form-control" id="tcno" placeholder="Enter TC Kimlik No"
                                       name="newtc">
                            </div>
                            <div class="form-group">
                                <label for="newpwd">Password:</label>
                                <input type="password" class="form-control" id="newpwd" placeholder="Password"
                                       name="newpwd">
                            </div>
                            <div class="form-group">
                                <label for="dob">Date of Birth:</label>
                                <div class="input-group date" id="dobDatePicker">
                                    <input type="text" class="form-control" id="dob" placeholder="Date of Birth">
                                    <span class="input-group-append">
                                            <span class="input-group-text bg-white d-block">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                        </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="license">Drive License</label>
                                <div class="input-group text" id="license">
                                    <input type="text" class="form-control" placeholder="Drive License" id="licensetext">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-warning mx-auto">Register</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-md-12 text-center pt-4 pb-4">
    <button class="btn btn-warning me-4" style="width: 140px;" type="button" data-toggle="collapse" data-target="suv"
            aria-expanded="false" aria-controls="suv">
        SUV
    </button>

    <button class="btn btn-warning me-4" style="width: 140px;" type="button" data-toggle="collapse" data-target="sedan"
            aria-expanded="false" aria-controls="sedan">
        SEDAN
    </button>
    <button class="btn btn-warning me-4" style="width: 140px;" type="button"  data-toggle="collapse" data-target="hatchback"
            aria-expanded="false" aria-controls="hatchback">
        HATCHBACK
    </button>
</div>
<div class="container">
    <div class="collapse cars justify-content-md-center align-items-center" id="sedan">
        <div class="row" >
            <?php
            if (!$sedanCarResult) {
                die("Invalid Query: " . $connect->error);
            }
            while ($row = $sedanCarResult->fetch_assoc()) {
                echo" <div class='col-md-4 mb-4'>
                <div class='card rounded'>
                    <div class='card-image'>
                        <img class='card-img-top v-100' src=../images/uploads/".$row['IMAGE']." />
                        <p class='card-title text-center'>". $row['CAR_NAME']."</p>
                        <ul class='list-group'>
                            <li class='list-group-item'>".$row['TYPE_NAME']."</li>
                            <li class='list-group-item'>".$row['ENGINE_NAME']."</li>
                            <li class='list-group-item'>". $row["PRICE"]." TL </li>
                        </ul>
                    </div>
                </div>
            </div>";} ?>
        </div>
    </div>
    <div class="collapse-show cars  justify-content-md-center align-items-center" id="suv">
        <div class="row" >
            <?php
            if (!$suvCarResut) {
    die("Invalid Query: " . $connect->error);
}
while ($row = $suvCarResut->fetch_assoc()) {
    echo" <div class='col-md-4 mb-4'>
                <div class='card rounded'>
                    <div class='card-image'>
                        <img class='card-img-top v-100' src=../images/uploads/".$row['IMAGE']." />
                        <p class='card-title text-center'>". $row['CAR_NAME']."</p>
                        <ul class='list-group'>
                            <li class='list-group-item'>".$row['TYPE_NAME']."</li>
                            <li class='list-group-item'>".$row['ENGINE_NAME']."</li>
                            <li class='list-group-item'>". $row["PRICE"]." TL </li>
                        </ul>
                    </div>
                </div>
            </div>";} ?>
        </div>
    </div>
    <div class="collapse cars  justify-content-md-center align-items-center" id="hatchback">
        <div class="row" >
            <?php
            if (!$hatchbackResult) {
                die("Invalid Query: " . $connect->error);
            }
            while ($row = $hatchbackResult->fetch_assoc()) {
                echo" <div class='col-md-4 mb-4'>
                <div class='card rounded'>
                    <div class='card-image'>
                        <img class='card-img-top v-100' src=../images/uploads/".$row['IMAGE']." />
                        <p class='card-title text-center'>". $row['CAR_NAME']."</p>
                        <ul class='list-group'>
                            <li class='list-group-item'>".$row['TYPE_NAME']."</li>
                            <li class='list-group-item'>".$row['ENGINE_NAME']."</li>
                            <li class='list-group-item'>". $row["PRICE"]." TL </li>
                        </ul>
                    </div>
                </div>
            </div>";} ?>
        </div>    </div>
    <div class="col-md-12 text-center pt-4 pb-4">
        <a class="btn btn-warning text-center mb-4" href="index.php">RENT NOW </a>
    </div>

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
<script>
    $('.btn').click(function () {
        var target = $(this).data('target');
        $(".cars").show();
        $(`.cars:not(#${target})`).hide();
    })
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>

</html>

