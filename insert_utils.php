<?php

/**
 * Insert data in database, using on a given SQL query
 *
 * Calls th function "query_db", that establishes a connection with the database and then runs the SQL query
 * @param string $q_data_insert SQL statement to be used for inserting data
 */
function insertData($q_data_insert)
{
    // Show a message if the insertion was succesful
    if (query_db($q_data_insert)) {
        echo "<br> Insertion completed successfully.";
        echo "<p class='sql_code'> Insert statement:";
        echo "<br>" . $q_data_insert . "</p>";
    }
}

/**
 * Builds the string to be used for insertion of data.
 *
 * Data inserted by the user is taken from the fields in the website. Each field has the "name"attribute identical to
 * the name of the column.
 * @param mysqli $r_column_names Object containing the names of the table's columns, together with their type
 * @return string The string to be used for data insertion
 */

function build_insertion_string($table_name)
{
    $r_column_names = query_column_names($table_name);

    $keys = "(`id`";
    $values = "VALUES (NULL";

    while ($r = mysqli_fetch_array($r_column_names, MYSQLI_ASSOC)) {
        if (!empty($_POST[$r['COLUMN_NAME']]) &&
            $r['COLUMN_NAME'] != 'id') {
            $keys = $keys . sprintf(", `%s`", $r['COLUMN_NAME']);
            $values = $values . sprintf(", '%s'", $_POST[$r['COLUMN_NAME']]);
        } elseif (empty($_POST[$r['COLUMN_NAME']])) {
            $isDataValid = false;
        }

    }
    // Close the parentheses of the keys and values strings
    $keys = $keys . ")";
    $values = $values . ")";

    return sprintf(
        "INSERT INTO `%s` %s %s",
        $table_name,
        $keys,
        $values
    );
}

/**
 * Checks that user entered data is not void.
 *
 * The user input is taken by means of a form. The form has an input element for each of the columns of the table whose
 * name is passed as a parameter to this function. The function takes the content of every input of the form and checks
 * that it is non-empty.
 * @param string $table_name Name of the table from which the column names will be taken.
 * @return bool TRUE if none of the inputs is void, FALSE otherwise
 */
function is_insert_data_valid($table_name)
{
    $r_column_names = query_column_names($table_name);
    while ($r = mysqli_fetch_array($r_column_names, MYSQLI_ASSOC)) {
        if (empty($_POST[$r['COLUMN_NAME']])) {
            return false;
        }
    }
    return true;
}

/**
 * Creates a data collection form, for the table received as an argument.
 * @param string $table_name Name of the table for which the form should be created.
 */
function print_insert_form($table_name)
{
    $r_table_columns = query_column_names($table_name);

    // Generate the content cells using php
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
            "<td><input name='id' type='text' value='Autoincrement' readonly></input></td>"
            );
        } else {
            echo(sprintf(
                "<td><input name='%s' type='%s' id='$s'></input></td>",
                $r['COLUMN_NAME'],
                $input_type,
                $r['COLUMN_NAME']
            ));
        }
    }
}

/**
 * Prints the elements of a table header.
 *
 * The elements of the header are the names of the columns contained in the table given by "table_name".
 * @param string $table_name Name of the table. The table header will use columns of this table as the text elements
 */
function print_table_header($table_name)
{
    $r_table_columns = query_column_names($table_name);

    while ($r = mysqli_fetch_array($r_table_columns, MYSQLI_ASSOC)) {
        echo(sprintf("<th><b>%s</b></th>", ucfirst($r['COLUMN_NAME'])));
        echo("<br>");
    }
}

