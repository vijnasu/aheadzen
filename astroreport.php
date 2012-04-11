<?php  
class AstroReport
{
	private $_birthLocation = array();
	private $_birthTime = array();
	private $_birthDay = array();
	private $_timezone;
	private $_LST = array();
	private $_houses = array();
	private $_jd2000;
	private $_jd;
	private $_d;
	private $_house = array();
	private $_planet = array();
	private $_aspect = array();
	private $objPlanet;
	private $ayanansh;
	private static $INCLINATION = 23.44;
	private static $DEGREETOHOUR = 15;
	public function __construct($birth_data)
	{
		$this->_birthLocation['longitude']['degree'] = $this->decimalDegree($birth_data['longitude']);
		$this->_birthLocation['longitude']['radian'] = deg2rad($this->_birthLocation['longitude']['degree']);
		$this->_birthLocation['longitude']['type'] = $this->birthLocationType( strtoupper( $birth_data['longitude']['direction'] ) );
		$this->_birthLocation['latitude']['degree'] = $this->decimalDegree($birth_data['latitude']);
		$this->_birthLocation['latitude']['radian'] = deg2rad($this->_birthLocation['latitude']['degree']);
		$this->_birthLocation['latitude']['type'] = $this->birthLocationType( strtoupper( $birth_data['latitude']['direction'] ) );

		$tz = $birth_data['timezone']['hours'] + $birth_data['timezone']['min']/60;
		
		$this->_timezone = $this->birthLocationType( strtoupper( $birth_data['timezone']['direction'] ) ) * $tz;
		$this->_birthTime['local']['HH'] = $birth_data['hour'];
		$this->_birthTime['local']['MM'] = $birth_data['min'];
		$this->_birthTime['local']['type'] = strtolower( $birth_data['am_pm'] );
		$this->_birthTime['local']['time24'] = $this->convertTime24($this->_birthTime['local']['HH'], $this->_birthTime['local']['MM'], $this->_birthTime['local']['type']);
		$this->convertTimeGMT();
		
		$this->_birthDay['DD'] = $birth_data['day'];
		$this->_birthDay['MM'] = $birth_data['month'];
		$this->_birthDay['YYYY'] = $birth_data['year'];
		
		$this->_jd = GregorianToJD($this->_birthDay['MM'], $this->_birthDay['DD'], $this->_birthDay['YYYY']) - 0.5;
		$this->_jd2000 = GregorianToJD(1, 1, 2000) - 0.5;
		$this->_d += $this->_jd - $this->_jd2000 + $this->_birthTime['GMT']['time24']/24 + 1;  //+1 is neccesary to make this code work accurately.
	
		$this->_planet = array( 'Sun' => array(),
								'Moon' => array(),
								'Mercury' => array(),
								'Venus' => array(),
								'Mars' => array(),
								'Jupiter' => array(),
								'Saturn' => array(),
								'Uranus' => array(),
								'Neptune' => array(),
								'Pluto' => array(),
								'Rahu' => array(),
								'Ketu' => array()
							 );
		if( !empty( $birth_data['type'] ) && $birth_data['type'] == 'western' )
		{
			$this->ayanansh = 0;
		} else $this->ayanansh = $this->ayanansh($this->_d);

		$this->calcLST();
		$this->getMidHeaven();
		$this->getAscendant();
		$this->housesEQUAL();
		$this->objPlanet = new Planet($this->_d, $this->ayanansh);
		
		$this->assignPlanet();
		//var_dump($this->_planet);
		//var_dump($this->_house);

	}
	private function ayanansh($d)
	{
		$a = 23.85694444 + 2.289558*pow(10, -5)*$d + 3.043487316*pow(10, -7)*$d;
		return deg2rad( $a );
	}
	public function getPlanets()
	{
		return $this->_planet;
	}
	public function getHouses()
	{
		return $this->_house;
	}
	public function getAspects()
	{
		return $this->_aspect;
	}
	private function birthLocationType($type)
	{
		switch($type)
		{
			case 'N':
			case 'E':
				return 1;
				break;
			case 'S':
			case 'W':
				return -1;
				break;
		}
	}
	private	function calcLST()
	{
		$acceleration = $this->getAcceleration()/60;
		$lsign = $this->_birthLocation['longitude']['type'];
		$olong = $this->_birthLocation['longitude']['degree'];
		$dno = $this->_jd - $this->_jd2000;
		$ws  = 282.9404 + 4.70935*pow(10.0, -5)*$dno;
		$ms  = 356.0470 + 0.9856002585*$dno;
		$meanlong = $this->mod2pi($ms + $ws);
		$gmst0 = ($meanlong)/self::$DEGREETOHOUR;
		$temp = $gmst0 + $this->_birthTime['GMT']['time24'];
		$lst = $gmst0 + $this->_birthTime['GMT']['time24'] + $lsign*$olong/self::$DEGREETOHOUR + 12.0655 + $acceleration;  // 12.0655 is a kind of correction, reason not known
		if($this->_birthLocation['latitude']['type'] == -1)
			$lst += 12; // Add 12 houra for Southern Latitudes
		$lst = $this->modDay($lst);

		$lstHH = (int) $lst;
		$lstmm = ($lst - $lstHH)*60;
		$lstMM = (int) $lstmm;
		$lstSS = ($lstmm - $lstMM) * 60;
		settype($lstSS, 'int');
		$this->_LST['HH'] = $lstHH;
		$this->_LST['MM'] = $lstMM;
		$this->_LST['SS'] = $lstSS;
		$this->_LST['RAMC'] = $lst*self::$DEGREETOHOUR;
		$this->_LST['radianRAMC'] = deg2rad($this->_LST['RAMC']);
	}
	private function convertTime24($hour, $min, $type)
	{
		switch($type)
		{
			case 'am':
				return $hour + $min/60;
				break;
			case 'pm':
				return 12 + $hour + $min/60;
				break;
		}
	}
	private function convertTimeGMT()
	{
		$gmt0 = $this->_birthTime['local']['time24'] - $this->_timezone;
		$this->_birthTime['GMT']['time24'] = $this->modDay($gmt0);
	}

