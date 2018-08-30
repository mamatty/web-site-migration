<?php
// include database connection
include 'DbOperation.php';

$conn = new DbOperation();

if(isset($_POST["query"]))
{
    $output = '';
    $req = $conn->autocomplete_user($_POST["query"]);
    $res = json_decode($req,True);
    if(in_array('not-result',$res)){
        $output .= '<li>No Message</li>';
    }else{
        foreach ($res as $key => $value){
            $output .= '<li>'.$value.'</li>';
        }
    }

    $output .= '</ul>';
    echo $output;
}
