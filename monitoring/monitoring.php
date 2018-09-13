<?php
/* Displays user information and some useful messages */
session_start();

// Check if user is logged in using the session variable
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
        <style>
            body {
                margin: 0;
            }

            #list ul {
                list-style-type: none;
                margin: 0;
                padding: 0;
                width: 25%;
                background-color: #f1f1f1;
                position: fixed;
                height: 100%;
                overflow: auto;
            }

            #list li a {
                display: block;
                width: 150px;
                color: #000;
                padding: 8px 16px;
                text-decoration: none;
            }

            #list li a.active {
                background-color: #0088af;
                color: white;
            }

            #list li a:hover:not(.active) {
                background-color: #555;
                color: white;
            }
            .group:after {
                content: "";
                display: table;
                clear: both;
            }

            .text {
                float: left;
                width: 30%;
            }

            .images {
                float: left;
                alignment: center;
                width: 70%;
            }

            .colorpicker {
                list-style-type:none;
            }

            .colorpicker li {
                float: left;
                list-style-type:none;
                text-align: center;
            }

            .colorpicker li img {
                width: 100px;
                height: auto;
                cursor: pointer;
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
                    <li><a href="../send_messages/read_messages.php">Send Messages</a></li>
                    <li class="current"><a href="monitoring.php">Monitoring</a></li>
                    <li><a href="../dashboard/dashboard.php">Dashboard</a></li>
                </ul>
            </nav>
        </header>

        <div class="group">
            <div class="text">
                <ul id="list">
                    <li><a class="btn active" onclick="showImage('images/smart_gym.png');">Gym Map</a></li>
                    <li><a class="btn" onclick="showImage('images/smart-gym-2.png');">Zone A</a></li>
                    <li><a class="btn" onclick="showImage('images/images-smart-gym.jpg');">Zone B</a></li>
                    <li><a class="btn" onclick="showImage('images/smart-gym-2.png');">Zone C</a></li>
                    <li><a class="btn" onclick="showImage('images/Smart-GYM-Egypt.jpg');">Shower cabins</a></li>
                    <li><a class="btn" onclick="showImage('images/images-smart-gym.jpg');">Cabinets</a></li>
                </ul>
            </div>

            <div class="images" >
                <img src="images/smart_gym.png" alt="Smart Gym" id="currentImg" height="330" width="511">
            </div>
        </div>

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

    <!-- Latest compiled and minified Bootstrap JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

        <script>
            // Add active class to the current button (highlight it)
            var header = document.getElementById("list");
            var btns = header.getElementsByClassName("btn");
            for (var i = 0; i < btns.length; i++) {
                btns[i].addEventListener("click", function() {
                    var current = document.getElementsByClassName("active");
                    if (current.length > 0) {
                        current[0].className = current[0].className.replace(" active", "");
                    }
                    this.className += " active";
                });
            }
        </script>

        <script type="text/javascript">

            function showImage(imgPath) {
                var curImage = document.getElementById('currentImg');

                curImage.src = imgPath;
            }

        </script>



        <footer>
            <p>© 2018 Fitness Club</p>
            <p>Design for Smart Gym</p>
        </footer>
    </div>
<script>Cufon.now();</script>
</body>
</html>