	private function decimalDegree($value)
	{
		return $value['degrees'] + $value['min']/60;		
	}
	private	function mod2pi($angle)
	{
		$b = $angle/360.0;
		$a = 360.0*($b - $this->absFloor($b));
		if ($a < 0) $a = 360.0 + $a;
		return $a;
	}
	private	function absFloor($val)
	{
		if ($val >= 0.0) return floor($val);
		else return ceil($val);
	}

	private	function getAcceleration()
	{
		$time = $this->_birthTime['GMT']['time24'];
		return $time*10/60;
	}
	private	function modDay($val)
	{
		$b = $val/24.0;
		if(empty($this->_d))
		{
			if( $b > 1)
				$this->_d = 1;
			else if ($b < 0)
				$this->_d = -1;
		}
		$a = 24.0*($b - $this->absFloor($b));
		if ($a < 0) $a = $a + 24.0;
		return $a;
	}
	private	function getMidHeaven()
	{
		$w = deg2rad(self::$INCLINATION);
		$MC = atan( tan($this->_LST['radianRAMC'])/cos($w) ) - $this->ayanansh;
		$this->assignZodiac($MC, 'MC');
	}
	private	function housesKOCH()
	{
		$e = deg2rad(self::$INCLINATION);
		$MC = deg2rad($this->_house['MC']['fulldegree']);
		$RAMC = $this->_LST['radianRAMC'];
		$latitude = $this->_birthLocation['latitude']['radian'];
		
		$D = asin(sin($MC)*sin($e));
		$OAMC = $RAMC - asin(tan($D)*tan($latitude));
		$DX = (($RAMC + pi()/2) - $OAMC)/3;
		$DX = deg2rad( $this->mod2pi( rad2deg( $DX ) ) );
		
		$H11 = $OAMC + $DX - pi()/2;
		$H12 = $H11 + $DX;
		$H1 = $H12 + $DX;
		$H2 = $H1 + $DX;
		$H3 = $H2 + $DX;

		$C11 = $this->calculateCusps($H11);
		$C12 = $this->calculateCusps($H12);
		$C1 = $this->calculateCusps($H1);
		$C2 = $this->calculateCusps($H2);
		$C3 = $this->calculateCusps($H3);

		$C10 = $MC;
		$C4 = $C10 + pi();
		$C5 = $C11 + pi();
		$C6 = $C12 + pi();
		$C7 = $C1 + pi();
		$C8 = $C2 + pi();
		$C9 = $C3 + pi();
		for($i = 1; $i < 13; $i++)
		{
			$this->assignZodiac(${'C' . $i}, $i, 'true');
		}
	}
	private	function housesEQUAL()
	{
		$ASC = deg2rad($this->_house['ASC']['fulldegree']);

		for($i = 1; $i < 13; $i++)
		{
			$this->assignZodiac($ASC + (($i-1)*pi()/6), $i, 'true');
		}
	}
	private function calculateCusps($angle)
	{
		$e = deg2rad(self::$INCLINATION);
		$latitude = $this->_birthLocation['latitude']['radian'];
		
		$temp = -( (tan($latitude)*sin($e)) + (sin($angle)*cos($e)) )/cos($angle);
		if($temp > 0)
			$out = atan(1/$temp);
		else $out = pi() + atan(1/$temp); // apply proper trigonometric rules
		
		if($angle > pi()/2 && $angle < 3*pi()/2)
			$out += pi();
		return $out;
	}

