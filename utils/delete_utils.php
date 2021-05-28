<?php

require_once "utils.php";

/**
 * Execute the deletion string sent as a function parameter
 *
 * @param string $q_data_delete The SQL deletion statement to be executed
 */
function deleteData($q_data_delete)
{
    if (query_db($q_data_delete)) {
        echo "<br> Delete completed successfully.";
        echo "<p class='sql_code'> Delete statement:";
        echo "<br>" . $q_data_delete . "</p>";
    }
}

/**
 * Builds the SQL statement that will delete the entity with the given $row_id from the table given by $table_name
 *
 * @param string $table_name Name of the table from which the entity will be deleted.
 * @param string $row_index Absolute row id of the entity to be deleted
 * @return string SQL statement that deletes the given entity
 */
function build_deletion_string_robust($table_name, $row_index)
{
    $r_table_columns = query_column_names($table_name);

    $set_string = "";
    $where_clause = "";
    $k = -1;

    // Take every row in the table that contains the column names and data types
    foreach ($r_table_columns as $col_row_idx => $col_array) {
        $k++;
        // Take every cell of the table
        $column_name = $col_array['COLUMN_NAME'];
//                DEBUG only
//                echo "Col name " . $column_name . "<br>";
//                echo "Col type " . $column_type . "<br>";
//                echo "Col value " . $column_value . "<br>";

        if ($k != 0) {
            $where_clause .= 'AND';
        }
//        if ($row_id == 0 || $row_id != null) {
        // If the field is non-empty or zero
        if ($_POST[$column_name] == 0 || $_POST[$column_name] != null) {
            $where_clause .=
                sprintf(" `%s`.`%s` = '%s' ",
                    $table_name,
                    $column_name,
                    $_POST[$column_name]
                );
        } else {
            $where_clause .=
                sprintf(" `%s`.`%s` IS NULL ",
                    $table_name,
                    $column_name
                );
        }


    }

    return sprintf(
        "DELETE
                FROM `%s`
                WHERE %s",
        $table_name,
        $where_clause
    );
}