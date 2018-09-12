<?php
// include database connection
include '../DbOperations/DbOperationMessages.php';

$conn = new DbOperationMessages();

if(isset($_POST["query"]))
{
    $output = '';
    $req = $conn->autocomplete_message($_POST["query"]);
    $res = json_decode($req,True);

    if(in_array('not-found',$res)){
        $output .= '<li>No User</li>';
    }else {
        for ($i = 0, $l = count($res['message']); $i < $l; ++$i) {
            $output .= '<li>' . $res['message'][$i][0] . '</li>';
        }
    }
    $output .= '</ul>';
    echo $output;
}
