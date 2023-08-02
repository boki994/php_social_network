<?php

function usernameValidation($u, $c)
{
    $query = "SELECT * FROM `users` WHERE `username` = '$u'";
    $result = $c->query($query);

    if (empty($u))
    {
        return "Username cannot be blank";
    }
    elseif (preg_match('/\s/', $u))
    {
        return "Username cannot contain spaces";
    }
    elseif (strlen($u) < 5 || strlen($u) > 25)
    {
        return "Username must be between 5 and 25 characters";
    }
    elseif ($result->num_rows > 0)
    {
        return "Username is reserved, please choose another one";
    }
    else
    {
        return "";
    }
}

function passwordValidation($u)
{
    if (empty($u))
    {
        return "Password cannot be blank";
    }
    elseif (preg_match('/\s/', $u))
    {
        return "Password cannot contain spaces";
    }
    elseif (strlen($u) < 5 || strlen($u) > 50)
    {
        return "Password must be between 5 and 50 characters";
    }
    else
    {
        return "";
    }
}

function nameValidation($n)
{
    $n = str_replace(' ', '', $n);
    if (empty($n))
    {
        return "Name cannot be empty";
    }
    elseif (strlen($n) > 50)
    {
        return "Name cannot contain more than 50 characters";
    }
    elseif (preg_match("/^[a-zA-ZŠšĐđŽžČčĆć]+$/", $n) == false)
    {
        return "Name must contain only letters";
    }
    else
    {
        return "";
    }
}

function genderValidation($g)
{
    if($g != "m" && $g != "f" && $g != "o")
    {
        return "Uknown gender";
    }
    else
    {
        return "";
    }
}

function dobValidation($d)
{
    if (empty($d))
    {
        return ""; // ok je da dob bude prazno
    }
    elseif ($d < "1900-01-01")
    {
        return "Date of birth not valid";
    }
    else
    {
        return "";
    }
}

function profileExists($id, $conn)
{
    $q = "SELECT * FROM `profiles` WHERE `id_user` = $id";
    $result = $conn->query($q);
    if($result->num_rows == 0)
    {
        return false;
    }
    else
    {
        $row = $result->fetch_assoc();
        return $row;
    }
}

function photoValidation($photo)
{
    $extensions = ["jpg","jpeg","png"];
    $maxFileSize = 10000000; //10MB
    // kada se uploaduju fajlovi preko forme informacije se cuvaju u superglobalu $_FILES koji sadrzi elemente kao asocijativni array->
    // $fileName = $photo["name"];
    // $fileSize = $photo["size"];
    // $fileTmp = $photo["tmp_name"];
    // $fileError = $photo["error"];
    $fileExtensions = strtolower(pathinfo($photo["name"], PATHINFO_EXTENSION));// cuvanje za koji tip exntenzije je fajl
    if($photo["size"] == 0) // prazno ako korisnik ne zeli da postavi sliku
    {
        return "";
    }

    if(!in_array($fileExtensions, $extensions))
    {
        return "Error! file format must be '.JPG','.JPEG' or'.PNG'";
    }
    elseif($photo["size"] > $maxFileSize)
    {
        return "Error! file size must not exceed: 10MB!";
    }
    elseif($photo["error"] !== UPLOAD_ERR_OK)
    {
        return "An error ocured while uploading your file. Please try again";
    }
    else
    {
        return "";
    }
}

function oldPassValidation($u, $c)
{
    $query = "SELECT * FROM `users` WHERE `password` = '$u'";
    $result = $c->query($query);
    if (empty($u))
    {
        return "Password cannot be blank";
    }
    elseif (preg_match('/\s/', $u))
    {
        return "Password cannot contain spaces";
    }
    elseif (strlen($u) < 5 || strlen($u) > 50)
    {
        return "Password must be between 5 and 50 characters";
    }
    elseif($u != $result)
    {
        return "The provided password does not match your old one";
    }
    else
    {
        return "";
    }
}

?>