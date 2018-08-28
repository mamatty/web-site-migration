<?php
/**
 * Created by PhpStorm.
 * User: matte
 * Date: 27/08/2018
 * Time: 12:04
 */

include_once dirname(__FILE__) . '../Config.php';


class DbOperation{

    public function autocomplete_user($surname){

        $data = array(
          'surname' => $surname
        );

        $options = array(
            'http' => array(
                'header'  => array(
                    "Content-type: application/x-www-form-urlencoded",
                    "Authorization: "
                ),
                'method' => 'GET',
                'content' => http_build_query($data)
            )
        );
        $context = stream_context_create($options);

        $sFile = file_get_contents(GENERALI, False, $context);

        return $sFile;
    }

    public function autocomplete_exercise($exercise){

        $data = array(
            'exercise' => $exercise
        );

        $options = array(
            'http' => array(
                'header'  => array(
                    "Content-type: application/x-www-form-urlencoded",
                    "Authorization: "
                ),
                'method' => 'GET',
                'content' => http_build_query($data)
            )
        );
        $context = stream_context_create($options);

        $sFile = file_get_contents(GENERALI, False, $context);

        return $sFile;
    }

    public function create_exercise($id, $name, $day, $series, $weight, $detail){

        $data = array(
            'id' => $id,
            'name' => $name,
            'day' => $day,
            'series' => $series,
            'weight' => $weight,
            'detail' => $detail
        );

        $options = array(
            'http' => array(
                'header'  => array(
                    "Content-type: application/x-www-form-urlencoded",
                    "Authorization: "
                ),
                'method' => 'POST',
                'content' => http_build_query($data)
            )
        );
        $context = stream_context_create($options);

        $sFile = file_get_contents(GENERALI, False, $context);

        return $sFile;
    }

    public function create_exercise_list($name, $description, $muscolar_zone, $url){

        $data = array(
            'name' => $name,
            'description' => $description,
            'muscolar_zone' => $muscolar_zone,
            'url' => $url
        );

        $options = array(
            'http' => array(
                'header'  => array(
                    "Content-type: application/x-www-form-urlencoded",
                    "Authorization: "
                ),
                'method' => 'POST',
                'content' => http_build_query($data)
            )
        );
        $context = stream_context_create($options);

        $sFile = file_get_contents(GENERALI, False, $context);

        return $sFile;
    }

    public function create_schedule($id, $name, $detail, $start_date, $end_date, $num_days, $objective){

        $data = array(
            'id' => $id,
            'name' => $name,
            'detail' => $detail,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'num_days' => $num_days,
            'objective' => $objective
        );

        $options = array(
            'http' => array(
                'header'  => array(
                    "Content-type: application/x-www-form-urlencoded",
                    "Authorization: "
                ),
                'method' => 'POST',
                'content' => http_build_query($data)
            )
        );
        $context = stream_context_create($options);

        $sFile = file_get_contents(GENERALI, False, $context);

        return $sFile;
    }

    public function delete_schedule($id){

        $data = array(
            'id' => $id
        );

        $options = array(
            'http' => array(
                'header'  => array(
                    "Content-type: application/x-www-form-urlencoded",
                    "Authorization: "
                ),
                'method' => 'POST',
                'content' => http_build_query($data)
            )
        );
        $context = stream_context_create($options);

        $sFile = file_get_contents(GENERALI, False, $context);

        return $sFile;
    }

    public function delete_single_exercise($id){

        $data = array(
            'id' => $id
        );

        $options = array(
            'http' => array(
                'header'  => array(
                    "Content-type: application/x-www-form-urlencoded",
                    "Authorization: "
                ),
                'method' => 'POST',
                'content' => http_build_query($data)
            )
        );
        $context = stream_context_create($options);

        $sFile = file_get_contents(GENERALI, False, $context);

        return $sFile;
    }

    public function delete_single_exercise_list($id){

        $data = array(
            'id' => $id
        );

        $options = array(
            'http' => array(
                'header'  => array(
                    "Content-type: application/x-www-form-urlencoded",
                    "Authorization: "
                ),
                'method' => 'POST',
                'content' => http_build_query($data)
            )
        );
        $context = stream_context_create($options);

        $sFile = file_get_contents(GENERALI, False, $context);

        return $sFile;
    }

    public function delete_single_schedule($id){

        $data = array(
            'id' => $id
        );

        $options = array(
            'http' => array(
                'header'  => array(
                    "Content-type: application/x-www-form-urlencoded",
                    "Authorization: "
                ),
                'method' => 'POST',
                'content' => http_build_query($data)
            )
        );
        $context = stream_context_create($options);

        $sFile = file_get_contents(GENERALI, False, $context);

        return $sFile;
    }

    public function manage_exercises($id, $records_per_page, $from_record_num){

        $data = array(
            'id' => $id,
            'records_per_page' => $records_per_page,
            'from_record_num' => $from_record_num
        );

        $options = array(
            'http' => array(
                'header'  => array(
                    "Content-type: application/x-www-form-urlencoded",
                    "Authorization: "
                ),
                'method' => 'GET',
                'content' => http_build_query($data)
            )
        );
        $context = stream_context_create($options);

        $sFile = file_get_contents(GENERALI, False, $context);

        return $sFile;
    }

    public function manage_schedules($id, $records_per_page, $from_record_num){

        $data = array(
            'id' => $id,
            'records_per_page' => $records_per_page,
            'from_record_num' => $from_record_num
        );

        $options = array(
            'http' => array(
                'header'  => array(
                    "Content-type: application/x-www-form-urlencoded",
                    "Authorization: "
                ),
                'method' => 'GET',
                'content' => http_build_query($data)
            )
        );
        $context = stream_context_create($options);

        $sFile = file_get_contents(GENERALI, False, $context);

        return $sFile;
    }

