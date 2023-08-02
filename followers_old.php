<?php
  include "bootstrap.php";
  session_start();
  if(empty($_SESSION["id"])){
    header("Location: index.php");
  }
  $id = $_SESSION["id"];

  require_once "connection.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Members of Social Network</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="text-white bg-dark p-3">
  <header class="row">
    <?php require_once "header.php"; ?>
  </header>
  <div class="container-fluid">
    <div class="row text-center mt-5">
      <h1>See other members from our site</h1>
    </div>
  <?php
    $q = "SELECT `u`.`id`, `u`.`username`,`p`.`photo`,`p`.`gender`,
          CONCAT(`p`.`first_name`,' ',`p`.`last_name`) AS `full_name`
          FROM `users` AS `u`
          LEFT JOIN `profiles` AS `p`
          ON `u`.`id` = `p`.`id_user`
          WHERE `u`.`id` != $id
          ORDER BY `full_name`;
          ";
    $result = $conn->query($q);
    if($result->num_rows == 0)
    {
      //nema korisnika
  ?>
    <div class="error">No other users in database :( </div>
  <?php
    }
    else{
    echo '<div class="row justify-content-center p-5">';
      echo "<table class='table-profiles'>";
      echo "<tr><th>Name</th><th>Profile Picture</th><th>Action</th></tr>";
      $defaultphoto = "anon.png";
      while($row = $result->fetch_assoc())
      {
        echo "<tr><td>";
        if($row["full_name"] !== NULL)
        {
          echo $row["full_name"];
        }
        else
        {
          echo $row["username"];
        }
        echo "</td><td>";
        //prikaz slike korisnika
        if ($row["photo"] !== "" && $row["photo"] !== null)
        {
          echo "<img src='" . $row["photo"] . "' alt='Profile Picture' width='100'>";
        }
        elseif($row["gender"] == "m")
        {
          echo "<img src='img/m.png' alt='Profile Picture' width='100'>";
        }
        elseif($row["gender"] == "f")
        {
          echo "<img src='img/f.png' alt='Profile Picture' width='100'>";
        }
        else
        {
          echo "<img src='img/anon.png' alt='Profile Picture' width='100'>";
        }
        echo '</td><td>';
        // Ovde cemo linkive za pracenje korisnika
        $friendId = $row["id"];
        echo "<a class='btn btn-primary mb-4' href='follow.php?friend_id=$friendId'>Follow</a>";
        echo "<br>";
        echo "<a class='btn btn-danger' href='unfollow.php?friend_id=$friendId'>Unfollow</a>";
        echo "</td></tr>";
      }
      echo "</table>";
    echo '</div>';
    }
    ?>
    <p class="text-center">Return to <a class="btn btn-info" href="index.php">Home Page</a></p>
  </div>
</body>
</html>