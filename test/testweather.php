<?php
/**
 * Created by PhpStorm.
 * User: eli9
 * Date: 5/7/2018
 * Time: 13:02
 */

include ("../weather.php");

$result = getWeatherInfo("产假");
echo $result;