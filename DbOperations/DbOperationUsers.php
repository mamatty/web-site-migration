<?php
/**
 * Created by PhpStorm.
 * User: matte
 * Date: 27/08/2018
 * Time: 12:04
 */

require_once 'Config.php';


class DbOperationUsers{

    public function autocomplete_user($surname){

        $data = http_build_query(
            array(
            'query' => $surname
            )
        );

        $options = array(
            'http' => array(
                'header'  => array(
                    "Content-type: application/x-www-form-urlencoded",
                    "Cookie: app-id=".$_COOKIE["app-id"].";token=".$_COOKIE["token"]
                ),
                'method'  => 'GET'
            )
        );

        $context  = stream_context_create($options);
        @$result = file_get_contents(AUTOCOMPLETE_USER.'?'.$data, false, $context);
        
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

    public function create_user($name, $surname, $email, $password, $address,$birth_date, $phone, $subscription, $end_subscription){

        $data = array(
            'name' => $name,
            'surname' => $surname,
            'email' => $email,
            'password' => $password,
            'address' => $address,
            'birth_date' => $birth_date,
            'phone' => $phone,
            'subscription' => $subscription,
            'end_subscription' => $end_subscription
        );

        $options = array(
            'http' => array(
                'header'  => array(
                    "Content-type: application/x-www-form-urlencoded",
                    "Cookie: app-id=".$_COOKIE["app-id"].";token=".$_COOKIE["token"]
                ),
                'method'  => 'POST',
                'content' => http_build_query($data)
            )
        );

        $context  = stream_context_create($options);
        @$result = file_get_contents(CREATE_USER, false, $context);
        
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

    public function delete_user($id){

        $data = array(
            'id' => $id
        );

        $options = array(
            'http' => array(
                'header'  => array(
                    "Content-type: application/x-www-form-urlencoded",
                    "Cookie: app-id=".$_COOKIE["app-id"].";token=".$_COOKIE["token"]
                ),
                'method'  => 'POST',
                'content' => http_build_query($data)
            )
        );

        $context  = stream_context_create($options);
        @$result = file_get_contents(DELETE_USER, false, $context);
        
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

    public function manage_users($records_per_page, $from_record_num){

        $data = http_build_query(
            array(
            'records_per_page' => $records_per_page,
            'from_record_num' => $from_record_num
            )
        );

        $options = array(
            'http' => array(
                'header'  => array(
                    "Content-type: application/x-www-form-urlencoded",
                    "Cookie: app-id=".$_COOKIE["app-id"].";token=".$_COOKIE["token"]
                ),
                'method'  => 'GET'
            )
        );

        $context  = stream_context_create($options);
        @$result = file_get_contents(MANAGE_USERS.'?'.$data, false, $context);

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

    public function account_profile(){

        $options = array(
            'http' => array(
                'header'  => array(
                    "Content-type: application/x-www-form-urlencoded",
                    "Cookie: app-id=".$_COOKIE["app-id"].";token=".$_COOKIE["token"]
                ),
                'method'  => 'GET'
            )
        );

        $context  = stream_context_create($options);
        @$result = file_get_contents(PROFILE, false, $context);

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

    public function read_one_user($id){

        $data = http_build_query(array(
            'id' => $id
            )
        );

        $options = array(
            'http' => array(
                'header'  => array(
                    "Content-type: application/x-www-form-urlencoded",
                    "Cookie: app-id=".$_COOKIE["app-id"].";token=".$_COOKIE["token"]
                ),
                'method'  => 'GET'
            )
        );

        $context  = stream_context_create($options);
        @$result = file_get_contents(READ_ONE_USER.'?'.$data, false, $context);

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

    public function search_user($surname, $records_per_page, $from_record_num){

        $data = http_build_query(
            array(
            'search' => $surname,
            'records_per_page' => $records_per_page,
            'from_record_num' => $from_record_num
            )
        );

        $options = array(
            'http' => array(
                'header'  => array(
                    "Content-type: application/x-www-form-urlencoded",
                    "Cookie: app-id=".$_COOKIE["app-id"]."; token=".$_COOKIE["token"]
                ),
                'method'  => 'GET'
            )
        );

        $context  = stream_context_create($options);
        @$result = file_get_contents(SEARCH_USER.'?'.$data, false, $context);

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

    #da testare! L'invio specifico di un solo parametro invece di un array non so se è consentito
    public function look_updated_user($id){

        $data = http_build_query(
            array(
            'id_user' => $id
            )
        );

        $options = array(
            'http' => array(
                'header'  => array(
                    "Content-type: application/x-www-form-urlencoded",
                    "Cookie: app-id=".$_COOKIE["app-id"].";token=".$_COOKIE["token"]
                ),
                'method'  => 'GET'
            )
        );

        $context  = stream_context_create($options);
        @$result = file_get_contents(LOOK_UPDATED_USER.'?'.$data, false, $context);

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

    public function update_user($id, $name, $surname, $email, $birth_date, $address, $subscription, $end_subscription){

        $data = array(
            'id' => $id,
            'name' => $name,
            'surname' => $surname,
            'email' => $email,
            'birth_date' => $birth_date,
            'address' => $address,
            'subscription' => $subscription,
            'end_subscription' => $end_subscription
        );

        $options = array(
            'http' => array(
                'header'  => array(
                    "Content-type: application/x-www-form-urlencoded",
                    "Cookie: app-id=".$_COOKIE["app-id"].";token=".$_COOKIE["token"]
                ),
                'method'  => 'POST',
                'content' => http_build_query($data)
            )
        );

        $context  = stream_context_create($options);
        @$result = file_get_contents(UPDATE_USER, false, $context);

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
}
