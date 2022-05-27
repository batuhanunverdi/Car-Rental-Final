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
$userId = $_GET['id'];
$userQuery = "SELECT * FROM customer WHERE ID='" . $userId . "'";
$userResult = mysqli_query($connect, $userQuery);

if (($_SERVER["REQUEST_METHOD"] ?? 'POST') == "POST") {
    function test_input($data): string
    {
        $data = trim($data);
        $data = stripslashes($data);
        return htmlspecialchars($data);
    }

    function edit()
    {
        global $err, $email, $password, $license, $dob, $connect, $userId;
        $age = "";

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
        $md5 = md5($password);
        $stmt = $connect->prepare("SELECT CUSTOMER_PASSWORD FROM customer WHERE id=?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        if ($result['CUSTOMER_PASSWORD'] != $md5) {
            $err = "Password is not correct";
            $connect->close();
            return;
        }
        $stmt = $connect->prepare("SELECT `ID` FROM customer WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        if ($result != null && (intval($result['ID']) != intval($userId))) {
            $err = "Email is exists";
            $connect->close();
            return;
        }
        $stmt = $connect->prepare("SELECT `ID` FROM customer WHERE license=?");
        $stmt->bind_param("s", $license);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        if ($result != null && (intval($result['ID']) != intval($userId))) {
            $err = "License Number is exists";
            $connect->close();
            return;
        }
        $stmt = $connect->prepare("UPDATE customer SET 
                    EMAIL=?,
                    `CUSTOMER_PASSWORD`=?,
                    DOB=?,
                    license=? 
                WHERE ID=?");
        $stmt->bind_param("ssssi", $email, $md5, $dob, $license, $userId);
        $stmt->execute();
        $stmt->close();
        $connect->close();
        header("Location:myprofile.php?id=$userId");
    }

    if (isset($_POST["editSubmit"])) {
        try {
            edit();
        } catch (Exception $e) {
        }
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
                if (!$_SESSION["isLoggedIn"]) {
                    ?>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#userForm" data-bs-toggle="modal"
                           data-bs-target="#userForm">Login / Sign
                            Up</a>
                    </li>
                    <?php
                } else {
                    ?>
                    <li class="nav-item">
                        <a class="nav-link text-white" href=mybookings.php?id=<?php echo $_SESSION['id'] ?> >My
                            Bookings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href=myprofile.php?id=<?php echo $_SESSION['id'] ?> >My
                            Profile</a>
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
    <div class="col-mg 4" style="margin-top: 15px; margin-left: 250px; margin-right: 250px; ">
        <form method="post" action="myprofile.php?id=<?php echo $userId ?>">
            <div class="form-group">
                <?php while ($row1 = mysqli_fetch_array($userResult)): ?>
                <label for="floatingInput">Name</label>
                <input type="text" readonly="true" class="form-control" name="name"
                       value="<?php echo $row1['CUSTOMER_NAME']; ?>">
            </div>
            <div class="form-group">
                <label for="floatingInput">Email</label>
                <input type="text" placeholder="Email" class="form-control" name="newemail"
                       value="<?php echo $row1['EMAIL']; ?>">
            </div>
            <div class="form-group">
                <label for="floatingInput">TC:</label>
                <input type="text" readonly class="form-control" name="tc" value="<?php echo $row1['TC_NO']; ?>">

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
                           value="<?php echo $row1['DOB']; ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="license">Drive License</label>
                <div class="input-group text" id="license">
                    <input type="text" id="dlicense" name="dlicense"
                           class="form-control"
                           placeholder="Drive License" value="<?php echo $row1['LICENSE']; ?>">
                    <?php endwhile; ?>
                </div>
            </div>
            <button type="submit" name="editSubmit" class="btn btn-warning mt-3 mb-3">UPDATE</button>
        </form>
        <a type="button" class="btn btn-danger mt-3 mb-3" onclick="isUserWantsToDeactive()">Deactivate Account</a>
    </div>
</div>
<?php
if (!empty($err))
    echo "<script type='text/javascript'>alert('$err');</script>";
?>

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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
<script>
    function isUserWantsToDeactive(){
        let isSure = confirm("Are You Sure?");
        if(isSure){
            window.location.href = "deactiveaccount.php?id=<?php echo $_SESSION['id'] ?>";
        }
    }
</script>

</html>