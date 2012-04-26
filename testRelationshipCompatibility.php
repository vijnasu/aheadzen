<?php

require_once 'orbit.php';
require_once 'planet.php';
require_once 'transit.php';
require_once 'astroreport.php';
require_once 'AstroData.php';
require_once 'AnalyzeChart.php';
require_once 'AnalyzeKutas.php';

$birth_data = array();

$birth_data[0] = array (
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
  'report_name' => 'AT',
  'city' => 'Jaipur',
  'country' => 'IN',
  'am_pm' => 'am',
  'sex' => 'male',
  'has_all_info' => true,
);

$birth_data[1] = array (
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
  'month' => 4,
  'day' => 24,
  'year' => 1987,
  'hour' => 11,
  'min' => 35,
  'report_name' => 'Richa',
  'city' => 'Jaipur',
  'country' => 'IN',
  'am_pm' => 'am',
  'sex' => 'female',
  'has_all_info' => true,
);

if ($birth_data[0]['sex'] == 'male' && $birth_data[1]['sex'] == 'female') {
   $male_data = $birth_data[0];
   $female_data = $birth_data[1];
} elseif ($birth_data[0]['sex'] == 'female' && $birth_data[1]['sex'] == 'male') {
   $male_data = $birth_data[1];
   $female_data = $birth_data[0];  
} else {
  echo "Error: can not find male and female data";
}

function br()
{
	return "<br />";
}

function h5()
{
	return "<h5>" . implode(func_get_args()) . "</h5>";
}

function h4()
{
	return "<h4>" . implode(func_get_args()) . "</h4>";
}

function h3()
{
	return "<h3>" . implode(func_get_args()) . "</h3>";
}

function h2()
{
	return "<h2>" . implode(func_get_args()) . "</h2>";
}

function begin_ul()
{
	return "<ul>";
}

function end_ul()
{
	return "</ul>";
}

function li()
{
	return "<li>" . implode( func_get_args() ) . "</li>";
}

function ul( $array )
{
	$string = begin_ul();
	foreach ( $array as $item )
	{
		$string.= li( $item );
	}
	return $string . end_ul();
}


$male_report = new AstroReport($male_data); 

$female_report = new AstroReport($female_data); 

$kuta_calculator = new AnalyzeKutas( $male_report, $female_report );
$kuta_calculator->prepareKutaReport();

$relationship_calculator = new AnalyzeChart( $male_report );
$relationship_calculator->prepareRelationshipReport( $female_report );

echo h2($male_data['report_name'], ' and ', $female_data['report_name']);
echo h3("Analyzing Capacity to Love");

echo h4($male_data['report_name']);
echo "Ascendant: ", $relationship_calculator->ascendant_name, br();
echo "Ascendant Lord: ", $relationship_calculator->ascendant_lord, br(), br();

echo h5("Planets Influencing Ascendant");

echo ul( $relationship_calculator->influencing_ascendant );


echo h5("Planets Influencing Ascendant Lord- ", $relationship_calculator->ascendant_lord);

echo ul( $relationship_calculator->influencing_ascendant_lord );

echo h5( "Natures Influencing Ascendant" );
echo ul( $relationship_calculator->natures_influencing_ascendant );

echo h5( "Natures Influencing Ascendant Lord" );
echo ul( $relationship_calculator->natures_influencing_ascendant_lord );

echo h5( "Position" );
echo $relationship_calculator->ascendant_lord, " is in the ",
     $relationship_calculator->ascendant_lord_house,
     " house from the Ascendant. ",
     $relationship_calculator->ascendant_lord_positional, br();







echo "Male Nakshatra: ";
echo $kuta_calculator->male_nakshatra, br();

echo "Female Nakshatra: ";
echo $kuta_calculator->female_nakshatra, br(), br();

echo "Nadi Kuta Score: ";
echo $kuta_calculator->nadiKutaScore;
echo "/8", br();

echo "Male Dosha: ";
echo $kuta_calculator->male_dosha, br();

echo "Female Dosha: ";
echo $kuta_calculator->female_dosha, br(), br();

echo "Rashi Kuta Score: ";
echo $kuta_calculator->rashiKutaScore;
echo "/7", br();

echo "Male Moon Sign Lord: ";
echo $kuta_calculator->male_moon_sign_lord, br();

echo "Female Moon Sign Lord: ";
echo $kuta_calculator->female_moon_sign_lord, br(), br();

echo "Gana Kuta Score: ";
echo $kuta_calculator->ganaKutaScore;
echo "/6", br();

echo "Male Gana: ";
echo $kuta_calculator->male_gana, br();

echo "Female Gana: ";
echo $kuta_calculator->female_gana, br(), br();

echo "Graha Maitri Score: ";
echo $kuta_calculator->grahaMaitriScore;
echo "/5", br();

echo "Male Moon Lord's Relationship with Female Moon Lord: ";
echo $kuta_calculator->m2f_moon_sign_lord_relationship, br();

echo "Female Moon Lord's Relationship with Male Moon Lord: ";
echo $kuta_calculator->f2m_moon_sign_lord_relationship, br(), br();

echo "Yoni Kuta Score: ";
echo $kuta_calculator->yoniKutaScore;
echo "/4", br();
echo "Male Yoni: ";
echo $kuta_calculator->male_yoni_sex;
echo " ";
echo $kuta_calculator->male_yoni, br();

echo "Female Yoni: ";
echo $kuta_calculator->female_yoni_sex;
echo " ";
echo $kuta_calculator->female_yoni, br(), br();

echo "Dina Kuta Score: ";
echo $kuta_calculator->dinaKutaScore;
echo "/3", br();
echo "Remainder: ";
echo $kuta_calculator->dinaRemainder, br(), br();

echo "Vasya Kuta Score: ";
echo $kuta_calculator->vasyaKutaScore;
echo "/2", br();

echo "Male Rashi: ";
echo $kuta_calculator->male_rashi, br();

echo "Female Rashi: ";
echo $kuta_calculator->female_rashi, br(), br();

echo "Varna Kuta Score: ";
echo $kuta_calculator->varnaKutaScore;
echo "/1", br();

echo "Male Varna: ";
echo $kuta_calculator->male_varna, br();

echo "Female Varna: ";
echo $kuta_calculator->female_varna, br(), br();

echo "Total Score: ";
echo $kuta_calculator->totalKutaScore;
echo "/36", br(), br();

echo "Kuja Dosha: ";
echo $kuta_calculator->kujaDosha, br();

echo "Male Kuja Dosha: ";
echo $kuta_calculator->male_kuja_dosha, br();

echo "Female Kuja Dosha: ";
echo $kuta_calculator->female_kuja_dosha;
?>