	private function assignZodiac($radian, $house, $checksouth = null)
	{
		$degree = $this->mod2pi(rad2deg($radian));
	
		$zodiacsign = (int)$degree/30;
		settype($zodiacsign, 'int');
		$this->_house[$house]['degree'] = $degree - $zodiacsign*30;
		$this->_house[$house]['fulldegree'] = $degree;
		
		if( is_null( $checksouth ) )
		{
			if($this->_birthLocation['latitude']['type'] == -1) 		// Reverse the order for Southern Latitudes
			{	if($zodiacsign >= 6)
					$zodiacsign -= 6;
				else if ($zodiacsign < 6)
					$zodiacsign += 6;
			}
		}
		
		switch($zodiacsign)
		{
			case 0:
				$this->_house[$house]['sign'] = 'Aries';
				break;
			case 1:
				$this->_house[$house]['sign'] = 'Taurus';
				break;
			case 2:
				$this->_house[$house]['sign'] = 'Gemini';
				break;
			case 3:
				$this->_house[$house]['sign'] = 'Cancer';
				break;
			case 4:
				$this->_house[$house]['sign'] = 'Leo';
				break;
			case 5:
				$this->_house[$house]['sign'] = 'Virgo';
				break;
			case 6:
				$this->_house[$house]['sign'] = 'Libra';
				break;
			case 7:
				$this->_house[$house]['sign'] = 'Scorpio';
				break;
			case 8:
				$this->_house[$house]['sign'] = 'Sagittarius';
				break;
			case 9:
				$this->_house[$house]['sign'] = 'Capricorn';
				break;
			case 10:
				$this->_house[$house]['sign'] = 'Aquarius';
				break;
			case 11:
				$this->_house[$house]['sign'] = 'Pisces';
				break;
		}
		$this->_house[$house]['sign_number'] = $zodiacsign + 1;
		$this->_house[$house]['Planet'] = array();
	}
	
