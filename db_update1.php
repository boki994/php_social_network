<?php
require_once "connection.php";
$sql = "";

$sql = "ALTER TABLE `profiles`
ADD COLUMN
`bio` TEXT
";

if ($conn->query($sql) === TRUE) {
  echo "Table created successfully";
} else {
  echo "Error creating table: " . $conn->error;
}


?>