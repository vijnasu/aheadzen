<?php  
require_once 'orbit.php5';
require_once 'solar_chart.php5';
require_once 'planet.php5';
require_once TEMPLATEPATH . '/include/classes/transit.php5';
require_once TEMPLATEPATH . '/include/classes/astroreport.php5';

//list($month, $numday, $year, $fullmonth, $dow, $hours, $am_pm) = split('[/.-]', date("m.d.Y.F.w.g.a"));
$aa = new AstroReport(26.5, 'N', 75.5, 'E', $day, $month, $year, $hour, $minutes, $amORpm, 5.5);
//$aa = new AstroReport(28.36, 'N', 77.12, 'E', 5, 1, 1987, 1, 50, 'am', 5.5);		//khushi
//$aa = new AstroReport(26.5, 'N', 75.5, 'E', 21, 11, 1983, 9, 33, 'pm', 5.5);		//Nidhi
//$aa = new AstroReport(28.36, 'N', 77.12, 'E', 28, 8, 1985, 7, 00, 'pm', 5.5);		//ahuja
//$aa = new AstroReport(23.16, 'N', 77.24, 'E', 30, 10, 1985, 9, 30, 'am', 5.5);	//gupta
//$aa = new AstroReport(28.36, 'N', 77.12, 'E', 6, 2, 1987, 6, 15, 'pm', 5.5);		//ishani ahuja

$houses = $aa->getHouses();
$planets = $aa->getPlanets();
var_dump( $houses );
foreach( $planets as $planet => $data )
{
	$planetId = getPlanetIdByName($planet);
	$h = $data['house'];
	$getContentHouses = mysql_query("SELECT content FROM birth_report WHERE content_type = 1 AND object_id = $h AND planet_id = $planetId",$link);
	$row = mysql_fetch_row($getContentHouses);
	echo '<strong>' . $planet . ' is in ' . $h . ' House.</strong>';
	echo "<p>$row[0]</p>";
	//echo "<p>SELECT content FROM birth_report WHERE content_type = 1 AND object_id = $h AND planet_id = $planetId</p>";
	$h = $data['sign_number'];
	$getContentZodiacSigns = mysql_query("SELECT content FROM birth_report WHERE content_type = 2 AND object_id = $h AND planet_id = $planetId",$link);
	$row = mysql_fetch_row($getContentZodiacSigns);
	echo '<strong>' . $planet . ' is in ' . $data['sign'] . '</strong>';
	echo "<p>$row[0]</p>";
	//echo "<p>SELECT content FROM birth_report WHERE content_type = 2 AND object_id = $h AND planet_id = $planetId</p><hr />";
	echo '<hr />';
}

function getPlanetIdByName($name)
{
	$pid = array( 'Sun' => 1, 'Moon' => 2, 'Mercury' => 3, 'Venus' => 4, 'Mars' => 5, 'Jupiter' => 6, 'Saturn' => 7, 'Neptune' => 8, 'Uranus' => 9, 'Pluto' => 10, 'Rahu'  => 20,  'Ketu'=> 21 );
	return $pid[$name];
}
function getHouseBySignNumber($sign, $houses)
{
	$house = $sign - $houses['ASC']['sign_number'] + 1;
	if( $house < 1 )
	{
		$house += 12;
	}
	return $house;
}
?>