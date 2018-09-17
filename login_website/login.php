<?php
/* User login_website process, checks if user exists and password is correct */

if(isset($_POST['email']) and isset($_POST['password'])){

    $req = $conn-> login($_POST['email'],$_POST['password']);
    $login = json_decode($req, True);

    if (in_array('not-found',$login) or in_array('wrong-pass',$login)){ // User doesn't exist
        $_SESSION['message'] = "User or password not correct";
        header("location: error.php");
    }
    else { // User exists

        setcookie('email', $_POST['email'],time()+60*60*24*30,'/','',False, True);
        setcookie('first_name', $login['first_name'],time()+60*60*24*30,'/','',False, True);
        setcookie('last_name', $login['last_name'],time()+60*60*24*30,'/','',False, True);

        // This is how we'll know the user is logged in
        setcookie('logged_in', True,time()+60*60*24*30,'/','',False, True);

        header("location: ../fitness-club/index.php");
    }
}


