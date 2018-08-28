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
        require_once 'send_messages/DbOperation.php';
        require_once 'Firebase.php';
        require_once 'Push.php';

        $response = array();

        $push = new Push(
                $title,
                $body
        );

        if(!empty($title)){

            if($destination =='topic') {
                //getting the push from push object
                $mPushNotification = $push->getPush();

                //creating firebase class object
                $firebase = new Firebase();

                //sending push notification and displaying result
                echo $firebase->send_topic($argument, $mPushNotification);
            }
            elseif($destination =='all') {
                //getting the push from push object
                $mPushNotification = $push->getPush();

                //creating firebase class object
                $firebase = new Firebase();
                $conn = new DbOperation();

                $tokens_firebase = array();
                $mess = $conn->getAllTokens();
                $array = json_decode($mess, True);

                foreach ($array as $arr){
                    array_push($tokens_firebase,$arr['tokens']);
                }

                //sending push notification and displaying result
                echo $firebase->send($tokens_firebase, $mPushNotification);
            }
            else{
                $response['error']=true;
                $response['message']='Parameters missing or invalid request!';
                return json_encode($response);
            }
        }else{
            $response['error']=true;
            $response['message']='Title missing!';
            return json_encode($response);
        }
    }
}
