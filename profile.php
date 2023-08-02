<?php
  include "bootstrap.php";
  session_start();
  if(!isset($_SESSION["id"]))
  {
    header("Location: index.php");
  }

  $id = $_SESSION["id"];
  $firstName = $lastName = $dob = $gender = "";
  $bio = "";
  $firstNameError = $lastNameError = $dobError = $genderError = "";
  $photoError = "";

  $succMessage = "";
  $errMessage = "";

  require_once "connection.php";
  require_once "validation.php";

  $profileRow = profileExists($id, $conn);
  // profileRow = false, ako profl ne postoji
  // $profileRow = asocijativni niz, ako profil postoji
  if($profileRow !== false)
  {
    $firstName = $profileRow["first_name"];
    $lastName = $profileRow["last_name"];
    $gender = $profileRow["gender"];
    $dob = $profileRow["dob"];
    $photo = $profileRow["photo"];
    $bio = isset($_POST["bio"]) ? $_POST["bio"] : $profileRow["bio"];
  }

  if($_SERVER["REQUEST_METHOD"] == "POST")
  {

    $firstName = $conn->real_escape_string($_POST["first_name"]);
    $lastName = $conn->real_escape_string($_POST["last_name"]);
    $gender = $conn->real_escape_string($_POST["gender"]);
    $dob = $conn->real_escape_string($_POST["dob"]);

    // Vrsimo validaciju polja
    $firstNameError = nameValidation($firstName);
    $lastNameError = nameValidation($lastName);
    $genderError = genderValidation($gender);
    $dobError = dobValidation($dob);
    $photoError = photoValidation($_FILES["photo"]);

    // Ako je sve u redu, ubacujemo novi red u tabelu `profiles`
    if($firstNameError == "" && $lastNameError == "" && $genderError == "" && $dobError == "" && $photoError == "")
    {
      if($_FILES["photo"]["size"] > 0)
      {
        $filename = $_FILES["photo"]["name"]; //biranje imena fajla iz superglobala $_FILES
        $destination = __DIR__."/img/".$filename; // destinacija gde ce temp file da se premesti
        move_uploaded_file($_FILES["photo"]["tmp_name"], $destination); // funkcija za premestanje temp fajla
        $photo = "img/".$filename;// promenljiva koja se koristi da se doda ime fajla u tabelu baze
      }else
      {
        $photo = "";
      }
      

        $q = "";
      if($profileRow === false)
      {
        $q = "INSERT INTO `profiles`(`first_name`, `last_name`, `gender`, `dob`, `photo`, `id_user`, `bio`)
        VALUE
        ('$firstName', '$lastName', '$gender', '$dob', '$photo', $id, '$bio')";
      }
      else
      {
        $q =  "UPDATE `profiles`
        SET `first_name` = '$firstName',
        `last_name` = '$lastName',
        `gender` = '$gender',
        `dob` = '$dob',
        `photo` = '$photo',
        `bio` = '$bio'
        WHERE `id_user` = $id";
      }
      if($conn->query($q))
      {
        // Uspesno kreiran ili editovan profil
        if($profileRow !== false)
        {
          $succMessage = "You have edited your profile";
        }
        else
        {
          $succMessage = "You have created your profile";
        }
        
      }
      else
      {
        // Desila se greska u upitu
        $errMessage = "Error creating profile: ". $conn->error;
      }
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="d-flex h-100 text-center text-white bg-dark">
  
  <div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
    <?php require_once "header.php"; ?>
  <!-- alert zelen za poruku ako je prazna ne prikazuje se-->
    <?php if(!empty($succMessage)) { ?>
  <div class="alert alert-info alert-dismissible fade show mx-auto" role="alert">
    <div class="success">
      <?php echo $succMessage; ?>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
      </button>
    </div>
  </div>
  <?php } ?>
  <!-- alert crven -->
  <?php if(!empty($errMessage)) { ?>
  <div class="alert alert-warning alert-dismissible fade show mx-auto" role="alert">
    <div class="success">
      <?php echo $errMessage; ?>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
      </button>
    </div>
  </div>
  <?php } ?>
  <!-- forma -->
      <div class="container-fluid d-flex align-items-center justify-content-center pt-5 form-custom">
        <div class="login mt-5">
        <p class="lead">Please fill out your profile details</p>
            <form action="#" method="post" enctype="multipart/form-data"> <!-- enctype -enkoding koji dozvoljava da se faljovi salju kroz post metodu -->
            <div class="form-group">
              <label for="first_name">First Name:</label>
              <input type="text" name="first_name" id="first_name" value="<?php echo $firstName ?>">
              <span class="error">* <?php echo $firstNameError ?> </span>
            </div>
            <div class="form-group">
              <label for="last_name">Last name:</label>
              <input type="text" name="last_name" id="last_name" value="<?php echo $lastName ?>">
              <span class="error">*<?php echo $lastNameError ?> </span>
            </div>
            <div class="form-group">
              <label for="gender">Gender:</label>
              <div class="form-check">
                <input type="radio" name="gender" id="m" value="m" <?php if($gender == "m"){echo "checked";} ?> class="form-check-input">
                <label for="m" class="form-check-label">Male</label>
              </div>
              <div class="form-check">
                <input type="radio" name="gender" id="f" value="f" <?php if($gender == "f"){echo "checked";} ?> class="form-check-input">
                <label for="f" class="form-check-label">Female</label>
              </div>
              <div class="form-check">
                <input type="radio" name="gender" id="o" value="o" <?php if($gender == "o" || $gender == ""){echo "checked";} ?> class="form-check-input">
                <label for="o" class="form-check-label">Other</label>
              </div>
              <span class="error"><?php echo $genderError; ?></span>
            </div>
            <div class="form-group">
              <label for="dob">Date of birth:</label>
              <input type="date" name="dob" id="dob" value="<?php echo $dob; ?>">
              <span class="error"><?php echo $dobError ?></span>
            </div>
            <div class="form-group">
              <label for="image"><?php echo ($profileRow === false) ? 'Upload Profile picture':'Change profile picture' ?></label>
              <input type="file" name="photo" id="photo" class="btn">
              <span class="error"><?php echo $photoError; ?></span>
            </div>
            <div class="form-group">
              <label for="bio">Your Bio:</label>
              <textarea name="bio" id="bio"><?php echo $bio ?></textarea>
            </div>
            <div class="form-group">
              <input type="submit" value="<?php echo ($profileRow === false) ? 'Create profile':'Edit profile' ?>" class="btn">
            </div>
          </form>
          <p>
            Go back to <a class="btn btn-info" href="index.php">Home page</a>
          </p>
        </div>  
      </div>
  </div>
  <!-- footer -->
  <footer class="mt-5 text-white footer-logo fixed-bottom w-100 bg-dark">
    <p class="footer-text"><a href="https://itbootcamp.rs/" class="logo-nav it-logo"><span class="it-span">IT</span>Bootcamp</a></p>
  </footer>
</body>
</html>