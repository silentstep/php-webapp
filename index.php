<?php
#require 'db.php';
#session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

<link href="https://fonts.googleapis.com/css?family=Pinyon+Script|Ubuntu" rel="stylesheet">

<link rel="stylesheet" href="css/main.css">
<?php
if ($_SESSION['logged_in']) {
    header("location: /php/profile.php");
}
?>
</head>
<body>
    <div class="intro">
        <div class="coral-left"></div>
        <div class="khaki-right"></div>
        <div class="boxfather">
            <div class="box">
                <h1>WELCOME</h1>
                <h2> EXPLORE THE WORLD WITH US</h2>
                <p> Login or register an account to get started</p>
                <span class="pulse-button">Enter</span>
            </div>
        </div>
    </div>
    <!-- /intro div -->
    <div class="homepage">
        <div class="content">
            <div class="container">
                <div class="menu">
                    <a href="#connect" class="btn-connect">
                        <h2>LOGIN</h2>
                    </a>
                    <a href="#register" class="btn-register active">
                        <h2>REGISTER</h2>
                    </a>
                </div>

                <div id="content" class="connect">
                    <form id="form" class="contact-form" action="#" method="POST">
                        <label class="label">USERNAME</label>
                        <input id="uid" name="uid" type="text" class="input" autocomplete="off" required />

                        <label class="label">PASSWORD</label>
                        <input id="pwd" name="pwd" type="password" class="input" required />
                        <hr>
                        <input name="signin" type="submit" class="input submit" onClick="return false" onmousedown="javascript:login();" value="LOGIN" />
                    </form>
                    <!-- /contact-form div -->
                </div>
                <!-- /signin section div -->

                <div id="reg-content" class="register active-section">
                    <form id="form" class="contact-form" action="#" method="POST">
                        <label class="label">FIRST NAME</label>
                        <input id="first" name="first" type="text" class="input" autocomplete="off" required />

                        <label class="label">LAST NAME</label>
                        <input id="last" name="last" type="text" class="input" autocomplete="off" required />

                        <label class="label">USERNAME</label>
                        <input id="reg-uid" name="reg-uid" type="text" class="input" autocomplete="off" required />

                        <label class="label">PASSWORD</label>
                        <input id="reg-pwd" name="reg-pwd" type="password" class="input" required />

                        <div class="check">
                            <hr>
                        </div>

                        <input name="register" type="submit" class="input submit" onClick="return false" onmousedown="javascript:reg();" value="REGISTER" />
                    </form>
                    <!-- /form -->
                </div>
                <!-- /registration section div -->
            </div>
            <!-- /container div -->
        </div>
        <!-- /content div -->
    </div>
    <!-- /homepage div -->
</body>
<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script src='http://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js'></script>
<script src="js/main.js"></script>
</html>
