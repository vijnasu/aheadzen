<?php

define( 'daysInAnYear', 365.242199 );

$nakshatra = array();

$nakshatra[] = array( 'Ashwini', 'Ketu' );
$nakshatra[] = array( 'Bharani', 'Venus' );
$nakshatra[] = array( 'Kritika', 'Sun' );
$nakshatra[] = array( 'Rohini', 'Moon' );
$nakshatra[] = array( 'Mrigsira', 'Mars' );
$nakshatra[] = array( 'Ardra', 'Rahu' );
$nakshatra[] = array( 'Punarvasu', 'Jupiter' );
$nakshatra[] = array( 'Pushyami', 'Saturn' );
$nakshatra[] = array( 'Ashlesha', 'Mercury' );

$nakshatra[] = array( 'Magha', 'Ketu' );
$nakshatra[] = array( 'Poorvaphalguni', 'Venus' );
$nakshatra[] = array( 'Uttaraphalguni', 'Sun' );
$nakshatra[] = array( 'Hasta', 'Moon' );
$nakshatra[] = array( 'Chitra', 'Mars' );
$nakshatra[] = array( 'Swati', 'Rahu' );
$nakshatra[] = array( 'Vishakha', 'Jupiter' );
$nakshatra[] = array( 'Anuradha', 'Saturn' );
$nakshatra[] = array( 'Jyeshta', 'Mercury' );

$nakshatra[] = array( 'Moola', 'Ketu' );
$nakshatra[] = array( 'Poorvashadha', 'Venus' );
$nakshatra[] = array( 'Uttarashadha', 'Sun' );
$nakshatra[] = array( 'Sravana', 'Moon' );
$nakshatra[] = array( 'Dhanishta', 'Mars' );
$nakshatra[] = array( 'Satabhisha', 'Rahu' );
$nakshatra[] = array( 'Poorvabhadrapada', 'Jupiter' );
$nakshatra[] = array( 'Uttarabhadrapada', 'Saturn' );
$nakshatra[] = array( 'Revati', 'Mercury' );

class VimshottariDasha
 {
	private static $_vimshottariYears;
	private static $_dashaSequence;
	private $_dashaStartDate;
	private $_firstDashaLord;

	private static function setupDasha()
	 {
		if( !isset(self::$_vimshottariYears) )
		{
			self::$_vimshottariYears = array();
			self::$_vimshottariYears['Ketu'] = 7;
			self::$_vimshottariYears['Venus'] = 20;
			self::$_vimshottariYears['Sun'] = 6;
			self::$_vimshottariYears['Moon'] = 10;
			self::$_vimshottariYears['Mars'] = 7;
			self::$_vimshottariYears['Rahu'] = 18;
			self::$_vimshottariYears['Jupiter'] = 16;
			self::$_vimshottariYears['Saturn'] = 19;
			self::$_vimshottariYears['Mercury'] = 17;
			self::$_vimshottariYears = self::$_vimshottariYears;
		}
		if( !isset(self::$_dashaSequence) )
			self::$_dashaSequence = array_keys( self::$_vimshottariYears );
	 }

	public function __construct($moonFullDegree, $birthDateTS)
	{
		$this->setupDasha();
		global $nakshatra;
		$eachNakshatraDegree = 360/27;

		$moonNakshatraPos = $moonFullDegree/$eachNakshatraDegree;
		$moonNakshatra = (int)$moonNakshatraPos;

		$this->_firstDashaLord = $nakshatra[ $moonNakshatra ][1];
		$firstDashaPeriod = self::$_vimshottariYears[$this->_firstDashaLord] * (ceil($moonNakshatraPos) - $moonNakshatraPos );
		$birthDate = $birthDateTS;
		$this->_dashaStartDate = $this->getEndDate($firstDashaPeriod, $birthDate) - self::$_vimshottariYears[$this->_firstDashaLord] * daysInAnYear * 86400;

		//echo date( 'r', $dashaStartDate );

		//echo date( 'r', $this->getEndDate($firstDashaPeriod, $birthDate) );

		//$now = mktime(15, 30, 00, 2, 4, 2010);

	}

	public function getDashaLord( $dateTS, $type )
	 {
		$now = $dateTS;

		$currentDasha = array();

		$level = 1;
		$majorPeriod = 120;
		$startTS = $this->_dashaStartDate;
		$dashaLord = $this->_firstDashaLord;

		while( $level < 6 )
		{
			$period = $this->getPeriod($majorPeriod, $dashaLord, $level);
			
			while( !$this->isDateBetweenDasha($now, $period, $startTS, $level) )
			{
				if( $level <= 2 )
				{
					$startTS += $period*86400*daysInAnYear;
				} else
				{
					$startTS += $period*86400;		
				}

				$dashaLord = $this->nextDasha( $dashaLord );
				$period = $this->getPeriod($majorPeriod, $dashaLord, $level);
			}
			$majorPeriod = $period;
			$currentDasha[$level] = array( 'dashaLord' => $dashaLord, 'startDate' => date( 'r', $startTS ), 'endDate' => date( 'r', $this->getEndDate($period, $startTS, $level) ) );
			$level++;
		}

		return $currentDasha[$type]['dashaLord'];

	 }

	private function getEndDate($period, $startTS, $level = 1)
	{
		list($month, $numday, $year, $hour, $minute, $second) = explode('.', date("m.d.Y.G.i.s", $startTS));

		if( $level <= 2 )
		{
			$dashaYears = (int)$period;
			$dashaDays = $this->getFractionPart( $period ) * daysInAnYear;
			$dashaSeconds = (int)( $this->getFractionPart( $dashaDays ) * 86400 );
		} else
		{
			$dashaYears = 0;
			$dashaDays = (int)$period;
			$dashaSeconds = (int)( $this->getFractionPart( $period ) * 86400 );
		}

		return mktime($hour, $minute, $second + $dashaSeconds , $month, $numday + $dashaDays, $year + $dashaYears);
	}

	private function getFractionPart($float)
	{
		return $float - (int)$float;
	}

	private function nextDasha($p)
	{
		$key = array_search( $p, self::$_dashaSequence ) + 1;
		if( $key > 8 )
			$key -= 9;
		return self::$_dashaSequence[$key];
	}

	private function getPeriod($currentPeriod, $dashaLord, $level = 1)
	{
		if( $level != 3 )
		{
			return $currentPeriod * self::$_vimshottariYears[$dashaLord]/120;
		} else return $currentPeriod * self::$_vimshottariYears[$dashaLord]*daysInAnYear/120;
	}

	private function isDateBetweenDasha($dateTS, $period, $startTS, $level = 1)
	{
		$diff = $dateTS - $startTS;
		if( $level <= 2 )
		{
			$period = $period * daysInAnYear * 86400;
		} else $period = $period * 86400;
		
		return ( $diff <= $period);
	}
 }
?>