<?php
session_start();

if ($_POST['otp'] == $_SESSION['admin_otp']) {
    $_SESSION['admin_otp_verified'] = true;
    echo "verified";
} else {
    echo "invalid";
}
