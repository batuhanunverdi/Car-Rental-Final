<?php
$err = $name = $email = $tc = $password = $license = $dob =  "";
session_start();
$servername = "localhost";
$serverusername = "root";
$serverpassword = "Sanane5885.";
$dbname = "carrental";

$conn = new mysqli($servername, $serverusername, $serverpassword, $dbname);
if ($conn->connect_error) {
    $conn->close();
    die("Connection failed: " . $conn->connect_error);
}

$locationQuery = "SELECT * FROM Location";
$typeQuery = "SELECT * FROM cartype";
$locationResult = mysqli_query($conn,$locationQuery);
$typeResult = mysqli_query($conn,$typeQuery);
if (!isset($_SESSION["isLoggedIn"])) {
    $_SESSION["isLoggedIn"] = false;
}

if (isset($_GET["logout"])) {
    session_destroy();
    header('Location: index.php');
    die();
}
if (($_SERVER["REQUEST_METHOD"] ?? 'POST') == "POST") {

    /**
     * @throws Exception
     */
    function test_input($data): string
    {
        $data = trim($data);
        $data = stripslashes($data);
        return htmlspecialchars($data);
    }

    /**
     * @throws Exception
     */
    function register()
    {
        global $err, $name, $email, $tc, $password, $license, $dob,$conn;
        $age = "";
        if (empty($_POST["name"])) {
            $err = "Name is required";
        } else {
            $name = test_input($_POST["name"]);
            if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
                $err = "Only letters and white space allowed";
                return;
            }
        }

        if (empty($_POST["newemail"])) {
            $err = "Email is required";
            return;
        } else {
            $email = test_input($_POST["newemail"]);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $err = "Invalid email format";
                return;
            }
        }

        if (empty($_POST["newpwd"])) {
            $err = "Password is Required";
            return;
        } else {
            $password = test_input($_POST["newpwd"]);
            if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/", $password)) {
                $err = "Invalid Password";
                return;
            }
        }

        if (empty($_POST["newtc"])) {
            $err = "Tc Number is required";
            return;
        } else if (strlen($_POST["newtc"]) != 11) {
            $err = "Tc Number is not valid.";
            return;
        } else {
            $tc = test_input($_POST["newtc"]);
        }
        if (empty($_POST["dlicense"])) {
            $err = "License is required";
            return;
        } else {
            $license = test_input($_POST["dlicense"]);
        }
        if (empty($_POST["dob"])) {
            $err = "Date Of Birth is Required";
            return;
        } else {
            $now = date("d.m.y");
            $age = date_diff(date_create($_POST["dob"]), date_create($now));
        }
        if ($age->y < 18) {
            $err = "Your age must be greater than 18";
            return;
        } else {
            $dob = $_POST["dob"];
        }

        $sql = "SELECT `CUSTOMER_NAME`,EMAIL,TC_NO,DOB,LICENSE,CUSTOMER_PASSWORD,IS_ACTIVE FROM customer WHERE email='$email'";
        $result = mysqli_query($conn, $sql);
        $count = mysqli_num_rows($result);
        if ($count > 0) {
            $err = "Email is exists";
            $conn->close();
            return;
        }
        $sql = "SELECT `CUSTOMER_NAME`,EMAIL,TC_NO,DOB,LICENSE,CUSTOMER_PASSWORD,IS_ACTIVE FROM customer WHERE TC_NO='$tc'";
        $result = mysqli_query($conn, $sql);
        $count = mysqli_num_rows($result);
        if ($count > 0) {
            $err = "TC Number is exists";
            $conn->close();
            return;
        }
        $sql = "SELECT `CUSTOMER_NAME`,EMAIL,TC_NO,DOB,LICENSE,CUSTOMER_PASSWORD,IS_ACTIVE FROM customer WHERE LICENSE='$license'";
        $result = mysqli_query($conn, $sql);
        $count = mysqli_num_rows($result);
        if ($count > 0) {
            $err = "License Number is exists";
            $conn->close();
            return;
        }
        $stmt = $conn->prepare("INSERT INTO customer (`CUSTOMER_NAME`,EMAIL,TC_NO,DOB,LICENSE,CUSTOMER_PASSWORD) VALUES(?,?,?,?,?,?)");
        $md5 = md5($password);
        $stmt->bind_param("ssssss", $name, $email, $tc, $dob, $license, $md5);
        $stmt->execute();
        $stmt->close();
        $conn->close();
    }

    /**
     * @throws Exception
     */
    function login()
    {
        global $err, $email, $password,$conn;
        if (empty($_POST["email"])) {
            $err = "Email is empty";
            return;
        } else {
            $email = test_input($_POST["email"]);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $err = "Invalid email format";
                return;
            }
        }
        if (empty($_POST["pwd"])) {
            $err = "Password is empty";
            return;
        } else {
            $password = test_input($_POST["pwd"]);
        }

        $sql = "SELECT ID,`CUSTOMER_NAME`,EMAIL,TC_NO,DOB,LICENSE,CUSTOMER_PASSWORD,IS_ACTIVE FROM customer WHERE email='$email'";
        $result = mysqli_query($conn, $sql);
        $count = mysqli_num_rows($result);
        if ($count > 0) {
            while ($row = $result->fetch_assoc()) {
                if ($row['IS_ACTIVE'] == 1) {
                    if ($row['CUSTOMER_PASSWORD'] == md5($password)) {
                        $_SESSION["name"] = $row['CUSTOMER_NAME'];
                        $_SESSION["id"] = $row['ID'];
                        $_SESSION["isLoggedIn"] = true;
                    } else {
                        $err = "Password is wrong";
                        $conn->close();
                        return;
                    }
                } else {
                    $err = "Your status is false";
                    $conn->close();
                    return;
                }
            }
            $conn->close();
        } else {
            $err = "Invalid Account";
            $conn->close();
        }

    }

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

    if (isset($_POST["registerSubmit"])) {
        try {
            register();
        } catch (Exception $e) {
        }
    }
    if (isset($_POST["loginSubmit"])) {
        try {
            login();
        } catch (Exception $e) {
        }
    }
    if(isset($_POST["searchSubmit"]))
    {
        search();
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
    <title>Home Page</title>

</head>

<body>
<?php include("navigation.php"); ?>
<div class="banner-image w-100 vh-100 d-flex justify-content-center align-items-center">
    <div class="card text-center" style="--bs-bg-opacity: 1;">
        <div class="card-body">
            <form class="row-g 3" method="post" action="index.php">
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
</div>
<div class="modal fade login-register-form pe-4" id="userForm" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header" style="height: 50px;">
                <ul class="nav nav-tabs mx-auto">
                    <li class="active">
                        <button class="btn btn-outline-warning active" data-toggle="tab" href="#login-form">
                            Login <span class="glyphicon glyphicon-user"></span></button>
                    </li>
                    &nbsp;
                    <li>
                        <button class="btn btn-outline-warning" data-toggle="tab" href="#registration-form">
                            Register <span class="glyphicon glyphicon-pencil"></span></a></button>
                    </li>
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
                            <input type="hidden" name="loginSubmit" value="1">
                            <button type="submit" id="loginSubmit" class="btn btn-warning">Login</button>
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
                                <label for="tcno"></label><input type="text" class="form-control" id="tcno"
                                                                 placeholder="Enter TC Kimlik No" name="newtc">
                            </div>
                            <div class="form-group">
                                <label for="newpwd">Password:</label>
                                <input type="password" class="form-control" id="newpwd" placeholder="Password"
                                       name="newpwd">
                            </div>
                            <div class="form-group">
                                <label for="dob">Date of Birth:</label>
                                <div class="input-group date">
                                    <input type="date" class="form-control" id="dob" name="dob"
                                           placeholder="Date of Birth">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="license">Drive License</label>
                                <div class="input-group text" id="license">
                                    <label for="dlicense"></label><input type="text" id="dlicense" name="dlicense"
                                                                         class="form-control"
                                                                         placeholder="Drive License">
                                </div>
                            </div>
                            <input type="hidden" name="registerSubmit" value="1">
                            <button type="submit" id="registerSubmit" class="btn btn-warning mx-auto">Register</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Modal End-->

<div class="card text-center bg-dark">
    <div class="card-body">
        <h1 class="text-uppercase mb-2 font-weight-bold text-dark mx-auto">
            WHY CHOOSE US ?
        </h1>
        <div class="container-lg">
            <div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
                <ol class="carousel-indicators">
                    <li data-bs-target="#myCarousel" data-bs-slide-to="0" class="active"></li>
                    <li data-bs-target="#myCarousel" data-bs-slide-to="1"></li>
                    <li data-bs-target="#myCarousel" data-bs-slide-to="2"></li>
                </ol>

                <div class="carousel-inner">
                    <div class="carousel-item active img-fluid">
                        <img src="images/car4.jpg" class="d-flex w-100 h-100" alt="Slide 1">
                        <div class="carousel-caption">
                            <h1>Luxury</h1>
                        </div>
                    </div>
                    <div class="carousel-item img-fluid">
                        <img src="images/car5.jpg" class="d-block w-100 h-100" alt="Slide 2">
                        <div class="carousel-caption">
                            <h1>Security</h1>
                        </div>
                    </div>
                    <div class="carousel-item img-fluid">
                        <img src="images/car3.jpg" class="d-block w-100 h-100" alt="Slide 3">
                        <div class="carousel-caption">
                            <h1>Happiness</h1>
                        </div>
                    </div>

                </div>

                <a class="carousel-control-prev" href="#myCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </a>
                <a class="carousel-control-next" href="#myCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </a>
            </div>
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
    <?php
    if (!empty($err))
        echo "<script type='text/javascript'>alert('$err');</script>";
    ?>

</footer>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
<script src="script.js"></script>
</html>