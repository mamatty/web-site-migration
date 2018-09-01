<?php ob_start();
/* Registration process, inserts user info into the database 
   and sends account confirmation email message
 */
require_once "DbOperation.php";
$conn = new DbOperation();
$password = password_hash($_POST['password'], PASSWORD_BCRYPT);
$email = $_POST['email'];
$app_id = $conn->generateToken();
setcookie("app-id", $app_id);
if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $req = $conn->register_account($email, $_POST['firstname'], $_POST['lastname'], $password);
    $register = json_decode($req, True);

    // We know user email exists if the rows returned are more than 0
    if ( $register['status'] == 'already-registered') {

        $_SESSION['message'] = 'User with this email already exists!';
        header("location: error.php");

    }
    elseif($register['status'] == 'successful'){ // Email doesn't already exist in a database, proceed...

            // Set session variables
        $_SESSION['email'] = $_POST['email'];
        $_SESSION['first_name'] = $_POST['firstname'];
        $_SESSION['last_name'] = $_POST['lastname'];
        $_SESSION['active'] = 1; //0 until user activates their account with verify.php
        $_SESSION['logged_in'] = true; // So we know the user has logged in
        /*preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $login, $matches);
          $cookies = array();
          foreach($matches[1] as $item) {
              parse_str($item, $cookie);
              $cookies = array_merge($cookies, $cookie);
          }
          var_dump($cookies);
          setcookie("token", $cookies[0]);*/

        header("location: profile.php");

    }
    else{
        $_SESSION['message'] = 'Registration failed!';
        header("location: error.php");
    }
}else {
    $_SESSION['message'] = 'Registration failed, not a valid email, try again!';
    header("location: error.php");
}
