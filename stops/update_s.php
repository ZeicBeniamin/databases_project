<!-- Author: Zeic Beniamin -->
<!DOCTYPE HTML>
<html>

<head>
</head>

<body>
<!--Trecut de body<br>-->
<?php
require_once "../init.php";

//echo "Trecut de init.php <br>";
// Define the required data
$table_name = "stops";

?>
<form id="update_form" method="POST" action="update_i.php">
    <!-- Pass the table name as an argument to the PHP script. -->
    <input type="hidden" name="table_name" value="<?=$table_name;?>"/>
    <table border="1px">

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

        <?php
        // Query data of the drivers
        $q_table_data = sprintf("SELECT * FROM %s", $table_name);
        $r_table_data = mysqli_query($connection, $q_table_data)
        or die("Query unsuccessful");

        // Generate the table rows using php
        while ($r = mysqli_fetch_array($r_table_data, MYSQLI_ASSOC)) {
            echo "<tr>";
            echo(sprintf("<td> <input class='id_button' type='radio' name='id' value='%s'></td>", $r['id']));
            // Make a new cell out of each data field in the currently processed row.
            foreach (array_keys($r) as $column_name) {
                echo(sprintf("<td id='%s'> %s </td>", $column_name . $r['id'], $r[$column_name]));
            }
            echo "</tr>";
        }

        ?>
    </table>

    <button type="button" onclick="checkAndSubmit()">Send</button>
</form>

</body>
</html>

<script>
    function checkAndSubmit() {
        let checked = [...document.getElementsByClassName('id_button')].some(c => c.checked);
        if (checked) {
            document.getElementById("update_form").submit();
        } else if (!document.getElementById("warning")) {
            let textField = document.createElement("h6");
            // textField.setAttribute("text");
            textField.innerText = "Please select one of the rows."
            textField.setAttribute("id", "warning");
            textField.setAttribute("style", "color:red");
            document.body.appendChild(textField)
        }

    }
</script>
