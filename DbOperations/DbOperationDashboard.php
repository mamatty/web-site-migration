<?php
/**
 * Created by PhpStorm.
 * User: matte
 * Date: 27/08/2018
 * Time: 12:04
 */

require_once 'Config.php';


class DbOperationDashboard{

    public function get_data(){

        $data = array(
            'data' => 'api_key=0FUJJDZFVYDH2PLB'
        );
        $options = array(
            'http' => array(
                'header'  => array(
                    "Content-type: application/x-www-form-urlencoded",
                    'host: api.thingspeak.com',
                    'Connection: close'
                ),
                'method' => 'GET',
                'content' => http_build_query($data)
            )
        );
        $context = stream_context_create($options);

        $sFile = file_get_contents(THINGSPEAK, False, $context);

        return $sFile;
    }

}