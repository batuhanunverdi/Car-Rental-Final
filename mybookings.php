<?php
session_start();
if(!$_SESSION["id"]){
    header("Location:index.php");
}
echo "sa";
?>