    public function manage_users($id, $records_per_page, $from_record_num){

        $data = array(
            'id' => $id,
            'records_per_page' => $records_per_page,
            'from_record_num' => $from_record_num
        );

        $options = array(
            'http' => array(
                'header'  => array(
                    "Content-type: application/x-www-form-urlencoded",
                    "Authorization: "
                ),
                'method' => 'GET',
                'content' => http_build_query($data)
            )
        );
        $context = stream_context_create($options);

        $sFile = file_get_contents(GENERALI, False, $context);

        return $sFile;
    }

    public function account_profile(){

        $options = array(
            'http' => array(
                'header'  => array(
                    "Content-type: application/x-www-form-urlencoded",
                    "Authorization: "
                ),
                'method' => 'GET'
            )
        );
        $context = stream_context_create($options);

        $sFile = file_get_contents(GENERALI, False, $context);

        return $sFile;
    }

    public function read_exercises($records_per_page, $from_record_num){

        $data = array(
            'records_per_page' => $records_per_page,
            'from_record_num' => $from_record_num
        );

        $options = array(
            'http' => array(
                'header'  => array(
                    "Content-type: application/x-www-form-urlencoded",
                    "Authorization: "
                ),
                'method' => 'GET',
                'content' => http_build_query($data)
            )
        );
        $context = stream_context_create($options);

        $sFile = file_get_contents(GENERALI, False, $context);

        return $sFile;
    }

    public function read_one_exercise($id){

        $data = array(
            'id' => $id
        );

        $options = array(
            'http' => array(
                'header'  => array(
                    "Content-type: application/x-www-form-urlencoded",
                    "Authorization: "
                ),
                'method' => 'GET',
                'content' => http_build_query($data)
            )
        );
        $context = stream_context_create($options);

        $sFile = file_get_contents(GENERALI, False, $context);

        return $sFile;
    }

    public function search_exercise_list($name, $records_per_page, $from_record_num){

        $data = array(
            'name' => $name,
            'records_per_page' => $records_per_page,
            'from_record_num' => $from_record_num
        );

        $options = array(
            'http' => array(
                'header'  => array(
                    "Content-type: application/x-www-form-urlencoded",
                    "Authorization: "
                ),
                'method' => 'GET',
                'content' => http_build_query($data)
            )
        );
        $context = stream_context_create($options);

        $sFile = file_get_contents(GENERALI, False, $context);

        return $sFile;
    }

    public function search_user($surname, $records_per_page, $from_record_num){

        $data = array(
            'name' => $surname,
            'records_per_page' => $records_per_page,
            'from_record_num' => $from_record_num
        );

        $options = array(
            'http' => array(
                'header'  => array(
                    "Content-type: application/x-www-form-urlencoded",
                    "Authorization: "
                ),
                'method' => 'GET',
                'content' => http_build_query($data)
            )
        );
        $context = stream_context_create($options);

        $sFile = file_get_contents(GENERALI, False, $context);

        return $sFile;
    }

    #da testare! L'invio specifico di un solo parametro invece di un array non so se è consentito
    public function look_updated_exercise($id){

        $options = array(
            'http' => array(
                'header'  => array(
                    "Content-type: application/x-www-form-urlencoded",
                    "Authorization: "
                ),
                'method' => 'GET',
                'content' => http_build_query($id)
            )
        );
        $context = stream_context_create($options);

        $sFile = file_get_contents(GENERALI, False, $context);

        return $sFile;
    }

    public function look_updated_exercise_list($id){

        $options = array(
            'http' => array(
                'header'  => array(
                    "Content-type: application/x-www-form-urlencoded",
                    "Authorization: "
                ),
                'method' => 'GET',
                'content' => http_build_query($id)
            )
        );
        $context = stream_context_create($options);

        $sFile = file_get_contents(GENERALI, False, $context);

        return $sFile;
    }

    public function look_updated_user($id){

        $options = array(
            'http' => array(
                'header'  => array(
                    "Content-type: application/x-www-form-urlencoded",
                    "Authorization: "
                ),
                'method' => 'GET',
                'content' => http_build_query($id)
            )
        );
        $context = stream_context_create($options);

        $sFile = file_get_contents(GENERALI, False, $context);

        return $sFile;
    }

    public function update_exercise($id, $name, $day, $ripetitions, $weight, $detail){

        $data = array(
            'id' => $id,
            'name' => $name,
            'day' => $day,
            'ripetitions' => $ripetitions,
            'weight' => $weight,
            'detail' => $detail
        );

        $options = array(
            'http' => array(
                'header'  => array(
                    "Content-type: application/x-www-form-urlencoded",
                    "Authorization: "
                ),
                'method' => 'GET',
                'content' => http_build_query($data)
            )
        );
        $context = stream_context_create($options);

        $sFile = file_get_contents(GENERALI, False, $context);

        return $sFile;
    }

    public function update_exercise_list($id, $name, $description, $muscolar_zone, $url){

        $data = array(
            'id' => $id,
            'name' => $name,
            'description' => $description,
            'muscolar_zone' => $muscolar_zone,
            'url' => $url
        );

        $options = array(
            'http' => array(
                'header'  => array(
                    "Content-type: application/x-www-form-urlencoded",
                    "Authorization: "
                ),
                'method' => 'GET',
                'content' => http_build_query($data)
            )
        );
        $context = stream_context_create($options);

        $sFile = file_get_contents(GENERALI, False, $context);

        return $sFile;
    }

}