<?php
    session_start();
    require_once "connection.php";
    require_once "validation.php";
    include "bootstrap.php";

    

    $poruka = "";
    if (isset($_GET["p"]) && $_GET["p"] == "ok")
    {
        $poruka = "You have successfully registered, please login to continue";
    } 

    $username = "Anonymus";
    if(isset($_SESSION["username"])) // da li  je logovan korisnik
    {
      $username = $_SESSION["username"];
      $id = $_SESSION["id"]; //id logovanog korisnika 
      $row = profileExists($id, $conn);
      $m = "";
      if($row === false)
      {
        //Logovani korisnik nema profil
        $m = "Create your ";
      }
      else
      {
        //Logovan korisnik ima profil
        $m = "Edit your ";
        $username = $row["first_name"]. " " . $row["last_name"];
      }
    }

    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Social Network</title>
</head>
<body class="d-flex h-100 text-center text-white bg-dark">
<div class="animated-background"></div>
<!-- cover container -->
<div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
    <!-- nav -->
 <?php require_once "header.php"; ?>
  <main class="px-3">
  <div class="container d-flex align-items-center justify-content-center pt-5">
    <!-- alert za poruku ako je prazna ne prikazuje se-->
    <?php if (!empty($poruka)) { ?>
      <div class="alert alert-info alert-dismissible fade show success mx-auto" role="alert">
        <?php echo $poruka; ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <?php } ?>
  </div>
      <!-- video pozadina -->
      <div class="container">
        <div class="embed-responsive embed-responsive-16by9">
            <video class="embed-responsive-item" autoplay muted loop id="video">
                <source src="img/network.mp4" type="video/mp4">
            </video>
        </div>
      </div>
      <div class="d-flex flex-column justify-content-center align-items-center vh-100">
        <div class="col-8">
          <div class="row mb-5 d-flex justify-content-center alignt-items-center">
              <h1 class="display-2">Welcome:</h1>
              <p class="h1 text-info"><?php echo $username ?></p>
              <h2 class="display-4">To our Social <span class="text-info">Network!</span></h2>
            <?php if(!isset($_SESSION["username"])){?>
                <div class="col-md-8 col-lg-6 col-xl-4 mt-3">
                    <p class="lead">New to our site?<p> 
                    <a class="btn btn-primary" href="register.php">Register here</a>
                    <p>To access our site!</p>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-4 mt-3">
                    <p class="lead">Have an account?<p> 
                    <a class="btn btn-primary" href="login.php">Login here</a>
                    <p>To continue to our site!</p>
                </div>
              <?php } else {?>
                <div class="mt-5">
                  <p class="lead mb-5"><?php echo $m; ?><a class="btn btn-primary" href="profile.php">PROFILE</a></p>
                  <p class="lead mb-5">See other members <a class="btn btn-info" href="followers.php">HERE</a></p>
                  <p class="lead mb-5"><a class="btn btn-secondary" href="logout.php">LOGOUT</a> from our site</p>
                  <p class="lead mb-5"><a class="btn btn-danger" href="delete.php">Delete</a> your profile</p>
              <?php }?>
                </div>
            </div>
        </div>
      </div>
  </main>
  </div>
  <!-- footer -->
  <footer class="mt-auto text-white footer-logo fixed-bottom w-100">
    <p class="footer-text"><a href="https://itbootcamp.rs/" class="logo-nav it-logo"><span class="it-span">IT</span>Bootcamp</a></p>
  </footer>
</body>
</html>