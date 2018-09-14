<?php
/* Log out process, unsets and destroys session variables */
session_start();

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Logout</title>
</head>
<?php
  include 'css/css.html';
  require_once "../DbOperations/DbOperationLogin.php";

  #skipping error warning

  error_reporting(E_ERROR | E_PARSE);

  setcookie ("logged_in", "", time() - 3600,'/');
  setcookie ("first_name", "", time() - 3600,'/');
  setcookie ("last_name", "", time() - 3600,'/');
  setcookie ("email", "", time() - 3600,'/');
  setcookie ("app-id", "", time() - 3600,'/');
  setcookie ("token", "", time() - 3600,'/');

  $conn = new DbOperation();
  $req = $conn-> logout();
  $logout = json_decode($req,True);
  if($logout['status'] == 'successful'){

      ?>

        <body>
        <div class="form">
            <h1>Thanks for stopping by</h1>

            <p><?= 'You have been logged out!'; ?></p>

            <a href="index.php"><button class="button button-block"/>Home</button></a>

        </div>
        </body>
      <?php
  }
  else{
      ?>
      <body>
      <div class="form">
          <h1>Error</h1>

          <p><?= 'Impossible to logout'; ?></p>

          <a href="index.php"><button class="button button-block"/>Home</button></a>

      </div>
      </body>
    <?php
  }
  ?>

</html>
