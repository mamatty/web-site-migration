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

$var_hmd = array();

$humidity = array();

for ($i = 0, $l = count($data['feeds']); $i < $l; ++$i) {
    if(!is_nan($data['feeds'][$i]['field2'])){
        $tmp = explode('T',$data['feeds'][$i]['created_at']);

        array_push($var_hmd,$data['feeds'][$i]['field2'], $tmp[0]);

        array_push($humidity,$var_hmd);

        unset($var_hmd);

        $var_hmd = array();
    }
}

$date = new DateTime();
$date->modify('-2 hour');
$datatime = $date->format('Y-m-d');

for ($i = 0, $l = count($humidity); $i < $l; ++$i) {
    if($humidity[$i][1] == $datatime ){
        continue;
    }
    else{
        unset($humidity[$i]);
    }
}
$humidity = array_values($humidity);
$td_humidity = 0;

foreach ($humidity as $hm){
    $td_humidity += $hm[0];
}

if(count($humidity) != 0){
    $mean_humidity = $td_humidity/count($humidity);
}else{
    $mean_humidity = 0.0;
}

echo "The average humidity today is: ".number_format((float)$mean_humidity, 2, '.', '')." g/m³";