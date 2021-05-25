<?php

require_once "utils/utils.php";

/**
 * Prints the elements of a table header, preceded by a "Select" header element.
 *
 * The elements of the header are the names of the columns contained in the table given by "table_name".
 * @param string $table_name Name of the table. The table header will use columns of this table as the text elements
 */
function print_select_table_header($table_name)
{
    echo "<tr>";

    // The first column will be the selection one
    echo(sprintf("<th><b>%s</b></th>", "Select"));

    $r_table_columns = query_column_names($table_name);

    while ($r = mysqli_fetch_array($r_table_columns, MYSQLI_ASSOC)) {
        echo(sprintf("<th><b>%s</b></th>", ucfirst($r['COLUMN_NAME'])));
        echo("<br>");
    }
    echo "</tr>";

}

/**
 * Prints all the entities stored in a given table, preceded by a radio button.
 *
 * The radio button will be used for selecting one of the entities for deletion.
 * @param string $table_name Name of the table from which the entities will be taken
 */
function print_entities_checklist($table_name)
{
    // Get all the entities in the specifed table
    $q_table_data = sprintf("SELECT * FROM %s", $table_name);
    $r_table_data = query_db($q_table_data);

    // Generate the table rows using php
    while ($r = mysqli_fetch_array($r_table_data, MYSQLI_ASSOC)) {
        echo "<tr>";
        // Print a radio button in front of each row.
        echo(sprintf("<td> <input class='id_button' type='radio' name='id' value='%s'></td>", $r['id']));
        // Make a new cell out of each data field in the currently processed row.
        foreach (array_keys($r) as $column_name) {
            echo(sprintf("<td> %s </td>", $r[$column_name]));
        }
        echo "</tr>";
    }
}

/**
 * TODO: Add documentation
 *
 * @param $table_name
 * @param $id
 */

function display_selected_entity($table_name, $id)
{
    $r_table_columns = query_column_names($table_name);
    $q_table_data = sprintf("SELECT * FROM %s WHERE `id` = %d", $table_name, $id);
    $r_table_data = query_db($q_table_data);

    $data = mysqli_fetch_array($r_table_data, MYSQLI_ASSOC);

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
        // Create a cell for every data type contained by the entity
        echo(sprintf(
            "<td><input name='%s' type='%s' id='%s' value='%s' readonly></input></td>",
            $r['COLUMN_NAME'],
            $input_type,
            $r['COLUMN_NAME'],
            $data[$r['COLUMN_NAME']]
        ));
    }
}