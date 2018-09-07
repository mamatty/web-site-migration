<?php
/**
 * Created by PhpStorm.
 * User: matte
 * Date: 20/03/2018
 * Time: 12:37
 */

class SendMultiplePush{

    public function sendNotification($title, $body, $destination, $argument){

        //importing required files
        require_once 'Firebase.php';

        $response = array();

        if(!empty($title)){

            if($destination =='topic') {

                //creating firebase class object
                $firebase = new Firebase();

                //sending push notification and displaying result
                $firebase->send_topic($argument, $title, $body);
            }
            elseif($destination =='all') {

                //creating firebase class object
                $firebase = new Firebase();

                //sending push notification and displaying result
                $firebase->send_all($title, $body);
            }
            else{
                $response['error']=true;
                $response['message']='Parameters missing or invalid request!';
            }
        }else{
            $response['error']=true;
            $response['message']='Title missing!';
        }
        return json_encode($response);
    }
}
