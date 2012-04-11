<?php
// Business tier class that manages Today astrological functionality

$path = dirname( __FILE__ );
//include $path . '/Atlas.php';
//include $path . '/AstroData.php';

class Today
{
	private $_Page = array();
	private $_TodaysChartInput = array();
	const SHOWTIME_STRING = "g:i:s A";
	
	public function __construct($current_location)
	{
		if( empty( $current_location ) )
			$current_location = unserialize( Atlas::SetDefaultCity( 5128581 ) );

		$this->_Page['country'] = $current_location['country_name'];
		$this->_Page['city'] = $current_location['name'];
		$this->_Page['placeSearch'] = $current_location;

		date_default_timezone_set( $current_location['timezone']['timezone'] );
		$current_time = time();
		$this->_Page['sun'] = date_sun_info($current_time, $current_location['latitude'], $current_location['longitude']);

		$this->_Page['tomorrow'] = array();
		$this->_Page['tomorrow']['sun'] = date_sun_info($current_time + 86400, $current_location['latitude'], $current_location['longitude']);

		$this->setTodaysChart();
		$this->calculateMoonPhase();
		$this->calculateMoonNakshatra();
		$this->calculateMoonKarana();
		$this->calculateYoga();
		$this->calculateSpecialPeriods();


	}

	public function __get($key)
	{
        if (array_key_exists($key, $this->_Page))
			echo $this->_Page[$key];
    }
	
	public function __set($key, $value)
	{
		$this->_Page[$key] = $value;
    }

