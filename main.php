<html>
<body style="padding:0px">

<iframe name="left_frame" src="menu.php" id="left_frame"></iframe>
<iframe name="middle_frame" src="main_view.php" class="" id="middle_frame"></iframe>
<iframe name="right_frame" src="username.php" id="right_frame"></iframe>

<!--<iframe name="left_frame" src="https://www.w3schools.com/html/default.asp" id="left_frame"></iframe>-->
<!--<iframe name="middle_frame" src="https://www.w3schools.com/" class="" id="middle_frame"></iframe>-->
<!--<iframe name="right_frame" src="https://www.w3schools.com/" id="right_frame"></iframe>-->

</body>
</html>

<style>
    #left_frame {
        padding:0px;
        position: absolute;
        height: 100%;
        width: 19%;
        border-style: solid;
        border-color:darkorange;
    }

    #middle_frame {
        position: absolute;
        height: 100%;
        width: 60%;
        border-style: solid;
        left: 20%;
        border-color: darkgoldenrod;
    }

    #right_frame {
        position: absolute;
        height: 100%;
        width: 19%;
        border-style: solid;
        left: 80.5%;

    }
</style>