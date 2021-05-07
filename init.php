<?php

$dbServer = "localhost";
$dbUsername = "root";
$dbPass = "";
$db = "public_transport";
$connection = mysqli_connect($dbServer, $dbUsername, $dbPass, $db);

if (mysqli_connect_errno()) {
    die("Connection failed: <br>" . mysqli_connect_error());
} else {
    echo "";
}

if (!isset($connection->server_info)) {
    echo "Sql connection closed";
}

?>
