<!-- Author: Zeic Beniamin -->
<!DOCTYPE HTML>
<html>

<head>
</head>
<body>
<?php
require "init.php";

$table_name = $_POST['table_name'];
$id = $_POST['id'];
$webp_title = ucfirst($table_name) . " table";
echo $webp_title . "<br>";
// Suppose inserted data is false - this prevents a database check when the
// page is first run
$isDataValid = false;

$r_column_names = query_column_names();
//echo "Query column names";
$q_data_insert = build_insertion_string($r_column_names);
//echo "<br>Updating string" . $q_data_insert;

$caller_path = preg_replace('#^https?://#', '', $_SERVER['HTTP_REFERER']);
$current_script_path = sprintf("%s%s", $_SERVER['HTTP_HOST'], $_SERVER['PHP_SELF']);

if ($isDataValid) {
    insertData($q_data_insert);
} // Avoid showing warnings when user first enters page
elseif ($caller_path == $current_script_path) {
    echo "<p class='error'>" . "Invalid data - some fields might be empty" . "</p>";
}

function insertData($q_data_insert)
{
    global $connection;
    try {
        mysqli_query($connection, $q_data_insert);
    } catch (Exception $exception) {
    }
    if (mysqli_errno($connection)) {
        echo "<br>Update failed<br>";
        echo mysqli_error($connection);
        echo "<br>" . $q_data_insert;

    } else {
        echo "<br> Update completed successfully.";
        echo "<p class='sql_code'> Update statement:";
        echo "<br>" . $q_data_insert . "</p>";
    }

}

function build_insertion_string(&$r_column_names)
{
    global $table_name;
    global $isDataValid;
    global $id;
    $isDataValid = true;

    // 'id' is the first column in every table
    $keys = "";

//    echo "<br>Build insertion string - interior";

    while ($r = mysqli_fetch_array($r_column_names, MYSQLI_ASSOC)) {
//        echo "<br>Build insertion string - iteration";
//        echo("<br>while " . $_POST[$r['COLUMN_NAME']] . "=>" . $r['COLUMN_NAME']);
        if (!empty($_POST[$r['COLUMN_NAME']]) &&
            $r['COLUMN_NAME'] != 'id') {
//            echo "<br>Build insertion string - iteration if";
            $keys = $keys . sprintf(", `%s` = '%s'", $r['COLUMN_NAME'], $_POST[$r['COLUMN_NAME']]);
        } elseif (empty($_POST[$r['COLUMN_NAME']])) {
            $isDataValid = false;
        }

    }

//    echo "<br>Build insertion string - after hwile";

    return sprintf(
        "UPDATE `%s`
                SET %s
                WHERE `%s`.`id` = %s",
        $table_name,
        ltrim($keys, ','),
        $table_name,
        $id
    );
}

function query_column_names()
{
    global $table_name;
    global $connection;

    $q_table_columns = sprintf(
        "SELECT *
                FROM INFORMATION_SCHEMA.COLUMNS
                WHERE TABLE_NAME = '%s'", $table_name);
//    echo "<br>" . $q_table_columns;
    $r_table_columns = mysqli_query($connection, $q_table_columns)
    or die("Query column names unsuccessful");
    if (mysqli_errno($connection)) {
        echo(mysqli_errno($connection));
    }

    return $r_table_columns;
}

// Test data in order to avoid attacks
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <input type="hidden" name="table_name" value="<?= $table_name; ?>"/>

    <table>

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

        <tr>

            <?php

            $q_table_columns = sprintf(
                "SELECT *
                FROM INFORMATION_SCHEMA.COLUMNS
                WHERE TABLE_NAME = '%s'", $table_name);
            $r_table_columns = mysqli_query($connection, $q_table_columns)
            or die("Query unsuccessful");


            $q_table_data = sprintf("SELECT * FROM %s WHERE `id` = %d", $table_name, $id);
            //            echo $q_table_data;
            $r_table_data = mysqli_query($connection, $q_table_data)
            or die("Query unsuccessful");
            //
            //            // Generate the table rows using php
            //            while ($r = mysqli_fetch_array($r_table_data, MYSQLI_ASSOC)) {
            //                echo "<tr>";
            //                echo(sprintf("<td> <input class='id_button' type='radio' name='id' value='%s'></td>", $r['id']));
            //                // Make a new cell out of each data field in the currently processed row.
            //                foreach (array_keys($r) as $column_name) {
            //                    echo(sprintf("<td id='%s'> %s </td>", $column_name . $r['id'], $r[$column_name]));
            //                }
            //                echo "</tr>";
            //            }

            // The result will contain only one array, so we fetch it
            $data = mysqli_fetch_array($r_table_data, MYSQLI_ASSOC);

            // Generate the table header using php
            while ($r = mysqli_fetch_array($r_table_columns, MYSQLI_ASSOC)) {
                switch ($r['DATA_TYPE']) {
                    case 'int':
                        $input_type = 'number';
                        $width = '20px';
                        break;
                    case 'varchar':
                        $input_type = 'text';
                        $width = '40px';
                        break;
                    case 'date':
                        $input_type = 'date';
                        $width = '40px';
                        break;
                }

                if ($r['COLUMN_NAME'] == 'id') {
                    echo(
                    sprintf("<td><input name='id' type='text' value='%s' readonly></input></td>",$data[$r['COLUMN_NAME']])
                    );
                } else {
                    echo(sprintf(
                        "<td><input name='%s' type='%s' id='%s' value='%s'></input></td>",
                        $r['COLUMN_NAME'],
                        $input_type,
                        $r['COLUMN_NAME'],
                        $data[$r['COLUMN_NAME']]
                    ));
                }


            }

            ?>

        </tr>

    </table>

    <button type="submit">Update</button>
</form>


</body>
</html>


<style>
    input {
        font-size: 0.7rem;
        display: block;
        height: 0.9rem;
    }

    input[type=date] {
        min-width: 6rem;
        width: 93%;
    }

    input[type=number] {
        width: 93%;
    }

    input[type=text] {
        width: 93%;
    }

    button {
        padding: 0.1rem;
        height: 1.2rem;
    }

    table {
        border-color: black;
        border-style: solid;
        border-width: 1px;
        border-collapse: collapse;
    }

    .sql_code {
        font-family: "Courier New", monspace;
        font-size: 0.55rem;
        font-size: 0.6rem;
    }

    .error {
        color: red;
    }
</style>