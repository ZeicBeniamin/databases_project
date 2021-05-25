<?php
// Change mysqli logging level to only report errors, not index warnings.
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

/**
 * Checks if a caller php file was called from another instance of the same file or from other webpages.
 *
 * IMPORTANT NOTE: The check is done based on the received parameters. It is not a check that involves this webpage
 * ('utils.php') and its caller webpage (whatever that page would be).
 * This function checks if the current instance of a php webpage (passed as a combination between $http_host and
 * $http_self) was called from another instance of the same webpage (passed as $http_referer).
 *
 * @param string $http_referer Value of the _SERVER['HTTP_REFERER'] variable - contains the path of the caller file
 * @param string $http_host Value of the _SERVER['HTTP_HOST'] variable - contains the path to the current file, excluding the file itself
 * @param string $php_self Name of the current file, stored in _SERVER['PHP_SELF']. To be appended to $http_host
 * @return bool
 * Boolean that indicates if the currently executing page was called from another instance of the same page.
 * See IMPORTANT NOTE from above for clarifications.
 */

function isCalledFromThis($http_referer, $http_host, $php_self)
{
    $caller_path = preg_replace('#^https?://#', '', $http_referer);
    $current_script_path = sprintf("%s%s", $http_host, $php_self);

    return $caller_path == $current_script_path;
}

/**
 * Creates a new user by first building the SQL query and then sending it to the database.
 *
 * User data (username and password) is received by the function through the function parameters
 * @param mysqli $connection Database connection on which the query should be performed.
 * @param string $username Username of the new user
 * @param string $password New user's password
 */

function createNewUser($connection, $username, $password)
{
    $q_insert_account = build_account_insertion_query($username, $password);

    $result = query_db($q_insert_account);

    if ($result != null) {
        echo("User insertion successful.<br>Username: " . $username);
    }
}


/**
 * Checks username and password to meet certain requirements.
 *
 * Username may contain only letters, numbers, dots or underscores.
 * Passwords must have at least 3 characters.
 *
 * @param string $userNameErr Text that details the username error. It is passed as reference, thus it modifies the variable in its original scope.
 * @param string $passwordErr Text that details the password error. It is passed as reference, thus it modifies the variable in its original scope.
 * @param string $username Username to be checked
 * @param string $password Password to be checked
 * @return bool
 * TRUE is returned if all checks are successfully passed (i.e. username and password meet requirements).
 * FALSE is returned on failure (i.e. one check is not passed).
 */
function check_user_and_password(&$userNameErr, &$passwordErr, $username, $password)
{
    // Check the username and password to meet certain criteria
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($username)) {
            $userNameErr = "Username is required";
            return false;
        } else {
            // Check that username only contains letters, numbers and underscores or periods
            if (!preg_match("/^[a-zA-z0-9_.]*$/", $username)) {
                $userNameErr = "Only letters, numbers, periods and underscores allowed";
                return false;
            } elseif (empty($username)) {
                $userNameErr = "User name must not contain trailing whitespaces";
                return false;
            }
        }
        if (empty($password)) {
            $passwordErr = "Password is required";
            return false;
        } else {
            // do not trim whitespaces from password
            if (!strlen($password) > 2) {
                $passwordErr = "Password must have at least 3 characters";
                return false;
            }
        }
        return true;
    }
}


/**
 * Builds up a query searching for a certain user account in table "accounts"
 *
 * Query is built using the username and password passed as parameters to
 * the function.
 * @param string $username The username to search for in the database. Will be used in the WHERE clause.
 * @param string $password The password of the user that is being searched. Will be used in the WHERE clause.
 * @return string String containing the query statement that searches for a specific username and password.
 */
function build_account_insertion_query($username, $password)
{
    $query_statement = "INSERT INTO `accounts` (`id`,`username`,`password`, `privileges`)";
    $query_statement = $query_statement . " VALUES (NULL, ";
    $query_statement = $query_statement . " '" . $username . "' " .
        ", " . " '" . $password . "' ";
    $query_statement = $query_statement . ", '2')";

    return $query_statement;
}

