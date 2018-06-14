<?php
include 'ChromePhp.php';
session_start();
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
$host = 'localhost';
$user = 'root';
$pass = 'bashguard!';
$db = 'user_accs';
$mysqli = new mysqli($host,$user,$pass,$db) or die($mysqli->error);

$savedfiledirectory="$uid/";
include 'ftp.php';
$ftp_con = ftp_connection($domainhost, $domainuser, $domainpass, $uid);

# FETCH THE USER (prepared statement procedure needed!!!)
$fetchUser = $mysqli->query("SELECT id FROM `usr` WHERE uid='$uid'");
$userRow = $fetchUser->fetch_array(MYSQLI_NUM);
$userID = $userRow[0];

# FETCH THE IMAGES(prepared statemet prodcedure needed!)
#$front = "SELECT imgID, path, images.id FROM `images` LEFT JOIN usr ON (usr.id=images.id) WHERE usr.id='$userID'";
$single= " SELECT imgID, path, caption, id, cname, name 
    FROM `images` 
    LEFT JOIN countries 
    ON(countries.countryID=images.countryID)
    RIGHT JOIN cities 
    ON(cities.cityID=images.cityID)
    WHERE images.id='$userID'";
#
# FIX SCOPE OF VARIABLES TO ONLY QUERY ONCE
#
#$front = $mysqli->query($front);
$single = $mysqli->query($single);

#function gallery(){
#    global $front;
#    $count = 1;
#    while( $gallery = $front->fetch_array(MYSQLI_NUM) ) {
#        #ChromePhp::log($gallery);
#        $src = explode("picblog", $gallery[1]);
#        echo '<li> <!-- ROW -->';
#        echo '<a href="#item'.$count.'"><img src="'.$src[1].'" id="'.$gallery[0].'" alt=""></a>';
#        echo '</li> <!-- /ROW -->';
#        $count++;
#    }
#}

function content(){
    global $single;
    global $ftp_con;
    $count = 1;
    while ( $gallery = $single->fetch_array(MYSQLI_NUM) ){
        #ChromePhp::log($gallery);
        $src = $gallery[1];
        $uri = ftp_fetch($ftp_con, $src);
        $cap_dot = explode("/", $src);
        $cap_id = explode(".", $cap_dot[3]);
        echo '<div>
                 <img id="'.$gallery[0].'='.$src.'" class="materialboxed responsive-img" src="'.$uri.'" alt="'.$gallery[2].'">
                 <div class="caption center-align">
                    <h4>'.$gallery[4].', '.$gallery[5].'</h4>
                    <p id="cap'.$cap_id[0].'" class="light yellow-text text-lighten-4">'.$gallery[2].'</p>
                 </div>
               </div>';
        $count++;
    }
    ftp_connection_quit($ftp_con);
}
ChromePhp::log("Memory usage[byte]:", memory_get_usage());
?>
