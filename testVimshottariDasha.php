<?php

	require_once 'orbit.php';
	require_once 'planet.php';
	require_once 'transit.php';
	require_once 'astroreport.php';
	require_once 'AspectsGenerator.php';
	require_once 'VimshottariDasha.php';
	require_once 'functions.php';

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
		$dasha = new VimshottariDasha($planets['Moon']['fulldegree'], $birthTS );
		$pratayantarDashaLord = $dasha->getDashaLord( time(), 3 );

var_dump( $pratayantarDashaLord );


?>