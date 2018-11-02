<?php
/**
 * Created by PhpStorm.
 * User: atomic
 * Date: 5/7/2018
 * Time: 13:02
 */

include("../code/weather.php");

$result = getWeatherInfo("产假");
echo $result;