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
            width: 300px;
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
            echo "<div class='alert alert-success'>Record was deleted.</div>";
        }
        echo "<td>";
        echo "<a href='create_exercise_list.php?' class='btn btn-primary m-b-1em'>Create New Exercise</a>";
        echo "<a> </a>";
        echo "<a href='manage_users.php' class='btn btn-primary m-b-1em'>Home</a>";
        echo "</td>";
        ?>
    <br>
    <br>
        <form action="<?php echo htmlspecialchars("search_exercise_list.php");?>" method="get">
            <input type="text" name="search" id="search" class="form-control" placeholder="Search Exercise" />
            <div id="searchList"></div>
        </form>
    <br>
        <?php
        // select all data
        if(isset($_GET['search'])) {

            $res = $conn->search_exercise_list($_GET['search']);
            $ex = json_decode($res, True);
            if (in_array('not-found', $ex)) {
                echo "<div class='alert alert-danger'>No Exercises found.</div>";
            } else {
                echo "<table class='table table-hover table-responsive table-bordered'>";//start table

                //creating our table heading
                echo "<tr>";
                echo "<th>Name</th>";
                echo "<th>Description</th>";
                echo "<th>Muscolar Zone</th>";
                echo "<th>Action</th>";
                echo "</tr>";
                for ($i = 0, $l = count($ex['exercises']); $i < $l; ++$i) {

                    $id_exercise = $ex['exercises'][$i]['id_exercise'];
                    $description = $ex['exercises'][$i]['description'];
                    $muscolar_zone = $ex['exercises'][$i]['muscular_zone'];
                    $name = $ex['exercises'][$i]['name'];

                    echo "<tr>";
                    echo "<td>$name</td>";
                    echo "<td>$description</td>";
                    echo "<td>$muscolar_zone</td>";
                    echo "<td>";

                    // we will use this links on next part of this post
                    echo "<a href='update_exercise_list.php?id=$id_exercise' class='btn btn-primary m-r-1em'>Edit</a>";

                    // we will use this links on next part of this post
                    echo "<a href='#' onclick='delete_exercise($id_exercise);'  class='btn btn-danger'>Delete</a>";

                }
                echo "</td>";
                echo "</tr>";

                // end table
                echo "</table>";
                // PAGINATION
                // count total number of rows
                $total_rows = count($ex);

                // paginate records
                $page_url = "read_schedules.php?";
                include_once "paging.php";
            }
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
    function delete_schedule( id ){

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
            window.location = 'search_exercise_list.php?search=' + $(this).data('name');
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
    <?php
    echo "<div align='center' class='alert alert-danger'>You must be logged to view this page.</div>";
}
?>
