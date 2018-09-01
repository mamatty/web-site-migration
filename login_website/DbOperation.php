<?php
/**
 * Created by PhpStorm.
 * User: matte
 * Date: 27/08/2018
 * Time: 12:04
 */

require_once '../Config.php';


class DbOperation{

    public function login($email, $password){

        $data = array(
          'email' => $email,
          'password' => $password
        );

        $header = array(
            "Content-type: application/json"
        );

        //Initializing curl to open a connection
        $ch = curl_init();

        //Setting the curl url
        curl_setopt($ch, CURLOPT_URL, LOGIN);

        //setting the method as post
        curl_setopt($ch, CURLOPT_POST, true);

        //adding headers
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        # sending cookies from file
        curl_setopt($ch, CURLOPT_COOKIEFILE, $_COOKIE["app-id"]);

        //disabling ssl support
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        //adding the fields in json format
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        //finally executing the curl request
        $result = curl_exec($ch);

        //Now close the connection
        curl_close($ch);

        return $result;
    }

    public function logout(){

        $header = array(
            "Content-type: application/json"
        );

        //Initializing curl to open a connection
        $ch = curl_init();

        //Setting the curl url
        curl_setopt($ch, CURLOPT_URL, LOGIN);

        //setting the method as post
        curl_setopt($ch, CURLOPT_POST, true);

        //adding headers
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        # sending cookies from file
        #curl_setopt($ch, CURLOPT_COOKIEFILE, array($_COOKIE["app-id"], $_COOKIE["token"]));
        curl_setopt($ch, CURLOPT_COOKIE, "app-id=".$_COOKIE['app-id'].';token='.$_COOKIE['token']);

        //disabling ssl support
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        //finally executing the curl request
        $result = curl_exec($ch);

        //Now close the connection
        curl_close($ch);

        return $result;
    }

    public function register_account($email, $first_name, $last_name, $password){

        $data = array(
            'email' => $email,
            'password' => $password,
            'first_name' => $first_name,
            'last_name' => $last_name
        );

        $header = array(
            "Content-type: application/json"
        );

        //Initializing curl to open a connection
        $ch = curl_init();

        //Setting the curl url
        curl_setopt($ch, CURLOPT_URL, REGISTER);

        //setting the method as post
        curl_setopt($ch, CURLOPT_POST, true);

        //adding headers
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        # sending cookies from file
        curl_setopt($ch, CURLOPT_COOKIEFILE, $_COOKIE["app-id"]);

        //disabling ssl support
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        //adding the fields in json format
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        //finally executing the curl request
        $result = curl_exec($ch);

        //Now close the connection
        curl_close($ch);

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
    public function generateToken($length = 21){
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