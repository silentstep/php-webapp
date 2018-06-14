<?php
session_start();
if ($_SESSION['logged_in'] != 1) {
    $_SESSION['error'] = true;
    header("location: /php/error.php");
} else {
    # take session variable for the specific user;
    $uid = $_SESSION['uid'];
}
$host = 'localhost';
$user = 'root';
$pass = 'bashguard!';
$db = 'user_accs';
$mysqli = new mysqli($host,$user,$pass,$db) or die($mysqli->error);

# CREATE USER DIRECTORY IF NON IS FOUND
$dir = "/var/www/picblog/test_upload/".$uid;
if(!is_dir($dir)){
    mkdir($dir, 0777);
} 

# PREVET SYMBOLS IN FORM
#if (!ctype_alpha($_POST['country']) ) {
#    die(" Use only alphabets in country, please! ");
#}
#if (!ctype_alpha($_POST['city']) ) {
#    die(" Use only alphabets in city, please! ");
#}
#if (!ctype_alpha($_POST['caption']) ) {
#    die(" Use only alphabets in caption, please! ");
#}

# PREVENT EMPTY FIELDS
if (isset($_FILES['image']['name'])) {
    if (!empty($_POST['country'])) {
        if (!empty($_POST['city'])) {
            if (!empty($_POST['caption'])) {
                # GRAB FILE ARRAY
                $name = $_FILES['image']['name'];
                $tmp_name  = $mysqli->escape_string($_FILES['image']['tmp_name']);
                $err = $_FILES['image']['error'];

                # SECURITY CHECKS
                # Check for errors
                if ($err !== UPLOAD_ERR_OK) {
                   die("Upload failed with error code " . $err);
                }
                
                # Determine file type
                $info = getimagesize($tmp_name);
                if ($info === FALSE) {
                   die("Unable to determine image type of uploaded file");
                }

                #Lowercase extensions
                $imgExt = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                $valid_extensions = array('jpeg', 'jpg', 'png', 'gif');
                $userpic = rand(100,1000000).".".$imgExt;
                if(!in_array($imgExt, $valid_extensions)){
                    die("Only GIF/JPEG/JPG/PNG are allowed");
                }

                # GRAB OTHER DATA
                $country = $mysqli->escape_string($_POST['country']);
                $country = ucfirst(strtolower($country));
                $city = $mysqli->escape_string($_POST['city']);
                $city = ucfirst(strtolower($city));
                $caption = $mysqli->escape_string($_POST['caption']);
                
                #QUERY DB FOR EISTING ENTRY(prepared statement procedure here!!!)
                $countryExist = $mysqli->query("SELECT * FROM countries WHERE name='$country'");
                # $qryExist has num_rows attribute. It must be less than zero to add location.
                if ( $countryExist->num_rows == 0 ) {
                    $mysqli->query("INSERT INTO `countries` VALUE(NULL, '$country')");     
                }
                $cityExist = $mysqli->query("SELECT * FROM `cities` WHERE `cname`='$city'");
                if ( $cityExist->num_rows == 0 ) {
                    $mysqli->query("
                        INSERT INTO cities(cname, countryID)
                        SELECT '$city', countryID
                        FROM countries
                        WHERE name= '$country'
                        LIMIT 1
                    ");
                }
                # STORAGE LOCATION
                $location = "/var/www/picblog/test_upload/".$uid."/";
                $path = $location.$userpic;
                if(move_uploaded_file($tmp_name, $path)){ // IF ALL IS OKAY, PREPARE DB ENTRIES.
                    # FETCH COUNTRY ID FROM DB;
                    $fetchCountry = $mysqli->query("SELECT countryID FROM `countries` WHERE name='$country'");
                    $countryRow= $fetchCountry->fetch_array(MYSQLI_NUM);
                    $countryID = $countryRow[0];

                    # FETCH CITY ID FROM DB;
                    $fetchCity = $mysqli->query("SELECT cityID FROM `cities` WHERE cname='$city'");
                    $cityRow= $fetchCity->fetch_array(MYSQLI_NUM);
                    $cityID= $cityRow[0];

                    # FETCH USER ID FROM DB;
                    $fetchUser = $mysqli->query("SELECT id FROM `usr` WHERE uid='$uid'");
                    $userRow = $fetchUser->fetch_array(MYSQLI_NUM);
                    $userID = $userRow[0];
                    $sql = "INSERT INTO `images` VALUE(NULL, '$path', '$caption', '$userID', '$cityID', '$countryID')";
                    $result = $mysqli->query($sql);
                    if($result){
                        echo "Success!";
                        $mysqli->close();
                    } else {
                        echo "Database error occured!";
                        $mysqli->close();
                    } 
                } else {
                    $mysqli->close();
                    die("File not uploaded to server, try again!");
                }
            } else {
                $mysqli->close();
                die("Adding a caption = beer for SysAdmin :D");
            }
        } else {
            $mysqli->close();
            die("Please specify location for your photo :)");
        }
    } else {
        $mysqli->close();
        die("Please specify country :)");
    }
} else {
    $mysqli->close();
    die("Oops, try to select an image first :) ");
}
?>
