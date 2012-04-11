<?php

	require_once 'orbit.php';
	require_once 'planet.php';
	require_once 'transit.php';
	require_once 'astroreport.php';
	require_once 'AspectsGenerator.php';

/*
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
);*/
/**/
$birth_data = array (
  'timezone' => 
	  array (
		'hours' => 2,
		'min' => 0,
		'direction' => 'E',
	  ),
  'longitude' => 
	  array (
		'degrees' => 26,
		'min' => 6,
		'direction' => 'E',
	  ),
  'latitude' => 
	  array (
		'degrees' => 44,
		'min' => 26,
		'direction' => 'N',
	  ),
  'month' => 2,
  'day' => 19,
  'year' => 1979,
  'hour' => 9,
  'min' => 45,
  'report_name' => 'Ishani Ahuja',
  'city' => 'New Delhi',
  'country' => 'IN',
  'am_pm' => 'am',
  'has_all_info' => true,
);


		$aa = new AstroReport( $birth_data );

		$houses = $aa->getHouses();
		$planets = $aa->getPlanets();

//var_dump( $planets );

	$a = new AspectsGenerator($birth_data);
	$res = $a->find_aspects("2010:8:27:12:00:am","2011:12:31:12:00:pm");
/*	$res1 = $a->find_aspects_year(2000);
	if(!array_diff($res,$res1))
	print "the two outputs are equal";
*/

$planetNumber = array();
$planetNumber['ASC'] = 0;
$planetNumber['Sun'] = 1;
$planetNumber['Moon'] = 2;
$planetNumber['Mercury'] = 3;
$planetNumber['Venus'] = 4;
$planetNumber['Mars'] = 5;
$planetNumber['Jupiter'] = 6;
$planetNumber['Saturn'] = 7;
$planetNumber['Rahu'] = 20;
$planetNumber['Ketu'] = 21;

$aspect_text = array(	0=>"conjunction",
						180 => "opposition",
						60 => "sextile",
						90 => "square",
						270 => "square",
						120 => "trine",
						240 => "trine"
					);
$link = mysql_connect('localhost', 'root', '');
	if (!$link) {
	   die('Could not connect: ' . mysql_error());
}

$db = mysql_select_db("wow", $link);
if (!$db)
   die('Could not select database: ' . mysql_error());

foreach( $res as $transitDate => $aspect )
{
	list($yyyy, $mm, $dd) = split('[:]', $transitDate);

	$yyyy = (int)$yyyy;
	$mm = (int)$mm;
	$dd = (int)$dd;


	$strTransitDate = date("F j, Y", mktime(0, 0, 0, $mm, $dd, $yyyy));

	foreach( $aspect as $a)
	{
		$skip = array('Neptune', 'Pluto', 'Uranus');
		if( in_array( $a[0], $skip ) || in_array( $a[1], $skip ) )
			continue;
		
		if( $a[2] == 180 && ($a[1] == 'Ketu' || $a[1] == 'Rahu'))
			continue;
		
		$thisAspect = $aspect_text[ $a[2] ];
		echo "<strong>$a[0] $thisAspect $a[1]: $strTransitDate</strong><br />";
		$planet_id = $planetNumber[ $a[0] ];
		$planet_aspected = $planetNumber[ $a[1] ];
		$aspect_type = $a[2];

		if( $aspect_type == 240 )
			$aspect_type = 120;


		$qry = "SELECT content FROM future_report WHERE planet_id = $planet_id AND planet_aspected = $planet_aspected AND aspect_type=$aspect_type";

		$getContent = mysql_query($qry, $link);
		$content = mysql_fetch_row( $getContent );
		echo "<p>" . $content[0] . "</p>";
	}



}
?>