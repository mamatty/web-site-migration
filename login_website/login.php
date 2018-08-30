<?php
/* User login_website process, checks if user exists and password is correct */
require_once "DbOperation.php";
if(isset($_POST['email']) and isset($_POST['password'])){
    $conn = new DbOperation();
    $token = $conn->generateToken();
    $req = $conn-> login($_POST['email'],$_POST['password'], $token);
    $login = json_decode($req, True);

if (in_array('not-found',$login) ){ // User doesn't exist
    $_SESSION['message'] = "User or password not correct";
    header("location: error.php");
}
else { // User exists

    $_SESSION['email'] = $_POST['email'];
    $_SESSION['first_name'] = $login['first_name'];
    $_SESSION['last_name'] = $login['last_name'];
    $_SESSION['cookie'] = array(
        'token' => $token,
        'cookie' => $login['cookie']
    );

    // This is how we'll know the user is logged in
    $_SESSION['logged_in'] = true;

    header("location: ../fitness-club/index.php");
}


}


