<!-- Author: Zeic Beniamin -->
<!DOCTYPE HTML>
<html>

<head>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
<?php
require "../utils/init.php";
require "../utils/update_utils.php";
require "../utils/insert_utils.php";

$table_name = $_POST['table_name'];
$id = $_POST['id'];
$webp_title = ucfirst($table_name) . " table";
echo $webp_title . " - UPDATE";
// Suppose inserted data is false - this prevents a database check when the
// page is first run
$isDataValid = false;

$r_column_names = query_column_names($table_name);
$q_data_insert = build_update_string($table_name, $id);


if (is_data_non_empty($table_name)) {
    insertData($q_data_insert);
} // Avoid showing warnings when user first enters page
elseif (isCalledFromThis($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'], $_SERVER['PHP_SELF'])) {
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
        <?php
        // Generate the table header using php
        print_table_header($table_name);
        print_entity_content($table_name, $id);
        ?>
    </table>
    <button type="submit">Update</button>
</form>
</body>
</html>