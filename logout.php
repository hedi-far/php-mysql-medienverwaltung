<?php
session_start();
//Session wird zerstört,
//alle Sessionwerte werden gelöscht
session_destroy();
header("location:login.php");
?>

