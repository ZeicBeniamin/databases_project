<?php

require_once "utils.php";

/**
 * Display an entity specified by its id from a specific table, specified by its table name.
 *
 * The id of the entity and the table name should be passed as arguments to the function. The caller must be sure that
 * the id exists in the given table
 * @param string $table_name Name of the table that contains an entity with the given id
 * @param int $id Id of the entity
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
 * Builds the SQL statement that will delete the entity with the given id from the specified table.
 *
 * @param string $table_name Name of the table from which the entity will be deleted.
 * @param string $id Id of the entity to delete from the table.
 * @return string SQL statement that deletes the given entity
 */
function build_deletion_string($table_name, $id)
{
    return sprintf(
        "DELETE 
                FROM `%s` 
                WHERE `%s`.`id` = %s",
        $table_name,
        $table_name,
        $id
    );
}