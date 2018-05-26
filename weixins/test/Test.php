<?php
/**
 * Created by PhpStorm.
 * User: eli9
 * Date: 5/18/2018
 * Time: 15:40
 */


class Test extends PHPUnit_Framework_TestCase
{
    public function test_XiaoIBot(){
        include_once ("../code/xiaoIBot.php");
        $openId = "adbc";
        $content = "深圳的天气怎么样?";
        $result = getXiaoIInfo($openId, $content);
        echo $result;
    }

    public function test(){
       include_once ("../orders/submit.php");
       sendMail();

    }
}
