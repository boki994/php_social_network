<?php
require_once "bootstrap.php";
require_once "connection.php";
require_once "validation.php";
session_start();
if(!isset($_SESSION["id"]))
{
  header("Location: index.php");
}
$sucMessage = $errMessage = "";


$id=$_SESSION["id"];


if($_SERVER["REQUEST_METHOD"] == "POST")
{
  $deleteQuery = "DELETE FROM `profiles` WHERE `id_user` = $id;";
  $deleteQuery .= "DELETE FROM `users` WHERE `id` = $id;";
  $conn->multi_query($deleteQuery);
  

  unset($_SESSION["id"]);
  session_destroy();

  header("Location: register.php");
  exit;
  
  
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Delete profile</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="d-flex h-100 text-center text-white bg-dark">
<div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
  <?php require_once "header.php"; ?>
    <div class="container-fluid d-flex align-items-center justify-content-center pt-5 h-100">
      <div class="login">
        <h1>Are you sure you want to delete your account</h1>
        <form action="#" method="post">
          <div class="form-group">
            <input type="submit" value="DELETE PROFILE" class="btn btn-danger">
          </div>
        </form>
      </div>
</div>
</div>
  
  

</body>
</html>