<?php
// Business tier class that analyzes a birth chart for Longevity

require_once 'ashtakvarg.php';

class AnalyzeLongevity {
  private $_birth_data;
  private $_chart;

  public function __construct($birth_data) {
    $this->_birth_data = $birth_data;
    $this->_chart = new AstroReport($birth_data);
  }

  public function calculateDifficultPeriods() {
    $years = array();
    $first_year = $this->_birth_data['year'];
    $birth_date = $this->_dateTimeFromBirthData($this->_birth_data);
    $birth_ts = $birth_date->format('U');
    $start_day_number = $birth_date->format('z');
    $difficult_houses = array(4, 6, 8, 12);
    $slow_movers = array('Saturn', 'Jupiter', 'Rahu', 'Ketu');
    $birth_planets = $this->_chart->getPlanets();
    $birth_houses = $this->_chart->getHouses();
    $birth_sun = $birth_planets['Sun']['fulldegree'];
    $birth_moon = $birth_planets['Moon']['fulldegree'];
    $birth_asc = $birth_houses['ASC']['fulldegree'];
    $janma_nakshatra_index = $this->_nakshatra28FromDegree($birth_moon);
    
    $kota_stambha = array($janma_nakshatra_index + 3, $janma_nakshatra_index + 10,
			  $janma_nakshatra_index + 17, $janma_nakshatra_index + 24);
    $kota_entrances = array($janma_nakshatra_index, $janma_nakshatra_index + 1,
			    $janma_nakshatra_index + 2, $janma_nakshatra_index + 7,
			    $janma_nakshatra_index + 8, $janma_nakshatra_index + 9,
			    $janma_nakshatra_index + 14, $janma_nakshatra_index + 15,
			    $janma_nakshatra_index + 16, $janma_nakshatra_index + 21,
			    $janma_nakshatra_index + 22, $janma_nakshatra_index + 23);
    $kota_exits = array($janma_nakshatra_index + 6, $janma_nakshatra_index + 5,
			$janma_nakshatra_index + 4, $janma_nakshatra_index + 13,
			$janma_nakshatra_index + 12, $janma_nakshatra_index + 11,
			$janma_nakshatra_index + 20, $janma_nakshatra_index + 19,
			$janma_nakshatra_index + 18, $janma_nakshatra_index + 27,
			$janma_nakshatra_index + 26, $janma_nakshatra_index + 25);

    if (substr($start_day_number, -1) != 0) {
      $start_day_number = substr_replace($start_day_number + 10, 0, -1);
    }

    // Calculate Ashtakvarga
    $av = new AshtakVarg($birth_planets, $birth_houses['ASC']['sign_number']);
    $av_houses = $av->getHouseRating($birth_houses);
    $tough_av_houses = array();
    for ($i = 1; $i <= 12; $i++)
      if ($av_houses[$i]['Points'][0] < 25)
	$tough_av_houses[$i] = $av_houses[$i]['Points'][0];

    // Calculate transits    
    foreach (range($first_year, $first_year + 70) as $y) {
      $years[$y] = array();
      $years[$y]['kota_stambha_transit'] = array();
      $years[$y]['kota_entrance_exit_transit'] = array();
      $years[$y]['transit'] = array();
      $years[$y]['Ashtakvarga'] = array();
      $start = 0;
      // skip dates before birth
      if ($y == $first_year && $start < $start_day_number) {
	$start = $start_day_number;
      }
      foreach (range($start, 360, 10) as $day) {
	$date = $this->_dateFromDayOfYear($day, $y);
	$new_data = $this->_birth_data;
	$new_data['year'] = $date->format('Y');
	$new_data['month'] = $date->format('m');
	$new_data['day'] = $date->format('d');
	$current_chart = new AstroReport($new_data);
	$current_planets = $current_chart->getPlanets();
	
	// calculate kota transits
	foreach (AstroData::$BAD_PLANETS as $mal) {
	  $current_planet = $current_planets[$mal]['fulldegree'];
	  $current_nakshatra = $this->_nakshatra28FromDegree($current_planet);
	  $current_ks_transit = $mal . " transits Kota Stambha.";
	  if (!in_array($current_ks_transit, $years[$y]['kota_stambha_transit']) &&
	      in_array($current_nakshatra, $kota_stambha)) {
	    $years[$y]['kota_stambha_transit'][] = $current_ks_transit;
	  }
	  // TODO: handle retrograde
	  $current_ee_transit = $mal . " transits a Kota entrance while a benefic transits an exit.";
	  if (!in_array($current_ee_transit, $years[$y]['kota_entrance_exit_transit']) &&
	      in_array($current_nakshatra, $kota_entrances) &&
	      $this->_beneficTransitsExit($current_planets, $kota_exits)) {
	    $years[$y]['kota_entrance_exit_transit'][] = $current_ee_transit;
	  }
	}

	foreach ($slow_movers as $sm) {
	  $current_planet = $current_planets[$sm]['fulldegree'];
	  $current_asc_house = $this->inHouseRelativeTo($birth_asc, $current_planet);
	  $current_sun_house = $this->inHouseRelativeTo($birth_sun, $current_planet);
	  $current_moon_house = $this->inHouseRelativeTo($birth_moon, $current_planet);
	  // Check Ashtakvarga for Saturn and Jupiter
	  if (($sm == 'Saturn' || $sm == 'Jupiter') &&
	      array_key_exists($current_asc_house, $tough_av_houses)) {
	    $current_asc_av_transit = $sm . " transits " .
	      $this->ordinal_suffix($current_asc_house) . " house, which has " .
	      $tough_av_houses[$current_asc_house] . " points.";
	    if (!in_array($current_asc_av_transit, $years[$y]['Ashtakvarga']))
	      $years[$y]['Ashtakvarga'][] = $current_asc_av_transit;
	  }
	  $current_asc_transit = $sm . " transits " .
	    $this->ordinal_suffix($current_asc_house) . " house from ASC.";
	  $current_sun_transit = $sm . " transits " .
	    $this->ordinal_suffix($current_sun_house) . " house from Sun.";
	  $current_moon_transit = $sm . " transits " .
	    $this->ordinal_suffix($current_moon_house) . " house from Moon.";
	  if (!in_array($current_asc_transit, $years[$y]['transit']) && in_array($current_asc_house, $difficult_houses)) {
	    $years[$y]['transit'][] = $current_asc_transit;
	  }
	  if (!in_array($current_sun_transit, $years[$y]['transit']) && in_array($current_sun_house, $difficult_houses)) {
	    $years[$y]['transit'][] = $current_sun_transit;
	  }
	  if (!in_array($current_moon_transit, $years[$y]['transit']) && in_array($current_moon_house, $difficult_houses)) {
	    $years[$y]['transit'][] = $current_moon_transit;
	  }
	}
      }
    }

    return $years;
  }

