<?php
/**
 * Created by PhpStorm.
 * User: ZZ
 * Date: 2018/5/13
 * Time: 16:30
 */

require_once('../simpletest/unit_tester.php');
require_once('../simpletest/reporter.php');
//$result = getJokeInfo();
//echo $result;

class MyTestCase extends UnitTestCase{

    function MyTestCase() {
        $this->UnitTestCase('File test');
    }

    function setUp() {
        @unlink('../temp/test.txt');
    }

    function tearDown() {
        @unlink('../temp/test.txt');
    }

    function testGetJokeInfo(){
        require_once ("../code/getJokeInfo.php");
        $this->assertTrue(getJokeInfo(), 'get joke');
    }
}

$test = &new MyTestCase();
$test->run(new HtmlReporter());