<!-- Author: Zeic Beniamin -->
<!DOCTYPE HTML>
<html>
<head>
</head>
<body>

<?php session_start(); ?>
Welcome <?php echo $_POST["name"]; ?><br>
Your username is: <?php echo $_SESSION["username"]; ?>
</body>
</html>