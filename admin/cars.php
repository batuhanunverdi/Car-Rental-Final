<?php
session_start();
if(!$_SESSION["isAdminLoggedIn"]){
    header("Location:login.php");
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
$brandQuery = "SELECT `ID`,`BRAND_NAME` FROM brand";
$colorQuery = "SELECT `ID`,`COLOR` FROM color";
$typeQuery = "SELECT `ID`,`TYPE_NAME` FROM cartype";
$gearQuery = "SELECT `ID`,`GEAR_NAME` FROM gear";
$engineQuery = "SELECT `ID`,`ENGINE_NAME` FROM engine";
$locationQuery = "SELECT `ID`,`LOCATION` FROM location";


$brandResult = mysqli_query($connect, $brandQuery);
$colorResult = mysqli_query($connect, $colorQuery);
$typeResult = mysqli_query($connect, $typeQuery);
$gearResult = mysqli_query($connect, $gearQuery);
$engineResult = mysqli_query($connect, $engineQuery);
$locationResult = mysqli_query($connect, $locationQuery);


if (($_SERVER["REQUEST_METHOD"] ?? 'POST') == "POST") {
    function addCar()
    {
        global $err, $newImageName, $connect;
        $carName = $brand = $color = $type = $gear = $engine = $price = $location = $year = $mileage = $plate = "";
        if (empty($_POST["addCarName"])) {
            $err = "Car name can not be empty";
            return;
        } else {
            $carName = $_POST["addCarName"];
        }
        if (empty($_POST["addBrand"])) {
            $err = "Car Brand can not be empty";
            return;
        } else {
            $brand = intval($_POST["addBrand"]);
        }
        if (empty($_POST["addColor"])) {
            $err = "Color can not be empty";
            return;
        } else {
            $color = intval($_POST["addColor"]);
        }
        if (empty($_POST["addType"])) {
            $err = "Car type can not be empty";
            return;
        } else {
            $type = intval($_POST["addType"]);
        }
        if (empty($_POST["addGear"])) {
            $err = "Car gear can not be empty";
            return;
        } else {
            $gear = intval($_POST["addGear"]);
        }
        if (empty($_POST["addPrice"])) {
            $err = "Car price can not be empty";
            return;
        } else {
            $price = intval($_POST["addPrice"]);
        }
        if (empty($_POST["addLocation"])) {
            $err = "Location can not be empty";
            return;
        } else {
            $location = intval($_POST["addLocation"]);
        }
        if (empty($_POST["addCarYear"])) {
            $err = "Year can not be empty";
            return;
        } else {
            $year = $_POST["addCarYear"];
        }
        if (empty($_POST["addCarPlate"])) {
            $err = "Plate can not be empty";
            return;
        } else {
            $plate = $_POST["addCarPlate"];
        }
        if (empty($_POST["addCarMileage"])) {
            $err = "Mileage can not be empty";
            return;
        } else {
            $mileage = intval($_POST["addCarMileage"]);
        }
        if (empty($_POST["addEngine"])) {
            $err = "Engine can not be empty";
            return;
        } else {
            $engine = intval($_POST["addEngine"]);
        }

        if ($_FILES['carImage']) {
            $imageName = $_FILES['carImage']['name'];
            $tmpName = $_FILES['carImage']["tmp_name"];
            $img_ex = pathinfo($imageName, PATHINFO_EXTENSION);
            $img_ex_lc = strtolower($img_ex);

            $allowed_exs = array("jpg", "jpeg", "png");
            if (in_array($img_ex_lc, $allowed_exs)) {
                $newImageName = uniqid("IMG-", true) . '.' . $img_ex_lc;
                $imageUploadPath = 'C:/Users/Batuhan/Desktop/Final Project/Car-Rental-Final/images/uploads/' . $newImageName;
                move_uploaded_file($tmpName, $imageUploadPath);
            } else {
                $err = "You cant upload files of this type";
                return;
            }
        } else {
            $err = "Unknown Error occured";
            return;
        }
        $sql = "SELECT `PLATE` FROM car WHERE PLATE='$plate'";
        $result = mysqli_query($connect, $sql);
        $count = mysqli_num_rows($result);
        if ($count > 0) {
            $err = "Plate Number is exists";
            $connect->close();
            return;
        }
        $stmt = $connect->prepare("INSERT INTO car(`BRAND_ID`,`TYPE_ID`,`GEAR_ID`,`ENGINE_ID`,
                `CAR_NAME`,`COLOR_ID`,`CAR_YEAR`,`MILEAGE`,`PRICE`,`LOCATION_ID`,`PLATE`,`IMAGE`)
        VALUES(?,?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param("iiiisisiiiss", $brand, $type, $gear, $engine, $carName, $color, $year, $mileage, $price, $location, $plate, $newImageName);
        $stmt->execute();
        $stmt->close();
        $connect->close();
    }
    if (isset($_POST['addCar'])) {
        addCar();
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
    <link rel="stylesheet" href="admin.css">
    <title>Admin Car</title>

</head>

<body>
<?php include('siderbar.php');?>

<div class="content-container">

    <div class="container-fluid">
        <div class="jumbotron">
            <div class="row">
                <div class="text-center">
                    <button class="btn btn-warning btn-login text-uppercase fw-bold" data-toggle="modal"
                            data-target="#addModal"
                            type="submit">Add
                    </button>
                </div>
                <div class="col-md mt-5 mb-5">
                    <table class="table table-image table-striped">
                        <thead>
                        <tr>
                            <th scope="col">Car Name</th>
                            <th scope="col">Location</th>
                            <th scope="col">Price</th>
                            <th scope="col">Type</th>
                            <th scope="col">Engine</th>
                            <th scope="col">Edit</th>
                            <th scope="col">Delete</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $connect = new mysqli($hostname, $username, $password, $databaseName);
                        $sql = "SELECT car.ID,`CAR_NAME`,`LOCATION`,`PRICE`,`TYPE_NAME`,`ENGINE_NAME` 
                                FROM car,location,cartype,engine 
                                WHERE car.TYPE_ID=cartype.ID 
                                AND car.LOCATION_ID=location.ID
                                AND car.ENGINE_ID=engine.ID";
                        $cars = $connect->query($sql);
                        if (!$cars) {
                            die("Invalid Query: " . $connect->error);
                        }
                        while ($row = $cars->fetch_assoc()) {
                            echo "<tr>
                                  <td hidden>". $row['ID']."</td>  
                                  <td>" . $row['CAR_NAME'] . "</td>
                                  <td>" . $row['LOCATION'] . "</td>
                                  <td>" . $row['PRICE'] . "</td>
                                  <td>" . $row['TYPE_NAME'] . "</td>
                                  <td>" . $row['ENGINE_NAME'] . "</td>
                                  <td><a class='btn btn-warning' href=\"editcar.php?id=".$row['ID']."\">Edit</a></td>
                                  <td><a class='btn btn-warning' href=\"deletecar.php?id=".$row['ID']."\">Delete</a></td>
                                  </tr>";
                        } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Edit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="cars.php" enctype="multipart/form-data">
                        <div class="form-floating mb-3">
                            <input type="text" placeholder="Car Name" class="form-control" name="addCarName">
                            <label for="floatingInput">Car Name</label>
                        </div>
                        <div class="form-floating mb-3">
                            <select class="form-control" name="addBrand">
                                <option value="" selected> Brand</option>
                                <?php while ($row1 = mysqli_fetch_array($brandResult)): ?>
                                    <option value="<?php echo $row1['ID']; ?>"><?php echo $row1['BRAND_NAME']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-floating mb-3">
                            <select class="form-control" name="addLocation">
                                <option value="" selected> Location</option>
                                <?php while ($row1 = mysqli_fetch_array($locationResult)): ?>
                                    <option value="<?php echo $row1['ID']; ?>"><?php echo $row1['LOCATION']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="addPrice">
                            <label for="floatingInput">Price</label>
                        </div>
                        <div class="form-floating mb-3">
                            <div class="input-group file" id="carImage">
                                <input type="file" class="form-control" name="carImage" id="carImage"">
                            </div>
                        </div>
                        <div class="form-floating mb-3">
                            <select class="form-control" name="addType">
                                <option value="" selected> Type</option>
                                <?php while ($row1 = mysqli_fetch_array($typeResult)): ?>
                                    <option value="<?php echo $row1['ID']; ?>"><?php echo $row1['TYPE_NAME']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-floating mb-3">
                            <select class="form-control" name="addEngine">
                                <option value="" selected> Engine</option>
                                <?php while ($row1 = mysqli_fetch_array($engineResult)): ?>
                                    <option value="<?php echo $row1['ID']; ?>"><?php echo $row1['ENGINE_NAME']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-floating mb-3">
                            <select class="form-control" name="addGear">
                                <option value="" selected> Gear</option>
                                <?php while ($row1 = mysqli_fetch_array($gearResult)): ?>
                                    <option value="<?php echo $row1['ID']; ?>"><?php echo $row1['GEAR_NAME']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-floating mb-3">
                            <select class="form-control" name="addColor">
                                <option value="" selected> Color</option>
                                <?php while ($row1 = mysqli_fetch_array($colorResult)): ?>
                                    <option value="<?php echo $row1['ID']; ?>"><?php echo $row1['COLOR']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="addCarYear">
                            <label for="floatingInput">Year</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="addCarMileage">
                            <label for="floatingInput">Mileage</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="addCarPlate">
                            <label for="floatingInput">Plate</label>
                        </div>
                        <div class="d-grid">
                            <button class="btn btn-warning btn-login text-uppercase fw-bold" name="addCar"
                                    type="submit">Add
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
if ($err) {
    echo "<script type='text/javascript'>alert('$err');</script>";
}
?>
</body>

</html>
