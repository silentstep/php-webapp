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

# FETCH SEARCH RESULLT;
$search = $mysqli->escape_string($_POST['search']);
$search = ucfirst(strtolower($search));

# INITIALIZE STATEMENT;
$stmt = $mysqli->stmt_init();

# PREPARE STATEMENT FOR QUERY;
$stmt->prepare(" SELECT `path`, `caption`, `cname`, `name`
    FROM `images` 
    LEFT JOIN `countries` 
    ON (countries.countryID=images.countryID) 
    RIGHT JOIN `cities` 
    ON (cities.cityID = images.cityID) 
   WHERE countries.name= ? ");

# BIND PARAMS;
$stmt->bind_param('s', $search);

# MAKE THE QUERY;
$stmt->execute();

# GET THE RESULT;
$result = $stmt->get_result();

#ChromePhp::log(print_r($result));
#die();

# RUN COMPILE FUNCTION;
compile_gallery();

ChromePhp::log("RUN compile_gallery()");
ChromePhp::log(memory_get_usage());

# THE COMPILE FUNCTION;
function compile_gallery(){
    global $result;
    global $ftp_con;
    $count = 0;
    while ($result_row = $result->fetch_array(MYSQLI_NUM)){
        $src = $result_row[0];
        $uri = ftp_fetch($ftp_con, $src);
        echo '<li id="result-list">
                <img id="img" src="'.$uri.'">
                <div class="caption right-align">
                    <h4>'.$result_row[3].','.$result_row[2].'</h4>
                    <p class="light yellow-text text-lighten-4">'.$result_row[1].'</p>
                    <a class="btn-floating btn-medium waves-effect waves-light red rewind tooltipped" data-position="left" data-delay="50" data-tooltip="Prev" onClick="rwd();">
                        <i class="fa fa-arrow-left"></i>
                    </a>
                    <a class="btn-floating btn-medium waves-effect waves-light green forward tooltipped" data-position="bottom" data-delay="50" data-tooltip="Next" onClick="fwd();">
                        <i class="fa fa-arrow-right"></i>
                    </a>    
 
                </div>
              </li>';
        $count++;
    }
    ftp_connection_quit($ftp_con);
    if ($count === 0){
        echo '<li id="result-list">
                  <h2 class="flow-text">This location is not in our database</h2>
              </li>';
    }
    ChromePhp::log("Result count: $count");
}
?>
