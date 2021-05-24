<!-- Author: Zeic Beniamin -->
<!DOCTYPE HTML>
<html>

<head>
</head>

<body>
<?php


require "init.php";
require "utils.php";
require "insert_utils.php";

session_start();
$table_name = $_SESSION['table'];
$webp_title = ucfirst($table_name) . " table";
echo $webp_title . "<br>";

// Suppose inserted data is false - this prevents a database check when the
// page is first run
$isDataValid = false;

$r_column_names = query_column_names($table_name);
//echo "Query column names";
$q_data_insert = build_insertion_string($r_column_names);

if ($isDataValid) {
    insertData($q_data_insert);
} // Avoid showing warnings when user first enters this page
elseif (isCalledFromThis($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'], $_SERVER['PHP_SELF'])) {
    echo "<p class='error'>" . "No empty data fields allowed" . "</p>";
}


/**
 * Insert data in database, using on a given SQL query
 *
 * Calls th function "query_db", that establishes a connection with the database and then runs the SQL query
 * @param string $q_data_insert SQL statement to be used for inserting data
 */
function insertData($q_data_insert)
{
    // Show a message if the insertion was succesful
    if (query_db($q_data_insert)) {
        echo "<br> Insertion completed successfully.";
        echo "<p class='sql_code'> Insert statement:";
        echo "<br>" . $q_data_insert . "</p>";
    }
}

?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
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
                WHERE TABLE_NAME = '%s'
                ORDER BY ORDINAL_POSITION", $table_name);
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
            // Query columns of the table
            // As a convention, I tried to prepend the names of the variables that held a query string or a result with 'q' or with
            // 'r' respectively
            $q_table_columns = sprintf(
                "SELECT *
                FROM INFORMATION_SCHEMA.COLUMNS
                WHERE TABLE_NAME = '%s'
                ORDER BY ORDINAL_POSITION", $table_name);
            $r_table_columns = mysqli_query($connection, $q_table_columns)
            or die("Query unsuccessful");

            // Generate the content cells using php
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
                    "<td><input name='id' type='text' value='Autoincrement' readonly></input></td>"
                    );
                } else {
                    echo(sprintf(
                        "<td><input name='%s' type='%s' id='$s'></input></td>",
                        $r['COLUMN_NAME'],
                        $input_type,
                        $r['COLUMN_NAME']
                    ));
                }


            }

            ?>

        </tr>

    </table>

    <button type="submit">Insert</button>
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