<?php

$domainname = "ftp://172.16.29.132";
$domainhost="127.0.0.1";
$domainuser="silent-ninja";
$domainpass="bashguard!";
$homelink = "/home/".$domainuser;
$publiclink = "/files/";
$savedfiledirectory="ventsi123/";
$con = ftp_connect($domainhost) or die ("Couldn't connect ...\n");
$login_result = ftp_login($con, $domainuser, $domainpass);
if ($login_result){
    echo "Logged in \n";
} else {
    echo "Cant log in\n";
}
function ftp_connection_quit($conn_id){
    @ftp_quit($conn_id);
    @ftp_close($conn_id);
    return true;
}

$filename = '148054.jpg';
$deleted = false;
ftp_set_option($con, FTP_TIMEOUT_SEC, 1000);
$path = $publiclink.$savedfiledirectory;
ftp_site($con, "CHMOD 0777 $path.");
ftp_pasv($con, true);

if(!ftp_delete($con, $path.$filename)){
    $deleted = false;
    echo $deleted;
} else {
    $deleted = true;
    echo $deleted;
}

if (ftp_connection_quit($con)){
    echo "Quit\n";
} else {
    echo "Can't close connection\n";
}
?>
