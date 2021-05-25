<!-- Author: Zeic Beniamin -->
<!DOCTYPE HTML>
<html>

<head>
</head>
<body>
<?php
require "utils/init.php";
require "utils/utils_update.php";
require "utils/insert_utils.php";

$table_name = $_POST['table_name'];
$id = $_POST['id'];
$webp_title = ucfirst($table_name) . " table";
echo $webp_title . "<br>";
// Suppose inserted data is false - this prevents a database check when the
// page is first run
$isDataValid = false;

$isDataValid = is_data_non_empty($table_name);
$r_column_names = query_column_names($table_name);
$q_data_insert = build_update_string($table_name, $id);

$caller_path = preg_replace('#^https?://#', '', $_SERVER['HTTP_REFERER']);
$current_script_path = sprintf("%s%s", $_SERVER['HTTP_HOST'], $_SERVER['PHP_SELF']);

if ($isDataValid) {
    insertData($q_data_insert);
} // Avoid showing warnings when user first enters page
elseif ($caller_path == $current_script_path) {
    echo "<p class='error'>" . "Invalid data - some fields might be empty" . "</p>";
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
            // Generate the table header using php
            print_table_header($table_name);
            ?>
        </tr>

        <tr>

            <?php

            print_entity_content($table_name, $id);

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