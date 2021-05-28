<!-- Author: Zeic Beniamin -->
<!DOCTYPE HTML>
<html>

<head>
    <link rel="stylesheet" href="style/style.css">
    <script src="script/delete.js"></script>
</head>

<body>
<!--Trecut de body<br>-->
<?php
require_once "../utils/init.php";
require_once "../utils/select_utils.php";
require_once "../utils/delete_utils.php";

session_start();
$table_name = $_SESSION['table'];
$webp_title = ucfirst($table_name) . " table";
echo $webp_title;
echo " - DELETE";
?>
<form id="delete_form" method="POST" action="delete_d.php">
    <!-- Pass the table name as an argument to the PHP script. -->
    <input type="hidden" name="table_name" value="<?= $table_name; ?>"/>
    <button type="button" onclick="checkAndSubmit()">Delete</button>
    <table border="1px">

        <colgroup>

            <col span="1" style="width: 20%">
            <col span="1" style="width: 20%">
            <col span="1" style="width: 20%">
            <col span="1" style="width: 20%">

        </colgroup>

        <?php
        // Print the table header.
        print_select_table_header($table_name);
        // Then print each entity of the table on one row.
        print_entities_checklist_robust($table_name);
        ?>
    </table>

    <button type="button" onclick="checkAndSubmit()">Delete</button>
</form>

</body>
</html>