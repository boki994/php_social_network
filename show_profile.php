<?php
include "bootstrap.php";
session_start();
require_once "connection.php";

$user = "";

if (isset($_GET["id"])) {
  $user = $_GET["id"];
}

$currentUser = $_SESSION["username"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="text-white bg-dark p-3">
  <header class="row">
    <?php require_once "header.php"; ?>
  </header>
  <h1>HELLO <?php echo $currentUser; ?></h1>
  <?php
  $q = "SELECT `u`.`id`, `u`.`username`, `p`.`dob`, `p`.`gender`, `p`.`first_name`, `p`.`last_name`, `p`.`bio`
  FROM `users` AS `u`
  LEFT JOIN `profiles` AS `p` ON `u`.`id` = `p`.`id_user`
  WHERE `u`.`id` = $user";

  $result = $conn->query($q);
  if (!$result) {
    echo "Error: " . $conn->error;
  } else {
    $res = $result->fetch_assoc();

    if ($result->num_rows == 0) {
      echo "Error";
    } else {
      echo '<div class="row justify-content-center p-5">';
      echo '<table class="table-profiles">';
      echo '<tr><td>';
      echo 'First Name:</td><td>';
      if ($res["first_name"] !== NULL) {
        echo $res["first_name"];
      } else {
        echo "";
      }
      echo '</td></tr>';
      echo '<tr><td>';
      echo 'Last Name:</td><td>';
      if ($res["last_name"] !== NULL) {
        echo $res["last_name"];
      } else {
        echo "";
      }
      echo '</td></tr>';
      echo '<tr><td>';
      echo 'Username:</td><td>' . $res['username'] . '</td></tr>';
      echo '<tr><td>';
      echo 'Date of Birth:</td><td>' . $res['dob'] . '</td></tr>';
      echo '<tr><td>';
      echo 'About Me:</td><td>';
      echo $res['bio'];
      echo '</td></tr>';
      echo '</table>';
      echo '</div>';
    }
  }
  ?>
</body>
</html>
