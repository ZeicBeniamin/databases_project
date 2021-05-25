<!-- Author: Zeic Beniamin -->
<!DOCTYPE HTML>
<html>

<head>
</head>
<body>
<?php
require "utils/init.php";
require_once "utils/utils.php";
require_once "utils/delete_utils.php";

$table_name = $_POST['table_name'];
$id = $_POST['id'];
$webp_title = ucfirst($table_name) . " table";
echo $webp_title;
echo " - DELETE";

$q_data_delete = build_deletion_string();
//echo "<br>Updating string" . $q_data_insert;

$caller_path = preg_replace('#^https?://#', '', $_SERVER['HTTP_REFERER']);
$current_script_path = sprintf("%s%s", $_SERVER['HTTP_HOST'], $_SERVER['PHP_SELF']);

// Avoid data deletion before user presses button
if ($caller_path == $current_script_path && isset($_POST['btnDelete'])) {
    deleteData($q_data_delete);
    echo "Delted data";
}

function deleteData($q_data_insert)
{
    global $connection;
    try {
        mysqli_query($connection, $q_data_insert);
    } catch (Exception $exception) {
    }
    if (mysqli_errno($connection)) {
        echo "<br>Delete failed<br>";
        echo mysqli_error($connection);
        echo "<br>" . $q_data_insert;

    } else {
        echo "<br> Delete completed successfully.";
        echo "<p class='sql_code'> Delete statement:";
        echo "<br>" . $q_data_insert . "</p>";
    }

}

function build_deletion_string()
{
    global $table_name;
    global $id;

    return sprintf(
        "DELETE 
                FROM `%s` 
                WHERE `%s`.`id` = %s",
        $table_name,
        $table_name,
        $id
    );
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
        print_table_header($table_name);
        ?>



        <?php
        display_selected_entity($table_name, $id);
        ?>


    </table>

    <button name='btnDelete' type="submit">Delete</button>
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
        background-color: rgba(216, 227, 233, 0.55);;
    }

    table {
        background-color: rgba(216, 227, 233, 0.55);
    }
</style>