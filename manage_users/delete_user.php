<?php ob_start();
/**
 * Created by PhpStorm.
 * User: matte
 * Date: 05/04/2018
 * Time: 10:44
 */
try{
    //include database connection
    include 'DbOperation.php';
    $conn = new DbOperation();

    // isset() is a PHP function used to verify if a value is there or not
    $id=isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');

    $req = $conn->delete_user($id);
    $res = json_decode($req, True);
    if(in_array('successful',$res)){
        header('Location: dashboard.php?action=deleted');
    }else{
        die('Unable to delete record.');
    }

}

    // show error
catch (mysqli_sql_exception $e) {
    throw $e;
}
?>