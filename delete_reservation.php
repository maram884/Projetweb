<?php
require_once("config.php");

$id = $_GET['id'];

mysqli_query($conn,
"DELETE FROM reservations WHERE id=$id");

header("Location: manage_reservations.php");
?>