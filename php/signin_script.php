<?php
# Call out the database;

$host = 'localhost';
$user = 'root';
$pass = 'bashguard!';
$db = 'user_accs';
$mysqli = new mysqli($host,$user,$pass,$db) or die($mysqli->error);

# Start a session
session_start();

# Escape $uid to protect from sql injection
$_SESSION['uid'] = $_POST['uid'];
$uid = $mysqli->escape_string($_POST['uid']);

# Search for #uid in database(prepared statement procedure here!!!!!)
$result = $mysqli->query("SELECT * FROM `usr` WHERE `uid`='$uid'") or die($mysqli->error());

if ( $result->num_rows == 0 ) {
    $_SESSION['message'] = 'Username does not exist !';
    $_SESSION['logged_in'] = false;
    echo 'Username does not exist!';
} else { #User exists!
    # fetch associated entries with the row that matches $uid
    $user = $result->fetch_assoc();
    # now check if the password matches the entered one;
    if (password_verify($_POST['pwd'], $user['pwd'])) {
        $_SESSION['uid'] = $user['uid'];
        $_SESSION['first'] = $user['first'];
        $_SESSION['last'] = $user['last'];
        $_SESSION['message'] = '';
        $_SESSION['logged_in'] = true;    
        $_SESSION['hash'] = $mysqli->escape_string(MD5(rand(0, 10000)));
        #header('location: /php/profile.php?u='. $_SESSION['hash']);
    } else {
        $_SESSION['message'] = 'Wrong password! Try again.';
        $_SESSION['logged_in'] = false;
        echo 'Wrong password! Try again.';
    }
}
?>
