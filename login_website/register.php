<?php ob_start();
/* Registration process, inserts user info into the database 
   and sends account confirmation email message
 */
require_once "../DbOperations/DbOperationLogin.php";
$conn = new DbOperation();
$password = password_hash($_POST['password'], PASSWORD_BCRYPT);
$email = $_POST['email'];
$app_id = $conn->generateToken();
$_COOKIE["app-id"] = "debug";
$_COOKIE["token"] = "token";
if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $req = $conn->register_account($email, $_POST['firstname'], $_POST['lastname'], $password);
    $register = json_decode($req, True);

    // We know user email exists if the rows returned are more than 0
    if ( $register['status'] == 'already-registered') {

        $_COOKIE['message'] = 'User with this email already exists!';
        header("location: error.php");

    }
    elseif($register['status'] == 'successful'){ // Email doesn't already exist in a database, proceed...

            // Set session variables
        $_COOKIE['email'] = $_POST['email'];
        $_COOKIE['first_name'] = $_POST['firstname'];
        $_COOKIE['last_name'] = $_POST['lastname'];
        $_COOKIE['active'] = 1; //0 until user activates their account with verify.php
        $_COOKIE['logged_in'] = true; // So we know the user has logged in
        setcookie("token", $register['token']);

        header("location: profile.php");

    }
    else{
        $_COOKIE['message'] = 'Registration failed!';
        header("location: error.php");
    }
}else {
    $_COOKIE['message'] = 'Registration failed, not a valid email, try again!';
    header("location: error.php");
}
