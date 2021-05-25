<!-- Author: Zeic Beniamin -->
<!DOCTYPE HTML>
<html>

<head>
    <link rel="stylesheet" href="insert_style.css">
</head>

<body>
<?php

require "utils/insert_utils.php";

session_start();
$table_name = $_SESSION['table'];
$webp_title = ucfirst($table_name) . " table";
echo $webp_title . "<br>";

$q_data_insert = build_insertion_string($table_name);

if (is_data_non_empty($table_name)) {
    // String building should be found inside this if
    //$q_data_insert = build_insertion_string($table_name);
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

        <?php
        // Generate the table header using php
        print_table_header($table_name);
        // Generate the table body. It consists of a form in which the user will input data.
        print_insert_form($table_name);
        ?>

    </table>

    <button type="submit">Insert</button>
</form>


</body>
</html>

<style>
    table {
        background-color: rgba(216, 227, 233, 0.55);
    }
</style>