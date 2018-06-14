<?php
#SETTINGS#
$domainname = "ftp://172.16.29.132";
$domainhost="127.0.0.1";
$domainuser="silent-ninja";
$domainpass="bashguard!";
$homelink = "/home/".$domainuser;
$publiclink = "/files/";

function ftp_connection($host, $user, $pass, $uid){
    $conn_id = @ftp_connect($host);
    $login_result = @ftp_login($conn_id, $user, $pass);
    if($login_result){      
        $dir = $uid; # $userid here!!
        $public_link = 'files';
        #echo " Connected and logged in to FTP <br />";
        ftp_chdir($conn_id, $public_link);
        $folder_array = ftp_nlist($conn_id, ".");
        if (!in_array("$dir", $folder_array)){
            #echo "User folder not found, making one ...<br />";
            if(ftp_mkdir($conn_id, $dir)){
                #echo "User folder created...  <br />";
                #if (!ftp_chdir($conn_id, $dir)) die ('Unable to switch to working dir');
                #$current_dir = ftp_pwd($conn_id);
                #print_r("Current dir is $current_dir <br />");
            } 
        } else {
            #echo "Folder found <br />";
            #if (!ftp_chdir($conn_id, $dir)) die ('Unable to switch to working dir');
            #$current_dir = ftp_pwd($conn_id);
            #print_r("Current dir is $current_dir <br />");
        }
        return $conn_id;
    } else {
        return false;
    }
}
function ftp_connection_quit($conn_id){
    @ftp_quit($conn_id);
    @ftp_close($conn_id);
    return true;
}
function PutFilesonFTPServer($host, $user, $pass, $folder, $newfilename,$existingfilename){
    global $publiclink;
    $uploaded = false;
    $conn = ftp_connection($host, $user, $pass);
    if($conn  == 0){
        exit("Error while connecting FTP Server");
    }
    @ftp_set_option($conn, FTP_TIMEOUT_SEC, 1000);
    $path = $publiclink.$folder;
    @ftp_site($conn,"CHMOD 0777 $path.");
    @ftp_pasv($conn, true);
    if(!@ftp_put($conn, $path.$newfilename, $existingfilename, FTP_BINARY)){
        $uploaded = false;
    }else{
        $uploaded = true;
        #$uri = ftp_fetch($conn, $path.$newfilename);
        #print_r($path.$newfilename);
        #echo '<img src="' . $uri /* URI goes here */ . '"><br />';
    }
    @ftp_site($conn,"CHMOD 0755 $path.");
    ftp_connection_quit($conn);
    return $uploaded;
}
function ftp_fetch($ftp_stream, $remote_file) {
    ob_end_flush();
    ob_start();
    $out = fopen('php://output', 'w');
    if (!@ftp_fget($ftp_stream, $out, $remote_file, FTP_BINARY)) die('Unable to get file: ' . $remote_file);
        fclose($out);
        $data = ob_get_clean();
        $uri = "data:image/png;base64," . base64_encode($data);
    return $uri;
}

function DeleteFilesFromFTPServer($host, $user, $pass, $folder, $filename){
    global $publiclink;
    $deleted = false;
    $conn_id = @ftp_connect($host);
    $login_result = @ftp_login($conn_id, $user, $pass);
    @ftp_set_option($conn_id, FTP_TIMEOUT_SEC, 1000);
    $path = $publiclink.$folder;
    @ftp_site($conn_id, "CHMOD 0777 $path.");
    @ftp_pasv($conn_id, true);
    if(!@ftp_delete($conn_id, $path.$filename)){
        $deleted = false;
    } else {
        $deleted = true;
    }
    @ftp_site($conn_id, "CHMOD 0755 $path.");
    ftp_connection_quit($conn_id);
    return $deleted;
}

?>
