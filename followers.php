<?php
  include "bootstrap.php";
  session_start();
  if(empty($_SESSION["id"])){
    header("Location: index.php");
  }
  $id = $_SESSION["id"];

  require_once "connection.php";

  if(isset($_GET['friend_id']))
  {
    // Zahtev za pracenje drugog korisnika
    $friendId = $conn->real_escape_string($_GET["friend_id"]);
    $q = "SELECT * FROM `followers`
    WHERE `id_sender` = $id
    AND `id_receiver` = $friendId";
    $result = $conn->query($q);
    if($result->num_rows == 0)
    {
      $upit = "INSERT INTO `followers`(`id_sender`,`id_receiver`)
      VALUE ($id, $friendId)";
      $result1 = $conn->query($upit);
    }
  }

  if(isset($_GET['unfriend_id']))
  {
    // Zahtev da se drugi korisnik odprati
    $friendId = $conn->real_escape_string($_GET["unfriend_id"]);
    $q = "DELETE FROM `followers`
    WHERE `id_sender` = $id
    AND `id_receiver` = $friendId";
  
    $conn->query($q);
  }

  // Odredimo koje druge korisnike prati logovan korisnik
  $upit1 = "SELECT `id_receiver` FROM `followers` WHERE `id_sender` = $id";
  $res1 = $conn->query($upit1);
  $niz1 = array();
  while($row = $res1->fetch_array(MYSQLI_NUM))
  {
    $niz1[]= $row[0];
  }
  //var_dump($niz1);
  //odrediti koji drugi korisnici prate logovanog korisnika
  $upit2 = "SELECT `id_sender` FROM `followers` WHERE `id_receiver` = $id";
  $res2 = $conn->query($upit2);
  $niz2 = array();
  while($row = $res2->fetch_array(MYSQLI_NUM))
  {
    $niz2[]= $row[0];
  }
  //var_dump($niz2);
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
      while($row = $result->fetch_assoc())
      {
        $idUser = $row["id"];
        echo "<tr><td>";
        if($row["full_name"] !== NULL)
        {
          echo "<a href='show_profile.php?id=".$idUser."'>".$row["full_name"]."</a>";
        }
        else
        {
          echo "<a href='show_profile.php?id=".$idUser."'>".$row["username"]."</a>";
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
        if(!in_array($friendId, $niz1))
        {
          if(!in_array($friendId, $niz2))
          {
            $text = "Follow";
          }
          else
          {
            $text = "follow back";
          }
          echo "<a class='btn btn-primary mb-4' href='followers.php?friend_id=$friendId'>$text</a>";
        }
        else
        {
          echo "<a class='btn btn-danger' href='followers.php?unfriend_id=$friendId'>Unfollow</a>";
        }
        echo "</td><td>";
        echo "<a href='show_profile.php?=id_user'></a>";
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