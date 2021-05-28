<!-- Author: Zeic Beniamin -->
<!DOCTYPE HTML>

<html>
<head>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>

<?php
require_once "../utils/init.php";
require "../utils/select_utils.php";
// Define the required data
session_start();
$table_name = $_SESSION['table'];
$webp_title = ucfirst($table_name) . " table";
echo $webp_title . " - SELECT";
?>

<table border="1px">
    <colgroup>
        <col span="1" style="width: 20%">
        <col span="1" style="width: 20%">
        <col span="1" style="width: 20%">
        <col span="1" style="width: 20%">
    </colgroup>
    <?php
    print_table_header($table_name);
    print_all_rows($table_name);
    ?>
</table>

</body>
</html>
