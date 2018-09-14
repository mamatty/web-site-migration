<?php
/**
 * Created by PhpStorm.
 * User: matte
 * Date: 13/09/2018
 * Time: 18:26
 */
require_once "../DbOperations/DbOperationDashboard.php";
$conn = new DbOperationDashboard();
$req = $conn->get_data();
$data = json_decode($req,True);

$var_tmp = array();

$temp = array();


for ($i = 0, $l = count($data['feeds']); $i < $l; ++$i) {
    $tmp = explode('T',$data['feeds'][$i]['created_at']);

    array_push($var_tmp,$data['feeds'][$i]['field1'], $tmp[0]);

    array_push($temp,$var_tmp);

    unset($var_tmp);

    $var_tmp = array();
}

$date = new DateTime();
$date->modify('-2 hour');
$datatime = $date->format('Y-m-d');

for ($i = 0, $l = count($temp); $i < $l; ++$i) {
    if($temp[$i][1] == $datatime ){
        continue;
    }else{
        unset($temp[$i]);
    }
}

$temp = array_values($temp);

$td_temp = 0;

foreach ($temp as $tm){
    $td_temp += $tm[0];
}

if(count($temp) != 0){
    $mean_temp = $td_temp/count($temp);
}else{
    $mean_temp = 0.0;
}

echo "The average temperature today is: ".number_format((float)$mean_temp, 2, '.', '')." °C \n";
