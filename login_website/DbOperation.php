<?php
/**
 * Created by PhpStorm.
 * User: matte
 * Date: 27/08/2018
 * Time: 12:04
 */

include_once dirname(__FILE__) . '../Config.php';


class DbOperation{

    public function login($email, $password, $token){

        $data = array(
          'email' => $email,
          'password' => $password,
          'token' => $token
        );

        $options = array(
            'http' => array(
                'header'  => array(
                    "Content-type: application/x-www-form-urlencoded",
                ),
                'method' => 'GET',
                'content' => http_build_query($data)
            )
        );
        $context = stream_context_create($options);

        $sFile = file_get_contents(LOGIN, False, $context);

        return $sFile;
    }

    public function logout($tokens){

        $options = array(
            'http' => array(
                'header'  => array(
                    "Content-type: application/x-www-form-urlencoded"
                ),
                'method' => 'GET',
                'content' => http_build_query($tokens)
            )
        );
        $context = stream_context_create($options);

        $sFile = file_get_contents(LOGOUT, False, $context);

        return $sFile;
    }

    public function register_account($token,$email, $first_name, $last_name, $password){

        $data = array(
            'token' => $token,
            'email' => $email,
            'password' => $password,
            'first_name' => $first_name,
            'last_name' => $last_name
        );

        $options = array(
            'http' => array(
                'header'  => array(
                    "Content-type: application/x-www-form-urlencoded"
                ),
                'method' => 'POST',
                'content' => http_build_query($data)
            )
        );
        $context = stream_context_create($options);

        $sFile = file_get_contents(REGISTER, False, $context);

        return $sFile;
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