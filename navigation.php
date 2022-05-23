<?php
?>

<nav class="navbar fixed-top navbar-expand-lg navbar-dark p-md-3">
    <div class="container">
        <a class="navbar-brand" href="index.php">MBU CAR RENTAL</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
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

