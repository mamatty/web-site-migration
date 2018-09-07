<?php
/**
 * Created by PhpStorm.
 * User: matte
 * Date: 20/03/2018
 * Time: 12:16
 */


class Firebase {

    public function send_all($title, $body) {
        $fields = array(
            "data" => array(
                "title"=> $title,
                "body"=> $body
            ),
            "notification" => array(
                "title"=> $title,
                "body"=> $body
            ),
            'to' => '/topics/all'
        );

        return $this->sendPushNotification($fields);
    }

    public function send_topic($argument, $title, $body) {
        $fields = array(
            "data" => array(
                "title"=> $title,
                "body"=> $body
            ),
            "notification" => array(
                "title"=> $title,
                "body"=> $body
            ),
            'to' => '/topics/'.$argument
        );

        return $this->sendPushNotification($fields);
    }

    /*
    * This function will make the actuall curl request to firebase server
    * and then the message is sent
    */
    private function sendPushNotification($fields) {

        //importing the constant files
        require_once 'Config.php';
        $response = array();

        //firebase server url to send the curl request
        $url = 'https://fcm.googleapis.com/fcm/send';

        //building headers for the request
        $headers = array(
            'Authorization: key=' . FIREBASE_API_KEY,
            'Content-Type: application/json'
        );

        //Initializing curl to open a connection
        $ch = curl_init();

        //Setting the curl url
        curl_setopt($ch, CURLOPT_URL, $url);

        //setting the method as post
        curl_setopt($ch, CURLOPT_POST, true);

        //adding headers
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //disabling ssl support
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        //adding the fields in json format
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        //finally executing the curl request
        $result = curl_exec($ch);
        if ($result === FALSE) {
            $response['error']=true;
            $response['message']='Curl failed: ' . curl_error($ch);
            //die('Curl failed: ' . curl_error($ch));
        }else{
            $response['error']=false;
            $response['message']='sent';
        }

        //Now close the connection
        curl_close($ch);

        //and return the result
        return $response;
    }
}