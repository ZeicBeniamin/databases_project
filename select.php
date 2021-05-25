<!-- Author: Zeic Beniamin -->
<!DOCTYPE HTML>
<html>

<head>
</head>

<body>
<?php

require_once "utils/init.php";
require "utils/utils.php";
require "utils/select_utils.php";

//echo "Trecut de utils/init.php <br>";
// Define the required data
session_start();
$table_name = $_SESSION['table'];
$webp_title = ucfirst($table_name) . " table";
echo $webp_title . "<br>";

?>

<table border="1px">

    <colgroup>

        <col span="1" style="width: 20%">
        <col span="1" style="width: 20%">
        <col span="1" style="width: 20%">
        <col span="1" style="width: 20%">

    </colgroup>


    <?php
    print_table_header($table_name);
    print_entities($table_name);
    ?>
</table>


</body>
</html>

<style>
    .sql_code {
        font-family: "Courier New", monspace;
        font-size: 0.55rem;
        font-size: 0.6rem;
    }

    table {
        background-color: rgba(216, 227, 233, 0.55);
        border-collapse: collapse;
    }
</style>