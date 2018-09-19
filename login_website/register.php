<?php ob_start();
/* Registration process, inserts user info into the database 
   and sends account confirmation email message
 */
require_once "../DbOperations/DbOperationLogin.php";
$conn = new DbOperation();
$email = $_POST['email'];
if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $req = $conn->register_account($email, $_POST['firstname'], $_POST['lastname'], $_POST['password']);
    $register = json_decode($req, True);

    // We know user email exists if the rows returned are more than 0
    if ( $register['status'] == 'already-registered') {

        $_SESSION['message'] = 'User with this email already exists!';
        header("location: error.php");

    }
    elseif(array_key_exists('error', $login)){
        header( "location: ../Errors/error.php?error=".$login['error']." - The application is not allowed to access the service");
    }
    elseif($register['status'] == 'successful'){ // Email doesn't already exist in a database, proceed...

            // Set session variables
        setcookie('email', $_POST['email'],time()+60*60*24*30,'/','',False, True);
        setcookie('first_name', $_POST['firstname'],time()+60*60*24*30,'/','',False, True);
        setcookie('last_name', $_POST['lastname'],time()+60*60*24*30,'/','',False, True);
        setcookie('logged_in', True,time()+60*60*24*30,'/','',False, True);

        header("location: ../fitness-club/index.php");

    }
    else{
        $_SESSION['message'] = 'Registration failed!';
        header("location: error.php");
    }
}else {
    $_SESSION['message'] = 'Registration failed, not a valid email, try again!';
    header("location: error.php");
}
