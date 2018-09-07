<?php
/**
 * Created by PhpStorm.
 * User: matte
 * Date: 27/08/2018
 * Time: 12:04
 */

require_once '../Config.php';


class DbOperation{

    public function autocomplete_user($surname){

        $data = array(
          'surname' => $surname
        );

        $header = array(
            "Content-type: application/json"
        );

        $params = http_build_query($data);
        $getUrl = AUTOCOMPLETE_USER."?".$params;

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

    public function autocomplete_exercise($exercise){

        $data = array(
            'exercise' => $exercise
        );

        $header = array(
            "Content-type: application/json"
        );

        $params = http_build_query($data);
        $getUrl = AUTOCOMPLETE_USER."?".$params;

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

    public function create_exercise($id, $name, $day, $series, $weight, $detail){

        $data = array(
            'id' => $id,
            'name' => $name,
            'day' => $day,
            'series' => $series,
            'weight' => $weight,
            'details' => $detail
        );

        $header = array(
            "Content-type: application/json"
        );

        //Initializing curl to open a connection
        $ch = curl_init();

        //Setting the curl url
        curl_setopt($ch, CURLOPT_URL, CREATE_EXERCISE);

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

    public function create_exercise_list($name, $description, $muscolar_zone, $url){

        $data = array(
            'name' => $name,
            'description' => $description,
            'muscular_zone' => $muscolar_zone,
            'url' => $url
        );

        $header = array(
            "Content-type: application/json"
        );

        //Initializing curl to open a connection
        $ch = curl_init();

        //Setting the curl url
        curl_setopt($ch, CURLOPT_URL, CREATE_EXERCISE_LIST);

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

    public function create_schedule($id, $name, $detail, $start_date, $end_date, $num_days, $objective){

        $data = array(
            'id' => $id,
            'name' => $name,
            'details' => $detail,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'num_days' => $num_days,
            'objective' => $objective
        );

        $header = array(
            "Content-type: application/json"
        );

        //Initializing curl to open a connection
        $ch = curl_init();

        //Setting the curl url
        curl_setopt($ch, CURLOPT_URL, CREATE_SCHEDULE);

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

    public function delete_schedule($id){

        $data = array(
            'id' => $id
        );

        $header = array(
            "Content-type: application/json"
        );

        //Initializing curl to open a connection
        $ch = curl_init();

        //Setting the curl url
        curl_setopt($ch, CURLOPT_URL, DELETE_SCHEDULE);

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

    public function delete_single_exercise($id){

        $data = array(
            'id' => $id
        );

        $header = array(
            "Content-type: application/json"
        );

        //Initializing curl to open a connection
        $ch = curl_init();

        //Setting the curl url
        curl_setopt($ch, CURLOPT_URL, DELETE_SINGLE_EXERCISE);

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

    public function delete_single_exercise_list($id){

        $data = array(
            'id' => $id
        );

        $header = array(
            "Content-type: application/json"
        );

        //Initializing curl to open a connection
        $ch = curl_init();

        //Setting the curl url
        curl_setopt($ch, CURLOPT_URL, DELETE_SINGLE_EXERCISE_LIST);

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

    public function delete_single_schedule($id){

        $data = array(
            'id' => $id
        );

        $header = array(
            "Content-type: application/json"
        );

        //Initializing curl to open a connection
        $ch = curl_init();

        //Setting the curl url
        curl_setopt($ch, CURLOPT_URL, DELETE_SINGLE_EXERCISE_SCHEDULE);

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

    public function manage_exercises($id, $records_per_page, $from_record_num){

        $data = array(
            'id' => $id,
            'records_per_page' => $records_per_page,
            'from_record_num' => $from_record_num
        );

        $header = array(
            "Content-type: application/json"
        );

        $params = http_build_query($data);
        $getUrl = MANAGE_EXERCISES."?".$params;

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

    public function manage_schedules($id, $records_per_page, $from_record_num){

        $data = array(
            'id' => $id,
            'records_per_page' => $records_per_page,
            'from_record_num' => $from_record_num
        );

        $header = array(
            "Content-type: application/json"
        );

        $params = http_build_query($data);
        $getUrl = MANAGE_SCHEDULES."?".$params;

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

    public function manage_users($id, $records_per_page, $from_record_num){

        $data = array(
            'id' => $id,
            'records_per_page' => $records_per_page,
            'from_record_num' => $from_record_num
        );

        $header = array(
            "Content-type: application/json"
        );

        $params = http_build_query($data);
        $getUrl = MANAGE_USERS."?".$params;

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

    public function account_profile(){

        $header = array(
            "Content-type: application/json"
        );

        //Initializing curl to open a connection
        $ch = curl_init();

        //Setting the curl url
        curl_setopt($ch, CURLOPT_URL, PROFILE);

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

    public function read_exercises($records_per_page, $from_record_num){

        $data = array(
            'records_per_page' => $records_per_page,
            'from_record_num' => $from_record_num
        );

        $header = array(
            "Content-type: application/json"
        );

        $params = http_build_query($data);
        $getUrl = READ_EXERCISES."?".$params;

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

    public function read_one_exercise($id){

        $data = array(
            'id' => $id
        );

        $header = array(
            "Content-type: application/json"
        );

        $params = http_build_query($data);
        $getUrl = READ_ONE_EXERCISE."?".$params;

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

    public function search_exercise_list($name, $records_per_page, $from_record_num){

        $data = array(
            'name' => $name,
            'records_per_page' => $records_per_page,
            'from_record_num' => $from_record_num
        );

        $header = array(
            "Content-type: application/json"
        );

        $params = http_build_query($data);
        $getUrl = SEARCH_EXERCISE_LIST."?".$params;

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

    public function search_user($surname, $records_per_page, $from_record_num){

        $data = array(
            'name' => $surname,
            'records_per_page' => $records_per_page,
            'from_record_num' => $from_record_num
        );

        $header = array(
            "Content-type: application/json"
        );

        $params = http_build_query($data);
        $getUrl = SEARCH_USER."?".$params;

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

    #da testare! L'invio specifico di un solo parametro invece di un array non so se è consentito
    public function look_updated_exercise($id){

        $data = array(
            'id' => $id
        );

        $header = array(
            "Content-type: application/json"
        );

        $params = http_build_query($data);
        $getUrl = LOOK_UPDATED_EXERCISE."?".$params;

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

    public function look_updated_exercise_list($id){

        $data = array(
            'id' => $id
        );

        $header = array(
            "Content-type: application/json"
        );

        $params = http_build_query($data);
        $getUrl = LOOK_UPDATED_EXERCISE_LIST."?".$params;

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

    public function look_updated_user($id){

        $data = array(
            'id' => $id
        );

        $header = array(
            "Content-type: application/json"
        );

        $params = http_build_query($data);
        $getUrl = LOOK_UPDATED_USER."?".$params;

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

    public function update_exercise($id, $name, $day, $ripetitions, $weight, $detail){

        $data = array(
            'id' => $id,
            'name' => $name,
            'day' => $day,
            'repetitions' => $ripetitions,
            'weight' => $weight,
            'details' => $detail
        );

        $header = array(
            "Content-type: application/json"
        );

        //Initializing curl to open a connection
        $ch = curl_init();

        //Setting the curl url
        curl_setopt($ch, CURLOPT_URL, UPDATE_EXERCISE);

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

    public function update_exercise_list($id, $name, $description, $muscolar_zone, $url){

        $data = array(
            'id' => $id,
            'name' => $name,
            'description' => $description,
            'muscular_zone' => $muscolar_zone,
            'url' => $url
        );

        $header = array(
            "Content-type: application/json"
        );

        //Initializing curl to open a connection
        $ch = curl_init();

        //Setting the curl url
        curl_setopt($ch, CURLOPT_URL, UPDATE_EXERCISE_LIST);

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

}