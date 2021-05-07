<?php
session_start();
if (isset($_POST['drivers/select'])) {
    $_SESSION['table'] = 'drivers';
    header("Location: " . "select.php");
} elseif (isset($_POST['schedule/select'])) {
    $_SESSION['table'] = 'schedule';
    header("Location: " . "select.php");
} elseif (isset($_POST['routes/select'])) {
    $_SESSION['table'] = 'routes';
    header("Location: " . "select.php");
} elseif (isset($_POST['stops/select'])) {
    $_SESSION['table'] = 'stops';
    header("Location: " . "select.php");
} elseif (isset($_POST['vehicles/select'])) {
    $_SESSION['table'] = 'vehicles';
    header("Location: " . "select.php");
}

if (isset($_POST['drivers/insert'])) {
    $_SESSION['table'] = 'drivers';
    header("Location: " . "insert.php");
} elseif (isset($_POST['schedule/insert'])) {
    $_SESSION['table'] = 'schedule';
    header("Location: " . "insert.php");
} elseif (isset($_POST['routes/insert'])) {
    $_SESSION['table'] = 'routes';
    header("Location: " . "insert.php");
} elseif (isset($_POST['stops/insert'])) {
    $_SESSION['table'] = 'stops';
    header("Location: " . "insert.php");
} elseif (isset($_POST['vehicles/insert'])) {
    $_SESSION['table'] = 'vehicles';
    header("Location: " . "insert.php");
}

if (isset($_POST['drivers/update'])) {
    $_SESSION['table'] = 'drivers';
    header("Location: " . "update.php");
} elseif (isset($_POST['schedule/update'])) {
    $_SESSION['table'] = 'schedule';
    header("Location: " . "update.php");
} elseif (isset($_POST['routes/update'])) {
    $_SESSION['table'] = 'routes';
    header("Location: " . "update.php");
} elseif (isset($_POST['stops/update'])) {
    $_SESSION['table'] = 'stops';
    header("Location: " . "update.php");
} elseif (isset($_POST['vehicles/update'])) {
    $_SESSION['table'] = 'vehicles';
    header("Location: " . "update.php");
}

$tables = array(['drivers', 'schedule', 'routes', 'stops', 'vehicles']);

if (isset($_POST['drivers/delete'])) {
    $_SESSION['table'] = 'drivers';
    header("Location: " . "delete.php");
} elseif (isset($_POST['schedule/delete'])) {
    $_SESSION['table'] = 'schedule';
    header("Location: " . "delete.php");
} elseif (isset($_POST['routes/delete'])) {
    $_SESSION['table'] = 'routes';
    header("Location: " . "delete.php");
} elseif (isset($_POST['stops/delete'])) {
    $_SESSION['table'] = 'stops';
    header("Location: " . "delete.php");
} elseif (isset($_POST['vehicles/delete'])) {
    $_SESSION['table'] = 'vehicles';
    header("Location: " . "delete.php");
}
?>


<div class="vertical-menu">
    <form target="middle_frame" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <a href="#" style="background-color: #CE0505FF;">Tests</a>
        <button name="select" target="middle_frame"> Select</button>
        <button name="insert" target="middle_frame"> Insert</button>
        <button name="update" target="middle_frame"> Update</button>
        <button name="delete" target="middle_frame"> Delete</button>

        <a name="#" class="active">Drivers</a>
        <button name="drivers/select" target="middle_frame"> Select</button>
        <button name="drivers/insert" target="middle_frame"> Insert</button>
        <button name="drivers/update" target="middle_frame"> Update</button>
        <button name="drivers/delete" target="middle_frame"> Delete</button>

        <a name="#" class="active">Schedule</a>
        <button name="schedule/select" target="middle_frame"> Select</button>
        <button name="schedule/insert" target="middle_frame"> Insert</button>
        <button name="schedule/update" target="middle_frame"> Update</button>
        <button name="schedule/delete" target="middle_frame"> Delete</button>

        <a name="#" class="active">Routes</a>
        <button name="routes/select" target="middle_frame"> Select</button>
        <button name="routes/insert" target="middle_frame"> Insert</button>
        <button name="routes/update" target="middle_frame"> Update</button>
        <button name="routes/delete" target="middle_frame"> Delete</button>

        <a name="#" class="active">Stops</a>
        <button name="stops/select" target="middle_frame"> Select</button>
        <button name="stops/insert" target="middle_frame"> Insert</button>
        <button name="stops/update" target="middle_frame"> Update</button>
        <button name="stops/delete" target="middle_frame"> Delete</button>

        <a name="#" class="active">Vehicles</a>
        <button name="vehicles/select" target="middle_frame"> Select</button>
        <button name="vehicles/insert" target="middle_frame"> Insert</button>
        <button name="vehicles/update" target="middle_frame"> Update</button>
        <button name="vehicles/delete" target="middle_frame"> Delete</button>
    </form>

</div>

<style>
    body {
        font-family: "Courier New", monspace;
    }

    /* Styling code copied from w3schools.com */
    .vertical-menu {
        /*width: 180px; !* Set a width if you like *!*/
        border-style: none;
        padding: 0 0 0 0;
    }

    .vertical-menu a {
        height: 19px;
        background-color: #eee; /* Grey background color */
        color: black; /* Black text color */
        display: block; /* Make the links appear below each other */
        padding: 2px; /* Add some padding */
        text-decoration: none; /* Remove underline from links */
        font-size: 12px;

    }

    .vertical-menu a.active {
        background-color: #ce5f05; /* Add an orange color to the "active/current" link */
        color: white;
        font-weight: bold;
    }

    button {
        display: block;
        border-style: none;
        height: 1.2rem;
        width: 100%;
        text-align: left;
    }

    button {
        transition-duration: 0.2s;
        font-family: "Courier New", monspace;
    }

    button:hover {
        background-color: darkgoldenrod;

    }
</style>
