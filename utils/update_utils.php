<?php

require_once "utils.php";

/**
 * Builds the string that will be used to update a specific SQL entity
 *
 * The entity is identified by its id column, stored in the $id parameter. The table that the entity belongs to is
 * transmitted through the parameter $table_name
 * @param string $table_name Name of the table to which the entity to be updated belongs
 * @param int $id Id of the entity to be updated
 * @return string SQL query that will be executed to update the entity.
 */

function build_update_string($table_name, $id)
{
    $r_column_names = query_column_names($table_name);

    $keys = "";

    // Adds the values of each column to the query string, except the value of the "id" column, which will be used in
    // WHERE clause
    while ($r = mysqli_fetch_array($r_column_names, MYSQLI_ASSOC)) {
        if ($r['COLUMN_NAME'] != 'id') {
            $keys = $keys . sprintf(", `%s` = '%s'", $r['COLUMN_NAME'], $_POST[$r['COLUMN_NAME']]);
        }
    }

    if ($table_name != 'routes') {
        return sprintf(
            "UPDATE `%s`
                SET %s
                WHERE `%s`.`id` = %s",
            $table_name,
            ltrim($keys, ','),
            $table_name,
            $id
        );
    } else {
        return sprintf(
            "UPDATE `%s`
                SET %s
                WHERE `%s`.`rid` = %s",
            $table_name,
            ltrim($keys, ','),
            $table_name,
            $id
        );
    }
}

/**
 * Prints the content of the entity given by id from the table $table_name
 *
 * The contents of the entity are printed each in a suitable input form. The input corresponding to the "id" attribute
 * of the entity is set to read-only, so the id will not be modified
 * @param string $table_name Name of the table that the entity belongs to
 * @param int $id ID of the entity to update
 */
function print_entity_content($table_name, $id)
{
    // Get column names
    $r_table_columns = query_column_names($table_name);
    // Get content of entity
    if ($table_name != 'routes') {
        $q_table_data = sprintf("SELECT * FROM %s WHERE `id` = %d", $table_name, $id);
    } else {
        $q_table_data = sprintf("SELECT * FROM %s WHERE `rid` = %d", $table_name, $id);
    }
    $r_table_data = query_db($q_table_data);
    $table_data = mysqli_fetch_array($r_table_data, MYSQLI_ASSOC);

    while ($r = mysqli_fetch_array($r_table_columns, MYSQLI_ASSOC)) {
        switch ($r['DATA_TYPE']) {
            case 'int':
                $input_type = 'number';
                $width = '20px';
                break;
            case 'varchar':
                $input_type = 'text';
                $width = '40px';
                break;
            case 'date':
                $input_type = 'date';
                $width = '40px';
                break;
        }
        if ($r['COLUMN_NAME'] == 'id') {
            echo(
            sprintf("<td><input name='id' type='text' value='%s' readonly></input></td>", $table_data[$r['COLUMN_NAME']]));
        } else {
            echo(sprintf(
                "<td><input name='%s' type='%s' id='%s' value='%s'></input></td>",
                $r['COLUMN_NAME'],
                $input_type,
                $r['COLUMN_NAME'],
                $table_data[$r['COLUMN_NAME']]
            ));
        }
    }
}

function print_entities_checklistmodif($table_name)
{
    // Get all the entities in the specified table
    $q_table_data = sprintf("SELECT * FROM %s", $table_name);
    $r_table_data = query_db($q_table_data);
    // Generate the table rows using php
    echo $r_table_data -> num_rows . "<br>Rows<br>";

//    foreach ($r_table_data as $a => $b) {
//        echo ($a . $b . "<br>");
//        if ($a == 23) {
//            foreach ($b as $c => $d) {
//                echo($c . $d . "<br>");
//            }
//        }
////        break;
//    }
    foreach ($r_table_data as $db_row => $data) {
        echo "<tr>";
//        echo(sprintf("<td> <input class='id_button' type='radio' name='id' value='%s'></td>", $r['rid']));
        echo(sprintf("<td> <input class='id_button' type='radio' name='id' value='%s'>%s</td>", $db_row, $db_row));

        /**
         * TODO: Modifica primirea id-ului in update_i sau in
         *
         */

        foreach($data as $column => $value) {
            echo(sprintf("<td> %s </td>", $value));
        }
        echo "</tr>";
    }

//    while ($r = mysqli_fetch_array($r_table_data, MYSQLI_ASSOC)) {
//        echo "<tr>";
//        // Print a radio button in front of each row.
//        if ($table_name != 'routes') {
//            echo(sprintf("<td> <input class='id_button' type='radio' name='id' value='%s'></td>", $r['id']));
//        } else {
//            echo(sprintf("<td> <input class='id_button' type='radio' name='id' value='%s'></td>", $r['rid']));
//        }
//        // Make a new cell out of each data field in the currently processed row.
//        foreach (array_keys($r) as $column_name) {
//            echo(sprintf("<td> %s </td>", $r[$column_name]));
//        }
//        echo "</tr>";
//    }
}