	private function assignPlanet()
	{
		foreach( $this->_planet as $key => $value)
		{
			$this->_planet[$key]['fulldegree'] = $this->objPlanet->{$key};
			$zodiacsign = (int)$this->_planet[$key]['fulldegree']/30;
			settype($zodiacsign, 'int');
			$this->_planet[$key]['degree'] = $this->_planet[$key]['fulldegree'] - $zodiacsign*30;
			switch($zodiacsign)
			{
				case 0:
					$this->_planet[$key]['sign'] = 'Aries';
					break;
				case 1:
					$this->_planet[$key]['sign'] = 'Taurus';
					break;
				case 2:
					$this->_planet[$key]['sign'] = 'Gemini';
					break;
				case 3:
					$this->_planet[$key]['sign'] = 'Cancer';
					break;
				case 4:
					$this->_planet[$key]['sign'] = 'Leo';
					break;
				case 5:
					$this->_planet[$key]['sign'] = 'Virgo';
					break;
				case 6:
					$this->_planet[$key]['sign'] = 'Libra';
					break;
				case 7:
					$this->_planet[$key]['sign'] = 'Scorpio';
					break;
				case 8:
					$this->_planet[$key]['sign'] = 'Sagittarius';
					break;
				case 9:
					$this->_planet[$key]['sign'] = 'Capricorn';
					break;
				case 10:
					$this->_planet[$key]['sign'] = 'Aquarius';
					break;
				case 11:
					$this->_planet[$key]['sign'] = 'Pisces';
					break;
			}
			$this->_planet[$key]['sign_number'] = $zodiacsign + 1;
			$this->_planet[$key]['house'] = $this->setPlanetsToHouses($key, $zodiacsign + 1);
			$this->_planet[$key]['aspect'] = $this->setPlanetAspect($key, $this->_planet[$key]['house']);
		}
	}
	
	private function setPlanetsToHouses($planet, $zsign)
	 {
	/*	$skip = array('Uranus', 'Neptune', 'Pluto');
		if( in_array( $planet, $skip ) )
			return ;*/

		$house =  $zsign - $this->_house[1]['sign_number'] + 1;
		if( $house < 1 )
		 {
			$house = $house + 12;
		 }
		$this->_house[$house]['Planet'][$planet] = $this->_planet[$planet];
		return $house;
	 }

	private function setPlanetAspect($planet, $house)
	 {
		$skip = array('Rahu', 'Ketu', 'Uranus', 'Neptune', 'Pluto');
		if( in_array( $planet, $skip ) )
			return ;

		$aspect = array();
		$aspect7 = $this->calcPlanetAspect($house, 7);
		$aspect[] = $aspect7;


		switch( $planet )
		{
			case 'Mars':
				$aspectA = $this->calcPlanetAspect($house, 4);
				$aspectB = $this->calcPlanetAspect($house, 8);
				break;
			case 'Jupiter':
				$aspectA = $this->calcPlanetAspect($house, 5);
				$aspectB = $this->calcPlanetAspect($house, 9);
				break;
			case 'Saturn':
				$aspectA = $this->calcPlanetAspect($house, 3);
				$aspectB = $this->calcPlanetAspect($house, 10);
				break;
			default:
				break;
		}
		if( isset( $aspectA ) )
		{
			$aspect[] = $aspectA;
			$this->setAspect($planet, $aspectA);
		
		}
		if( isset( $aspectB ) )
		{
			$aspect[] = $aspectB;
			$this->setAspect($planet, $aspectB);		
		}
		$this->setAspect($planet, $aspect7);		
		sort($aspect);
		return $aspect;
	 }
	private function setAspect($planet, $type)
	{
		if( !empty( $this->_aspect[$type] ) )
		{
			$this->_aspect[$type][] = $planet;
		} else $this->_aspect[$type] = array( $planet );
	}
	private function calcPlanetAspect($house, $type)
	{
		$aspect = $house + $type - 1;
		if( $aspect > 12 )
			$aspect -= 12;

		return $aspect;
	}
	private	function getAscendant()
	{
		$A = $this->_LST['radianRAMC'];
		$ASC = $this->calculateCusps($A) - $this->ayanansh;
		$this->assignZodiac($ASC, 'ASC', 'true');
	}
	private	function geocentricLatitude($lat)
	{
		$lat = deg2rad($lat);
		$newlat = atan(tan($lat) + 9.9970710); // + for N - for South
		return rad2deg($newlat);
	}

} 

?>