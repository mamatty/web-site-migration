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

    <!-- Latest compiled and minified Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />

    <!-- custom css -->
    <style>
        .m-r-1em{ margin-right:1em; }
        .m-b-1em{ margin-bottom:1em; }
        .m-l-1em{ margin-left:1em; }
        .mt0{ margin-top:0; }
    </style>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        #searchList ul{
            background-color: #c2c2c2;
            cursor:pointer;
        }
        li{
            padding:2px;
        }
    </style>
    <style>
        input[type=text] {
            width: 350px;
            box-sizing: border-box;
            border: 2px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            background-color: white;
            background-position: 10px 10px;
            background-repeat: no-repeat;
            padding: 12px 20px 12px 40px;
            -webkit-transition: width 0.4s ease-in-out;
            transition: width 0.4s ease-in-out;
        }

        input[type=text]:focus {
            width: 100%;
        }
        td{
            word-wrap:break-word;
        }

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
                <li><a href="../monitoring/monitoring.php">Monitoring</a></li>
                <li><a href="../dashboard/dashboard.php">Dashboard</a></li>
            </ul>
        </nav>
    </header>
        <?php
        /**
         * Created by PhpStorm.
         * User: matte
         * Date: 29/03/2018
         * Time: 12:55
         */

        $id=isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');

        // include database connection
        include '../DbOperations/DbOperationSchedules.php';

        $conn = new DbOperationSchedules();

        // PAGINATION VARIABLES
        // page is the current page, if there's nothing set, default is page 1
        $page = isset($_GET['page']) ? $_GET['page'] : 1;

        // set records or rows of data per page
        $records_per_page = 7;

        // calculate for the query LIMIT clause
        $from_record_num = ($records_per_page * $page) - $records_per_page;

        $action = isset($_GET['action']) ? $_GET['action'] : "";

        // if it was redirected from delete.php
        if($action=='deleted'){
            echo "<div class='alert alert-success'>Exercise was deleted.</div>";
        }
        echo "<td>";
        echo "<a href='create_exercise.php?id=".$id."' class='btn btn-primary m-b-1em'>Create New Exercise</a>";
        echo "<a> </a>";
        echo "<a href='manage_users.php' class='btn btn-danger'>Home</a>";
        echo "<a> </a>";
        echo "<a href='read_exercises.php' class='btn btn-primary m-b-1em'>Read Exercises</a>";
        echo "</td>";
        ?>
    <br>
    <br>

    <h2>Exercise List</h2>
    <br>
        <?php
        $req = $conn->manage_exercises($id,$records_per_page,$from_record_num);
        $ex = json_decode($req, True);

        if (in_array('not-found',$ex)) {
            echo "<div class='alert alert-danger'>No exercise found.</div>";
        }elseif(in_array('exercise-not-found',$ex)) {
            echo "<div class='alert alert-danger'>Some exercise has been removed.</div>";
        }else{
            echo "<table class='table table-hover table-responsive table-bordered'>";//start table

            //creating our table heading
            echo "<tr>";
            echo "<th>Day</th>";
            echo "<th>Name</th>";
            echo "<th>Repetitions</th>";
            echo "<th>Weight</th>";
            echo "<th>Details</th>";
            echo "<th>Action</th>";
            echo "</tr>";

            for($i = 0, $l = count($ex['exercises']); $i < $l; ++$i) {
                $day = $ex['exercises'][$i]['day'];
                $name = $ex['exercises'][$i]['name'];
                $ripetitions = $ex['exercises'][$i]['repetitions'];
                $weight = $ex['exercises'][$i]['weight'];
                $details = $ex['exercises'][$i]['details'];
                $id_list = $ex['exercises'][$i]['id_list'];
                $id_exercise = $ex['exercises'][$i]['id_exercise'];


                // creating new table row per record
                echo "<tr>";
                echo "<td>$day</td>";
                echo "<td>$name</td>";
                echo "<td>$ripetitions</td>";
                echo "<td>$weight</td>";
                echo "<td>$details</td>";
                echo "<td>";
                // read one record
                echo "<a href='read_one_exercise.php?id=$id_exercise' class='btn btn-info m-r-1em'>Read</a>";

                // we will use this links on next part of this post
                echo "<a href='update_exercise.php?id=$id_list' class='btn btn-primary m-r-1em'>Edit</a>";

                // we will use this links on next part of this post
                echo "<a href='#' onclick='delete_exercise($id_list);'  class='btn btn-danger'>Delete</a>";

            }
            echo "</td>";
            echo "</tr>";

            // end table
            echo "</table>";
            $total_rows = $ex['total_rows'];

            // paginate records
            $page_url="manage_exercises.php?";
            include_once "paging.php";
        }

        ?>
    <footer>
        <p>© 2018 Fitness Club</p>
        <p>Design for Smart Gym</p>
    </footer>
</div>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

<!-- Latest compiled and minified Bootstrap JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<script type='text/javascript'>
    // confirm record deletion
    function delete_exercise( id ){

        let answer = confirm('Are you sure?');
        if (answer){
            // if user clicked ok,
            // pass the id to delete.php and execute the delete query
            window.location = 'delete_single_exercise.php?id=' + id;
        }
    }
</script>
<script>
    $(document).ready(function(){
        $('#search').keyup(function(){
            let query = $(this).val();
            if(query != '')
            {
                $.ajax({
                    url:"autocomplete_exercise.php",
                    method:"POST",
                    data:{query:query},
                    success:function(data)
                    {
                        $('#searchList').fadeIn();
                        $('#searchList').html(data);
                    }
                });
            }
        });
        $(document).on('click', 'li', function(){
            $('#search').val($(this).text());
            $('#searchList').fadeOut();
        });
    });
</script>

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