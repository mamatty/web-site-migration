<?php
// include database connection
include 'DbOperation.php';

$conn = new DbOperation();

if(isset($_POST["query"]))
{
    $output = '';
    $req = $conn->autocomplete_message($_POST["query"]);
    $res = json_decode($req,True);

    $output .= '<li>'.$res['title'].'</li>';

    $output .= '</ul>';
    echo $output;
}
