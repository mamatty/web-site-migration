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
        <link rel="stylesheet" type="text/css" media="screen" href="css/slider.css">
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

        <script>
            $(window).load(function(){
                $('.slider')._TMS({
                    prevBu:'.prev',
                    nextBu:'.next',
                    pauseOnHover:true,
                    pagNums:false,
                    duration:800,
                    easing:'easeOutQuad',
                    preset:'Fade',
                    slideshow:7000,
                    pagination:'.pagination',
                    waitBannerAnimation:false,
                    banners:'fade'
                })
            })
        </script>
        <!--[if lt IE 9]>
        <script src="js/html5.js"></script>
        <link rel="stylesheet" type="text/css" media="screen" href="css/ie.css">
        <![endif]-->
    </head>
    <body>
    <div class="main">
        <div class="bg-img"></div>
        <!--==============================header=================================-->
        <header>
            <h1><a href="index.php">Fitness <strong>Club.</strong></a></h1>
            <nav>

                <ul class="menu">
                    <li><h3><?php echo "Welcome  " ; ?></h3> <h2><?php echo $first_name.' '.$last_name; ?></h2></li>
                    <li><a href="../login_website/logout.php"><button class="button"><span>Log Out</span>
                        </button></a></li>
</ul>

<ul class="menu">
    <li class="current"><a href="index.php">Home</a></li>
    <li><a href="../manage_users/manage_users.php">Manage Users</a></li>
    <li><a href="../manage_schedules/manage_users.php">Manage Schedules</a></li>
    <li><a href="../send_messages/read_messages.php">Send Messages</a></li>
    <li><a href="dashboard.html">Dashboard</a></li>
</ul>
</nav>
</header>
<!--==============================content================================-->

<section id="content">
    <div class="container_12">
        <div class="grid_12">
            <div class="slider">
                <ul class="items">
                    <li><img src="images/slider-1.jpg" alt="">
                        <div class="banner">
                            <p class="font-1">Smart<span>Gym</span></p>
                            <p class="font-2">Short description here.</p>
                        </div>
                    </li>
                    <li><img src="images/slider-2.jpg" alt="">
                        <div class="banner">
                            <p class="font-1">Smart<span>Gym</span></p>
                            <p class="font-2">Short description here.</p>
                        </div>
                    </li>
                    <li><img src="images/slider-3.jpg" alt="">
                        <div class="banner">
                            <p class="font-1">Smart<span>Gym</span></p>
                            <p class="font-2">Short description here.</p>
                        </div>
                    </li>
                </ul>
                <div class="pagination">
                    <ul>
                        <li><a href="#"></a></li>
                        <li><a href="#"></a></li>
                        <li><a href="#"></a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="grid_12 top-1">
            <div class="block-1 box-shadow">
                <p class="font-3">Fitness Club is one of free website templates created by TemplateMonster. This website template is optimized for 1280X1024 screen resolution. This Fitness Club Template goes with 2 packages – with PSD source files and without them. PSD source files are available for free for the registered members of TemplateMonster.com.</p>
            </div>
        </div>
        <div class="clear"></div>
    </div>
</section>
<!--==============================footer=================================-->
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
