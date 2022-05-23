<?php
session_start();

if (($_SERVER["REQUEST_METHOD"] ?? 'POST') == "POST") {
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
    if (empty($_POST["textArea1"]) || empty($_POST["subject"]) || empty($_POST["sendEmail"])) {
        $err = "You have too fill the all blanks.";
    }
    $email = $_POST["sendEmail"];
    $subject = $_POST["subject"];
    $message = $_POST["textArea1"];

    $sql = "INSERT INTO contact(email,subject,message) VALUES(?,?,?)";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("sss", $email, $subject, $message);
    $stmt->execute();
    $stmt->close();
    $connect->close();
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
<!--Navbar End-->

<!-- Banner -->
<div class="container vh-100 d-flex flex-column justify-content-center align-items-center">
    <div class="card" style="width: 75%;">
        <div class="card-body d-flex flex-column">
            <div class="row">
                <div class="col-lg-8">
                    <iframe class="w-100 h-100"
                            src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d12763.300174947375!2d30.655353!3d36.8945335!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xfbd4e14587332eeb!2sAkdeniz%20%C3%9Cniversitesi!5e0!3m2!1str!2str!4v1648630104013!5m2!1str!2str"
                            width="500" height="400" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
                <div class="col-lg p-e-4">
                    <form method="post" class="form-group" action="contact.php">
                        <div class="form-grop">
                            <label for="email">Email:</label>
                            <input type="email" class="form-control" id="email" placeholder="Enter email"
                                   name="sendEmail">
                        </div>
                        <div class="form-grop">
                            <label for="email">Email:</label>
                            <input type="text" class="form-control" id="subject" placeholder="Enter Subject"
                                   name="subject">
                        </div>
                        <div class="form-group">
                            <label for="context">What About ?</label>
                            <textarea class="form-control" name="textArea1" rows="4"></textarea>
                            <br>
                        </div>
                        <div class="form-group"></div>
                        <button type="submit" onclick="alert('Your Message has sent');"
                                class="btn btn-warning">Send
                        </button>
                        <div class="text-dark">
                            <div class="col-md-8">
                                <i class="bi bi-facebook" style="color:black;"></i> <a style=color:black; href="#">MBU
                                    Rental Facebook</a>
                            </div>
                            <div class="col-md-8">
                                <i class="bi bi-twitter" style="color:black;"></i> <a style=color:black; href="#">MBU
                                    Rental Twitter</a>
                            </div>
                            <div class="col-md-8">
                                <i class="bi bi-github" style="color:black;"></i> <a style=color:black; href="#">MBU
                                    Rental Github</a>
                            </div>
                            <div class="col-md-8">
                                <i class="bi bi-telephone-fill" style="color:black;"></i> <a style=color:black;
                                                                                             href="#">MBU Rental</a>
                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<!--Banner End-->
<!-- Modal -->
<!-- Login / Register Modal-->
<div class="modal fade login-register-form pe-4" id="userForm" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header" style="height: 50px;">
                <ul class="nav nav-tabs mx-auto">
                    <li class="active">
                        <button class="btn btn-outline-warning active" data-toggle="tab"
                                href="#login-form">
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
                        <form method="post">
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
                        <form action="index.html">
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
                                <label for="newemail">Email:</label>
                                <input type="text" class="form-control" id="tcno" placeholder="Enter TC Kimlik No"
                                       name="newemail">
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
                                    <input type="text" class="form-control" placeholder="Drive License"
                                           id="licensetext">
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

</html>
