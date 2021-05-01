<!-- Author: Zeic Beniamin -->
<!DOCTYPE HTML>
<html>

<head>
</head>

<body>
<?php

require_once "../init.php";

$table_name = "schedule";
$webp_title = "Drivers table";

// Suppose inserted data is false - this prevents a database check when the
// page is first run
$isDataValid = false;

$r_column_names = query_column_names();
//echo "Query column names";
$q_data_insert = build_insertion_string($r_column_names);

$caller_path = preg_replace('#^https?://#', '', $_SERVER['HTTP_REFERER']);
$current_script_path = sprintf("%s%s", $_SERVER['HTTP_HOST'], $_SERVER['PHP_SELF']);

if ($isDataValid) {
    insertData($q_data_insert);
}
// Avoid showing warnings when user first enters page
elseif ($caller_path == $current_script_path) {
    echo "No empty data fields allowed";
}

function insertData($q_data_insert) {
    global $connection;
    try {
        mysqli_query($connection, $q_data_insert);
    }
    catch (Exception $exception) {
    }
    if(mysqli_errno($connection)) {
        echo "<br>Insertion failed<br>";
        echo mysqli_error($connection);
    } else {
        echo "<br> Insertion completed successfully.";
        echo "<br> Insert statement:";
        echo "<br>" . $q_data_insert;
    }

}

function build_insertion_string(&$r_column_names) {
    global $table_name;
    global $isDataValid;
    $isDataValid = true;

    // 'id' is the first column in every table
    $keys = "(`id`";
    $values = "VALUES (NULL";

//    echo "<br>Build insertion string - interior";

    while ($r = mysqli_fetch_array($r_column_names, MYSQLI_ASSOC)) {
//        echo "<br>Build insertion string - iteration";
        if(!empty($_POST[$r['COLUMN_NAME']]) &&
            $r['COLUMN_NAME'] != 'id') {
//            echo "<br>Build insertion string - iteration if";
            $keys = $keys . sprintf(", `%s`", $r['COLUMN_NAME']);
            $values = $values . sprintf(", '%s'", $_POST[$r['COLUMN_NAME']]);
        }
        elseif (empty($_POST[$r['COLUMN_NAME']])) {
            $isDataValid = false;
        }

    }

//    echo "<br>Build insertion string - after hwile";

    // Close the parentheses of the two sets of data
    $keys = $keys . ")";
    $values = $values . ")";

    return sprintf(
        "INSERT INTO `%s` %s %s",
        $table_name,
        $keys,
        $values
    );
}

function query_column_names() {
    global $table_name;
    global $connection;

    $q_table_columns = sprintf(
        "SELECT *
                FROM INFORMATION_SCHEMA.COLUMNS
                WHERE TABLE_NAME = '%s'", $table_name);
    $r_table_columns = mysqli_query($connection, $q_table_columns)
    or die("Query unsuccessful");

    return $r_table_columns;
}


//check_input_data($isDataValid, $nameErr, $passwordErr, $username, $password);
//$isValidUser = search_user_in_DB($isDataValid, $username, $password);





function check_input_data(&$isDataValid, &$nameErr, &$passwordErr, &$username, &$password) {
    // Check the username and password to meet certain criteria
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Suppose data is valid - if one of the further checks fails, the variable
        // will be set to false and data will be invalidated
        $isDataValid = true;
        if (empty($_POST["username"])) {
            $nameErr = "Username is required";
            $isDataValid = false;
        } else {
            $username = test_input($_POST["username"]);
            // Check that username only contains letters, numbers and underscores or periods
            if (!preg_match("/^[a-zA-z0-9_.]*$/", $username)) {
                $nameErr = "Only letters, numbers, periods and underscores allowed";
                $isDataValid = false;
            } elseif (empty($username)) {
                $nameErr = "User name must not contain trailing whitespaces";
                $isDataValid = false;
            }
        }

        if (empty($_POST["password"])) {
            $passwordErr = "Password is required";
        } else {
            $password = $_POST["password"];
            // do not trim whitespaces from password
            if (!strlen($password) > 2) {
                $passwordErr = "Password must have at least 3 characters";
                $isDataValid = false;
            }
        }
    }
}

function search_user_in_DB($isDataValid, $username, $password) {
    $isValidUser = false;
    // If data is valid, check it against users in the database
    if ($isDataValid) {
        require "init.php";

        // Query the database for the user and password entered in the login form
        $query_string = sprintf(
            "SELECT * FROM `accounts` WHERE user='%s' AND password='%s'",
            $username,
            $password
        );

        $result = mysqli_query($connection, $query_string)
        or die("<br>Querry fail");

        // If the result contains some data about
        if (mysqli_num_rows($result)) {
            $isValidUser = true;
        } else {
            $isValidUser = false;
        }
    }

    return $isValidUser;
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

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" >
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
            // Query columns of the table
            // As a convention, I tried to prepend the names of the variables that held a query string or a result with 'q' or with
            // 'r' respectively
            $q_table_columns = sprintf(
                "SELECT *
                FROM INFORMATION_SCHEMA.COLUMNS
                WHERE TABLE_NAME = '%s'", $table_name);
            $r_table_columns = mysqli_query($connection, $q_table_columns)
            or die("Query unsuccessful");

            $q_column_type = sprintf(
                "SELECT COLUMN_NAME, DATA_TYPE
            FROM information_schema.COLUMNS
            WHERE TABLE_NAME='%s'",
                $table_name
            );
            $r_column_type = mysqli_query($connection, $q_column_type)
            or die("Query unsuccessful");

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
</style>