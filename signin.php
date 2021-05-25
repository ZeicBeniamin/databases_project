<!-- Author: Zeic Beniamin -->
<!DOCTYPE HTML>
<html>

<head>
</head>

<body>

<?php

require "utils/init.php";
require "utils/utils.php";

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
    $isExistingUser = search_user_in_DB($username);

    if ($isExistingUser) {
        echo "<p class='error'>User already exists</p>";
    } elseif ($isDataValid) {
        echo "<p class='error'>Create new user</p>";
        createNewUser($connection, $username, $password);
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