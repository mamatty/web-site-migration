<?php
/**
 * Created by PhpStorm.
 * User: matte
 * Date: 27/08/2018
 * Time: 12:04
 */

include_once dirname(__FILE__) . '/Config.php';


class DbOperation{

    public function login($email,$password){

        $data = array(
            'email' => $email,
            'password' => $password
        );

        $options = array(
            'http' => array(
                'header'  => array(
                    "Content-type: application/x-www-form-urlencoded",
                    "Cookie: app-id=".$_COOKIE["app-id"]
                ),
                'method'  => 'POST',
                'content' => http_build_query($data)
            )
        );

        $context  = stream_context_create($options);
        @$result = file_get_contents(LOGIN, false, $context);

        foreach ($http_response_header as $line){
            preg_match('/Set-Cookie: token=([^;]+)/mi', $line, $match);
            if($match){
                $token_cookie = $match[1];
            }
        }
        setcookie('token', $token_cookie,time()+60*60*24*30,'/','',False, True);

        if(!$result) {
            if (isset($http_response_header) && strpos($http_response_header[0], "401")) {
                $error = $http_response_header[0];
            }
            else {
                $error = "Error 500: Impossible to enstablish a connection with the server! Please, Try in another moment.";
            }
            header( "location: ../Errors/error.php?error=".$error );
        }

        return $result;
    }

    public function logout(){

        $data = array('foo' => 'data');
        $data = http_build_query($data);

        $context_options = array (
            'http' => array (
                'method' => 'GET',
                'header'=> "Content-type: application/x-www-form-urlencoded\r\n"
                    . "Cookie: app-id=".$_COOKIE["app-id"].";token=".$_COOKIE["token"]."\r\n"
                    . "Content-Length: " . strlen($data) . "\r\n",
                'content' => $data
            )
        );

        $context  = stream_context_create($context_options);
        $result = file_get_contents(LOGOUT, false, $context);

        if(!$result) {
            if (isset($http_response_header) && strpos($http_response_header[0], "401")) {
                $error = $http_response_header[0];
            }
            else {
                $error = "Error 500: Impossible to enstablish a connection with the server! Please, Try in another moment.";
            }
            header( "location: ../Errors/error.php?error=".$error );
        }

        return $result;
    }

    public function register_account($email, $first_name, $last_name, $password){

        $data = array(
            'email' => $email,
            'password' => $password,
            'first_name' => $first_name,
            'last_name' => $last_name
        );

        $options = array(
            'http' => array(
                'header'  => array(
                    "Content-type: application/x-www-form-urlencoded",
                    "Cookie: app-id=".$_COOKIE["app-id"]
                ),
                'method'  => 'POST',
                'content' => http_build_query($data)
            )
        );

        $context  = stream_context_create($options);
        $result = file_get_contents(REGISTER, false, $context);

        foreach ($http_response_header as $line){
            preg_match('/Set-Cookie: token=([^;]+)/mi', $line, $match);
            if($match){
                $token_cookie = $match[1];
            }
        }
        setcookie('token', $token_cookie,time()+60*60*24*30,'/','',False, True);


        if(!$result) {
            if (isset($http_response_header) && strpos($http_response_header[0], "401")) {
                $error = $http_response_header[0];
            }
            else {
                $error = "Error 500: Impossible to enstablish a connection with the server! Please, Try in another moment.";
            }
            header( "location: ../Errors/error.php?error=".$error );
        }

        return $result;

    }

    public function is_logged(){

        $options = array(
            'http' => array(
                'header'  => array(
                    "Content-type: application/x-www-form-urlencoded",
                    "Cookie: app-id=".$_COOKIE["app-id"]
                ),
                'method'  => 'GET'
            )
        );

        $context  = stream_context_create($options);
        @$result = file_get_contents(REGISTER, false, $context);

        if(!$result) {
            if (isset($http_response_header) && strpos($http_response_header[0], "401")) {
                $error = $http_response_header[0];
            }
            else {
                $error = "Error 500: Impossible to enstablish a connection with the server! Please, Try in another moment.";
            }
            header( "location: ../Errors/error.php?error=".$error );
        }

        return $result;

    }

    //cryting for the authentication token
    public function crypto_rand_secure($min, $max){
        $range = $max - $min;
        if ($range < 1) return $min;
        $log = ceil(log($range, 2));
        $bytes = (int) ($log / 8) + 1;
        $bits = (int) $log + 1;
        $filter = (int) (1 << $bits) - 1;
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter;
        } while ($rnd > $range);
        return $min + $rnd;
    }

    //generating the authentication token of length equal to 21
    public function generateToken($length = 25){
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet.= "0123456789";
        $max = strlen($codeAlphabet); // edited

        for ($i=0; $i < $length; $i++) {
            $token .= $codeAlphabet[$this->crypto_rand_secure(0, $max-1)];
        }
        return $token;
    }

}