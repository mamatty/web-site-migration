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
    // get passed parameter value, in this case, the record ID
    // isset() is a PHP function used to verify if a value is there or not
    $id=isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');

    //include database connection
    include 'DbOperation.php';
    $conn = new DbOperation();

    // read current record's data
    $res = $conn->look_updated_exercise_list($id);
    $ex = json_decode($res,True);
    if($ex['status'] == 'found'){
        $name = $ex['name'];
        $day = $ex['day'];
        $detail = $ex['detail'];
        $weight = $ex['weight'];
    }else{
        echo "<div class='alert alert-danger'>Exercise not found</div>";
    }

    ?>
    <?php

    // check if form was submitted
    if($_POST){

        try{
            // read current record's data
            $res_up = $conn->update_exercise_list($id, $_POST['name'], $_POST['description'], $_POST['muscolar_zone'], $_POST['url']);
            $ex_up = json_decode($res,True);
            if(in_array('not-updated', $ex_up)) {
                echo "<div class='alert alert-danger'>Unable to update exercise. Please try again.</div>";
                throw new Exception();
            } else {

                $name = $ex_up['name'];
                $description = $ex_up['description'];
                $muscolar_zone = $ex_up['muscolar_zone'];
                $url = $ex_up['url'];

                    echo "<div class='alert alert-success'>Exercise was updated.</div>";

            }
        }
        catch (Exception $e){
            echo "<div class='alert alert-danger'>Exercise not correctly updated!</div>";
        }
    }
    ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id={$id}");?>" method="post"><!--we have our html table here where the record will be displayed-->
        <table class='table table-hover table-responsive table-bordered'>
            <tr>
                <td>Name</td>
                <td><input type="text" required autocomplete="off" name="name" id="name" value="<?php echo htmlspecialchars($name, ENT_QUOTES);  ?>" class='form-control' />
                    <div id="nameList"></div></td>
            </tr>
            <tr>
                <td>Description</td>
                <td><input type='text' required autocomplete="off" name='description'  value="<?php echo htmlspecialchars($description, ENT_QUOTES);  ?>" class='form-control' /></td>
            </tr>
            <tr>
                <td>Muscolar Zone</td>
                <td><input type='text' name='muscolar_zone' value="<?php echo htmlspecialchars($muscolar_zone, ENT_QUOTES);  ?>" class='form-control' /></td>
            </tr>
            <tr>
                <td>URL</td>
                <td><input type='text' name='url' value="<?php echo htmlspecialchars($url, ENT_QUOTES);  ?>" class='form-control' /></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <input type='submit' value='Save Changes' class='btn btn-primary' />
                    <a href="read_exercises.php" class='btn btn-danger'>Back to read exercises</a>
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