  private function _nakshatra28FromDegree($degree) {
    $division = $degree/AstroData::$NAKSHATRA_SIZE;
    $index = floor( $division );
    // adjust index for 28 nakshatras
    // 20 is Uttara Ashadha
    if ($index == 20) {
      // if in last quarter of Uttara Ashadha, set to Abhijit
      if ($division - $index > 0.75)
	$index = 21;
      // 21 was Shravana, now Abhijit
      // Shravana now 22
    } elseif ($index == 21) {
      // if not in first 15th of Shravana, set to new Shravana index
      if ($division - $index > 1.0/15)
	$index = 22;
    } elseif ($index > 21) {
      $index += 1;
    }
    return $index;
  }

  private function _beneficTransitsExit($current_planets, $exits) {
    foreach (AstroData::$GOOD_PLANETS as $ben) {
      $nakshatra = $this->_nakshatra28FromDegree($current_planets[$ben]['fulldegree']);
      if (in_array($nakshatra, $exits))
	return true;
    }
    return false;
  }

  private function _dateFromDayOfYear($day, $year) {
    return DateTime::createFromFormat('z-Y', $day . '-' . $year);
  }

  private function _dateTimeFromBirthData($bd) {
    $min = $bd['min'];
    if ($min < 10)
      $min = 0 . $min;
    return DateTime::createFromFormat('Y-n-j g:i a', $bd['year'] . '-' . $bd['month'] . '-' . $bd['day'] .
				      ' ' . $bd['hour'] . ':' . $min . ' ' . $bd['am_pm']);
  }

  /**
   * function inHouseRelativeTo
   * 
   * Quickly calculates house position of a point from a reference.
   * 
   * @param float $ref Reference fulldegree usually Ascendant, Sun or Moon.
   * @param float $transitPoint Any point fulldegree whose house position is to be determined.
   * @return int House number from Reference.
   */
  private function inHouseRelativeTo( $ref, $transitPoint )  {
    $deltaDegrees = $this->deltaDegrees( $ref, $transitPoint  );
    $deltaHouse = (int)($deltaDegrees/30);
    $deltaHouse += 1;
    return $deltaHouse;
  }

  private function deltaDegrees( $ref, $transitPoint ) {
    $deltaDegrees = $transitPoint - $ref;
    $deltaDegrees = $this->modDegree($deltaDegrees);
    return $deltaDegrees;
  }

  private function modDegree($degree) {
    if( $degree < 0 )
      $degree += 360;
    return $degree;
  }

  private function ordinal_suffix($n) {
    $n_last = $n % 100;
    if (($n_last > 10 && $n_last < 14) || $n == 0){
      return "{$n}th";
    }
    switch(substr($n, -1)) {
    case '1':    return "{$n}st";
    case '2':    return "{$n}nd";
    case '3':    return "{$n}rd";
    default:     return "{$n}th";
    }
  }

}?>