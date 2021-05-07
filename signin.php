<!-- Author: Zeic Beniamin -->
<!DOCTYPE HTML>
<html>

<head>
</head>

<body>

<?php

function check_input_data(&$isDataValid, &$nameErr, &$passwordErr, &$username, &$password) {
    // Check the username and password to meet certain criteria
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Suppose data is valid - if one of the further checks fails, the variable
        // will be set to false and data will be invalidated
        $isDataValid = true;
        if (empty($_POST['newUsername'])) {
            $nameErr = "Username is required";
            $isDataValid = false;
        } else {
            $username = test_input($_POST['newUsername']);
            // Check that username only contains letters, numbers and underscores or periods
            if (!preg_match("/^[a-zA-z0-9_.]*$/", $username)) {
                $nameErr = "Only letters, numbers, periods and underscores allowed";
                $isDataValid = false;
            } elseif (empty($username)) {
                $nameErr = "User name must not contain trailing whitespaces";
                $isDataValid = false;
            }
        }

        if (empty($_POST['newPassword'])) {
            $passwordErr = "Password is required";
        } else {
            $password = $_POST['newPassword'];
            // do not trim whitespaces from password
            if (!strlen($password) > 2) {
                $passwordErr = "Password must have at least 3 characters";
                $isDataValid = false;
            }
        }
    }
}

function search_user_in_DB($isDataValid, $username, $password) {
    $isExistingUser = false;
    // If data is valid, check it against users in the database
    if ($isDataValid) {
        require "init.php";
        // Query the database for the user and password entered in the login form
        $query_string = sprintf(
            "SELECT * FROM `accounts` WHERE username='%s' AND password='%s'",
            $username,
            $password
        );

        $result = mysqli_query($connection, $query_string)
        or die("<br>Querry fail - login");

        // If the result contains some data about the user, proceed with the login, otherwise set the flag to indicate
        // no user was found
        if (mysqli_num_rows($result)) {
            $isExistingUser = true;
        } else {
            $isExistingUser = false;
        }
    }

    return $isExistingUser;
}

// Test data in order to avoid attacks
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function build_insertion_string() {
    $string = "INSERT INTO `accounts` (`id`,`username`,`password`, `privileges`)";
    $string = $string . " VALUES (NULL, ";
    $string = $string . " '" . $_POST['newUsername'] . "' " .
                 ", " . " '" . $_POST['newPassword'] . "' ";
    $string = $string . ", '2')";

    return $string;
}


// Suppose inserted data is false - this prevents a database check when the
// page is first run
$isDataValid = false;
$isExistingUser = false;
$mainPage = "main.php";

// define error variables and set to empty values
$nameErr = $passwordErr = "";
$password = $username = "";

check_input_data($isDataValid, $nameErr, $passwordErr, $username, $password);

$isExistingUser = search_user_in_DB($isDataValid, $username, $password);

if ($isExistingUser) {
    echo "<p class='error'>User already exists</p>";
//    session_start();
//    $_SESSION['username'] = $username;
//    header("Location: " . $mainPage);
} elseif ($isDataValid) {
    createNewUser();
}

function createNewUser() {
    $username = $_POST['newUsername'];
    $password = $_POST['newPassword'];

    $q_insert_account = build_insertion_string();

    require "init.php";
    $result = mysqli_query($connection, $q_insert_account)
    or die("<br>Querry fail - login");

    if(mysqli_errno($connection)) {
        echo "<br>User insertion failed<br>";
        echo mysqli_error($connection);
    } else {
        echo "<br> User insertion successful.";
        echo "<p class='sql_code'> Insert statement:</p>";
        echo "<p class='sql_code'>" . $q_insert_account . "</p>";
    }
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