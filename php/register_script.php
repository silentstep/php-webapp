<?php
# Callout the Database
$host = 'localhost';
$user = 'root';
$pass = 'bashguard!';
$db = 'user_accs';
$mysqli = new mysqli($host,$user,$pass,$db) or die($mysqli->error);
# Start a session.
session_start();

# make the posted variables into session variables;
$_SESSION['uid'] = $_POST['reg-uid'];
$_SESSION['first'] = $_POST['first'];
$_SESSION['last'] = $_POST['last'];

# Escape all POST variables to protect from sql injection;
$uid = $mysqli->escape_string($_POST['reg-uid']);
$first = $mysqli->escape_string($_POST['first']);
$last = $mysqli->escape_string($_POST['last']);
$pwd = $mysqli->escape_string(password_hash($_POST['reg-pwd'], PASSWORD_BCRYPT)); 

# check if everything is okay with the info entered;
if (!isset($_POST['first']) || !ctype_alpha($_POST['first']) ) {
    $_SESSION['message'] = 'Name can contain only alphabets and space';
    $_SESSION['logged_in'] = false; 
    echo "First name only in alphabets!";
    die;
}
if (!isset($_POST['last']) || !ctype_alpha($_POST['last']) ) {
    $_SESSION['message'] = 'Name can contain only alphabets and space';
    $_SESSION['logged_in'] = false; 
    echo "Last name only in alphabets!";
    die;
}
$check_pass = strlen($_POST['reg-pwd']);
if ($check_pass < 4) {
    $_SESSION['message'] = 'Password too short';
    $_SESSION['logged_in'] = false;
    echo "Password too short!";
    die;
}

# if check data is okay, query the database..(prepared statement procedure here!!)
$result = $mysqli->query("SELECT * FROM `usr` WHERE `uid`='$uid'") or die($mysqli->error());

# $result has num_rows attribute. It must be more than zero if the uid exists.
if ( $result->num_rows > 0 ) {
    $_SESSION['message'] = 'User already exists';
    $_SESSION['logged_in'] = false; 
    #header("location: /index.php");
    echo "User already exists!";
    die;
} else {
    $sql = "INSERT INTO `usr` (`first`, `last`, `uid`, `pwd`) VALUES ('$first', '$last', '$uid', '$pwd')";
    if ($mysqli->query($sql)) { # if no error, send a congrats message

        $domainname = "172.16.29.132";
        $domainhost="127.0.0.1";
        $domainuser="silent-ninja";
        $domainpass="bashguard!";
        $homelink = "/home/".$domainuser;
        $publiclink = "/files/";
        $savedfiledirectory="$uid";

        function ftp_connection($host, $user, $pass){
            global $pucliclink;
            $conn_id = @ftp_connect($host);
            $login_result = @ftp_login($conn_id, $user, $pass);
            if($login_result){      
                return $conn_id;
                if (ftp_chdir($con, $publiclink)){
                    echo "Changed folder \n";
                    $pwd = ftp_pwd($con);
                    print_r("Current dir $pwd \n");
                }
                $folder_array = ftp_nlist($con, ".");
                if (!in_array("$savedfiledirectory", $folder_array)){
                    echo "User folder not found, making a new one ... !";
                    if(ftp_mkdir($con, $dir)){
                        echo "User folder created";
                        ftp_chdir($con, $dir);
                    } else {
                        echo "Something went wrong";
                    }
                } else {
                    echo "Folder found, cd into it";
                    ftp_chdir($con, $dir);
                }
            }else{
                return 0;
            }
        }
        function ftp_connection_quit($conn_id){
            @ftp_quit($conn_id);
            @ftp_close($conn_id);
        }
        ftp_connection($domainhost, $domainuser, $domainpass);
        $_SESSION['message'] = 'Congrats! Successfuly registered!';
        $_SESSION['logged_in'] = true;
        $_SESSION['hash'] = $mysqli->escape_string(MD5(rand(0, 10000)));
        #header('location: /php/profile.php?u='. $_SESSION['hash']);
    } else { # else send a sad message
        $_SESSION['message'] = 'Oops! Something went wrong!';
        $_SESSION['logged_in'] = false;
        #header("location: /index.php");
        echo "Error!";
    }
}
?>
