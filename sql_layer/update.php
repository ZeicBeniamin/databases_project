<!-- Author: Zeic Beniamin -->
<!DOCTYPE HTML>
<html>

<head>
    <link rel="stylesheet" href="style/style.css">
    <script src="script/update.js"></script>
</head>

<body>
<?php
require "../utils/init.php";
require "../utils/select_utils.php";
// Get the table name and show it on the webpage
session_start();
$table_name = $_SESSION['table'];
$webp_title = ucfirst($table_name) . " table";
echo $webp_title . " - UPDATE";
?>

<form id="update_form" method="POST" action="update_i.php">
    <!-- Pass the table name as an argument to the PHP script. -->
    <input type="hidden" name="table_name" value="<?= $table_name; ?>"/>
    <table border="1px">
        <colgroup>

            <col span="1" style="width: 20%">
            <col span="1" style="width: 20%">
            <col span="1" style="width: 20%">
            <col span="1" style="width: 20%">

        </colgroup>
        <?php
        print_select_table_header($table_name);
        print_entities_checklist($table_name);
        ?>
    </table>
    <button type="button" onclick="checkAndSubmit()">Modify</button>
</form>

</body>
</html>
