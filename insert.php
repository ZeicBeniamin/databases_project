<!-- Author: Zeic Beniamin -->
<!DOCTYPE HTML>
<html>

<head>
    <link rel="stylesheet" href="insert_style.css">
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

$q_data_insert = build_insertion_string($table_name);

if (is_insert_data_valid($table_name)) {
    insertData($q_data_insert);
} // Avoid showing warnings when user first enters this page
elseif (isCalledFromThis($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'], $_SERVER['PHP_SELF'])) {
    echo "<p class='error'>" . "No empty data fields allowed" . "</p>";
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
            // Generate the table header using php
            print_table_header($table_name);
            ?>
        </tr>

        <tr>
            <?php
            // Generate the table body. It consists of a form in which the user will input data.
            print_insert_form($table_name);
            ?>
        </tr>

    </table>

    <button type="submit">Insert</button>
</form>


</body>
</html>

<style>
    table {
        background-color:  rgba(216,227,233,0.55);
    }
</style>