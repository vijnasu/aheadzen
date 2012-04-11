<?php

	require_once 'orbit.php';
	require_once 'planet.php';
	require_once 'transit.php';
	require_once 'astroreport.php';
	require_once 'NorthernChartMaker.php';
	require_once 'NavamshMaker.php';

$birth_data = array (
  'timezone' =>
      array (
        'hours' => 7,
        'min' => 0,
        'direction' => 'W',
      ),
  'longitude' =>
      array (
        'degrees' => 112,
        'min' => 26,
        'direction' => 'W',
      ),
  'latitude' =>
      array (
        'degrees' => 42,
        'min' => 52,
        'direction' => 'N',
      ),
  'month' => 7,
  'day' => 28,
  'year' => 1942,
  'hour' => 6,
  'min' => 11,
  'report_name' => '',
  'city' => 'Pocatello',
  'country' => 'USA',
  'am_pm' => 'pm',
  'has_all_info' => true,
);

		$aa = new AstroReport( $birth_data );

		$houses = $aa->getHouses();
		$planets = $aa->getPlanets();
		$nmaker = new NavamshMaker($houses,$planets);
		print '<pre>';
		
		$nhouses = $nmaker->get_nhouses();
print '\nPlanets';
		var_dump($nhouses);
		//var_dump($nmaker->get_nplanets());

?>