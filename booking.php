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
$locationQuery = "SELECT * FROM Location";
$typeQuery = "SELECT * FROM cartype";
$locationResult = mysqli_query($connect,$locationQuery);
$typeResult = mysqli_query($connect,$typeQuery);

function search(){

    if($_SESSION["isLoggedIn"]){
        if(!empty($_POST["city"]) && !empty($_POST["pickupDate"]) && !empty($_POST["deliveryDate"]) && !empty($_POST["carType"])){
            $day = date_diff(date_create($_POST["pickupDate"]),date_create($_POST["deliveryDate"]));
            $today =date_create(date("d-m-Y"));
            $currentAndPickupDate = date_diff(date_create($_POST["pickupDate"]),$today)->format("%r%a");
            $currentAndDeliveryDate = date_diff(date_create($_POST["deliveryDate"]),$today)->format("%r%a");
            if($currentAndDeliveryDate>0 || $currentAndPickupDate >0){
                echo "<script type='text/javascript'>alert('The pickup date or delivery date cannot be earlier than today.');</script>";
                return;
            }

            $day = $day->format("%r%a");
            if($day<1){
                echo "<script type='text/javascript'>alert('The pickup date cannot be later than the delivery date. .');</script>";
            }
            else {
                $_SESSION["city"] = $_POST["city"];
                $_SESSION["pickupDate"] = $_POST["pickupDate"];
                $_SESSION["deliveryDate"] = $_POST["deliveryDate"];
                $_SESSION["carType"] = $_POST["carType"];
                header("Location:booking.php");
            }
        }
        else{
            echo "<script type='text/javascript'>alert('You have to fill the blanks.');</script>";
        }
    }
    else{
        echo "<script type='text/javascript'>alert('You have to login');</script>";
    }
}


if(isset($_POST["searchSubmit"]))
{
    search();
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
                        <a class="nav-link text-white" href=myprofile.php?id=<?php echo $_SESSION['id']?> >My Profile</a>
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
<div class="container" style="margin-bottom: 500px">
    <div class="row">
        <div class="col-md mt-5 mb-5">
            <form class="row-g 3" method="post" action="booking.php">
                <div class="row form-group">
                    <div class="col-lg pb-2 pt-2">
                        <select class="form-control" name="city">
                            <option value="" selected> Location</option>
                            <?php while ($row1 = mysqli_fetch_array($locationResult)): ?>
                                <option value="<?php echo $row1['ID']; ?>"><?php echo $row1['LOCATION']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-lg pb-2 pt-2">
                        <div class="input-group date">
                            <label for="pickUpDate"></label><input placeholder="Pick Up Date" class="form-control"
                                                                   type="text" onfocus="(this.type='date')"
                                                                   id="pickupDate" name="pickupDate" min="07-05-2022">
                        </div>
                    </div>
                    <div class="col-lg pb-2 pt-2">
                        <div class="input-group date">
                            <label for="deliveryDate"></label><input placeholder="Delivery Date" class="form-control"
                                                                     type="text" onfocus="(this.type='date')"
                                                                     id="deliveryDate" name="deliveryDate">
                        </div>
                    </div>
                    <div class="col-lg pb-2 pt-2">
                        <select class="form-control" name="carType">
                            <option value="" selected> Car Type</option>
                            <?php while ($row1 = mysqli_fetch_array($typeResult)): ?>
                                <option value="<?php echo $row1['ID']; ?>"><?php echo $row1['TYPE_NAME']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-lg pb-2 pt-2">
                        <input type="hidden" name="searchSubmit" value="1">
                        <input type="submit" id="searchSubmit" value="Search" class="btn btn-warning"></input>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="col-md mt-5 mb-5">
        <table class="table table-image">
            <thead>
            <tr>
                <th scope="col"">Car</th>
                <th scope="col">Car Name</th>
                <th scope="col">Location</th>
                <th scope="col">Price</th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $city = $_SESSION["city"];
            $pickUpDate = $_SESSION["pickupDate"];
            $deliveryDate = $_SESSION["deliveryDate"];
            $carType = $_SESSION["carType"];
            $sql = 'SELECT c.ID,c.TYPE_ID,c.GEAR_ID,c.ENGINE_ID,c.CAR_NAME,c.COLOR_ID,c.CAR_YEAR,
                    c.MILEAGE,c.PRICE,l.LOCATION,c.PLATE,c.IMAGE FROM car c INNER JOIN location l 
                    ON l.ID = c.LOCATION_ID WHERE c.ID NOT IN(SELECT cc.CAR_ID FROM customer_car cc 
                    WHERE NOT (cc.RETURN_DATE < "' . $pickUpDate . '" OR cc.PICK_UP > "'.$deliveryDate.'")) AND c.LOCATION_ID ='.$city.' AND c.TYPE_ID='.$carType;
            $cars = $connect->query($sql);
            if (!$cars) {
                die("Invalid Query: " . $connect->error);
            }
            if(mysqli_num_rows($cars)==0)
            {
                echo "<script type='text/javascript'>alert('According to the information you entered, there are no cars available.');
                    window.location.href='index.php';</script>";
            }
            while ($row = $cars->fetch_assoc()) {
                echo "<tr>
                                  <td class='w-25'> <img class='img-fluid img-thumbnail' src=../images/uploads/".$row['IMAGE']."></td> 
                                  <td>" . $row['CAR_NAME'] . "</td>
                                  <td>" . $row['LOCATION'] . "</td>
                                  <td>" . $row['PRICE'] . " TL </td>
                                  <td><a class='btn btn-warning' href=\"payment.php?id=".$row['ID']."\">Buy</a></td>
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
</html>

