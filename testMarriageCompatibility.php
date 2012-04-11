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

$male_report = new AstroReport($male_data); 

$female_report = new AstroReport($female_data); 

$calculator = new AnalyzeKutas( $male_report, $female_report );
$calculator->prepareKutaReport();

echo "Male Nakshatra: ";
echo $calculator->male_nakshatra;
echo "<br />";

echo "Female Nakshatra: ";
echo $calculator->female_nakshatra;
echo "<br />";
echo "<br />";

echo "Nadi Kuta Score: ";
echo $calculator->nadiKutaScore;
echo "/8<br />";

echo "Male Dosha: ";
echo $calculator->male_dosha;
echo "<br />";

echo "Female Dosha: ";
echo $calculator->female_dosha;
echo "<br />";
echo "<br />";

echo "Rashi Kuta Score: ";
echo $calculator->rashiKutaScore;
echo "/7<br />";

echo "Male Moon Sign Lord: ";
echo $calculator->male_moon_sign_lord;
echo "<br />";

echo "Female Moon Sign Lord: ";
echo $calculator->female_moon_sign_lord;
echo "<br />";
echo "<br />";

echo "Gana Kuta Score: ";
echo $calculator->ganaKutaScore;
echo "/6<br />";

echo "Male Gana: ";
echo $calculator->male_gana;
echo "<br />";

echo "Female Gana: ";
echo $calculator->female_gana;
echo "<br />";
echo "<br />";

echo "Graha Maitri Score: ";
echo $calculator->grahaMaitriScore;
echo "/5<br />";

echo "Male Moon Lord's Relationship with Female Moon Lord: ";
echo $calculator->m2f_moon_sign_lord_relationship;
echo "<br />";

echo "Female Moon Lord's Relationship with Male Moon Lord: ";
echo $calculator->f2m_moon_sign_lord_relationship;
echo "<br />";
echo "<br />";

echo "Yoni Kuta Score: ";
echo $calculator->yoniKutaScore;
echo "/4<br />";
echo "Male Yoni: ";
echo $calculator->male_yoni_sex;
echo " ";
echo $calculator->male_yoni;
echo "<br />";

echo "Female Yoni: ";
echo $calculator->female_yoni_sex;
echo " ";
echo $calculator->female_yoni;
echo "<br />";
echo "<br />";

echo "Dina Kuta Score: ";
echo $calculator->dinaKutaScore;
echo "/3<br />";
echo "Remainder: ";
echo $calculator->dinaRemainder;
echo "<br />";
echo "<br />";

echo "Vasya Kuta Score: ";
echo $calculator->vasyaKutaScore;
echo "/2<br />";

echo "Male Rashi: ";
echo $calculator->male_rashi;
echo "<br />";

echo "Female Rashi: ";
echo $calculator->female_rashi;
echo "<br />";
echo "<br />";

echo "Varna Kuta Score: ";
echo $calculator->varnaKutaScore;
echo "/1<br />";

echo "Male Varna: ";
echo $calculator->male_varna;
echo "<br />";

echo "Female Varna: ";
echo $calculator->female_varna;
echo "<br />";
echo "<br />";

echo "Total Score: ";
echo $calculator->totalKutaScore;
echo "/36<br /><br />";

echo "Kuja Dosha: ";
echo $calculator->kujaDosha;
echo "<br />";

echo "Male Kuja Dosha: ";
echo $calculator->male_kuja_dosha;
echo "<br />";

echo "Female Kuja Dosha: ";
echo $calculator->female_kuja_dosha;
?>