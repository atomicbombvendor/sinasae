<?php
/**
 * Created by PhpStorm.
 * User: ZZ
 * Date: 2018/5/13
 * Time: 16:10
 */
function getJokeInfo(){
    if(isset($_SERVER['HTTP_APPNAME'])){
        $mysql_host = SAE_MYSQL_HOST_M;
        $mysql_host_s = SAE_MYSQL_HOST_S;
        $mysql_port = SAE_MYSQL_PORT;
        $mysql_user = SAE_MYSQL_USER;
        $mysql_password = SAE_MYSQL_PASS;
        $mysql_database = SAE_MYSQL_DB;
    }else{
        $mysql_host = "localhost";
        $mysql_host_s = "localhost";
        $mysql_port = "3306";
        $mysql_user = "root";
        $mysql_password = "123456";
        $mysql_database = "weixin";
    }

    $mysql_table = "joke";
    $id = rand(1, 1000);
    $mysql_state = "SELECT * FROM ".$mysql_table." Where id='".$id."'";
    $con = mysqli_connect($mysql_host, $mysql_user, $mysql_password, $mysql_database, $mysql_port);
    if(!$con){
        logger("Couldn't connect： ".  mysqli_connect_error());
        die("Couldn't connect： ".  mysqli_connect_error());
    }

    mysqli_query($con, "SET NAMES 'UTF-8'");
    mysqli_select_db($con, $mysql_database);
    $result = mysqli_query($con, $mysql_state);
    if(!$result){
        logger("Error:%s\n".mysqli_error($con));
    }
    $joke = "";

    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
        $joke = $row["content"];
        break;
    }
    mysqli_close($con);
    return $joke;
}

function logger($log_content){
    $content = date('Y-m-d H:i:s')." ".$log_content."\r\n";
    if (isset($_SERVER['HTTP_APPNAME'])) { //如果是SAE
        sae_set_display_errors(false);
        sae_debug(trim($content));
        sae_set_display_errors(true);
    } else if ($_SERVER['REMOTE_ADDR'] != "127.0.0.1") {//LOCAL
        $max_size = 100000;
        $log_filename = "log.txt";
        if (file_exists($log_filename) and (abs(filesize($log_filename)) > $max_size)) {
            unlink($log_filename);
        }
        file_put_contents($log_filename, $content, FILE_APPEND);
    }
}

?>