<?php

require_once "utils.php";

/**
 * Prints all the entities stored in a given table.
 *
 * @param string $table_name Name of the table from which the entities will be taken
 */
function print_entities($table_name)
{
    // Get all the entities in the specifed table
    $q_table_data = sprintf("SELECT * FROM %s", $table_name);
    $r_table_data = query_db($q_table_data);

    // Generate the table rows using php
    while ($r = mysqli_fetch_array($r_table_data, MYSQLI_ASSOC)) {
        echo "<tr>";
        // Make a new cell out of each data field in the currently processed row.
        foreach (array_keys($r) as $column_name) {
            echo(sprintf("<td> %s </td>", $r[$column_name]));
        }
        echo "</tr>";
    }
}


/**
 * Prints all the entities stored in a given table, preceded by a radio button.
 *
 * The radio button will be used for selecting one of the entities for deletion.
 * @param string $table_name Name of the table from which the entities will be taken
 */
function print_entities_checklist($table_name)
{
    // Get all the entities in the specified table
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
 * Prints the elements of a table header, preceded by a "Select" header element.
 *
 * The elements of the header are the names of the columns contained in the table given by "table_name".
 * @param string $table_name Name of the table. The table header will use columns of this table as the text elements
 */
function print_select_table_header($table_name)
{
    // Generate a new row
    echo "<tr>";
    // The first column will be the selection one
    echo(sprintf("<th><b>%s</b></th>", "Select"));
    // Get the name of the columns in the table
    $r_table_columns = query_column_names($table_name);
    // Print a header cell for each of the columns. The header cell will also display the name of the column
    while ($r = mysqli_fetch_array($r_table_columns, MYSQLI_ASSOC)) {
        echo(sprintf("<th><b>%s</b></th>", ucfirst($r['COLUMN_NAME'])));
        echo("<br>");
    }
    echo "</tr>";
}