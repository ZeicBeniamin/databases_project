<!-- Author: Zeic Beniamin -->
<!DOCTYPE HTML>
<html>

<head>
</head>

<body>
<!--Trecut de body<br>-->
<?php
require_once "utils/init.php";
require_once "utils/select_utils.php";
require_once "utils/delete_utils.php";

session_start();
$table_name = $_SESSION['table'];
$webp_title = ucfirst($table_name) . " table";
echo $webp_title . "<br>";
echo "Delete";
?>
<form id="delete_form" method="POST" action="delete_d.php">
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
        // Print the table header.
        print_select_table_header($table_name);
        // Then print each entity of the table on one row.
        print_entities_checklist($table_name);
        ?>
    </table>

    <button type="button" onclick="checkAndSubmit()">Delete</button>
</form>

</body>
</html>

<script>
    function checkAndSubmit() {
        let checked = [...document.getElementsByClassName('id_button')].some(c => c.checked);
        if (checked) {
            document.getElementById("delete_form").submit();
        } else if (!document.getElementById("warning")) {
            let textField = document.createElement("h6");
            textField.setAttribute("class", "error");
            // textField.setAttribute("text");
            textField.innerText = "Please select one of the rows."
            textField.setAttribute("id", "warning");
            document.body.appendChild(textField)
        }

    }
</script>

<style>
    table {
        background-color: rgba(216, 227, 233, 0.55);
    }

    .error {
        font-size: 0.9rem;
        color: red;
        background-color: rgba(255, 255, 255,.9);
        display : block;
        max-width: 20vw;
    }
</style>
