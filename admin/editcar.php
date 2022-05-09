<?php
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
$id = $_GET['id'];
$brandQuery = "SELECT `ID`,`BRAND_NAME` FROM brand";
$colorQuery = "SELECT `ID`,`COLOR` FROM color";
$typeQuery = "SELECT `ID`,`TYPE_NAME` FROM cartype";
$gearQuery = "SELECT `ID`,`GEAR_NAME` FROM gear";
$engineQuery = "SELECT `ID`,`ENGINE_NAME` FROM engine";
$locationQuery = "SELECT `ID`,`LOCATION` FROM location";
$editCarName  = "SELECT CAR_NAME FROM car WHERE ID='" . $id . "'";
$editCarPrice = "SELECT PRICE FROM car WHERE ID='" . $id . "'";
$editCarYear  = "SELECT CAR_YEAR FROM car WHERE ID='" . $id . "'";
$editCarMileage  = "SELECT MILEAGE FROM car WHERE ID='" . $id . "'";
$editCarPlate  = "SELECT PLATE FROM car WHERE ID='" . $id . "'";



$brandResult = mysqli_query($connect, $brandQuery);
$colorResult = mysqli_query($connect, $colorQuery);
$typeResult = mysqli_query($connect, $typeQuery);
$gearResult = mysqli_query($connect, $gearQuery);
$engineResult = mysqli_query($connect, $engineQuery);
$locationResult = mysqli_query($connect, $locationQuery);
$editCarNameResult = mysqli_query($connect,$editCarName);
$editCarPriceResult = mysqli_query($connect,$editCarPrice);
$editCarYearResult = mysqli_query($connect,$editCarYear);
$editCarMileageResult = mysqli_query($connect,$editCarMileage);
$editCarPlateResult = mysqli_query($connect,$editCarPlate);

