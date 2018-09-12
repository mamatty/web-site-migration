<?php
include '../DbOperations/DbOperationSchedules.php';

$conn = new DbOperationSchedules();

if(isset($_POST["query"]))
{
    $output = '';
    $req = $conn->autocomplete_exercise($_POST["query"]);
    $res = json_decode($req,True);
    $output = '<ul class="list-unstyled">';

    if(empty($res['exercise'])){
        $output .= '<li>No Exercise</li>';
    }else {
        for ($i = 0, $l = count($res['exercise']); $i < $l; ++$i) {
            $output .= '<li data-name="'.$res['exercise'][$i][0].'" value="'.$res['exercise'][$i][0].'">'.$res['exercise'][$i][0].'</a></li>';
        }
    }
    $output .= '</ul>';
    echo $output;
}

?>