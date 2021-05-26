<html>
<body style="padding:0px">

<iframe name="left_frame" id="left_frame"  src="menu.php" id="left_frame"></iframe>
<iframe name="middle_frame" id="middle_frame" class="" id="middle_frame"></iframe>
<iframe name="right_frame" id="right_frame" src="username.php" id="right_frame"></iframe>

</body>
</html>

<style>
    body {
        background-color: #FE9920;
        background-image : url("../images/ctp.png");
        background-repeat: no-repeat;
        background-position: center;
        background-size: 50%;
    }
    #left_frame {
        padding:0px;
        position: absolute;
        height: 100%;
        width: 19%;
        border-style: none;
        border-color:darkorange;
    }

    #middle_frame {
        position: absolute;
        height: 100%;
        width: 60%;
        border-style: none;
        left: 20%;
        border-color: darkgoldenrod;
    }

    #right_frame {
        position: absolute;
        height: 100%;
        width: 19%;
        border-style: none;
        left: 80.5%;

    }
</style>