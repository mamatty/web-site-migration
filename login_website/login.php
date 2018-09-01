<?php
/* User login_website process, checks if user exists and password is correct */
require_once "DbOperation.php";
if(isset($_POST['email']) and isset($_POST['password'])){
    $conn = new DbOperation();
    $app_id = $conn->generateToken();
    setcookie("app-id", $app_id);
    $req = $conn-> login($_POST['email'],$_POST['password']);
    $login = json_decode($req, True);

if (in_array('not-found',$login) ){ // User doesn't exist
    $_SESSION['message'] = "User or password not correct";
    header("location: error.php");
}
else { // User exists

    $_SESSION['email'] = $_POST['email'];
    $_SESSION['first_name'] = $login['first_name'];
    $_SESSION['last_name'] = $login['last_name'];

    /*preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $login, $matches);
    $cookies = array();
    foreach($matches[1] as $item) {
        parse_str($item, $cookie);
        $cookies = array_merge($cookies, $cookie);
    }
    var_dump($cookies);
    setcookie("token", $cookies[0]);*/

    // This is how we'll know the user is logged in
    $_SESSION['logged_in'] = true;

    header("location: ../fitness-club/index.php");
}

}


