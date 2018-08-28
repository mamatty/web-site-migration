<?php
include 'DbOperation.php';

$conn = new DbOperation();

if(isset($_POST["query"]))
{
    $output = '';
    $req = $conn->autocomplete_exercise($_POST["query"]);
    $res = json_decode($req,True);

    $output .= '<li>'.$res['exercise'].'</li>';

    $output .= '</ul>';
    echo $output;
}

?>