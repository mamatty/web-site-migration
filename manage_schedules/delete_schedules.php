<?php ob_start();
/**
 * Created by PhpStorm.
 * User: matte
 * Date: 05/04/2018
 * Time: 10:44
 */
try{
    //include database connection
    include '../DbOperations/DbOperationSchedules.php';
    $conn = new DbOperationSchedules();

    // isset() is a PHP function used to verify if a value is there or not
    $id=isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');
    $req = $conn->delete_schedule($id);
    $del = json_decode($req,True);
    // Execute the query
    if(in_array('successful',$del)){
        header('Location: manage_users.php?action=deleted');
    }else{
        header('Location: manage_users.php?action=not-deleted');
    }

}

    // show error
catch (mysqli_sql_exception $e) {
    throw $e;
}
?>