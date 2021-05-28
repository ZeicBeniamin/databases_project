<!-- Author: Zeic Beniamin -->
<!DOCTYPE HTML>
<html>

<head>
    <link rel="stylesheet" href="style/style.css">
    <script src="script/delete_d.js"></script>
</head>
<body>
<?php
require "../utils/init.php";
require_once "../utils/utils.php";
require_once "../utils/delete_utils.php";

$table_name = $_POST['table_name'];
$row_id = $_POST['row_id'];
$webp_title = ucfirst($table_name) . " table";
echo $webp_title;
echo " - DELETE";

// Delete data only if the user presses the button AND if an entity with the id displayed in the webpage exists
if (isCalledFromThis($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'], $_SERVER['PHP_SELF'])
    && isset($_POST['btnDelete'])) {
    // Build the SQL statement that will delete the entity with the given id
    $q_data_delete = build_deletion_string_robust($table_name, $row_id);
    // Check that t he id of the element to delete was transmitted as a parameter through the POST method.
    if ($row_id == 0 || $row_id != null) {
        deleteData($q_data_delete);
    } else {
        // If we have no id transmitted, we will not send the SQL deletion statement.
        echo "<p class='error'>There is no entity to be deleted</p> ";
    }
}
?>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <input type="hidden" name="table_name" value="<?= $table_name; ?>"/>
    <input type="hidden" name="row_id" value="<?= $row_id; ?>"/>

    <table>
        <colgroup>

            <col span="1" style="width: 20%">
            <col span="1" style="width: 20%">
            <col span="1" style="width: 20%">
            <col span="1" style="width: 20%">

        </colgroup>
        <?php
        print_table_header($table_name);
        print_entity_content_robust($table_name, $row_id, true);
        ?>
    </table>
    <button name='btnDelete' type="submit">Delete</button>
    <button name='btnBack' onclick="loadDeleteWebpage()" value="Back" type="button">Back</button>
</form>
</body>
</html>

<style>

</style>