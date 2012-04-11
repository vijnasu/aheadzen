<?php

				require_once 'orbit.php';
				require_once 'planet.php';
				require_once 'transit.php';
				require_once 'astroreport.php';
				require_once 'ChartMaker.php';
				require_once 'DivisionalChartMaker.php';
/**/

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
        'degrees' => 36,
        'min' => 49,
        'direction' => 'E',
      ),
  'latitude' =>
      array (
        'degrees' => 1,
        'min' => 16,
        'direction' => 'S',
      ),
  'month' => 3,
  'day' => 27,
  'year' => 1980,
  'hour' => 9,
  'min' => 30,
  'report_name' => 'Arpit Tambi',
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
        'degrees' => 77,
        'min' => 12,
        'direction' => 'E',
      ),
  'latitude' =>
      array (
        'degrees' => 28,
        'min' => 36,
        'direction' => 'N',
      ),
  'month' => 1,
  'day' => 5,
  'year' => 1987,
  'hour' => 1,
  'min' => 50,
  'report_name' => 'Khushboo Azad',
  'city' => 'Jaipur',
  'country' => 'IN',
  'am_pm' => 'am',
  'sex' => 'female',
  'has_all_info' => true,
);

$birth_data[2] = array (
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
  'month' => 5,
  'day' => 21,
  'year' => 1959,
  'hour' => 9,
  'min' => 51,
  'report_name' => 'JKT',
  'city' => 'Jaipur',
  'country' => 'IN',
  'am_pm' => 'pm',
  'sex' => 'male',
  'has_all_info' => true,
);

$birth_data[3] = array (
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
  'month' => 11,
  'day' => 21,
  'year' => 1983,
  'hour' => 9,
  'min' => 33,
  'report_name' => 'nidhi',
  'city' => 'Jaipur',
  'country' => 'IN',
  'am_pm' => 'pm',
  'sex' => 'female',
  'has_all_info' => true,
);

$birth_data[4] = array (
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
  'month' => 6,
  'day' => 5,
  'year' => 1983,
  'hour' => 7,
  'min' => 25,
  'report_name' => 'VIJAY',
  'city' => 'Jaipur',
  'country' => 'IN',
  'am_pm' => 'pm',
  'sex' => 'male',
  'has_all_info' => true,
);

$birth_data[5] = array (
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
  'month' => 3,
  'day' => 16,
  'year' => 1961,
  'hour' => 7,
  'min' => 49,
  'report_name' => 'Mummy',
  'city' => 'Jaipur',
  'country' => 'IN',
  'am_pm' => 'am',
  'sex' => 'female',
  'has_all_info' => true,
);

$birth_data[6] = array (
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
  'month' => 8,
  'day' => 10,
  'year' => 1981,
  'hour' => 11,
  'min' => 5,
  'report_name' => 'Manila',
  'city' => 'Jaipur',
  'country' => 'IN',
  'am_pm' => 'am',
  'sex' => 'female',
  'has_all_info' => true,
);

$birth_data[7] = array (
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
  'month' => 2,
  'day' => 14,
  'year' => 1980,
  'hour' => 0,
  'min' => 5,
  'report_name' => 'Bhavuk',
  'city' => 'Jaipur',
  'country' => 'IN',
  'am_pm' => 'am',
  'sex' => 'male',
  'has_all_info' => true,
);

$birth_data[8] = array (
  'timezone' => 
  array (
    'hours' => 5,
    'min' => 30,
    'direction' => 'E',
  ),
  'longitude' => 
  array (
    'degrees' => 74,
    'min' => 51,
    'direction' => 'E',
  ),
  'latitude' => 
  array (
    'degrees' => 31,
    'min' => 37,
    'direction' => 'N',
  ),
  'month' => 5,
  'day' => 23,
  'year' => 1942,
  'hour' => 6,
  'min' => 24,
  'report_name' => 'Case study 41',
  'city' => 'Amritsar',
  'country' => 'IN',
  'am_pm' => 'pm',
  'sex' => 'male',
  'has_all_info' => true,
);

