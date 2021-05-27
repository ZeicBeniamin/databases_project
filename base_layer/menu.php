<?php
session_start();
if (isset($_POST['drivers/select'])) {
    $_SESSION['table'] = 'drivers';
    header("Location: " . "../sql_layer/select.php");
} elseif (isset($_POST['schedule/select'])) {
    $_SESSION['table'] = 'schedule';
    header("Location: " . "../sql_layer/select.php");
} elseif (isset($_POST['routes/select'])) {
    $_SESSION['table'] = 'routes';
    header("Location: " . "../sql_layer/select.php");
} elseif (isset($_POST['stops/select'])) {
    $_SESSION['table'] = 'stops';
    header("Location: " . "../sql_layer/select.php");
} elseif (isset($_POST['vehicles/select'])) {
    $_SESSION['table'] = 'vehicles';
    header("Location: " . "../sql_layer/select.php");
}

if (isset($_POST['drivers/insert'])) {
    $_SESSION['table'] = 'drivers';
    header("Location: " . "../sql_layer/insert.php");
} elseif (isset($_POST['schedule/insert'])) {
    $_SESSION['table'] = 'schedule';
    header("Location: " . "../sql_layer/insert.php");
} elseif (isset($_POST['routes/insert'])) {
    $_SESSION['table'] = 'routes';
    header("Location: " . "../sql_layer/insert.php");
} elseif (isset($_POST['stops/insert'])) {
    $_SESSION['table'] = 'stops';
    header("Location: " . "../sql_layer/insert.php");
} elseif (isset($_POST['vehicles/insert'])) {
    $_SESSION['table'] = 'vehicles';
    header("Location: " . "../sql_layer/insert.php");
}

if (isset($_POST['drivers/update'])) {
    $_SESSION['table'] = 'drivers';
    header("Location: " . "../sql_layer/update.php");
} elseif (isset($_POST['schedule/update'])) {
    $_SESSION['table'] = 'schedule';
    header("Location: " . "../sql_layer/update.php");
} elseif (isset($_POST['routes/update'])) {
    $_SESSION['table'] = 'routes';
    header("Location: " . "../sql_layer/update.php");
} elseif (isset($_POST['stops/update'])) {
    $_SESSION['table'] = 'stops';
    header("Location: " . "../sql_layer/update.php");
} elseif (isset($_POST['vehicles/update'])) {
    $_SESSION['table'] = 'vehicles';
    header("Location: " . "../sql_layer/update.php");
}

$tables = array(['drivers', 'schedule', 'routes', 'stops', 'vehicles']);

if (isset($_POST['drivers/delete'])) {
    $_SESSION['table'] = 'drivers';
    header("Location: " . "../sql_layer/delete.php");
} elseif (isset($_POST['schedule/delete'])) {
    $_SESSION['table'] = 'schedule';
    header("Location: " . "../sql_layer/delete.php");
} elseif (isset($_POST['routes/delete'])) {
    $_SESSION['table'] = 'routes';
    header("Location: " . "../sql_layer/delete.php");
} elseif (isset($_POST['stops/delete'])) {
    $_SESSION['table'] = 'stops';
    header("Location: " . "../sql_layer/delete.php");
} elseif (isset($_POST['vehicles/delete'])) {
    $_SESSION['table'] = 'vehicles';
    header("Location: " . "../sql_layer/delete.php");
}

if(isset($_POST['view/test_view'])) {
    $_SESSION['table'] = 'test_view';
    header("Location: " . "../sql_layer/select.php");
}
?>


<div class="vertical-menu">
    <form target="middle_frame" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

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

        <a name="#" class="active">Views</a>
        <button name="view/test_view" target="middle_frame">test_view</button>
<!--        <button name="vehicles/insert" target="middle_frame"> Insert</button>-->
<!--        <button name="vehicles/update" target="middle_frame"> Update</button>-->
<!--        <button name="vehicles/delete" target="middle_frame"> Delete</button>-->
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
        background-color: red; /* Grey background color */
        color: black; /* Black text color */
        display: block; /* Make the links appear below each other */
        padding: 2px; /* Add some padding */
        text-decoration: none; /* Remove underline from links */
        font-size: 12px;

    }

    .vertical-menu a.active {
        background-color: rgba(48, 86, 161, 0.97); /* Add an orange color to the "active/current" link */
        color: #d8e3e9; /* Font color */
        font-weight: bold;
    }

    button {
        background-color: rgba(95, 180, 156, 0.87);
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
        background-color: rgba(86, 110, 61, 0.79);
    }
</style>
