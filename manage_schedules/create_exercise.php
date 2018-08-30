<?php
/* Displays user information and some useful messages */
session_start();

// Check if user is logged in using the session variable
if ( isset($_SESSION['logged_in']) and $_SESSION['logged_in'] == true) {
    // Makes it easier to read
    $first_name = $_SESSION['first_name'];
    $last_name = $_SESSION['last_name'];
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
                <li><a href="dashboard.html">Dashboard</a></li>
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

            include 'DbOperation.php';

            $conn = new DbOperation();

            $name = $_POST['name'];
            $detail = $_POST['detail'];
            $day = $_POST['day'];
            $weight = $_POST['weight'];
            $series = $_POST['series'];

            if($_POST['day'] < 1 or $_POST['day'] > 7){
                echo "<div class='alert alert-danger'>Day not valid.</div>";
            }

            $req = $conn->create_exercise($id,$name,$day,$series,$weight,$detail);
            $ex = json_decode($req,True);

            if (in_array('successful',$ex)){
                echo "<div class='alert alert-success'>Exercise was inserted.</div>";
            }
            elseif(in_array('not-inserted',$ex)){
                echo "<div class='alert alert-danger'>Impossible to insert the exercise!</div>";
                throw new Exception();
            }
            else{
                echo "<div class='alert alert-danger'>Exercise name not found.</div>";
                throw new Exception();
            }
        }
        catch (Exception $e){
            echo "<div class='alert alert-danger'>Exercise not correctly inserted! Try Again!</div>";
        }
    }
    ?>
    <!-- html form here where the product information will be entered -->
    <form action="<?php echo htmlspecialchars("create_exercise.php?id=".$id."");?>" method="post" enctype="multipart/form-data">
        <table class='table table-hover table-responsive table-bordered'>
            <tr>
                <td>Exercise Name</td>
                <td><input type="text" required autocomplete="off" name="name" id="name" class='form-control' />
                    <div id="nameList"></div></td>
            </tr>
            <tr>
                <td>Detail</td>
                <td><input type='text' name='detail' class='form-control'></textarea></td>
            </tr>
            <tr>
                <td>Day</td>
                <td><input type='number' name='day' class='form-control'></textarea></td>
            </tr>
            <tr>
                <td>Weight</td>
                <td><input type='text' name='weight' class='form-control'></textarea></td>
            </tr>
            <tr>
                <td>Series</td>
                <td><input type='text' name='series' class='form-control'></textarea></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <input type='submit' value='Save' class='btn btn-primary' />
                    <a href="<?php echo htmlspecialchars("manage_exercises.php?id=".$id."");?>" class='btn btn-danger'>Back to read user's exercises</a>
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

<script>
    $(document).ready(function(){
        $('#name').keyup(function(){
            let query = $(this).val();
            if(query != '')
            {
                $.ajax({
                    url:"autocomplete_exercise.php",
                    method:"POST",
                    data:{query:query},
                    success:function(data)
                    {
                        $('#nameList').fadeIn();
                        $('#nameList').html(data);
                    }
                });
            }
        });
        $(document).on('click', 'li', function(){
            $('#name').val($(this).text());
            $('#nameList').fadeOut();
        });
    });
</script>


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