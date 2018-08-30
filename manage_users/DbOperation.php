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

        $sFile = file_get_contents(AUTOCOMPLETE_USER, False, $context);

        return $sFile;
    }

    public function create_user($name, $surname, $email, $password, $address,$birth_date, $phone, $image, $subscription, $end_subscription){

        $data = array(
            'name' => $name,
            'surname' => $surname,
            'email' => $email,
            'password' => $password,
            'address' => $address,
            'birth_date' => $birth_date,
            'phone' => $phone,
            'image' => $image,
            'subscription' => $subscription,
            'end_subscription' => $end_subscription
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

        $sFile = file_get_contents(CREATE_USER, False, $context);

        return $sFile;
    }

    public function delete_user($id){

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

        $sFile = file_get_contents(DELETE_USER, False, $context);

        return $sFile;
    }

    public function manage_users($records_per_page, $from_record_num){

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

        $sFile = file_get_contents(MANAGE_USERS, False, $context);

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

        $sFile = file_get_contents(PROFILE, False, $context);

        return $sFile;
    }

    public function read_one_user($id){

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

        $sFile = file_get_contents(READ_ONE_USER, False, $context);

        return $sFile;
    }

    public function search_user($surname, $records_per_page, $from_record_num){

        $data = array(
            'surname' => $surname,
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

        $sFile = file_get_contents(SEARCH_USER, False, $context);

        return $sFile;
    }

    #da testare! L'invio specifico di un solo parametro invece di un array non so se è consentito
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

        $sFile = file_get_contents(LOOK_UPDATED_USER, False, $context);

        return $sFile;
    }

    public function update_user($id, $name, $surname, $email, $birth_date, $address, $id_subscription, $end_subscription){

        $data = array(
            'id' => $id,
            'name' => $name,
            'surname' => $surname,
            'email' => $email,
            'birth_date' => $birth_date,
            'address' => $address,
            'id_subscription' => $id_subscription,
            'end_subscription' => $end_subscription
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

        $sFile = file_get_contents(UPDATE_USER, False, $context);

        return $sFile;
    }
}