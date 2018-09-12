<?php
/* User login_website process, checks if user exists and password is correct */

if(isset($_POST['email']) and isset($_POST['password'])){

    $req = $conn-> login($_POST['email']);
    $login = json_decode($req, True);
    print_r($login);

if (in_array('not-found',$login) ){ // User doesn't exist
    $_SESSION['message'] = "User or password not correct";
    header("location: error.php");
}
else { // User exists
    if ( password_verify($_POST['password'], $login['password']) ) {
        setcookie('email', $_POST['email'],time()+60*60*24*30,'/','',False, True);
        setcookie('first_name', $login['first_name'],time()+60*60*24*30,'/','',False, True);
        setcookie('last_name', $login['last_name'],time()+60*60*24*30,'/','',False, True);

        #qui va sostituito con $_COOKIE['token'] appena viene definito il metodo per far tornare il cookie dopo il login o la registrazione.
        setcookie("token", "token",time()+60*60*24*30,'/','',False, True);

        /*preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $login, $matches);
        $cookies = array();
        foreach($matches[1] as $item) {
            parse_str($item, $cookie);
            $cookies = array_merge($cookies, $cookie);
        }
        var_dump($cookies);
        setcookie("token", $cookies[0]);*/

        // This is how we'll know the user is logged in
        setcookie('logged_in', True,time()+60*60*24*30,'/','',False, True);

        header("location: ../fitness-club/index.php");
    }
}

}


