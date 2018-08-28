<?php
/**
 * Created by PhpStorm.
 * User: matte
 * Date: 20/03/2018
 * Time: 12:16
 */
class Push {
    //notification title
    private $title;

    //notification message
    private $message;

    //initializing values in this constructor
    function __construct($title, $message) {
        $this->title = $title;
        $this->message = $message;
    }

    //getting the push notification
    public function getPush() {
        $res = array();
        $res['data']['title'] = $this->title;
        $res['data']['message'] = $this->message;
        return $res;
    }

}