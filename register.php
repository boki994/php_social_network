<?php
require_once "bootstrap.php";
    // Ne dozvoljavamo pristup ovoj stranici logovanim korisnicima
    session_start();
    if(isset($_SESSION["id"]))
    {
        header("Location: index.php");
    }
    require_once "connection.php";
    require_once "validation.php";

    $usernameError = "";
    $passwordError = "";
    $retypeError = "";
    $username = "";
    $password = "";
    $retype = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // forma je poslata, treba pokupiti vrednosti iz polja

        $username = $conn->real_escape_string($_POST["username"]);
        $password =  $conn->real_escape_string($_POST["password"]);
        $retype = $conn->real_escape_string($_POST["retype"]);

        // 1) Izvrsiti validaciju za $username
        $usernameError = usernameValidation($username, $conn);

        // 2) Izvrsiti validaciju za $password
        $passwordError = passwordValidation($password);

        // 3) Izvrsiti validaciju za $retype
        $retypeError = passwordValidation($retype);
        if ($password !== $retype)
        {
            $retypeError = "You must enter two same passwords";
        }

        // 4.1) Ako su sva polja validna, onda treba dodati novog korisnika
        // (treba izvrsiti INSERT upit nad tabelom users)
        if ($usernameError == "" && $passwordError == "" && $retypeError == "")
        {
            // lozinka treba prvo da se sifruje
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $q = "INSERT INTO `users`(`username`, `password`)
            VALUE
            ('$username', '$hash');";

            if($conn->query($q))
            {
                // Kreirali smo novog korisnika, vodi ga na stranicu za logovanje
                header("Location: index.php?p=ok");
            }
            else
            {  header("Location: error.php?" . http_build_query(['m' => "Greska kod kreiranja usera"]));
              
            }
        }

        // 4.2) Ako postoji neko polje koje nije validno, ne raditi upit
        // nego vratiti korisnika na stranicu i prikazati poruke
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register new user</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="d-flex h-100 text-center text-white bg-dark">
<div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
<?php require_once "header.php"; ?>
    <div class="container-fluid d-flex align-items-center justify-content-center pt-5 h-100">
        <div class="login">
            <h1>Register to our site</h1>
        <form action="register.php" method="POST">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" value="<?php echo $username; ?>">
            <span class="error">* <?php echo $usernameError; ?></span>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" value="">
            <span class="error">* <?php echo $passwordError; ?></span>
        </div>
        <div class="form-group">
            <label for="retype">Retype password:</label>
            <input type="password" name="retype" id="retype" value="">
            <span class="error">* <?php echo $retypeError; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" value="Register me!" class="btn">
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