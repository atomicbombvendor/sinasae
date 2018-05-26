<?php
/**
 * Created by PhpStorm.
 * User: eli9
 * Date: 5/26/2018
 * Time: 18:48
 */

namespace app\weixin\controller;
include("messageWeixin.php");
use think\Controller;
use think\Db;

define("TOKEN_WEIXIN", "weixin");

class Index extends Controller{
    public function index(){
        if(!isset($_GET["echostr"])){
            $this->responseMsg();
        }else{
            $this->valid();
        }
    }

    public function valid(){
        $echoStr = $_GET["echostr"];
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $token = TOKEN_WEIXIN;

        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        if($tmpStr == $signature){
            echo $echoStr;
            exit;
        }
    }

    private function responseMsg()
    {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        responseMsg($postStr);
    }

}
