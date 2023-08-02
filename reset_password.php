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
$passwordNewError = $passwordOldError = $retypeError = "";

$id=$_SESSION["id"];
$qPass = "SELECT `password` FROM `users` WHERE `id` = $id;";

$result = $conn->query($qPass);

$row= $result->fetch_assoc(); 
$password = $row['password'];


if($_SERVER["REQUEST_METHOD"] == "POST")
{

  $passwordNew = $conn->real_escape_string($_POST['new_password']);
  $retype = $conn->real_escape_string($_POST['retype_password']);
  $passwordOld = $conn->real_escape_string($_POST['old_pass']);

  $passwordNewError = passwordValidation($passwordNew);
  $passwordOldError = passwordValidation($passwordOld);
  $retypeError = passwordValidation($retype);

  if($passwordNewError == "" && $passwordOldError == "" && $retypeError =="")
  {
    $q = "";
    if (password_verify($passwordOld, $password))
    {
      if($passwordNew === $retype)
      {
        $passwordNew = password_hash($passwordNew, PASSWORD_DEFAULT);
        $q = "UPDATE `users`
        SET `password` = '$passwordNew' 
        WHERE `id` = $id;";

        if($conn->query($q))
        {
          $sucMessage = "You have changed your profile";
        }
        else
        {
          // desila se greska u u pitu
          $errMessage = "Error chaning password: " . $conn->error;
        }
      }
      else
      {
        $retypeError = "You must enter two same passwords";
      }
    } 
    else
    {
      $passwordOldError = "Invalid password";
    }
    
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Change your password</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="d-flex h-100 text-center text-white bg-dark">
<div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
  <?php require_once "header.php"; ?>
    <div class="container-fluid d-flex align-items-center justify-content-center pt-5 h-100">
      <div class="login">
        <h1>Please fill out the form to change your password</h1>
        <form action="#" method="post">
          <div class="form-group">
            <label for="password">Old Password:</label>
            <input type="password" name="old_pass" id="old_pass" placeholder="Enter your old password" value="">
            <span class="error"><?php echo $passwordOldError ?></span>
          </div>
          <div class="form-group">
            <label for="password">New Password:</label>
            <input type="password" name="new_password" id="new_password"placeholder="Enter your new password">
            <span class="error"><?php echo $passwordNewError ?></span>
          </div>
          <div class="form-group">
            <label for="password">Retype new password:</label>
            <input type="password" name="retype_password" id="retype_password">
            <span class="error"><?php echo $retypeError ?></span>
          </div>
          <div class="form-group">
            <input type="submit" value="Change passwords" class="btn btn-info">
          </div>
        </form>
      </div>
</div>
</div>
  
  

</body>
</html>