if (($_SERVER["REQUEST_METHOD"] ?? 'POST') == "POST") {
    function editCar()
    {
        global $err, $newImageName, $connect,$id;
        $carName = $brand = $color = $type = $gear = $engine = $price = $location = $year = $mileage = $plate = "";
        if (empty($_POST["editCarName"])) {
            $err = "Car name can not be empty";
            return;
        } else {
            $carName = $_POST["editCarName"];
        }
        if (empty($_POST["editBrand"])) {
            $err = "Car Brand can not be empty";
            return;
        } else {
            $brand = intval($_POST["editBrand"]);
        }
        if (empty($_POST["editColor"])) {
            $err = "Color can not be empty";
            return;
        } else {
            $color = intval($_POST["editColor"]);
        }
        if (empty($_POST["editType"])) {
            $err = "Car type can not be empty";
            return;
        } else {
            $type = intval($_POST["editType"]);
        }
        if (empty($_POST["editGear"])) {
            $err = "Car gear can not be empty";
            return;
        } else {
            $gear = intval($_POST["editGear"]);
        }
        if (empty($_POST["editPrice"])) {
            $err = "Car price can not be empty";
            return;
        } else {
            $price = intval($_POST["editPrice"]);
        }
        if (empty($_POST["editLocation"])) {
            $err = "Location can not be empty";
            return;
        } else {
            $location = intval($_POST["editLocation"]);
        }
        if (empty($_POST["editCarYear"])) {
            $err = "Year can not be empty";
            return;
        } else {
            $year = $_POST["editCarYear"];
        }
        if (empty($_POST["editCarPlate"])) {
            $err = "Plate can not be empty";
            return;
        } else {
            $plate = $_POST["editCarPlate"];
        }
        if (empty($_POST["editCarMileage"])) {
            $err = "Mileage can not be empty";
            return;
        } else {
            $mileage = intval($_POST["editCarMileage"]);
        }
        if (empty($_POST["editEngine"])) {
            $err = "Engine can not be empty";
            return;
        } else {
            $engine = intval($_POST["editEngine"]);
        }

        if ($_FILES['editCarImage']) {
            $imageName = $_FILES['editCarImage']['name'];
            $tmpName = $_FILES['editCarImage']["tmp_name"];
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
        $sql = "SELECT `ID`,`PLATE` FROM car WHERE PLATE='$plate'";
        $result = mysqli_query($connect, $sql);
        $count = mysqli_num_rows($result);
        $plateCheck="";
        while ($row = $result->fetch_assoc()){
            $plateCheck= $row["ID"];
        }
        if ($count > 0) {
            if($id!=$plateCheck){
                $err = "Plate Number is exists";
                $connect->close();
                return;
            }

        }
        $sql = "UPDATE car SET 
               BRAND_ID=?,
               TYPE_ID=?,
               GEAR_ID=?,
               ENGINE_ID=?,
               CAR_NAME=?,
               COLOR_ID=?,
               CAR_YEAR=?,
               MILEAGE=?,
               PRICE=?,
               LOCATION=?,
               PLATE=?,
               IMAGE=? WHERE ID=?";
        $stmt = $connect->prepare($sql);
        $stmt->bind_param("iiiisisiiissi", $brand, $type, $gear, $engine, $carName, $color, $year, $mileage, $price, $location, $plate, $newImageName,$id);
        $stmt->execute();
        $stmt->close();
        $connect->close();
    }

    if (isset($_POST['editCar'])) {
        editCar();
    }
}
?>
<html>
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
    <title>Edit Car</title>

</head>
<body>
<div class="container">
    <div class="col-mg 4" style="margin-top: 15px; margin-left: 250px; margin-right: 250px; ">
        <form method="post" action="editcar.php?id=<?php echo $id?>" enctype="multipart/form-data">
            <div class="form-floating mb-3">
                <?php while ($row1 = mysqli_fetch_array($editCarNameResult)): ?>
                <input type="text" placeholder="Car Name" class="form-control" name="editCarName">
                <label for="floatingInput"><?php echo $row1['CAR_NAME']; ?></label>
                <?php endwhile;?>
            </div>
            <div class="form-floating mb-3">
                <select class="form-control" name="editBrand">
                    <option value="" selected> Brand</option>
                    <?php while ($row1 = mysqli_fetch_array($brandResult)): ?>
                        <option value="<?php echo $row1['ID']; ?>"><?php echo $row1['BRAND_NAME']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-floating mb-3">
                <select class="form-control" name="editLocation">
                    <option value="" selected> Location</option>
                    <?php while ($row1 = mysqli_fetch_array($locationResult)): ?>
                        <option value="<?php echo $row1['ID']; ?>"><?php echo $row1['LOCATION']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-floating mb-3">
                <?php while ($row2 = mysqli_fetch_array($editCarPriceResult)): ?>
                    <input type="text" placeholder="Car Name" class="form-control" name="editPrice">
                    <label for="floatingInput"><?php echo $row2['PRICE']; ?></label>
                <?php endwhile;?>
            </div>
            <div class="form-floating mb-3">
                <div class="input-group file" id="carImage">
                    <input type="file" class="form-control" name="editCarImage" id="carImage"">
                </div>
            </div>
            <div class="form-floating mb-3">
                <select class="form-control" name="editType">
                    <option value="" selected> Type</option>
                    <?php while ($row1 = mysqli_fetch_array($typeResult)): ?>
                        <option value="<?php echo $row1['ID']; ?>"><?php echo $row1['TYPE_NAME']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-floating mb-3">
                <select class="form-control" name="editEngine">
                    <option value="" selected> Engine</option>
                    <?php while ($row1 = mysqli_fetch_array($engineResult)): ?>
                        <option value="<?php echo $row1['ID']; ?>"><?php echo $row1['ENGINE_NAME']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-floating mb-3">
                <select class="form-control" name="editGear">
                    <option value="" selected> Gear</option>
                    <?php while ($row1 = mysqli_fetch_array($gearResult)): ?>
                        <option value="<?php echo $row1['ID']; ?>"><?php echo $row1['GEAR_NAME']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-floating mb-3">
                <select class="form-control" name="editColor">
                    <option value="" selected> Color</option>
                    <?php while ($row1 = mysqli_fetch_array($colorResult)): ?>
                        <option value="<?php echo $row1['ID']; ?>"><?php echo $row1['COLOR']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-floating mb-3">
                <?php while ($row1 = mysqli_fetch_array($editCarYearResult)): ?>
                    <input type="text" placeholder="Car Name" class="form-control" name="editCarYear">
                    <label for="floatingInput"><?php echo $row1['CAR_YEAR']; ?></label>
                <?php endwhile;?>
            </div>
            <div class="form-floating mb-3">
                <?php while ($row1 = mysqli_fetch_array($editCarMileageResult)): ?>
                    <input type="text" placeholder="Car Name" class="form-control" name="editCarMileage">
                    <label for="floatingInput"><?php echo $row1['MILEAGE']; ?></label>
                <?php endwhile;?>
            </div>
            <div class="form-floating mb-3">
                <?php while ($row1 = mysqli_fetch_array($editCarPlateResult)): ?>
                    <input type="text" placeholder="Car Name" class="form-control" name="editCarPlate">
                    <label for="floatingInput"><?php echo $row1['PLATE']; ?></label>
                <?php endwhile;?>
            </div>
            <div class="d-grid">
                <button class="btn btn-warning btn-login text-uppercase fw-bold" name="editCar"
                        type="submit">Edit
                </button>
            </div>

        </form>
    </div>
</div>
</body>
<?php
if ($err) {
    echo "<script type='text/javascript'>alert('$err');</script>";
}
?>
</html>
