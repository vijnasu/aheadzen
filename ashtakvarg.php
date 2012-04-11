<?php  
 class AshtakVarg
 {
	private $_rules = array();
	private $_planetData = array();
	private $_prastarak = array();
	private $_lagna = array();
	public function __construct($planets, $lagnaNumber)
	{
		$this->createRules();
		$this->_planetData = $planets;
		$this->_lagna = $lagnaNumber;

		foreach( $this->_rules as $planet => $prastarak )
		{
			$this->_prastarak[$planet] = $this->createPrastarak($planet);
		}

		$sarvashtakvarg = new Prastarak();
		foreach( $this->_prastarak as $planet => $table )
		{	
			for($i = 1; $i < 13; $i++)
			{
				$subPlanets = $table->getGoodPlanetsBySign($i);
				foreach( $subPlanets as $subPlanet )
				{
					$sarvashtakvarg->addPoint($i, $subPlanet, true);
				}
			}
		}
		$this->_prastarak['Sarvashtakvarg'] = $sarvashtakvarg;

		/*foreach($this->_prastarak as $p => $k)
		{
			echo $p . '<br />';
			var_dump( $k );
		}*/
	}
	private function createRules()
	{
		$this->_rules = array( 'Sun' => array(),
								'Moon' => array(),
								'Mercury' => array(),
								'Venus' => array(),
								'Mars' => array(),
								'Jupiter' => array(),
								'Saturn' => array(),
							 );

		$this->_rules['Sun']['Sun'] = array(1,2,4,7,8,9,10,11);
		$this->_rules['Sun']['Moon'] = array(3,6,10,11);
		$this->_rules['Sun']['Mars'] = array(1,2,4,7,8,9,10,11);
		$this->_rules['Sun']['Mercury'] = array(3,5,6,9,10,11,12);
		$this->_rules['Sun']['Jupiter'] = array(5,6,9,11);
		$this->_rules['Sun']['Venus'] = array(6,7,12);
		$this->_rules['Sun']['Saturn'] = array(1,2,4,7,8,9,10,11);
		$this->_rules['Sun']['Lagna'] = array(3,4,6,10,11,12);

		$this->_rules['Moon']['Sun'] = array(3,6,7,8,10,11);
		$this->_rules['Moon']['Moon'] = array(1,3,6,7,10,11);
		$this->_rules['Moon']['Mars'] = array(2,3,5,6,9,10,11);
		$this->_rules['Moon']['Mercury'] = array(1,3,4,5,7,8,10,11);
		$this->_rules['Moon']['Jupiter'] = array(1,4,7,8,10,11,12);
		$this->_rules['Moon']['Venus'] = array(3,4,5,7,9,10,11);
		$this->_rules['Moon']['Saturn'] = array(3,5,6,11);
		$this->_rules['Moon']['Lagna'] = array(3,6,10,11);

		$this->_rules['Mars']['Sun'] = array(3,5,6,10,11);
		$this->_rules['Mars']['Moon'] = array(3,6,11);
		$this->_rules['Mars']['Mars'] = array(1,2,4,7,8,10,11);
		$this->_rules['Mars']['Mercury'] = array(3,5,6,11);
		$this->_rules['Mars']['Jupiter'] = array(6,10,11,12);
		$this->_rules['Mars']['Venus'] = array(6,8,11,12);
		$this->_rules['Mars']['Saturn'] = array(1,4,7,8,9,10,11);
		$this->_rules['Mars']['Lagna'] = array(1,3,6,10,11);

		$this->_rules['Mercury']['Sun'] = array(5,6,9,11,12);
		$this->_rules['Mercury']['Moon'] = array(2,4,6,8,10,11);
		$this->_rules['Mercury']['Mars'] = array(1,2,4,7,8,9,10,11);
		$this->_rules['Mercury']['Mercury'] = array(1,3,5,6,9,10,11,12);
		$this->_rules['Mercury']['Jupiter'] = array(6,8,11,12);
		$this->_rules['Mercury']['Venus'] = array(1,2,3,4,5,8,9,11);
		$this->_rules['Mercury']['Saturn'] = array(1,2,4,7,8,9,10,11);
		$this->_rules['Mercury']['Lagna'] = array(1,2,4,6,8,10,11);

		$this->_rules['Jupiter']['Sun'] = array(1,2,3,4,7,8,9,10,11);
		$this->_rules['Jupiter']['Moon'] = array(2,5,7,9,11);
		$this->_rules['Jupiter']['Mars'] = array(1,2,4,7,8,10,11);
		$this->_rules['Jupiter']['Mercury'] = array(1,2,4,5,6,9,10,11);
		$this->_rules['Jupiter']['Jupiter'] = array(1,2,3,4,7,8,10,11);
		$this->_rules['Jupiter']['Venus'] = array(2,5,6,9,10,11);
		$this->_rules['Jupiter']['Saturn'] = array(3,5,6,12);
		$this->_rules['Jupiter']['Lagna'] = array(1,2,4,5,6,7,9,10,11);

		$this->_rules['Venus']['Sun'] = array(8,11,12);
		$this->_rules['Venus']['Moon'] = array(1,2,3,4,5,8,9,11,12);
		$this->_rules['Venus']['Mars'] = array(3,5,6,9,11,12);
		$this->_rules['Venus']['Mercury'] = array(3,5,6,9,11);
		$this->_rules['Venus']['Jupiter'] = array(5,8,9,10,11);
		$this->_rules['Venus']['Venus'] = array(1,2,3,4,5,8,9,10,11);
		$this->_rules['Venus']['Saturn'] = array(3,4,5,8,9,10,11);
		$this->_rules['Venus']['Lagna'] = array(1,2,3,4,5,8,9,11);

		$this->_rules['Saturn']['Sun'] = array(1,2,4,7,8,10,11);
		$this->_rules['Saturn']['Moon'] = array(3,6,11);
		$this->_rules['Saturn']['Mars'] = array(3,5,6,10,11,12);
		$this->_rules['Saturn']['Mercury'] = array(6,8,9,10,11,12);
		$this->_rules['Saturn']['Jupiter'] = array(5,6,11,12);
		$this->_rules['Saturn']['Venus'] = array(6,11,12);
		$this->_rules['Saturn']['Saturn'] = array(3,5,6,11);
		$this->_rules['Saturn']['Lagna'] = array(1,3,4,6,10,11);	
	}
	public function getHouseRating($houses = array())
	 {
		$rating = array('Demands Attention', 'Normal', 'Good');
		for($i = 1; $i < 13; $i++)
		{
			$points = $this->_prastarak['Sarvashtakvarg']->getGoodPointsBySign( $houses[$i]['sign_number'] );
			if( $points <= 25 )
			{
				$ratingNum = 0;
			}
			else if( $points >= 30 )
			{
				$ratingNum = 2;
			}
			else $ratingNum = 1;
			$houses[$i]['Points'] = array($points, $rating[$ratingNum], $ratingNum);

			if( !empty( $houses[$i]['Planet'] ) )
			{
				foreach( $houses[$i]['Planet'] as $planet => $dump )
				{
					$planetPoints = $this->_prastarak['Sarvashtakvarg']->getGoodPointsByPlanetAndSign( $houses[$i]['sign_number'], $planet );
					if( $planetPoints <= 1 )
					{
						$planetRatingNum = 0;
					}
					else if( $planetPoints >= 4 )
					{
						$planetRatingNum = 2;
					}
					else $planetRatingNum = 1;
					$houses[$i]['Planet'][$planet]['Points'] = array( $planetPoints, $rating[$planetRatingNum], $planetRatingNum );
				}
			}
		}
			return $houses;
	 }
	public function getPrastarak($planet, $sign)
	 {
		return $this->_prastarak[$planet]->getGoodPlanetsBySign( $sign );
	 }
	public function getParticularPoint($planet, $sign, $kaksha)
	 {
		return $this->_prastarak[$planet]->getGoodPointsByPlanetAndSign( $sign, $kaksha );
	 }
	private function createPrastarak($planet)
	 {
		$table = new Prastarak();
		foreach( $this->_rules[$planet] as $sub_planet => $rule )
		{
			if( $sub_planet == 'Lagna' )
				$zsign = $this->_lagna;
			else $zsign = $this->_planetData[$sub_planet]['sign_number'];

			foreach($rule as $house)
			{
				$zsign_number = $house + $zsign -1;
				if( $zsign_number > 12 )
					$zsign_number -= 12;
				$table->addPoint($zsign_number, $sub_planet);
			}
		}
		return $table;
	 }
 }
 
 class Prastarak
 {
	private $_table = array();
	public static $signByNumber = array(1 => 'aries', 'taurus', 'gemini', 'cancer', 'leo', 'virgo', 'libra', 'scorpio', 'sagittarius', 'capricorn', 'aquarius', 'pisces');
	public static $planets = array( 'Sun' => 0,
								'Moon' => 0,
								'Mercury' => 0,
								'Venus' => 0,
								'Mars' => 0,
								'Jupiter' => 0,
								'Saturn' => 0,
								'Lagna' => 0
								);
	public function __construct()
	{
		$this->_table = array( 'Aries' => self::$planets,
								'Taurus' => self::$planets,
								'Gemini' => self::$planets,
								'Cancer' => self::$planets,
								'Leo' => self::$planets,
								'Virgo' => self::$planets,
								'Libra' => self::$planets,
								'Scorpio' => self::$planets,
								'Sagittarius' => self::$planets,
								'Capricorn' => self::$planets,
								'Aquarius' => self::$planets,
								'Pisces' => self::$planets
							 );

	}
	public function addPoint($zsign_number, $planet, $multi = false)
	 {
		$zsign = ucfirst( self::$signByNumber[$zsign_number] );
		if( $multi )
			$this->_table[$zsign][$planet] += 1;
		else $this->_table[$zsign][$planet] = 1;
	 }
	
	public function getGoodPlanetsBySign($zsign_number)
	 {
		$zsign = ucfirst( self::$signByNumber[$zsign_number] );
		$contribs = array();
		foreach($this->_table[$zsign] as $planet => $point)
		 {
			if( $point )
				$contribs[] = $planet;
		 }
		 return $contribs;
	 }
	public function getGoodPointsBySign($zsign_number)
	 {
		$zsign = ucfirst( self::$signByNumber[$zsign_number] );
		$total = 0;
		foreach($this->_table[$zsign] as $planet => $point)
		 {
			if( $point )
				$total += $point;
		 }
		 return $total;
	 }
	public function getGoodPointsByPlanetAndSign($zsign_number, $planet)
	 {
		$zsign = ucfirst( self::$signByNumber[$zsign_number] );
		return $this->_table[$zsign][$planet];
	 }
 }

?>