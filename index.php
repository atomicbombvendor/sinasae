<?php
/**
 * Created by PhpStorm.
 * User: eli9
 * Date: 5/5/2018
 * Time: 16:07
 */

include("code/wechatCallbackapiTest.php");
header('Content-type:text');
define("TOKEN", "weixin");

$wechatObj = new wechatCallbackapiTest();
if(isset($_GET['echostr'])){
    $wechatObj->valid();
}else{
    $wechatObj->responseMsg();
}

?>