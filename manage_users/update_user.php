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
                    <li class="current"><a href="manage_users.php">Manage Users</a></li>
                    <li><a href="../manage_schedules/manage_users.php">Manage Schedules</a></li>
                    <li><a href="../send_messages/read_messages.php">Send Messages</a></li>
                    <li><a href="dashboard.html">Dashboard</a></li>
                </ul>
            </nav>
        </header>

        <?php
        // get passed parameter value, in this case, the record ID
        // isset() is a PHP function used to verify if a value is there or not
        $id=isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');

        include 'DbOperation.php';
        $conn = new DbOperation();
        $req = $conn->look_updated_user($id);
        $user = json_decode($req, True);
        if($user['status'] == 'found'){

            $name = $user['name'];
            $surname = $user['surname'];
            $email = $user['email'];
            $birthdate = $user['birth_date'];
            $address = $user['address'];
            $subscription = $user['subscription'];

        }

        ?>
        <?php

        // check if form was submitted
        if($_POST ){
            if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){

                $new_name = $_POST['name'];
                $new_surname = $_POST['surname'];
                $new_email = $_POST['email'];
                $new_birth = $_POST['birthdate'];
                $new_addr = $_POST['address'];
                $new_sub = $_POST['subscription'];

                $date = new DateTime();
                $datatime = $date->format('Y-m-d');

                if($new_sub == 'daily'){
                    $new_end_sub = $datatime;
                }
                elseif ($new_sub == 'weekly'){
                    $date->modify('+7 day');
                    $datatime = $date->format('Y-m-d');
                    $new_end_sub = $datatime;
                }
                elseif ($new_sub == 'monthly'){
                    $date->modify('+1 month');
                    $datatime = $date->format('Y-m-d');
                    $new_end_sub = $datatime;
                }
                elseif ($new_sub == 'quarterly'){
                    $date->modify('+3 month');
                    $datatime = $date->format('Y-m-d');
                    $new_end_sub = $datatime;
                }
                else{
                    $date->modify('+1 year');
                    $datatime = $date->format('Y-m-d');
                    $new_end_sub = $datatime;
                }

                // Execution
                $req_up = $conn->update_user($id,$new_name,$new_surname,$new_email,$new_birth,$new_addr,$new_sub, $new_end_sub);
                $user_up = json_decode($req_up, True);

                if($user_up['status'] == 'found'){

                    $name = $user_up['name'];
                    $surname = $user_up['surname'];
                    $email = $user_up['email'];
                    $birthdate = $user_up['birth_date'];
                    $address = $user_up['address'];
                    $subscription = $user_up['subscription'];

                    echo "<div class='alert alert-success'>User was updated.</div>";

                }else{
                    echo "<div class='alert alert-danger'>Unable to update user. Please try again.</div>";
                }
            }
            else{
                echo "<div class='alert alert-danger'>Email format not valid. Please try again.</div>";
            }
        }
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id={$id}");?>" method="post"><!--we have our html table here where the record will be displayed-->
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Name</td>
                    <td><input type='text' name='name' value="<?php echo htmlspecialchars($name, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Surname</td>
                    <td><input type='text' name='surname' value="<?php echo htmlspecialchars($surname, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><input type='email' name='email' value="<?php echo htmlspecialchars($email, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Birthdate</td>
                    <td><input type='date' name='birthdate' value="<?php echo htmlspecialchars($birthdate, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Address</td>
                    <td><input type='text' name='address' value="<?php echo htmlspecialchars($address, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Subscription</td>
                    <td>
                        <select id="subscription" name="subscription" >
                            <option value="daily" <?php if($subscription == 'daily'){echo("selected");}?>>1 Day</option>
                            <option value="weekly" <?php if($subscription == 'weekly'){echo("selected");}?>>1 Week</option>
                            <option value="monthly" <?php if($subscription == 'monthly'){echo("selected");}?>>1 Month</option>
                            <option value="quarterly" <?php if($subscription == 'quarterly'){echo("selected");}?>>3 Month</option>
                            <option value="annual" <?php if($subscription == 'annual'){echo("selected");}?>>1 Year</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save Changes' class='btn btn-primary' />
                        <a href="manage_users.php" class='btn btn-danger'>Back to read users</a>
                        <!--<a onclick="goBack()" class='btn btn-danger'>Back to read users</a>-->
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