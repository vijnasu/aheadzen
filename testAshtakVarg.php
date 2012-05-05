<?php

	require_once 'orbit.php';
	require_once 'planet.php';
	require_once 'transit.php';
	require_once 'astroreport.php';
	require_once 'functions.php';
	require_once 'ashtakvarg.php';

$birth_data = array (
  'timezone' => 
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
  'report_name' => 'Arpit Tambi',
  'city' => 'Jaipur',
  'country' => 'IN',
  'am_pm' => 'am',
  'has_all_info' => true,
);

		$aa = new AstroReport( $birth_data );
		$birthTS = getBirthTS( $birth_data );
		$houses = $aa->getHouses();
		$planets = $aa->getPlanets();
		$my = new AshtakVarg( $planets, $houses['ASC']['sign_number'] );
		$houses = $my->getHouseRating($houses);
$br = '<br />';

echo 'House Evaluation' . $br;
echo '----------------' . $br;

for($i = 1; $i < 13; $i++)
{
	echo 'House ' . $i . ' - ' . $houses[$i]['Points'][0] . ' - ' . $houses[$i]['Points'][1] . $br;
}

var_dump( $houses );


?>