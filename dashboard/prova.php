<?php
/**
 * Created by PhpStorm.
 * User: matte
 * Date: 29/08/2018
 * Time: 16:54
 */
require_once "DbOperation.php";
$conn = new DbOperation();
$req = $conn->get_data();
$data = json_decode($req,True);
$var_tmp = array();
$var_hmd = array();
$temp = array();
$humidity = array();

for ($i = 0, $l = count($data['feeds']); $i < $l; ++$i) {
    $tmp = explode('T',$data['feeds'][$i]['created_at']);

    array_push($var_tmp,$data['feeds'][$i]['field1'], $tmp[0]);
    array_push($var_hmd,$data['feeds'][$i]['field2'], $tmp[0]);

    array_push($temp,$var_tmp);
    array_push($humidity,$var_hmd);

    unset($var_tmp);
    unset($var_hmd);

    $var_tmp = array();
    $var_hmd = array();
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
for ($i = 0, $l = count($humidity); $i < $l; ++$i) {
    if($humidity[$i][1] == $datatime ){
        continue;
    }
    else{
        unset($humidity[$i]);
    }
}
$humidity = array_values($humidity);
$temp = array_values($temp);
$td_humidity = 0;
$td_temp = 0;

foreach ($temp as $tm){
    $td_temp += $tm[0];
}
foreach ($humidity as $hm){
    $td_humidity += $hm[0];
}

$mean_humidity = $td_humidity/count($humidity);
$mean_temp = $td_temp/count($temp);
