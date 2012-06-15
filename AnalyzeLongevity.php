<?php
// Business tier class that analyzes a birth chart for Longevity

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

    if (substr($start_day_number, -1) != 0) {
      $start_day_number = substr_replace($start_day_number + 10, 0, -1);
    }
    // Calculate transits    
    foreach (range($first_year, $first_year + 70) as $y) {
      $years[$y] = array();
      $years[$y]['transit'] = array();
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
	foreach ($slow_movers as $sm) {
	  $current_planet = $current_planets[$sm]['fulldegree'];
	  $current_asc_house = $this->inHouseRelativeTo($birth_asc, $current_planet);
	  $current_sun_house = $this->inHouseRelativeTo($birth_sun, $current_planet);
	  $current_moon_house = $this->inHouseRelativeTo($birth_moon, $current_planet);
	  $current_asc_transit = $sm . " transits " .
	    $this->ordinal_suffix($current_asc_house) . " house from ASC";
	  $current_sun_transit = $sm . " transits " .
	    $this->ordinal_suffix($current_sun_house) . " house from Sun";
	  $current_moon_transit = $sm . " transits " .
	    $this->ordinal_suffix($current_moon_house) . " house from Moon";
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