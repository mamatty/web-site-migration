<?php
/**
 * Created by PhpStorm.
 * User: matte
 * Date: 27/08/2018
 * Time: 12:04
 */
require_once '../Config.php';


class DbOperation{

    public function autocomplete_message($title){

        $data = array(
          'title' => $title
        );

        $header = array(
            "Content-type: application/json"
        );

        $params = http_build_query($data);
        $getUrl = AUTOCOMPLETE_MESSAGE."?".$params;

        //Initializing curl to open a connection
        $ch = curl_init();

        //Setting the curl url
        curl_setopt($ch, CURLOPT_URL, $getUrl);

        //setting the method as get
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

        //adding headers
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        # sending cookies from file
        #curl_setopt($ch, CURLOPT_COOKIEFILE, array($_COOKIE["app-id"], $_COOKIE["token"]));
        #curl_setopt($ch, CURLOPT_COOKIE, "app-id=".$_COOKIE['app-id'].';token='.$_COOKIE['token']);
        if(isset($_COOKIE["app-id"]) and isset($_COOKIE["token"])){
            curl_setopt($ch, CURLOPT_COOKIEFILE, $_COOKIE["app-id"]);
            curl_setopt($ch, CURLOPT_COOKIEFILE, $_COOKIE["token"]);
        }

        //disabling ssl support
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        //finally executing the curl request
        $result = curl_exec($ch);

        //Now close the connection
        curl_close($ch);

        return $result;
    }

    public function create_message($title, $body, $send_date, $destination){

        $data = array(
            'title' => $title,
            'body' => $body,
            'send_date' => $send_date,
            'destination' => $destination
        );

        $header = array(
            "Content-type: application/json"
        );

        //Initializing curl to open a connection
        $ch = curl_init();

        //Setting the curl url
        curl_setopt($ch, CURLOPT_URL, CREATE_MESSAGE);

        //setting the method as post
        curl_setopt($ch, CURLOPT_POST, true);

        //adding headers
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        # sending cookies from file
        #curl_setopt($ch, CURLOPT_COOKIEFILE, array($_COOKIE["app-id"], $_COOKIE["token"]));
        #curl_setopt($ch, CURLOPT_COOKIE, "app-id=".$_COOKIE['app-id'].';token='.$_COOKIE['token']);
        if(isset($_COOKIE["app-id"]) and isset($_COOKIE["token"])){
            curl_setopt($ch, CURLOPT_COOKIEFILE, $_COOKIE["app-id"]);
            curl_setopt($ch, CURLOPT_COOKIEFILE, $_COOKIE["token"]);
        }

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

    public function read_messages($records_per_page, $from_record_num){

        $data = array(
            'records_per_page' => $records_per_page,
            'from_record_num' => $from_record_num
        );

        $header = array(
            "Content-type: application/json"
        );

        $params = http_build_query($data);
        $getUrl = READ_MESSAGES."?".$params;

        //Initializing curl to open a connection
        $ch = curl_init();

        //Setting the curl url
        curl_setopt($ch, CURLOPT_URL, $getUrl);

        //setting the method as get
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

        //adding headers
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        # sending cookies from file
        #curl_setopt($ch, CURLOPT_COOKIEFILE, array($_COOKIE["app-id"], $_COOKIE["token"]));
        #curl_setopt($ch, CURLOPT_COOKIE, "app-id=".$_COOKIE['app-id'].';token='.$_COOKIE['token']);
        if(isset($_COOKIE["app-id"]) and isset($_COOKIE["token"])){
            curl_setopt($ch, CURLOPT_COOKIEFILE, $_COOKIE["app-id"]);
            curl_setopt($ch, CURLOPT_COOKIEFILE, $_COOKIE["token"]);
        }

        //disabling ssl support
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        //finally executing the curl request
        $result = curl_exec($ch);

        //Now close the connection
        curl_close($ch);

        return $result;
    }

    public function read_one_message($id){

        $data = array(
            'id' => $id
        );

        $header = array(
            "Content-type: application/json"
        );

        $params = http_build_query($data);
        $getUrl = READ_ONE_MESSAGE."?".$params;

        //Initializing curl to open a connection
        $ch = curl_init();

        //Setting the curl url
        curl_setopt($ch, CURLOPT_URL, $getUrl);

        //setting the method as get
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

        //adding headers
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        # sending cookies from file
        #curl_setopt($ch, CURLOPT_COOKIEFILE, array($_COOKIE["app-id"], $_COOKIE["token"]));
        #curl_setopt($ch, CURLOPT_COOKIE, "app-id=".$_COOKIE['app-id'].';token='.$_COOKIE['token']);
        if(isset($_COOKIE["app-id"]) and isset($_COOKIE["token"])){
            curl_setopt($ch, CURLOPT_COOKIEFILE, $_COOKIE["app-id"]);
            curl_setopt($ch, CURLOPT_COOKIEFILE, $_COOKIE["token"]);
        }

        //disabling ssl support
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        //finally executing the curl request
        $result = curl_exec($ch);

        //Now close the connection
        curl_close($ch);

        return $result;
    }

    public function search_message($title, $records_per_page, $from_record_num){

        $data = array(
            'title' => $title,
            'records_per_page' => $records_per_page,
            'from_record_num' => $from_record_num
        );

        $header = array(
            "Content-type: application/json"
        );

        $params = http_build_query($data);
        $getUrl = SEARCH_MESSAGE."?".$params;

        //Initializing curl to open a connection
        $ch = curl_init();

        //Setting the curl url
        curl_setopt($ch, CURLOPT_URL, $getUrl);

        //setting the method as get
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

        //adding headers
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        # sending cookies from file
        #curl_setopt($ch, CURLOPT_COOKIEFILE, array($_COOKIE["app-id"], $_COOKIE["token"]));
        #curl_setopt($ch, CURLOPT_COOKIE, "app-id=".$_COOKIE['app-id'].';token='.$_COOKIE['token']);
        if(isset($_COOKIE["app-id"]) and isset($_COOKIE["token"])){
            curl_setopt($ch, CURLOPT_COOKIEFILE, $_COOKIE["app-id"]);
            curl_setopt($ch, CURLOPT_COOKIEFILE, $_COOKIE["token"]);
        }

        //disabling ssl support
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        //finally executing the curl request
        $result = curl_exec($ch);

        //Now close the connection
        curl_close($ch);

        return $result;
    }
}