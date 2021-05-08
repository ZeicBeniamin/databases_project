<!-- Author: Zeic Beniamin -->
<!DOCTYPE HTML>
<html>

<head>
</head>

<body>

<?php

require "init.php";
require_once "utils.php";

// Suppose inserted data is false - this prevents a database check when the
// page is first run and the fields are empty
$isDataValid = false;
$isExistingUser = false;
$mainPage = "main.php";

// define error variables and set to empty values
$nameErr = $passwordErr = "";
$password = $username = "";

$username = test_input($_POST['newUsername']);
$password = test_input($_POST['newPassword']);
$isDataValid = check_user_and_password($nameErr, $passwordErr, $username, $password);

if ($isDataValid) {
    $isExistingUser = search_user_in_DB($connection, $username);

    if ($isExistingUser) {
        echo "<p class='error'>User already exists</p>";
    } elseif ($isDataValid) {
        createNewUser($connection,$username, $password);
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
        // Suppose data is valid - if one of the further checks fails, the variable
        // will be set to false and data will be invalidated
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
 * Searches for a specific user in the 'accounts' table of the database.
 *
 * @param mysqli $connection Database connection
 * @param string $username Username of the user we search for
 * @return bool Returns TRUE if user exists in database, FALSE otherwise.
 */
function search_user_in_DB($connection, $username)
{
    require "init.php";
    // Query the database for the user and password entered in the login form
    $query_string = sprintf(
        "SELECT * FROM `accounts` WHERE username='%s'",
        $username
    );
    $result = query_db($connection, $query_string);
    // If the result contains some data about the user, proceed with the login, otherwise set the flag to indicate
    // no user was found
    if (mysqli_num_rows($result)) {
        $isExistingUser = true;
    } else {
        $isExistingUser = false;
    }
    return $isExistingUser;
}

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
function query_db($connection, $query_string)
{
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
 * Builds up a query searching for a certain user account in table "accounts"
 *
 * Query is built using the username and password passed as parameters to
 * the function.
 * @param string $username The username to search for in the database. Will be used in the WHERE clause.
 * @param string $password The password of the user that is being searched. Will be used in the WHERE clause.
 * @return string String containing the query statement that searches for a specific username and password.
 */
function build_insertion_string($username, $password)
{
    $query_statement = "INSERT INTO `accounts` (`id`,`username`,`password`, `privileges`)";
    $query_statement = $query_statement . " VALUES (NULL, ";
    $query_statement = $query_statement . " '" . $username . "' " .
        ", " . " '" . $password . "' ";
    $query_statement = $query_statement . ", '2')";

    return $query_statement;
}



?>

<h2>SignIn Page</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <!--Add the text fields for username and password. The "value" attribute
    specifies the initial value of the field. This attribute helps keep
    data in text fields after submit, in case an error occured with the data.-->
    Username: <input type="text" name="newUsername" value="<?php echo $username; ?>">
    <span class="error"> <?php echo $nameErr; ?></span>
    <br><br>
    Password: <input type="password" name="newPassword" value="<?php echo $password; ?>">
    <span class="error"> <?php echo $passwordErr; ?></span>
    <br><br>

    <button type="button" onclick="loadLogInPage()">Back to Log In</button>
    <button type="submit" name="submit">Sign In</button>

</form>

</body>
</html>


<style>
    input {
        font-size: 0.7rem;
        height: 0.9rem;
    }

    button {
        font-size: 0.7rem;
        height: 1.1rem;
        /*display: block;*/
    }

    button {
        padding: 0.1rem;
        height: 1.2rem;
    }

    table {
        border-color: black;
        border-style: solid;
        border-width: 1px;
        border-collapse: collapse;
    }

    .sql_code {
        font-family: "Courier New", monspace;
        font-size: 0.55rem;
        font-size: 0.6rem;
    }

    .error {
        color: red;
    }
</style>

<script language="javascript">
    // Load the signIn page, if the user clicks that button
    function loadLogInPage() {
        window.location = "login.php";
    }
</script>