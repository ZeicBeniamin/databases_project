<?php

/**
 * Prints all the entities stored in a given table
 *
 *
 * @param string $table_name
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