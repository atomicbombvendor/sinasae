<?php
/**
 * Created by PhpStorm.
 * User: atomic
 * Date: 5/18/2018
 * Time: 15:23
 */
//Key：z2Yry0BNVzGq
//Secret：kaHz8jhF3gmmLp7QL32Y

function getXiaoIInfo($openId, $content){

    //define APP
    $app_key = "z2Yry0BNVzGq";
    $app_secret = "kaHz8jhF3gmmLp7QL32Y";

    //signature
    //签名算法
    $realm = "xiaoi.com";
    $method = "POST";
    $uri = "/robot/ask.do";
    $nonce = "";
    $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
    for ($i = 0; $i < 40; $i++) {
        $nonce .= $chars[ mt_rand(0, strlen($chars) - 1) ];
    }
    $HA1 = sha1($app_key.":".$realm.":".$app_secret);
    $HA2 = sha1($method.":".$uri);
    $sign = sha1($HA1.":".$nonce.":".$HA2);

    //接口调用
    $url = "http://nlp.xiaoi.com/robot/ask.do";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-Auth:	app_key="'.$app_key.'", nonce="'.$nonce.'", signature="'.$sign.'"'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "question=".urlencode($content)."&userId=".$openId."&platform=custom&type=0");
    $output = curl_exec($ch);
    if ($output === FALSE){
        return "cURL Error: ". curl_error($ch);
    }
    return trim($output);
}
?>