<?php
/**
 * Created by PhpStorm.
 * User: matte
 * Date: 27/08/2018
 * Time: 12:04
 */
require_once 'Config.php';


class DbOperationMessages{

    public function autocomplete_message($title){

        $data = http_build_query(
            array(
            'query' => $title
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
        @$result = file_get_contents(AUTOCOMPLETE_MESSAGE.'?'.$data, false, $context);

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

    public function create_message($title, $body, $send_date, $destination){

        $data = array(
            'title' => $title,
            'body' => $body,
            'send_date' => $send_date,
            'destination' => $destination
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
        @$result = file_get_contents(CREATE_MESSAGE, false, $context);

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

    public function read_messages($from_record_num,$records_per_page){

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
        @$result = file_get_contents(READ_MESSAGES.'?'.$data, false, $context);

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

    public function read_one_message($id){

        $data = http_build_query(
            array(
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
        @$result = file_get_contents(READ_ONE_MESSAGE.'?'.$data, false, $context);

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

    public function search_message($title, $records_per_page, $from_record_num){

        $data = http_build_query(
            array(
            'search' => $title,
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
        @$result = file_get_contents(SEARCH_MESSAGE.'?'.$data, false, $context);

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