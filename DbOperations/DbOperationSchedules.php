<?php
/**
 * Created by PhpStorm.
 * User: matte
 * Date: 27/08/2018
 * Time: 12:04
 */

require_once 'Config.php';

class DbOperationSchedules{

    public function autocomplete_user($surname){

        $data = http_build_query(array(
            'query' => $surname
            )
        );

        $options = array(
            'http' => array(
                'header'  => array(
                    "Content-type: application/x-www-form-urlencoded",
                    "Cookie: app-id=".$_COOKIE['app-id']."; token=".$_COOKIE['token']
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

    public function autocomplete_exercise($exercise){

        $data = http_build_query(
            array(
            'query' => $exercise
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
        @$result = file_get_contents(AUTOCOMPLETE_EXERCISE.'?'.$data, false, $context);

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

    public function create_exercise($id, $name, $day, $series, $weight, $detail){

        $data = array(
            'id' => $id,
            'name' => $name,
            'day' => $day,
            'series' => $series,
            'weight' => $weight,
            'details' => $detail
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
        @$result = file_get_contents(CREATE_EXERCISE, false, $context);

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

    public function create_exercise_list($name, $description, $muscolar_zone, $url){

        $data = array(
            'name' => $name,
            'description' => $description,
            'muscular_zone' => $muscolar_zone,
            'url' => $url
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

        @$result = file_get_contents(CREATE_EXERCISE_LIST, false, $context);

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
        @$result = file_get_contents(CREATE_SCHEDULE, false, $context);

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

    public function delete_schedule($id){

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
        @$result = file_get_contents(DELETE_SCHEDULES, false, $context);

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

    public function delete_single_exercise($id){

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
        @$result = file_get_contents(DELETE_SINGLE_EXERCISE, false, $context);

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

    public function delete_single_exercise_list($id){

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
        @$result = file_get_contents(DELETE_SINGLE_EXERCISE_LIST, false, $context);

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

    public function delete_single_schedule($id){

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
        @$result = file_get_contents(DELETE_SINGLE_EXERCISE_SCHEDULE, false, $context);

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

    public function manage_exercises($id, $records_per_page, $from_record_num){

        $data = http_build_query(
            array(
            'id' => $id,
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
        $result = file_get_contents(MANAGE_EXERCISES.'?'.$data, false, $context);

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

    public function manage_schedules($id, $records_per_page, $from_record_num){

        $data = http_build_query(
            array(
            'id' => $id,
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
        @$result = file_get_contents(MANAGE_SCHEDULES.'?'.$data, false, $context);

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

    public function account_profile($id){

        $data= http_build_query(
            array(
                'id'=>$id
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
        @$result = file_get_contents(PROFILE.'?'.$data, false, $context);

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

    public function read_exercises($records_per_page, $from_record_num){

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
        @$result = file_get_contents(READ_EXERCISES.'?'.$data, false, $context);

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

    public function read_one_exercise($id){

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
        @$result = file_get_contents(READ_ONE_EXERCISE.'?'.$data, false, $context);

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

    public function search_exercise_list($name){

        $data = http_build_query(
            array(
            'search' => $name
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
        @$result = file_get_contents(SEARCH_EXERCISE_LIST.'?'.$data, false, $context);

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
            'name' => $surname,
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

    public function look_updated_exercise($id){

        $data = http_build_query(
            array(
            'id_list' => $id
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
        @$result = file_get_contents(LOOK_UPDATED_EXERCISE.'?'.$data, false, $context);

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

    public function look_updated_exercise_list($id){

        $data = http_build_query(
            array(
            'id_exercise' => $id
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
        @$result = file_get_contents(LOOK_UPDATED_EXERCISE_LIST.'?'.$data, false, $context);

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

    public function update_exercise($id, $day, $ripetitions, $weight, $detail){

        $data = array(
            'id' => $id,
            'day' => $day,
            'repetitions' => $ripetitions,
            'weight' => $weight,
            'details' => $detail
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
        @$result = file_get_contents(UPDATE_EXERCISE, false, $context);

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

    public function update_exercise_list($id, $name, $description, $muscolar_zone, $url){

        $data = array(
            'id' => $id,
            'name' => $name,
            'description' => $description,
            'muscular_zone' => $muscolar_zone,
            'url' => $url
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
        @$result = file_get_contents(UPDATE_EXERCISE_LIST, false, $context);

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