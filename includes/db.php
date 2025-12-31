<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);
$conn = mysqli_connect("localhost", "root", "", "sanddmotorsports");

if (!$conn) {
    die("Database connection failed");
}