function print_entity_contentmodif($table_name, $row_id)
{

    $q_table_data = sprintf("SELECT * FROM %s", $table_name);
    $r_table_data = query_db($q_table_data);
    // Generate the table rows using php
//    echo $r_table_data -> num_rows . "<br>Rows<br>";
    $r_table_columns = query_column_names($table_name);
//    foreach ($r_table_columns as $cr => $ce) {
//        echo $cr . " " . $ce . "<br>";
//        foreach ($ce as $col => $val) {
////            echo $col . " > " . $val . "<br>";
//            if ($col == 'COLUMN_NAME') {
//
//                $col_name = $val;
////                echo $col_name . "<br>";
//            } else if ($col == 'DATA_TYPE') {
//                $data_type = $val;
////                echo $data_type . "<br>";
//            }
//        }
//    }
    foreach ($r_table_data as $row => $entity) {
        $col_name = null;
        $data_type = null;
        $cell_value = null;
        // Search for the element that has the same absolute row id as that received as a parameter
        if ($row == $row_id) {
            // Cycle through all the columns of the table and generate a cell for each of the columns. Consider the
            // datatype of that cell too.
            foreach ($r_table_columns as $abs_row => $key_val_array) {
                foreach ($key_val_array as $key => $value) {
                    if ($key == 'COLUMN_NAME') {
                        $col_name = $value;
                        $cell_value = $entity[$col_name];
//                        echo("Col name & value " . $col_name . " " . $entity[$col_name] . "<br>");
                    } else if ($key == 'DATA_TYPE') {
                        $data_type = $value;
//                        echo "Type " . $data_type . "<br>";
                    }
                }
                if ($col_name == 'id') {
                    echo(
                    sprintf("<td><input name='id' type='text' value='%s' readonly></input></td>", $cell_value));
                } else {
                    echo(sprintf(
                        "<td><input name='%s' type='%s' id='%s' value='%s'></input></td>",
                        $col_name,
                        $data_type,
                        $col_name,
                        $cell_value
                    ));
                }
            }
//            echo ($r_table_columns['rid'] . "<br>");
//            foreach ($entity as $column => $value) {
////                echo($entity[$column] . "<br>");
//                echo("colval" . $column . $value . "<br>");
//                echo($r_table_columns);
//            }
        }
    }

//    /**
//     * TODO: Modify so it takes the data based on row_id not on db id
//     */
//    $q_table_data = sprintf("SELECT * FROM %s", $table_name);
//    $r_table_data = query_db($q_table_data);
//    // Generate the table rows using php
////    echo $r_table_data -> num_rows . "<br>Rows<br>";
//
//    // Get column names
//    $r_table_columns = query_column_names($table_name);
//    // Get content of entity
//    if ($table_name != 'routes') {
//        $q_table_data = sprintf("SELECT * FROM %s WHERE `id` = %d", $table_name, $id);
//    } else {
//        $q_table_data = sprintf("SELECT * FROM %s WHERE `rid` = %d", $table_name, $id);
//    }
//    $r_table_data = query_db($q_table_data);
//    $table_data = mysqli_fetch_array($r_table_data, MYSQLI_ASSOC);
//
//    while ($r = mysqli_fetch_array($r_table_columns, MYSQLI_ASSOC)) {
//        switch ($r['DATA_TYPE']) {
//            case 'int':
//                $input_type = 'number';
//                $width = '20px';
//                break;
//            case 'varchar':
//                $input_type = 'text';
//                $width = '40px';
//                break;
//            case 'date':
//                $input_type = 'date';
//                $width = '40px';
//                break;
//        }
//        if ($r['COLUMN_NAME'] == 'id') {
//            echo(
//            sprintf("<td><input name='id' type='text' value='%s' readonly></input></td>", $table_data[$r['COLUMN_NAME']]));
//        } else {
//            echo(sprintf(
//                "<td><input name='%s' type='%s' id='%s' value='%s'></input></td>",
//                $r['COLUMN_NAME'],
//                $input_type,
//                $r['COLUMN_NAME'],
//                $table_data[$r['COLUMN_NAME']]
//            ));
//        }
//}
}

function build_update_stringmodif($table_name, $id)
{
    $q_table_data = sprintf("SELECT * FROM %s", $table_name);
    $r_table_data = query_db($q_table_data);
    // Generate the table rows using php
    echo $r_table_data -> num_rows . "<br>Rows<br>";
//
//    foreach ($r_table_data as $a => $b) {
//        echo ($a . $b . "<br>");
//        foreach($b as $c => $d) {
//            echo ($c . $d . "<br>");
//        }
//        break;
//    }
    foreach ($r_table_data as $db_row => $data) {
        echo "<tr>";
//        echo(sprintf("<td> <input class='id_button' type='radio' name='id' value='%s'></td>", $r['rid']));
        if ($table_name != 'routes') {
            echo(sprintf("<td> <input class='id_button' type='radio' name='id' value='%s'>%s</td>", $db_row, $db_row));
        } else {
            echo(sprintf("<td> <input class='id_button' type='radio' name='id' value='%s'>%s</td>", $db_row, $db_row));
        }

        foreach($data as $column => $value) {
            echo(sprintf("<td> %s </td>", $value));
        }
        echo "</tr>";
    }

    //------------------------------------------------------------------

    $r_column_names = query_column_names($table_name);

    $keys = "";

    // Adds the values of each column to the query string, except the value of the "id" column, which will be used in
    // WHERE clause
    while ($r = mysqli_fetch_array($r_column_names, MYSQLI_ASSOC)) {
        if ($r['COLUMN_NAME'] != 'id') {
            $keys = $keys . sprintf(", `%s` = '%s'", $r['COLUMN_NAME'], $_POST[$r['COLUMN_NAME']]);
        }
    }

    if ($table_name != 'routes') {
        return sprintf(
            "UPDATE `%s`
                SET %s
                WHERE `%s`.`id` = %s",
            $table_name,
            ltrim($keys, ','),
            $table_name,
            $id
        );
    } else {
        return sprintf(
            "UPDATE `%s`
                SET %s
                WHERE `%s`.`rid` = %s",
            $table_name,
            ltrim($keys, ','),
            $table_name,
            $id
        );
    }
}

