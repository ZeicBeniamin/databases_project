<?php

require_once "utils/utils.php";

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

    return sprintf(
        "UPDATE `%s`
                SET %s
                WHERE `%s`.`id` = %s",
        $table_name,
        ltrim($keys, ','),
        $table_name,
        $id
    );
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
    $q_table_data = sprintf("SELECT * FROM %s WHERE `id` = %d", $table_name, $id);
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