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
$domainname = "ftp://172.16.29.132";
$domainhost="127.0.0.1";
$domainuser="silent-ninja";
$domainpass="bashguard!";
$homelink = "/home/".$domainuser;
$publiclink = "/files/";
$savedfiledirectory="$uid/";
include 'ftp.php';
#get path of image
$src = $_POST['src'];
$exp = explode("/", $src);
$fname = $exp[3];
$imgID = $_POST['id'];
ChromePhp::log("source variable: ".$src);
ChromePhp::log("filename variable: ".$fname);
ChromePhp::log("imgID variable: ".$imgID);
#
#delete file from server
if(DeleteFilesFromFTPServer($domainhost, $domainuser, $domainpass, $savedfiledirectory, $fname)){
    #grab the city and country id for the image
    $grab = $mysqli->stmt_init();
    $grab->prepare("SELECT `countryID`, `cityID` 
        FROM `images` 
        WHERE `path`=?");
    $grab->bind_param('s', $src);
    $grab->execute();
    $result_grab = $grab->get_result();
    $result_grab_row = $result_grab->fetch_array(MYSQLI_NUM);

    #get country id and search for images in DB on it
    $countryID = $result_grab_row[0];
    $cityID = $result_grab_row[1];
    ChromePhp::log("Country ID: ". $countryID);

    #searching ...
    //$search = $mysqli->stmt_init();
    $search = $mysqli->prepare("SELECT `countryID`, COUNT(*) as `cnt` FROM `images` WHERE `countryID`= '$countryID'");
    #$search->bind_param("i",$countryID);
    $search->execute();
    $result_search = $search->get_result();
    $result_search_row = $result_search->fetch_array(MYSQLI_NUM);
    ChromePhp::log($result_search_row[1]);
    if($result_search_row[1] == 1){
        //delete this location
        $delete = $mysqli->prepare("DELETE e,f 
            FROM `cities` e 
            LEFT JOIN `countries` f 
            ON (e.countryID=f.countryID) 
            WHERE f.countryID = '$countryID'");
        $delete->execute();
    }    
    
    #update DB =>
    $stmt = $mysqli->stmt_init();
    $stmt->prepare("DELETE FROM `images` WHERE `path`=?");
    $stmt->bind_param('s', $src);
    $stmt->execute();
    # return a json with truthy bool
    header('Content-Type: application/json');
    echo json_encode(array(
        'success' => true,
    ));
    ChromePhp::log("deleted!");
    $mysqli->close();
} else {
    # return a json with falsy bool
    header('Content-Type: application/json');
    echo json_encode(array(
        'success' => false,
    ));
    ChromePhp::log("failed to delete!");
    $mysqli->close();
}
ChromePhp::log(" ... PHP did its job");
?>
