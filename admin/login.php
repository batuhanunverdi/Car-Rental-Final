<?php
session_start();
if (isset($_GET["logout"])) {
    session_destroy();
    header('Location: login.php');
    die();
}
if (($_SERVER["REQUEST_METHOD"] ?? 'POST') == "POST") {

    function test_input($data): string
    {
        $data = trim($data);
        $data = stripslashes($data);
        return htmlspecialchars($data);
    }

    /**
     * @throws Exception
     */
    function login()
    {
        global $err, $email, $password;
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
        if (empty($_POST["password"])) {
            $err = "Password is empty";
            return;
        } else {
            $password = test_input($_POST["password"]);
        }
        $servername = "localhost";
        $serverusername = "root";
        $serverpassword = "Sanane5885.";
        $dbname = "carrental";

        $conn = new mysqli($servername, $serverusername, $serverpassword, $dbname);
        if ($conn->connect_error) {
            $conn->close();
            die("Connection failed: " . $conn->connect_error);
        }
        $sql = "SELECT ID,`EMPLOYEE_NAME`,EMAIL,EMPLOYEE_PASSWORD,IS_ACTIVE FROM employee WHERE email='$email'";
        $result = mysqli_query($conn, $sql);
        $count = mysqli_num_rows($result);
        if ($count > 0) {
            while ($row = $result->fetch_assoc()) {
                if ($row['IS_ACTIVE'] == 1) {
                    if ($row['EMPLOYEE_PASSWORD'] == md5($password)) {
                        $_SESSION["name"] = $row['EMPLOYEE_NAME'];
                        $_SESSION["id"] = $row['ID'];
                        $_SESSION["isAdminLoggedIn"] = true;
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
            return;
        }
        header("Location:dashboard.php");

    }
    if(isset($_POST["login"])){
        login();
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
<div class="container">
    <div class="row">
        <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
            <div class="card border-0 shadow rounded-3 my-5">
                <div class="card-body p-4 p-sm-5">
                    <h5 class="card-title text-center mb-5 fw-light fs-5">Sign In</h5>
                    <form method="post" action="login.php">
                        <div class="form-floating mb-3">
                            <input type="email" name="email" class="form-control">
                            <label for="floatingInput">Email address</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" name="password" class="form-control">
                            <label for="floatingInput">Password</label>
                        </div>

                        <div class="d-grid">
                            <button class="btn btn-warning btn-login text-uppercase fw-bold" name="login" type="submit">Log
                                in</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
if (!empty($err))
    echo "<script type='text/javascript'>alert('$err');</script>";
?>
</body>


</html>

