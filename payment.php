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
$id = $_SESSION["id"];
$CustomerQuery = "SELECT * FROM customer WHERE ID =$id";
$carId = $_GET["id"];
$CarQuery = "SELECT c.CAR_NAME,l.LOCATION,c.PRICE FROM car c INNER JOIN location l ON l.ID = c.LOCATION_ID WHERE c.ID =$carId";
$locationQuery = "SELECT `ID`,`LOCATION` FROM location";
$locationResult = mysqli_query($connect, $locationQuery);
$carResult = mysqli_query($connect,$CarQuery);
$customerResult = mysqli_query($connect,$CustomerQuery);
$pickupDate = $_SESSION["pickupDate"];
$deliveryDate = $_SESSION["deliveryDate"];
if (($_SERVER["REQUEST_METHOD"] ?? 'POST') == "POST") {
    function rent(){
        global $err,$dropAddress,$cardNumber,$CVV,$connect,$id,$carId,$pickupDate,$deliveryDate;
        if(empty($_POST["delivered"])){
            $err = "You have to choose a delivery location";
            return;
        }
        else{
            $dropAddress = intval($_POST["delivered"]);
        }
        if(empty($_POST["cardnumber"])){
            $err = "You have to enter a a card number";
            return;
        }
        else{
            $cardNumber = intval($_POST["cardnumber"]);
        }
        if(empty($_POST["cvv"])){
            $err = "You have to enter the cvv correctly";
            return;
        }
        else{
            $CVV = intval($_POST["cvv"]);
        }
        $price = $_POST["price"];

        $stmt = $connect->prepare("INSERT INTO customer_car(`CUSTOMER_ID`,`CAR_ID`,`PICK_UP`
        ,`RETURN_DATE`,`RETURN_LOCATION_ID`,`TOTAL_PRICE`) VALUES(?,?,?,?,?,?)");
        $stmt->bind_param("iissii",$id,$carId,$pickupDate,$deliveryDate,$dropAddress,$price);
        $stmt->execute();
        $stmt->close();
        $connect->close();
        header("Location:/mybookings.php?id=$id");
    }

    if(isset($_POST["rent"])){
        rent();
    }
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
        <div class="col lg">
        </div>
        <div class="col lg">
            <form class="mb-5 mt-5" method="post" action="/payment.php/?id=<?php echo $carId?>">
                <div class="form-group">
                    <?php while ($row1 = mysqli_fetch_array($customerResult)): ?>
                        <label for="name">Your Name:</label>
                    <input type="text" readonly="true" class="form-control" id="name"
                           value="<?php echo $row1["CUSTOMER_NAME"];?>" name="name">
                </div>
                <div class="form-group">
                    <label for="newemail">Email:</label>
                    <input type="email" readonly="true" class="form-control" id="newemail"
                           value="<?php echo $row1["EMAIL"];?>" name="newemail">
                </div>
                <div class="form-group">
                    <label for="tcno">TC:</label>
                    <input type="text" readonly="true" class="form-control" id="tcno"
                           value="<?php echo $row1["TC_NO"];?> " name="tcno">
                </div>
                <?php endwhile;?>

                <div class="form-group">
                    <?php while ($row1 = mysqli_fetch_array($carResult)): ?>
                    <label for="carname">Car Name:</label>
                    <input type="text" readonly="true" class="form-control" id="carname"
                           value="<?php echo $row1["CAR_NAME"];?>" name="carname">
                </div>
                <div class="form-group">
                    <label for="location">Location:</label>
                    <input type="text" readonly="true" class="form-control" id="location"
                           value="<?php echo $row1["LOCATION"];?>" name="location">
                </div>
                <div class="form-group">
                    <label for="price">Price:</label>
                    <input type="text" readonly="true" class="form-control" id="price"
                           value="<?php echo $row1["PRICE"]*(date_diff(date_create($_SESSION["pickupDate"]),
                                   date_create($_SESSION["deliveryDate"]))->d);?>" name="price">
                </div>
                <?php endwhile;?>
                <div class="form-group">
                    <label for="dob">Starting Date:</label>
                    <div class="input-group date" id="dobDatePicker">
                        <input type="text" readonly="true" class="form-control" id="startDatePicker"
                               value="<?php echo $_SESSION["pickupDate"];?>">
                        <span class="input-group-append">
                                <span class="input-group-text bg-white d-block">
                                    <i class="fa fa-calendar"></i>
                                </span>
                            </span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="dob">Ending Date:</label>
                    <div class="input-group date" id="dobDatePicker">
                        <input type="text" readonly="true" class="form-control" id="endDatePicker"
                               value="<?php echo $_SESSION["deliveryDate"];?>">
                        <span class="input-group-append">
                                <span class="input-group-text bg-white d-block">
                                    <i class="fa fa-calendar"></i>
                                </span>
                            </span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="dob">Delivery Location:</label>
                    <select class="form-control" name="delivered">
                        <option value="" selected> Location</option>
                        <?php while ($row1 = mysqli_fetch_array($locationResult)): ?>
                            <option value="<?php echo $row1['ID']; ?>"><?php echo $row1['LOCATION']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="cardnumber">Card Number:</label>
                    <input type="text" class="form-control" id="cardnumber" placeholder="Enter Your Card Number"
                           name="cardnumber" minlength="19" maxlength="19">
                </div>
                <div class="form-group">
                    <label for="cvv">CVV:</label>
                    <input type="password" class="form-control" id="cvv" placeholder="CVV"
                           name="cvv" maxlength="3">
                </div>
                <div class="form-group text-center">
                    <button type="submit" name="rent" class="btn btn-warning mx-auto mt-5">Rent</button>
                </div>
            </form>
        </div>
        <div class="col lg"></div>
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
<?php
if (!empty($err))
    echo "<script type='text/javascript'>alert('$err');</script>";
?>

</body>

</html>

