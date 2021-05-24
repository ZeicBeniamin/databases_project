<!-- Author: Zeic Beniamin -->
<!DOCTYPE HTML>
<html>

<head>
</head>

<body>
<?php

require_once "init.php";

//echo "Trecut de init.php <br>";
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

    <tr>

        <?php
        // Query columns of the table
        // As a convention, I tried to prepend the names of the variables that held a query string or a result with 'q' or with
        // 'r' respectively
        $q_table_columns = sprintf(
            "SELECT *
                FROM INFORMATION_SCHEMA.COLUMNS
                WHERE TABLE_NAME = '%s'", $table_name);
        $r_table_columns = mysqli_query($connection, $q_table_columns)
        or die("Query unsuccessful");

        // Generate the table header using php
        while ($r = mysqli_fetch_array($r_table_columns, MYSQLI_ASSOC)) {
            echo(sprintf("<th><b>%s</b></th>", ucfirst($r['COLUMN_NAME'])));
            echo("<br>");
        }

        ?>

    </tr>

    <?php
    // Query data of the drivers
    $q_table_data = sprintf("SELECT * FROM %s", $table_name);
    $r_table_data = mysqli_query($connection, $q_table_data)
    or die("Query unsuccessful");

    // Generate the table rows using php
    while ($r = mysqli_fetch_array($r_table_data, MYSQLI_ASSOC)) {
        echo "<tr>";
        // Make a new cell out of each data field in the currently processed row.
        foreach (array_keys($r) as $column_name) {
            echo (sprintf("<td> %s </td>", $r[$column_name]));
        }
        echo "</tr>";
    }

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
        background-color:  rgba(216,227,233,0.55);
    }
</style>