<?php ob_start();
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
                <li><a href="../manage_schedules/manage_users.php">Manage Schedules</a></li>
                <li class="current"><a href="read_messages.php">Send Messages</a></li>
                <li><a href="../dashboard/dashboard.php">Dashboard</a></li>
            </ul>
        </nav>
    </header>
    <?php ob_start();
/**
 * Created by PhpStorm.
 * User: matte
 * Date: 29/03/2018
 * Time: 11:37
 */

    // include database connection
    include 'DbOperation.php';
    include '../SendMultiplePush.php';


    if($_POST){

        $send = new SendMultiplePush();
        $conn = new DbOperation();

        $load = false;
        $title = $_POST['title'];
        $body = $_POST['body'];
        $destination = $_POST['destination'];
        $argument = '';
        if(isset($_POST['argument'])){
            $argument = $_POST['argument'];
        }

        if(empty($title)){
            echo "<div class='alert alert-danger'>Title not inserted!</div>";
        }
        else{

            $load = true;
            /*$now = new DateTime();
            $attuale = $now;
            $datatime = $attuale->format('Y-m-d');

            $req = $conn->create_message($title,$body,$datatime, $destination);
            $mes = json_decode($req, True);
            if($mes['status'] == 'successful'){
                echo "<div class='alert alert-success'>Message has been saved.</div>";

            }else{
                echo "<div class='alert alert-danger'>Unable to save the message. Please try again.</div>";
            }*/

            if ($load == true){
                $result = $send -> sendNotification($title, $body, $destination, $argument);
                if (isset($result['error']) != true){
                    echo "<div class='alert alert-success'>Message has been sent.</div>";
                }else{
                    echo "<div class='alert alert-danger'>".$result['message']."</div>";
                }
            }
        }
    }

    ?>
    <!-- html form here where the message information will be entered -->

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
        <table class='table table-hover table-responsive table-bordered'>
            <tr>
                <td>Title</td>
                <td><input type='text' name='title' class='form-control' /></td>
            </tr>
            <tr>
                <td>Body</td>
                <td ><textarea type='text' name='body' class='form-control' rows="4" cols="50"></textarea></td>
            </tr>
            <tr>
                <td>Destination</td>
                <td>
                    <select id="destination" name="destination" onchange="destinationChange(this);">
                        <option value="all">Send to everybody</option>
                        <option value="topic">Topic</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Argument</td>
                <td>
                    <select id="argument" name="argument">
                        <option value="0"> -- </option>
                    </select>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <input type='submit' value='Send' class='btn btn-primary' />
                    <a onclick="goBack()" class='btn btn-danger'>Back to read messages.</a>
                </td>
            </tr>
        </table>
    </form>

    <footer>
        <p>© 2018 Fitness Club</p>
        <p>Design for Smart Gym</p>
    </footer>
</div>
<script>Cufon.now();</script>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

<!-- Latest compiled and minified Bootstrap JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>


</body>
</html>

<script type="text/javascript">

    let list = new Array(2);
    list["all"] = [" -- "];
    list["topic"] = ["Notizie", "Avvisi", "Promozioni"];

    function destinationChange(selectObj) {

        let idx = selectObj.selectedIndex;
        let which = selectObj.options[idx].value;
        cList = list[which];
        let cSelect = document.getElementById("argument");
        let len=cSelect.options.length;
        while (cSelect.options.length > 0) {
            cSelect.remove(0);
        }
        let newOption;

        for (let i=0; i<cList.length; i++) {
            newOption = document.createElement("option");
            newOption.value = cList[i];
            newOption.text=cList[i];
            // add the new option
            try {
                cSelect.add(newOption);
            }
            catch (e) {
                cSelect.appendChild(newOption);
            }
        }
    }
</script>

<script>
    function goBack() {
        window.history.back();
    }
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