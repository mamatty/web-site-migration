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
            width: 220px;
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
                    <li><a href="../manage_schedules/manage_users.php">Manage Schedules</a></li>
                    <li class="current"><a href="read_messages.php">Send Messages</a></li>
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
        include '../DbOperations/DbOperationMessages.php';

        $conn = new DbOperationMessages();

        // PAGINATION VARIABLES
        // page is the current page, if there's nothing set, default is page 1
        $page = isset($_GET['page']) ? $_GET['page'] : 1;

        // set records or rows of data per page
        $records_per_page = 7;

        // calculate for the query LIMIT clause
        $from_record_num = ($records_per_page * $page) - $records_per_page;

        $action = isset($_GET['action']) ? $_GET['action'] : "";


        // link to create record form
        echo "<a href='create_message.php' class='btn btn-primary m-b-1em'>Create New Message</a>";
        ?>
    <br>
    <br>
        <form action="search.php" method="get">
            <input type="text" name="search" id="search" class="form-control" placeholder="Search by Title" />
            <div id="searchList"></div>
        </form>
    <br>
        <?php
        $req = $conn->read_messages($from_record_num,$records_per_page);
        $res = json_decode($req, True);
        if (in_array('not-found', $res)) {
                echo "<div class='alert alert-danger'>No Message found.</div>";
        }
        else {

            echo "<table class='table table-hover table-responsive table-bordered'>";//start table

            //creating our table heading
            echo "<tr>";
            echo "<th>Title</th>";
            echo "<th>Date</th>";
            echo "<th>Destination</th>";
            echo "<th>Action</th>";
            echo "</tr>";
            for ($i = 0, $l = count($res['messages']); $i < $l; ++$i) {

                $id_message = $res['messages'][$i]['id_message'];
                $title = $res['messages'][$i]['title'];
                $send_date = $res['messages'][$i]['send_date'];
                $destination = $res['messages'][$i]['destination'];

                echo "<tr>";
                echo "<td>$title</td>";
                echo "<td>$send_date</td>";
                echo "<td>$destination</td>";
                echo "<td>";
                // read one record
                echo "<a href='read_one_message.php?id=$id_message' class='btn btn-info m-r-1em'>Read</a>";


            }
            // end table
            echo "</table>";

            // PAGINATION
            // count total number of rows
            $total_rows = $res['total_rows'];

            // paginate records
            $page_url="read_messages.php?";
            include_once "paging.php";
        }

        ?>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

    <!-- Latest compiled and minified Bootstrap JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function(){
            $('#search').keyup(function(){
                let query = $(this).val();
                if(query != '')
                {
                    $.ajax({
                        url:"autocomplete.php",
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
        <footer>
            <p>© 2018 Fitness Club</p>
            <p>Design for Smart Gym</p>
        </footer>
    </div>
<script>Cufon.now();</script>
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