<!-- Author: Zeic Beniamin -->
<!DOCTYPE HTML>
<html>

<head>
</head>

<body>

<?php
require_once "utils.php";
function check_input_data(&$isDataValid, &$nameErr, &$passwordErr, &$username, &$password) {
    // Check the username and password to meet certain criteria
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Suppose data is valid - if one of the further checks fails, the variable
        // will be set to false and data will be invalidated
        $isDataValid = true;
        if (empty($_POST["username"])) {
            $nameErr = "Username is required";
            $isDataValid = false;
        } else {
            $username = test_input($_POST["username"]);
            // Check that username only contains letters, numbers and underscores or periods
            if (!preg_match("/^[a-zA-z0-9_.]*$/", $username)) {
                $nameErr = "Only letters, numbers, periods and underscores allowed";
                $isDataValid = false;
            } elseif (empty($username)) {
                $nameErr = "User name must not contain trailing whitespaces";
                $isDataValid = false;
            }
        }

        if (empty($_POST["password"])) {
            $passwordErr = "Password is required";
        } else {
            $password = $_POST["password"];
            // do not trim whitespaces from password
            if (!strlen($password) > 2) {
                $passwordErr = "Password must have at least 3 characters";
                $isDataValid = false;
            }
        }
    }
}

if (isCalledFromThis($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'], $_SERVER['PHP_SELF'])) {
    // Suppose inserted data is false - this prevents a database check when the
    // page is first run
    $isDataValid = false;
    $isExistingUser = false;
    $isValidPassword = false;
    $mainPage = "main.php";

    // define error variables and set to empty values
    $nameErr = $passwordErr = "";

    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($check_input_data($isDataValid, $nameErr, $passwordErr, $username, $password);

    if ($isDataValid) {
        $isExistingUser = search_user_in_DB($username);
        if ($isExistingUser) {
            $isValidPassword = search_user_and_pass_in_DB($username, $password);
        }
    }

    if ($isExistingUser && $isValidPassword) {
        session_start();
        $_SESSION['username'] = $username;
        header("Location: " . $mainPage);
    } elseif (!$isExistingUser) {
        echo "<p class='error'>User does not exist</p>";
    } elseif (!$isValidPassword) {
        echo "<p class='error'>Wrong password</p>";
    }
}

?>

<h2>Login Page</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <!--Add the text fields for username and password. The "value" attribute
    specifies the initial value of the field. This attribute helps keep
    data in text fields after submit, in case an error occured with the data.-->
    Username: <input type="text" name="username" value="<?php echo $username; ?>">
    <span class="error"> <?php echo $nameErr; ?></span>
    <br><br>
    Password: <input type="password" name="password" value="<?php echo $password; ?>">
    <span class="error"> <?php echo $passwordErr; ?></span>
    <br><br>

    <input type="submit" name="submit" value="Login">
    <input type="button" onclick="loadSignInPage()" value="Sign In">

</form>

<?php
echo "<h2>Your Login details:</h2>";
echo "Username: ";
echo $username;
echo "<br>";
echo "Password: ";
echo $password;
echo "<br>";
?>

</body>

</html>

<!-- CSS code -->

<style>
    .error {
        color: red;
    }
</style>

<!-- JS code -->


<script language="javascript">
    // Load the signIn page, if the user clicks that button
    function loadSignInPage() {
        window.location = "signin.php";
    }
</script>