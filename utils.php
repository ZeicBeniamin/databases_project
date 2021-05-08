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

function isCalledFromThis($http_referer, $http_host, $php_self) {
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
    $q_insert_account = build_insertion_string($username, $password);

    $result = query_db($connection, $q_insert_account);

    if ($result != null) {
        echo("User insertion successful.<br>Username: " . $username);
    }
}