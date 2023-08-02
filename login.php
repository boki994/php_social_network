<?php
require_once "bootstrap.php";
//cim treba nekako da koristimo sesiju, mora ova funkcija da se pozove
  session_start(); // Ova f-ja treba na pocetku(kao prva) da se pozove
  if(isset($_SESSION["id"]))
  {
    header("Location: index.php");
  }
  
  require_once "connection.php";

  $usernameError = "*";
  $passwordError = "*";
  $username = "";

  if($_SERVER["REQUEST_METHOD"] == "POST")
  {
    //korisnik je poslao username i pokusava logovanje
    //real escape string se poziva kada se sakupljaju polja iz forme a te vrednosti kasnije stavljamo u sql upit, Na siguran nacin se tretiraju vrednosti koje je korsinik uneo u formi
    $username = $conn->real_escape_string($_POST["username"]);
    $password = $conn->real_escape_string($_POST["password"]);


    //Vrsimo razlicite validacije
    if(empty($username))
    {
      $usernameError = "Username cannot be blank!";
    }

    if(empty($password))
    {
      $passwordError = "Password cannot be blank!";
    }
    if($usernameError == "*" && $passwordError == "*")
    {
      // Ovde mozemo da pokusamo da logujemo korisnika
      //(ako svi kredencijali za logovanje se podudaraju)
      $q = "SELECT * FROM `users` WHERE `username` = '$username';";
      $result = $conn->query($q);
      if($result->num_rows == 0)
      {
        $usernameError = "This username doesn't exist!";
      }
      else
      {
        // Postoji takav korisnik proveriti lozinke
        $row = $result->fetch_assoc();
        $dbPassword = $row["password"];  //heshirana vrednost iz baze
        if(!password_verify($password, $dbPassword)) //password verify vraca boolean
        {
          // Poklopili su se username ali lozinkan ije dobra
          $passwordError = "Wrong password, try again!";
        }
        else
        {
          // Dobri su i username i password, izvrsi logovanje
          $_SESSION["id"] = $row["id"];
          $_SESSION["username"] = $row["username"];
          header("Location:index.php");
        }
      }
    }
    //Ako je sve u redu, loguj korisnika
    
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="d-flex h-100 text-center text-white bg-dark">
<div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
  <?php require_once "header.php"; ?>
    <div class="container-fluid d-flex align-items-center justify-content-center pt-5 h-100">
      <div class="login">
        <h1>Please login</h1>
        <form action="#" method="post">
          <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" placeholder="Enter your username" value="<?php echo $username; ?>">
            <span class="error"><?php echo $usernameError ?></span>
          </div>
          <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password"placeholder="Enter your password">
            <span class="error"><?php echo $passwordError ?></span>
          </div>
          <div class="form-group">
            <input type="submit" value="Login" class="btn">
          </div>
        </form>
      </div>
</div>
</div>
<!-- footer -->
<footer class="mt-auto text-white footer-logo fixed-bottom w-100">
<p class="footer-text"><a href="https://itbootcamp.rs/" class="logo-nav it-logo"><span class="it-span">IT</span>Bootcamp</a></p>
</footer>
</body>
</html>