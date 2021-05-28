<?php

require_once "utils.php";


/**
 * TODO: Update documentation
 * Builds the string that will be used to update a specific SQL entity
 *
 * The entity is identified by its id column, stored in the $id parameter. The table that the entity belongs to is
 * transmitted through the parameter $table_name
 * @param string $table_name Name of the table to which the entity to be updated belongs
 * @param int $row_index Id of the entity to be updated
 * @return string SQL query that will be executed to update the entity.
 */


function build_update_string_robust($table_name, $row_index)
{
    $q_table_data = sprintf("SELECT * FROM %s", $table_name);
    $r_table_data = query_db($q_table_data);
    $r_table_columns = query_column_names($table_name);

    $set_string = "";
    $where_clause = "";
    $k = -1;

    // Searches for the row with the index corresponding to that received as a parameter
    foreach ($r_table_data as $data_row_idx => $data_array) {
        if ($data_row_idx == $row_index) {
            // Take every row in the table that contains the column names and data types
            foreach ($r_table_columns as $col_row_idx => $col_array) {
                $k++;
                // Take every cell of the table
                $column_name = $col_array['COLUMN_NAME'];
                $column_type = $col_array['DATA_TYPE'];
                $column_value = $data_array[$column_name];
//                DEBUG only
//                echo "Col name " . $column_name . "<br>";
//                echo "Col type " . $column_type . "<br>";
//                echo "Col value " . $column_value . "<br>";

                if ($k != 0) {
                    $set_string .= ',';
                    $where_clause .= 'AND';
                }
                $set_string .=
                    sprintf(" `%s` = '%s'",
                        $column_name,
                        $_POST[$column_name]
                    );
                $where_clause .=
                    sprintf(" `%s`.`%s` = '%s' ",
                        $table_name,
                        $column_name,
                        $column_value
                    );
            }
        }
    }

    return sprintf(
        "UPDATE %s
        SET %s
        WHERE %s",
        $table_name,
        $set_string,
        $where_clause
    );
}

/**
 * Update data in database, using on a given SQL query
 *
 * Calls th function "query_db", that establishes a connection with the database and then runs the SQL query
 * @param string $q_data_update SQL statement to be used for inserting data
 */
function updateData($q_data_update)
{
    // Show a message if the insertion was succesful
    if (query_db($q_data_update)) {
        echo "<br> Update completed successfully.";
        echo "<p class='sql_code'> Update statement:";
        echo "<br>" . $q_data_update . "</p>";
        return true;
    } else {
        return null;
    }
}

