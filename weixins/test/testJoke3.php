<?php
/**
 * Created by PhpStorm.
 * User: ZZ
 * Date: 2018/5/13
 * Time: 16:30
 */

class MyTestCase extends PHPUnit_Framework_TestCase{

    public function testGetJokeInfo(){
        require_once ("../code/getJokeInfo.php");
        $this->assertTrue(getJokeInfo()!=null);
    }
}

class StackTest extends PHPUnit_Framework_TestCase
{
    public function testPushAndPop()
    {
        $stack = [];
        $this->assertEquals(0, count($stack));

        array_push($stack, 'foo');
        $this->assertEquals('foo', $stack[count($stack) - 1]);
        $this->assertEquals(1, count($stack));

        $this->assertEquals('foo', array_pop($stack));
        $this->assertEquals(0, count($stack));
    }
}