$birth_data[9] = array (
  'timezone' => 
  array (
    'hours' => 5,
    'min' => 30,
    'direction' => 'E',
  ),
  'longitude' => 
  array (
    'degrees' => 77,
    'min' => 19,
    'direction' => 'E',
  ),
  'latitude' => 
  array (
    'degrees' => 28,
    'min' => 25,
    'direction' => 'N',
  ),
  'month' => 3,
  'day' => 7,
  'year' => 1976,
  'hour' => 0,
  'min' => 16,
  'report_name' => 'Case study 14',
  'city' => 'Faridabad',
  'country' => 'IN',
  'am_pm' => 'am',
  'sex' => 'male',
  'has_all_info' => true,
);

$birth_data[10] = array (
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
  'month' => 12,
  'day' => 10,
  'year' => 1954,
  'hour' => 1,
  'min' => 55,
  'report_name' => 'Case study 33',
  'city' => 'Jaipur',
  'country' => 'IN',
  'am_pm' => 'pm',
  'sex' => 'male',
  'has_all_info' => true,
);

// NO RESULT -- But marrriage date found in 2nd test.
$birth_data[11] = array (
  'timezone' => 
  array (
    'hours' => 5,
    'min' => 30,
    'direction' => 'E',
  ),
  'longitude' => 
  array (
    'degrees' => 80,
    'min' => 20,
    'direction' => 'E',
  ),
  'latitude' => 
  array (
    'degrees' => 26,
    'min' => 28,
    'direction' => 'N',
  ),
  'month' => 11,
  'day' => 20,
  'year' => 1964,
  'hour' => 7,
  'min' => 30,
  'report_name' => 'Case study 25',
  'city' => 'Kanpur',
  'country' => 'IN',
  'am_pm' => 'am',
  'sex' => 'male',
  'has_all_info' => true,
);

// NO RESULT -- Early marriage at age of 19
$birth_data[12] = array (
  'timezone' => 
  array (
    'hours' => 3,
    'min' => 0,
    'direction' => 'E',
  ),
  'longitude' => 
  array (
    'degrees' => 30,
    'min' => 15,
    'direction' => 'E',
  ),
  'latitude' => 
  array (
    'degrees' => 59,
    'min' => 53,
    'direction' => 'N',
  ),
  'month' => 12,
  'day' => 31,
  'year' => 1965,
  'hour' => 0,
  'min' => 30,
  'report_name' => 'Case study 9',
  'city' => 'Leningrad',
  'country' => 'RU',
  'am_pm' => 'am',
  'sex' => 'female',
  'has_all_info' => true,
);

$birth_data[13] = array (
  'timezone' => 
  array (
    'hours' => 5,
    'min' => 30,
    'direction' => 'E',
  ),
  'longitude' => 
  array (
    'degrees' => 80,
    'min' => 27,
    'direction' => 'E',
  ),
  'latitude' => 
  array (
    'degrees' => 16,
    'min' => 18,
    'direction' => 'N',
  ),
  'month' => 5,
  'day' => 16,
  'year' => 1929,
  'hour' => 11,
  'min' => 0,
  'report_name' => 'Case study 19',
  'city' => 'Guntur',
  'country' => 'IN',
  'am_pm' => 'pm',
  'sex' => 'male',
  'has_all_info' => true,
);

$birth_data[14] = array (
  'timezone' => 
  array (
    'hours' => 5,
    'min' => 30,
    'direction' => 'E',
  ),
  'longitude' => 
  array (
    'degrees' => 78,
    'min' => 46,
    'direction' => 'E',
  ),
  'latitude' => 
  array (
    'degrees' => 30,
    'min' => 8,
    'direction' => 'N',
  ),
  'month' => 2,
  'day' => 1,
  'year' => 1974,
  'hour' => 10,
  'min' => 48,
  'report_name' => 'Case study 29',
  'city' => 'Pauri',
  'country' => 'IN',
  'am_pm' => 'pm',
  'sex' => 'male',
  'has_all_info' => true,
);

// No Result -- Year not found
$birth_data[15] = array (
  'timezone' => 
  array (
    'hours' => 5,
    'min' => 30,
    'direction' => 'E',
  ),
  'longitude' => 
  array (
    'degrees' => 79,
    'min' => 31,
    'direction' => 'E',
  ),
  'latitude' => 
  array (
    'degrees' => 26,
    'min' => 49,
    'direction' => 'N',
  ),
  'month' => 1,
  'day' => 27,
  'year' => 1964,
  'hour' => 10,
  'min' => 30,
  'report_name' => 'Case study 39',
  'city' => 'Bidhuna',
  'country' => 'IN',
  'am_pm' => 'pm',
  'sex' => 'female',
  'has_all_info' => true,
);

