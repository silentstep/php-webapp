<?php
include 'ChromePhp.php';
session_start();

$host = 'localhost';
$user = 'root';
$pass = 'bashguard!';
$db = 'user_accs';
$mysqli = new mysqli($host,$user,$pass,$db) or die($mysqli->error);

if ($_SESSION['logged_in'] != 1) {
    $_SESSION['error'] = true;
    header("location: /php/error.php");
} else {
    # take session variable for the specific user;
    $uid = $_SESSION['uid'];
    $first = $_SESSION['first'];
    $last = $_SESSION['last'];
    $hash = $_SESSION['hash'];
}
$desc = $mysqli->escape_string($_POST['desc']);
$src = $_POST['src'];
ChromePhp::log("data1: ".$desc);
ChromePhp::log("data2: ".$src);
#assemble path of action
$imgID = $src;
ChromePhp::log("php imgID: ".$imgID);

$edit = $mysqli->prepare("UPDATE `images`
    SET `caption`='$desc'
    WHERE `imgID`='$imgID'");
if ($edit->execute()) {
    header('Content-Type: application/json');
    echo json_encode(array(
        'success' => true,
    ));
    $mysqli->close();
} else {
    header('Content-Type: application/json');
    echo json_encode(array(
        'success' => false,
    ));
    $mysqli->close();
}
?>
