<?php ob_start();
/**
 * Created by PhpStorm.
 * User: matte
 * Date: 08/04/2018
 * Time: 12:11
 */

/* Displays user information and some useful messages */
session_start();
include 'css/css.html';

// Check if user is logged in using the session variable
if ( $_SESSION['logged_in'] != 1 ) {
    echo "<div class='alert alert-danger'>You must log in before viewing your profile page!.</div>";
    echo "<a href='index.php'><button class='button button-block'/>Home</button></a>";
    header("location: error.php");
}
else {
    // Makes it easier to read
    $first_name = $_SESSION['first_name'];
    $last_name = $_SESSION['last_name'];
    $email = $_SESSION['email'];
    $active = $_SESSION['active'];
    header("location: ../fitness-club/index.php");
    exit();
}
?>