	public function showSunrise()
	{
		echo date(Today::SHOWTIME_STRING, $this->_Page['sun']['sunrise'] );
	}
	public function showSunset()
	{
		echo date(Today::SHOWTIME_STRING, $this->_Page['sun']['sunset'] );
	}
	public function showRahuKaal()
	{
		echo date(Today::SHOWTIME_STRING, $this->_Page['special']['rahu']['start'] ) . ' to ' . date(Today::SHOWTIME_STRING, $this->_Page['special']['rahu']['end'] );
	}
	public function showGulikaKaal()
	{
		echo date(Today::SHOWTIME_STRING, $this->_Page['special']['gulika']['start'] ) . ' to ' . date(Today::SHOWTIME_STRING, $this->_Page['special']['gulika']['end'] );
	}
	public function showNakshatra()
	{
		echo $this->_Page['nakshatra']['current']['text'];
	}
	public function showYoga()
	{
		echo $this->_Page['yoga']['current']['text'];
	}
	private function calculateMoonNakshatra()
	{
		$nakshatra_index = floor( $this->_Page['todayPlanet']['Moon']['fulldegree']/AstroData::$NAKSHATRA_SIZE );

		$this->_Page['nakshatra'] = array( 'current' => array() );
		$this->_Page['nakshatra']['current']['text'] =  AstroData::$NAKSHATRA[$nakshatra_index];
		$this->_Page['nakshatra']['current']['index'] =  $nakshatra_index;
	}
	private function calculateSpecialPeriods()
	{
		$this->_Page['special'] = array();
		$duration_of_day = $this->_Page['sun']['sunset'] - $this->_Page['sun']['sunrise'];

		$dow = (int)date( "w", $this->_Page['sun']['sunrise'] );

		$rahu_kaal_start = $this->_Page['sun']['sunrise'] + AstroData::$RAHU_KAAL[$dow]*$duration_of_day;
		$rahu_kaal_end = $rahu_kaal_start + $duration_of_day*0.125;

		$this->_Page['special']['rahu'] = array('start' => $rahu_kaal_start, 'end' => $rahu_kaal_end);

		$gulika_kaal_start = $this->_Page['sun']['sunrise'] + AstroData::$GULIKA_KAAL[$dow]*$duration_of_day;
		$gulika_kaal_end = $gulika_kaal_start + $duration_of_day*0.125;

		$this->_Page['special']['gulika'] = array('start' => $gulika_kaal_start, 'end' => $gulika_kaal_end);


		$yama_ghantaka_start = $this->_Page['sun']['sunrise'] + AstroData::$YAMA_GHANTAKA[$dow]*$duration_of_day;
		$yama_ghantaka_end = $yama_ghantaka_start + $duration_of_day*0.125;

		$durmuhurta = $this->calculateDurMuhurta($duration_of_day, $dow);

//		echo date(Today::SHOWTIME_STRING, $durmuhurta['start'][0] );
	}
	private function calculateDurMuhurta($duration_of_day, $dow)
	{
		$afterSunrise = array( false, false );
		switch($dow)
		{
			case 0:
				$afterSunrise[0] = 10.4/12;
				break;
			case 1:
				$afterSunrise[0] = 6.4/12;
				$afterSunrise[1] = 8.8/12;
				break;
			case 2:
				$afterSunrise[0] = 2.4/12;
//				$afterSunrise[1] = 4.8/12;	FOR NIGHT PERIOD
				break;
			case 3:
				$afterSunrise[0] = 5.6/12;
				break;
			case 4:
				$afterSunrise[0] = 4/12;
				$afterSunrise[1] = 8.8/12;
				break;
			case 5:
				$afterSunrise[0] = 2.4/12;
				$afterSunrise[1] = 6.4/12;
				break;
			case 6:
				$afterSunrise[0] = 1.6/12;
				break;
		}
		$afterSunrise[0] = $afterSunrise[0]*$duration_of_day + $this->_Page['sun']['sunrise'];
		
		if( $afterSunrise[1] )
			$afterSunrise[1] = $afterSunrise[1]*$duration_of_day + $this->_Page['sun']['sunrise'];

		$duration = $duration_of_day*0.8/12;

		$end = $afterSunrise;
		$end[0] += $duration;

		if( $end[1] )
			$end[1] += $duration;

		$durmuhurta = array( 'start' => $afterSunrise, 'end' => $end );

		return $durmuhurta;
		
	}
	private function calculateMoonKarana()
	{
		$karana_index = floor( $this->_Page['moon_phase']['tithi']['exact']*2 );

		$this->_Page['karana'] = array( 0 => array(), 1 => array() );
		$this->_Page['karana'][0]['text'] =  AstroData::$KARANA[$karana_index];
		$this->_Page['karana'][0]['index'] =  $karana_index;
		$this->_Page['karana'][1]['text'] =  AstroData::$KARANA[$karana_index + 1];
		$this->_Page['karana'][1]['index'] =  $karana_index + 1;
	}
	private function calculateYoga()
	{
		$sum = $this->_Page['todayPlanet']['Moon']['fulldegree'] + $this->_Page['todayPlanet']['Sun']['fulldegree'];

		if( $sum >= 360 )
			$sum -= 360;

		$yoga_index = floor( $sum/AstroData::$NAKSHATRA_SIZE );

		$this->_Page['yoga'] = array( 'current' => array() );
		$this->_Page['yoga']['current']['text'] =  AstroData::$YOGA[$yoga_index];
		$this->_Page['yoga']['current']['index'] =  $yoga_index;
	}
	private function calculateMoonPhase()
	{
		if( $this->_Page['todayPlanet']['Moon']['fulldegree'] < $this->_Page['todayPlanet']['Sun']['fulldegree'] )
			$const = 360;
		else $const = 0;

		$diff = $const + $this->_Page['todayPlanet']['Moon']['fulldegree'] - $this->_Page['todayPlanet']['Sun']['fulldegree'];

		$paksha = ($diff - 180 )*100/180;

		$pakshaName = 'Krishna';
		$pakshaNameEnglish = 'Waning';
		$quarter = 'Gibbous';

		if( $paksha < 0 )
		{
			$pakshaName = 'Shukla';
			$pakshaNameEnglish = 'Waxing';
		}

		$tithi_index = $diff/12;
		
		$tithi = round($tithi_index + 1);

		if( $tithi == 0 )
			$tithi = 30;

		$illumination = 100 - abs( round( $paksha ) );

		if( $illumination < 50 )
			$quarter = 'Crescent';


		$this->_Page['moon_phase'] = array( 'illumination' => $illumination, 'paksha' => $pakshaName, 'tithi' => array(), 'phase' => "$pakshaNameEnglish $quarter $illumination%" );
		$this->_Page['moon_phase']['tithi']['id'] = $tithi;
		$this->_Page['moon_phase']['tithi']['text'] = AstroData::$TITHI[$tithi];
		$this->_Page['moon_phase']['tithi']['exact'] = $tithi_index;


//		var_dump( $this->_Page['moon_phase'] );
		
	}
	private function setTodaysChart()
	{
		list($mm, $numday, $yyyy, $fullmonth, $dow, $hours, $minutes, $am_pm) = split('[/.-]', date("m.d.Y.F.w.g.i.a", $this->_Page['sun']['sunrise']));

		$this->_TodaysChartInput['timezone'] = Atlas::getTimeZone( $this->_Page['sun']['sunrise'], $this->_Page['placeSearch']['timezone']['timezone'] );
		$this->_TodaysChartInput['longitude'] = $current_location['longitude'];
		$this->_TodaysChartInput['latitude'] = $current_location['latitude'];

		$this->_TodaysChartInput['day'] = $numday;
		$this->_TodaysChartInput['month'] = $mm;
		$this->_TodaysChartInput['year'] = $yyyy;
		$this->_TodaysChartInput['hour'] = $hours;
		$this->_TodaysChartInput['min'] = $minutes;
		$this->_TodaysChartInput['report_name'] = 'Todays Chart At Sunrise';
		$this->_TodaysChartInput['city'] = $this->_Page['city'];
		$this->_TodaysChartInput['country'] = $this->_Page['country'];
		$this->_TodaysChartInput['am_pm'] = $am_pm;;
		$this->_TodaysChartInput['sex'] = 'female';
		$this->_TodaysChartInput['has_all_info'] = true;

		$todayTransits = new AstroReport( $this->_TodaysChartInput );

		$this->_Page['todayPlanet'] = $todayTransits->getPlanets();

	}
	public function showPlanetLocation( $p )
	{
		$data = $this->_Page['todayPlanet'][$p];
		$degree = (int)$data['degree'];
		$min = (int)(($data['degree'] - $degree) * 60);

		echo $degree . ' ' . $data['sign'] . ' ' . $min;
	}
	public function showMoonPhase()
	{
		echo $this->_Page['moon_phase']['phase'];
	}
	public function showTithi()
	{
		echo $this->_Page['moon_phase']['tithi']['text'];
	}

}?>
