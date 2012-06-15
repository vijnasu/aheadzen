<?php

require_once 'orbit.php';
require_once 'planet.php';
require_once 'transit.php';
require_once 'astroreport.php';
require_once 'AstroData.php';
require_once 'AnalyzeChart.php';
require_once 'AnalyzeLongevity.php';
error_reporting(0);
$birth_data = array('timezone' =>
		    array (
			   'hours' => 5,
			   'min' => 30,
			   'direction' => 'E',
			   ),
		    'longitude' =>
		    array (
			   'degrees' => 75,
			   'min' => 49,
			   'direction' => 'E',
			   ),
		    'latitude' =>
		    array (
			   'degrees' => 26,
			   'min' => 55,
			   'direction' => 'N',
			   ),
		    'month' => 7,
		    'day' => 7,
		    'year' => 1986,
		    'hour' => 8,
		    'min' => 53,
		    'report_name' => 'AT',
		    'city' => 'Jaipur',
		    'country' => 'IN',
		    'am_pm' => 'am',
		    'sex' => 'male',
		    'has_all_info' => true,
		    );

$calculator = new AnalyzeLongevity($birth_data);
$years = $calculator->calculateDifficultPeriods();
var_dump($years);

?>