///**
// * Searches for a specific user in the 'accounts' table of the database.
// *
// * @param mysqli $connection Database connection
// * @param string $username Username of the user we search for
// * @return bool Returns TRUE if user exists in database, FALSE otherwise.
// */
//function search_user_in_DB($username)
//{
//    require "init.php";
//    // Query the database for the user and password entered in the login form
//    $query_string = sprintf(
//        "SELECT * FROM `accounts` WHERE username='%s'",
//        $username
//    );
//    $result = query_db($query_string);
//    // If the result contains some data about the user, proceed with the login, otherwise set the flag to indicate
//    // no user was found
//    if (mysqli_num_rows($result)) {
//        $isExistingUser = true;
//    } else {
//        $isExistingUser = false;
//    }
//    return $isExistingUser;
//}


/**
 * Make a query using the connection and query string passed as arguments
 *
 * Try to query the database and return the result, in case of success.
 * Otherwise, display an explanatory text and print both the query string
 * and the error text.
 *
 * @param mysqli $connection An object representing the connection to the database.
 * @param string $query_string String holding the query to be applied on the database
 * @return mysqli_result|true|null
 * The result of the query, if the query must return a result.
 * TRUE will be returned for other successful queries
 * NULL will be returned on failure
 */
function query_db($query_string)
{
    require "init.php";
    $result = null;
    if ($connection == null || $connection == false) {
        echo "<p class='error'>'Connection inactive; Cannot execute query'</p>";
        return null;
    }
    try {
        $result = mysqli_query($connection, "" . $query_string);
    } catch (mysqli_sql_exception $e) {
        echo "<p class='error'>Query failed</p>";
        echo sprintf("<br>Query string: %s<br>", $query_string);
        echo sprintf("<br>Error: %s<br>", $e->getMessage());
        return null;
    }

    return $result;
}


/**
 * Searches for a specific user in the 'accounts' table, based on a password and username received as arguments
 *
 * @param string $username Username that, in combination with the password, defines an account.
 * @param string $password Password that, in combination with the username, defines an account.
 * @return bool TRUE if the account was found in the table, FALSE otherwise.
 *
 */
function search_user_and_pass_in_DB($username, $password)
{
    require "init.php";
    // Query the database for the user and password entered in the login form
    $query_string = sprintf(
        "SELECT * FROM `accounts` WHERE username='%s' AND password='%s'",
        $username,
        $password
    );
    $result = mysqli_query($connection, $query_string)
    or die("<br>Query fail - search_user_and_pass_in_DB()");
    // If the result contains some data about the user, the account exists in the database
    if (mysqli_num_rows($result)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Prepares the string sent as argument for further processing.
 *
 * This is done in order to avoid attacks through form inputs.
 *
 * @param string $data String to be prepared.
 * @return string
 * Original string from which trailing whitespaces and backslashes were removed.
 * Special characters (like "<", ">") are also converted to strings like &lt, &gt.
 */
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/**
 * Fetches the column names and column types for a specific table.
 *
 * Columns are ordered in ascending order by ORDINAL_POSITION
 * @param string $table_name Name of the table for which to fetch the columns
 * @return bool|mysqli_result Columns of the table
 *
 */

function query_column_names($table_name)
{
    require "init.php";

    $q_table_columns = sprintf(
        "SELECT *
                FROM INFORMATION_SCHEMA.COLUMNS
                WHERE TABLE_NAME = '%s'
                ORDER BY ORDINAL_POSITION", $table_name);
    $r_table_columns = mysqli_query($connection, $q_table_columns)
    or die("Query unsuccessful");

    return $r_table_columns;
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
function is_data_non_empty($table_name)
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
 * Prints the elements of a table header.
 *
 * The elements of the header are the names of the columns contained in the table given by "table_name".
 * @param string $table_name Name of the table. The table header will use columns of this table as the text elements
 */
function print_table_header($table_name)
{
    echo "<tr>";
    $r_table_columns = query_column_names($table_name);

    while ($r = mysqli_fetch_array($r_table_columns, MYSQLI_ASSOC)) {
        echo(sprintf("<th><b>%s</b></th>", ucfirst($r['COLUMN_NAME'])));
        echo("<br>");
    }
    echo "</tr>";

}