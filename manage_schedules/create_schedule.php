<?php
/* Displays user information and some useful messages */
session_start();

// Check if user is logged in using the session variable
if ( isset($_COOKIE['logged_in']) and $_COOKIE['logged_in'] == true) {
    // Makes it easier to read
    $first_name = $_COOKIE['first_name'];
    $last_name = $_COOKIE['last_name'];
?>

<!DOCTYPE html>
    <html lang="en">
    <head>
    <title>Fitness Club</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" media="screen" href="css/reset.css">
    <link rel="stylesheet" type="text/css" media="screen" href="css/style.css">
    <link rel="stylesheet" type="text/css" media="screen" href="css/grid_12.css">

    <script src="js/jquery-1.7.min.js"></script>
    <script src="js/jquery.easing.1.3.js"></script>
    <script src="js/tms-0.3.js"></script>
    <script src="js/tms_presets.js"></script>
    <script src="js/cufon-yui.js"></script>
    <script src="js/Asap_400.font.js"></script>
    <script src="js/Coolvetica_400.font.js"></script>
    <script src="js/Kozuka_M_500.font.js"></script>
    <script src="js/cufon-replace.js"></script>
    <script src="js/FF-cash.js"></script>

    <!--[if lt IE 9]>
    <script src="js/html5.js"></script>
    <link rel="stylesheet" type="text/css" media="screen" href="css/ie.css">
    <![endif]-->


        <style>
            .button {
                background-color: #518daf;
                border: none;
                color: white;
                padding: 7px 15px;
                text-align: center;
                text-decoration: none;
                display: inline-block;
                font-size: 16px;
                margin: 4px 2px;
                cursor: pointer;
            }
        </style>

    <!-- Latest compiled and minified Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>

    </style>

</head>
<body>

<!--==============================header=================================-->
<!-- container -->
<div class="main">
    <div class="bg-img"></div>
    <header>
        <h1><a href="../fitness-club/index.php">Fitness <strong>Club.</strong></a></h1>
        <nav>
            <div class="social-icons"> <a href="#" class="icon-2"></a> <a href="#" class="icon-1"></a> </div>
            <ul class="menu">
                <li><a href="../fitness-club/index.php">Home</a></li>
                <li><a href="../manage_users/manage_users.php">Manage Users</a></li>
                <li class="current"><a href="manage_users.php">Manage Schedules</a></li>
                <li><a href="../send_messages/read_messages.php">Send Messages</a></li>
                <li><a href="../dashboard/dashboard.php">Dashboard</a></li>
            </ul>
        </nav>
    </header>
    <?php
/**
 * Created by PhpStorm.
 * User: matte
 * Date: 29/03/2018
 * Time: 11:37
 */

    $id=isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');

    if($_POST){
        try{
            // include database connection
            include '../DbOperations/DbOperationSchedules.php';

            $conn = new DbOperationSchedules();

            $name = $_POST['name'];
            $detail = $_POST['detail'];
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];
            $num_days = $_POST['num_days'];
            $objective = $_POST['objective'];

            if ($name == ''){
                echo "<div class='alert alert-danger'>Please insert a valid name.</div>";
                throw new Exception();
            }

            if($_POST['num_days'] < 1 or $_POST['num_days'] > 7){
                echo "<div class='alert alert-danger'>Day not valid.</div>";
                throw new Exception();
            }

            $now = new DateTime();
            $attuale = $now;
            $datatime = $attuale->format('Y-m-d');

            if($_POST['start_date'] < $datatime or $_POST['end_date'] < $datatime or $_POST['start_date'] == $_POST['end_date']){
                echo "<div class='alert alert-danger'>Start date or/and end date not valid. Schedule must have at least 24 h of validity!</div>";
                throw new Exception();
            }

            $req = $conn->create_schedule($id,$name,$detail,$start_date,$end_date,$num_days,$objective);
            $schedule = json_decode($req, True);

            if (in_array('successful',$schedule)) {
                echo "<div class='alert alert-success'>Schedule was inserted.</div>";
            } else{
                echo "<div class='alert alert-danger'>Schedule not correctly inserted!</div>";
                throw new Exception();
            }
        }
        catch (Exception $e){
            echo "<div class='alert alert-danger'>Schedule not correctly inserted!</div>";
        }
    }
    ?>
    <!-- html form here where the product information will be entered -->
    <form action="<?php echo htmlspecialchars("create_schedule.php?id=".$id."");?>" method="post" enctype="multipart/form-data">
        <table class='table table-hover table-responsive table-bordered'>
            <tr>
                <td>Schedule Name</td>
                <td><input type="text" name="name" class='form-control' /></td>
            </tr>
            <tr>
                <td>Detail</td>
                <td><input type='text' name='detail' class='form-control'></textarea></td>
            </tr>
            <tr>
                <td>Start Date</td>
                <td><input type='date' name='start_date' class='form-control' /></td>
            </tr>
            <tr>
                <td>End Date</td>
                <td><input type='date' name='end_date' class='form-control' /></td>
            </tr>
            <tr>
                <td>Number of Days</td>
                <td><input type='number' name='num_days' class='form-control'></textarea></td>
            </tr>
            <tr>
                <td>Objective</td>
                <td><input type='text' name='objective' class='form-control' /></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <input type='submit' value='Save' class='btn btn-primary' />
                    <a href="manage_users.php" class='btn btn-danger'>Back to read users</a>
                </td>
            </tr>
        </table>
    </form>

    <footer>
        <p>© 2018 Fitness Club</p>
        <p>Design for Smart Gym</p>
    </footer>
</div>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

<!-- Latest compiled and minified Bootstrap JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</body>
</html>



    <?php
    }
    else {

    ?><!-- Latest compiled and minified Bootstrap CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
    <style>
        .button {
            background-color: #518daf;
            border: none;
            color: white;
            padding: 7px 15px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            cursor: pointer;
            margin-left: 660px;
            margin-top: 19px;
            width: 84px;
            height: 40px;
        }
    </style>
    <?php
    echo "<div align='center' class='alert alert-danger'>You must be logged to view this page.</div>";
    echo "<a href=\"../login_website/index.php\"><button class=\"button\"><span>Log In</span>
                        </button></a>";
}
?>