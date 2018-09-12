<?php
// include database connection
include '../DbOperations/DbOperationUsers.php';

if(isset($_POST["query"]))
{
    $output = '';
    $conn = new DbOperationUsers();
    $req = $conn->autocomplete_user($_POST["query"]);
    $res = json_decode($req,True);
    $output = '<ul class="list-unstyled">';

    if(empty($res['surname'])){
        $output .= '<li>No User</li>';
    }else {
        for ($i = 0, $l = count($res['surname']); $i < $l; ++$i) {
            $output .= '<li data-surname="'.$res['surname'][$i][0].'">'.$res['surname'][$i][0].'</a></li>';
        }
    }
    $output .= '</ul>';
    echo $output;

}
