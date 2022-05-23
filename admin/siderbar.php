<?php
?>
<div class="sidebar-container">
    <div class="sidebar-logo">
        MBU CAR RENTAL
    </div>
    <ul class="sidebar-navigation">
        <li class="header">Welcome <?php echo $_SESSION["name"] ?></li>
        <li>
            <a href="dashboard.php">
                <i class="fa fa-home" aria-hidden="true"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="bookings.php">
                <i class="fa fa-book" aria-hidden="true"></i> Bookings
            </a>
        </li>
        <li>
            <a href="cars.php">
                <i class="fa fa-car" aria-hidden="true"></i> Cars
            </a>
        </li>
        <li>
            <a href="login.php?logout">
                <i class="fa fa-sign-out" aria-hidden="true"></i> Logout
            </a>
        </li>
    </ul>
</div>