$birth_data[16] = array (
  'timezone' => 
  array (
    'hours' => 5,
    'min' => 30,
    'direction' => 'E',
  ),
  'longitude' => 
  array (
    'degrees' => 76,
    'min' => 46,
    'direction' => 'E',
  ),
  'latitude' => 
  array (
    'degrees' => 30,
    'min' => 22,
    'direction' => 'N',
  ),
  'month' => 8,
  'day' => 6,
  'year' => 1970,
  'hour' => 3,
  'min' => 0,
  'report_name' => 'Case study 49',
  'city' => 'Ambala',
  'country' => 'IN',
  'am_pm' => 'pm',
  'sex' => 'female',
  'has_all_info' => true,
);

$birth_data[17] = array (
  'timezone' => 
  array (
    'hours' => 5,
    'min' => 30,
    'direction' => 'E',
  ),
  'longitude' => 
  array (
    'degrees' => 88,
    'min' => 22,
    'direction' => 'E',
  ),
  'latitude' => 
  array (
    'degrees' => 22,
    'min' => 34,
    'direction' => 'N',
  ),
  'month' => 1,
  'day' => 11,
  'year' => 1972,
  'hour' => 7,
  'min' => 16,
  'report_name' => 'Case study 40',
  'city' => 'Kolkata',
  'country' => 'IN',
  'am_pm' => 'pm',
  'sex' => 'female',
  'has_all_info' => true,
);

$birth_data[18] = array (
  'timezone' => 
  array (
    'hours' => 5,
    'min' => 30,
    'direction' => 'E',
  ),
  'longitude' => 
  array (
    'degrees' => 74,
    'min' => 20,
    'direction' => 'E',
  ),
  'latitude' => 
  array (
    'degrees' => 31,
    'min' => 32,
    'direction' => 'N',
  ),
  'month' => 9,
  'day' => 28,
  'year' => 1941,
  'hour' => 6,
  'min' => 7,
  'report_name' => 'Case study 32',
  'city' => 'lahore',
  'country' => 'PK',
  'am_pm' => 'am',
  'sex' => 'male',
  'has_all_info' => true,
);

$birth_data[19] = array (
  'timezone' => 
  array (
    'hours' => 5,
    'min' => 30,
    'direction' => 'E',
  ),
  'longitude' => 
  array (
    'degrees' => 77,
    'min' => 12,
    'direction' => 'E',
  ),
  'latitude' => 
  array (
    'degrees' => 28,
    'min' => 36,
    'direction' => 'N',
  ),
  'month' => 2,
  'day' => 5,
  'year' => 1954,
  'hour' => 4,
  'min' => 45,
  'report_name' => 'Case study 15',
  'city' => 'New Delhi',
  'country' => 'IN',
  'am_pm' => 'am',
  'sex' => 'male',
  'has_all_info' => true,
);

$birth_data[20] = array (
  'timezone' => 
  array (
    'hours' => 5,
    'min' => 30,
    'direction' => 'E',
  ),
  'longitude' => 
  array (
    'degrees' => 77,
    'min' => 12,
    'direction' => 'E',
  ),
  'latitude' => 
  array (
    'degrees' => 28,
    'min' => 36,
    'direction' => 'N',
  ),
  'month' => 1,
  'day' => 22,
  'year' => 1975,
  'hour' => 5,
  'min' => 25,
  'report_name' => 'Case study 35',
  'city' => 'New Delhi',
  'country' => 'IN',
  'am_pm' => 'am',
  'sex' => 'female',
  'has_all_info' => true,
);
$bd = $birth_data[0];
		$aa = new AstroReport( $bd );

		$houses = $aa->getHouses();
		$planets = $aa->getPlanets();

//var_dump( $houses );

$dcm = new DivisionalChartMaker( $houses, $planets );
/*
for( $i = 1; $i < 13; $i++ )
{
	$chart = $dcm->CreateDivisionalChart( $i );

	$h = $dcm->_chart_houses;

	if( $i == 3 )
		var_dump( $h );

	$maker1 = new NorthernChartMaker( $h );
	$maker1->saveChart('D' . $i . '.png','../testDC/');
}*/


$i = 1;
	$chart = $dcm->CreateDivisionalChart( $i );

	$h = $dcm->_chart_houses;

	$maker1 = new NorthernChartMaker( $h );
	$maker1->saveChart('D' . $i . '.png','../testDC/');

?>