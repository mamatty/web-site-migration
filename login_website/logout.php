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
  require_once "DbOperation.php";

  $conn = new DbOperation();
  $req = $conn-> logout();
  $logout = json_decode($req,True);
  if($logout['status'] = 'successful'){
      session_unset();
      session_destroy();
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
