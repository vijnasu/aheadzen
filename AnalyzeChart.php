<?php
// Business tier class that analyzes a birth chart for many things

$path = dirname( __FILE__ );

class AnalyzeChart
{
	private $_Page = array();
	private $_ChartInput;
	private $_ChartInfo = array();
	private $_Aspects = array();
	private $_AspectDetails = array();
	private $_SynastryAspects = array();
	private $_SynastryAspectDetails = array();
	private $_Lordship = array();
	private $_isCombust = array();
	private $_Potency = array();
	private $_SynastryPotency = array();
	const SHOWTIME_STRING = "g:i:s A";
	const DEGREE_STRING = "fulldegree";

	private $_partner_report;
	private $_partner_planets;
	private $_partner_houses;
	public $male_ascendant_name;
	public $male_ascendant_lord;
	public $male_seventh_house;
	public $male_seventh_house_lord;
	public $male_influences;
	public $male_natures_influencing;
	public $male_houses;
	public $male_positionals;
	public $female_ascendant_name;
	public $female_ascendant_lord;
	public $female_seventh_house;
	public $female_seventh_house_lord;
	public $female_influences;
	public $female_natures_influencing;
	public $female_houses;
	public $female_positionals;
	public $relative_positionals;
	public $relative_houses;
	public $female_male_influences;
	public $male_female_influences;

	public function __construct($chart)
	{
		$this->_ChartInput = $chart;
		$this->_ChartInfo['house'] = $this->_ChartInput->getHouses();
		$this->_ChartInfo['planet'] = $this->_ChartInput->getPlanets();
		$planets = $this->_ChartInput->getPlanets();
		$houses = $this->_ChartInput->getHouses();
		$this->setLordship();

		//$this->referenceFrom( $planets['Sun']['fulldegree'] );

		$auspicious_from_asc = $this->getAuspicousPlanets( $houses['ASC']['sign'] );
		$auspicious_from_sun = $this->getAuspicousPlanets( $planets['Sun']['sign'] );
		$auspicious_from_moon = $this->getAuspicousPlanets( $planets['Moon']['sign'] );


		$position = array( 'GOOD' => array(), 'BAD' => array());
		$ascHouseDegree = $this->deltaDegrees( 15, $houses['ASC']['fulldegree'] );

		foreach( AstroData::$GOOD_PLANETS as $good )
		{
			
			if( in_array( $this->inHouseRelativeTo( $ascHouseDegree, $planets[$good]['fulldegree'] ),
			    	      AstroData::$POSITION_GOOD_BAD['GOOD'] ) )
				$position['GOOD'][] = $good;
			else $position['BAD'][] = $good;
		}
		foreach( AstroData::$BAD_PLANETS as $bad )
		{
			if( in_array( $this->inHouseRelativeTo( $ascHouseDegree, $planets[$bad]['fulldegree'] ),
			    	      AstroData::$POSITION_GOOD_BAD['BAD'] ) )
				$position['GOOD'][] = $bad;
			else $position['BAD'][] = $bad;

			if( $bad == 'Rahu' || $bad == 'Ketu' )
			{
				if( in_array( $this->inHouseRelativeTo( $ascHouseDegree,
				    	      $planets[$bad]['fulldegree'] ), AstroData::$POSITION_GOOD_BAD['BAD'] ) )
					$position['GOOD'][] = $bad;
				else $position['BAD'][] = $bad;
			}

		}

		$grandlist = array_merge_recursive( $auspicious_from_asc, $position );
		$good = array_count_values( $grandlist['GOOD'] );
		$bad = array_count_values( $grandlist['BAD'] );
		$killer = array_count_values( $grandlist['KILLER'] );
		$yogakaraka = array_count_values( $grandlist['YOGAKARAKA'] );

		$all_planets = array_merge( AstroData::$GOOD_PLANETS, AstroData::$BAD_PLANETS );

		$true = array();
		$this->referenceFrom( $planets, $houses['ASC'] );
		foreach( $all_planets as $p )
		{
			$true[$p] = $good[$p]*10 - $bad[$p]*10 + $yogakaraka[$p]*10 +
				    $this->calculateExaltationStrength($p, $planets[$p]['fulldegree'] ) +
				    $this->calculateRashiStrength( $p, $planets[$p]['sign'] ) +
				    $this->getAspectScore( $p );
		}
		arsort( $true );
		$this->_Potency = $true;
		//var_dump( $true );

	}
	public function prepareRelationshipReport( $partner_report )
	{
		$this->_partner_report = $partner_report;
		$this->_partner_planets = $this->_partner_report->getPlanets();
		$this->_partner_houses = $this->_partner_report->getHouses();

		$this->male_ascendant_name = $this->_ChartInfo['house'][1]['sign'];
		$this->male_ascendant_lord = AstroData::$ZODIAC_SIGNS_LORD[$this->male_ascendant_name];

		$this->male_influences['ASC'] = $this->calculateInfluences( $this->_ChartInfo['house'][1],
									    $this->_ChartInfo['planet'], 'ASC' );
		$this->male_influences['ASCL'] = $this->calculateInfluences( $this->_ChartInfo['house'][1],
						    			     $this->_ChartInfo['planet'],
									     $this->male_ascendant_lord );

		$this->male_natures_influencing['ASC'] = $this->naturesFromPlanets( $this->male_influences['ASC'] );
		$this->male_natures_influencing['ASCL'] = $this->naturesFromPlanets( $this->male_influences['ASCL'] );

		$house_number = $this->calculateHouseFrom(
					    $this->_ChartInfo['house'][1]['fulldegree'],
					    $this->_ChartInfo['planet'][$this->male_ascendant_lord]['fulldegree'] );
		$this->male_houses['ASCL'] = $this->ordinal( $house_number );
		$this->male_positionals['ASCL'] = $this->positionalFromHouseNumber( $house_number );

		foreach ( array( 'Moon', 'Sun', 'Venus' ) as $planet )
		{
			$house_number = $this->calculateHouseFrom(
				      		    $this->_ChartInfo['house'][1]['fulldegree'],
						    $this->_ChartInfo['planet'][$planet]['fulldegree'] );
			$this->male_houses[$planet] = $this->ordinal( $house_number );
			$this->male_positionals[$planet] = $this->positionalFromHouseNumber( $house_number );

			$this->male_influences[$planet] = $this->calculateInfluences( $this->_ChartInfo['house'][1],
					  			      $this->_ChartInfo['planet'], $planet );
			$this->male_natures_influencing[$planet] = $this->naturesFromPlanets( $this->male_influences[$planet] );
		}

		$this->male_seventh_house = $this->_ChartInfo['house'][7]['sign'];
		$this->male_seventh_house_lord = AstroData::$ZODIAC_SIGNS_LORD[$this->male_seventh_house];
		$this->male_influences[7] = $this->calculateInfluences( $this->_ChartInfo['house'][1],
								   $this->_ChartInfo['planet'], 7 );
		$this->male_influences['7L'] = $this->calculateInfluences( $this->_ChartInfo['house'][1],
					  			      $this->_ChartInfo['planet'],
								      $this->male_seventh_house_lord );
		$this->male_natures_influencing[7] = $this->naturesFromPlanets( $this->male_influences[7] );
		$this->male_natures_influencing['7L'] = $this->naturesFromPlanets( $this->male_influences['7L'] );
		$houses = $this->setupHouses( $this->_ChartInfo['house'][1]['fulldegree'] );
		$house_number = $this->calculateHouseFrom(
					    $houses[7],
					    $this->_ChartInfo['planet'][$this->male_seventh_house_lord]['fulldegree'] );
		$this->male_houses['7L'] = $this->ordinal( $house_number );
		$this->male_positionals['7L'] = $this->positionalFromHouseNumber( $house_number );




		$this->female_ascendant_name = $this->_partner_houses[1]['sign'];
		$this->female_ascendant_lord = AstroData::$ZODIAC_SIGNS_LORD[$this->female_ascendant_name];

		$this->female_influences['ASC'] = $this->calculateInfluences( $this->_partner_houses[1],
									    $this->_partner_planets, 'ASC' );
		$this->female_influences['ASCL'] = $this->calculateInfluences( $this->_partner_houses[1],
						    			     $this->_partner_planets,
									     $this->female_ascendant_lord );

		$this->female_natures_influencing['ASC'] = $this->naturesFromPlanets( $this->female_influences['ASC'] );
		$this->female_natures_influencing['ASCL'] = $this->naturesFromPlanets( $this->female_influences['ASCL'] );

		$house_number = $this->calculateHouseFrom(
					    $this->_partner_houses[1]['fulldegree'],
					    $this->_partner_planets[$this->female_ascendant_lord]['fulldegree'] );
		$this->female_houses['ASCL'] = $this->ordinal( $house_number );
		$this->female_positionals['ASCL'] = $this->positionalFromHouseNumber( $house_number );

		foreach ( array( 'Moon', 'Sun', 'Venus' ) as $planet )
		{
			$house_number = $this->calculateHouseFrom(
				      		    $this->_partner_houses[1]['fulldegree'],
						    $this->_partner_planets[$planet]['fulldegree'] );
			$this->female_houses[$planet] = $this->ordinal( $house_number );
			$this->female_positionals[$planet] = $this->positionalFromHouseNumber( $house_number );

			$this->female_influences[$planet] = $this->calculateInfluences( $this->_partner_houses[1],
					  			      $this->_partner_planets, $planet );
			$this->female_natures_influencing[$planet] = $this->naturesFromPlanets( $this->female_influences[$planet] );
		}

		$this->female_seventh_house = $this->_partner_houses[7]['sign'];
		$this->female_seventh_house_lord = AstroData::$ZODIAC_SIGNS_LORD[$this->female_seventh_house];
		$this->female_influences[7] = $this->calculateInfluences( $this->_partner_houses[1],
								   $this->_partner_planets, 7 );
		$this->female_influences['7L'] = $this->calculateInfluences( $this->_partner_houses[1],
					  			      $this->_partner_planets,
								      $this->female_seventh_house_lord );
		$this->female_natures_influencing[7] = $this->naturesFromPlanets( $this->female_influences[7] );
		$this->female_natures_influencing['7L'] = $this->naturesFromPlanets( $this->female_influences['7L'] );
		$houses = $this->setupHouses( $this->_partner_houses[1]['fulldegree'] );
		$house_number = $this->calculateHouseFrom(
					    $houses[7],
					    $this->_partner_planets[$this->female_seventh_house_lord]['fulldegree'] );
		$this->female_houses['7L'] = $this->ordinal( $house_number );
		$this->female_positionals['7L'] = $this->positionalFromHouseNumber( $house_number );

		$this->relative_houses['Ascendant'] = $this->calculateSignPosition($this->_partner_houses,
						    $this->_ChartInfo['house'][1]['sign'] );
		$this->relative_positionals['Ascendant'] = $this->positionalFromHouseNumber(
							$this->relative_houses['Ascendant'] );

		foreach ( array( 'Moon', 'Sun', 'Venus' ) as $planet )
		{
			$this->relative_houses[$planet] = $this->calculateHouseFrom(
							$this->_ChartInfo['planet'][$planet]['fulldegree'],
				      			$this->_partner_planets[$planet]['fulldegree'] );
			$this->relative_positionals[$planet] = $this->positionalFromHouseNumber(
							    $this->relative_houses[$planet] );
		}


		$this->male_female_influences['Ascendant'] = $this->calculatePartnerInfluences(
						           $this->_partner_planets,
						       	   $this->_ChartInfo['house'][1]['fulldegree'],
						       	   'ASC' );

		$this->female_male_influences['Ascendant'] = $this->calculatePartnerInfluences(
						       	   $this->_ChartInfo['planet'],
						       	   $this->_partner_houses[1]['fulldegree'],
						       	   'ASC' );

		foreach ( array( 'Sun', 'Moon', 'Venus' ) as $planet )
		{
			$this->male_female_influences[$planet] = $this->calculatePartnerInfluences(
							       $this->_partner_planets,
							       $this->_ChartInfo['planet'][$planet]['fulldegree'],
							       $planet );

			$this->female_male_influences[$planet] = $this->calculatePartnerInfluences(
							       $this->_ChartInfo['planet'],
							       $this->_partner_planets[$planet]['fulldegree'],
							       $planet );
	     	}
	}
	private function calculateSignPosition( $houses, $sign )
	{
		foreach ( range( 1, 12 ) as $h )
		{
			if ( $houses[$h]['sign'] == $sign )
			   return $h;
		}
		return "Sign not found.";
	}
	private function calculatePartnerInfluences( $partner_planets, $target_fulldegree, $target )
	{
		$this->referenceFromPartner( $partner_planets, $target_fulldegree, $target );
		return $this->extractInfluences( $target );
	}
	private function calculateInfluences( $ascendant, $planets, $target )
	{
		if ( is_int( $target ) )
		   $this->referenceFromHouse( $planets, $ascendant, $target );
		else
		   $this->referenceFrom( $planets, $ascendant );

		return $this->extractInfluences( $target );
	}		
	private function extractInfluences( $target )
	{
		$influences = array();

		foreach ( $this->_AspectDetails[$target] as $name => $detail )
		{
			$target_name = $target;
			if ( $target == 'ASC')
			   $target_name = "Ascendant";
			$influences[$name] = $name . " " . $detail['aspect_type'] . " " . $target_name;
		}
		return $influences;
	}
	private function naturesFromPlanets( $planets ) {
		$natures = array();
		foreach ( $planets as $p => $details ) {
			if ( in_array( $p, AstroData::$GOOD_PLANETS ) )
			   $natures[] = 'Benefic';
			else if ( in_array( $p, AstroData::$BAD_PLANETS ) )
			     $natures[] = 'Malefic';
		}
		if ( count( $natures ) == 0)
		   $natures[] = 'None';
		return array_unique( $natures );
	}
	private function calculateHouseFrom( $reference, $target )
	{
		$houses = $this->setupHouses( $reference );
		foreach ( $houses as $house => $degree ) {
			$delta = $this->deltaDegrees( $target, $degree );
			if ( $delta < 15 || $delta > 345 )
			{
			   return $house;
			}
		}
		return "House Not Found";
	}
	private function ordinal($n)
	{ 
		$test_c = abs($n) % 10;
	        $ext = ((abs($n) % 100 < 21 && abs($n) % 100 > 4) ? 'th'
            	     : (($test_c < 4) ? ($test_c < 3) ? ($test_c < 2) ? ($test_c < 1)
            	     ? 'th' : 'st' : 'nd' : 'rd' : 'th'));
    		return $n.$ext;
	}
	private function positionalFromHouseNumber( $number )
	{
		switch ( $number )
		{
			case 1:
			     return "1-1";
			case 2:
			case 12:
			     return "2-12";
			case 3:
			case 11:
			     return;
			case 4:
			case 10:
			     return "4-10";
			case 5:
			case 9:
			     return;
			case 6:
			case 8:
			     return "6-8";
			case 7:
			     return "7-7";
		}
	}
	private function getAspectScore( $planet )
	{
		$total = 0;
		if( !isset( $this->_Aspects[$planet] ) )
			return $total;

		foreach( $this->_Aspects[$planet] as $type )
		{
			if( $type == 'Sun' )
			{
				if( !empty( $this->_isCombust[$planet] ) )
					$total += $this->_isCombust[$planet] * 5;
			}
			else if( in_array( $type, AstroData::$GOOD_PLANETS ) )
				$total += 5;
			else $total -= 5;
		}
		return $total;
	}
	private function calculateSynastryAspectScore()
	{
		$total = 0;
		if( !isset( $this->_SynastryAspects ) )
			return $total;

		foreach( $this->_SynastryAspects as $base_planet => $type )
		{
			foreach( $type as $partner_planet )
			{
				if( $this->_SynastryAspectDetails[$base_planet][$partner_planet]['aspect_type'] ==
				    'Problems' && in_array( $partner_planet, AstroData::$GOOD_PLANETS ) )
					$total = 0;
				else if( $this->_SynastryAspectDetails[$base_planet][$partner_planet]['aspect_type'] ==
				     	 'Problems' && in_array( $partner_planet, AstroData::$BAD_PLANETS ) )
					$total = 0;
				else if( in_array( $partner_planet, AstroData::$GOOD_PLANETS ) ||
				     	 $partner_planet == 'ASC' ||
					 $this->_SynastryAspectDetails[$base_planet][$partner_planet]['aspect_type'] ==
					 'trine' )
					$total = 5;
				else $total = 0;
				$this->_SynastryAspectDetails[$base_planet][$partner_planet]['score'] = $total;

			}
		}
	}
	public function getAspects( $planet )
	{
		return $this->_AspectDetails[$planet];
	}

	public function getPlanetInfo( $planet )
	{
		$info = array();
		$info['planet'] = $planet;
		$info['longitude'] = $this->getLongitude( $this->_ChartInfo['planet'][$planet] );
		$info['position'] = $this->_ChartInfo['planet'][$planet]['house'];
		$info['lordship'] = $this->getLordship( $planet );
		$info['potency'] = $this->showPlanetPotency( $planet );
		
		return $info;
	}
	private function getLongitude( $data )
	{
		$degree = (int)$data['degree'];
		$min = (int)(($data['degree'] - $degree) * 60);

		return $degree . ' ' . $data['sign'] . ' ' . $min;
	}
	private function getLordship( $planet )
	{
		$lord = array_keys( $this->_Lordship, $planet );
		
		if( $planet == 'Rahu' || $planet == 'Ketu' )
			$lord = array( $this->_ChartInfo['planet'][$planet]['house'] );

		return $lord;
	}
	private function setLordship()
	{
		for( $i = 1; $i < 13; $i++)
		{
			$sign = $this->_ChartInfo['house'][$i]['sign'];
			$this->_Lordship[$i] = AstroData::$ZODIAC_SIGNS_LORD[$sign];
		}
	}
	public function showPlanetPotency( $planet )
	{
		$potential = $this->_Potency[$planet];
		$text = '';
		if( $potential >= 10 && $potential < 20 )
			$text = 'Good and Auspicious';
		else if( $potential <= -10 && $potential > -20)
			$text = 'Poor, Inauspicious and Needs Attention';
		else if( $potential <= -20 )
			$text = 'Very Poor, Inauspicious and Needs Immediate Attention';
		else if( $potential >= 20 )
			$text = 'Excellent and Auspicious';
		else if( $potential > -10 && $potential < 10 )
			$text = 'Normal, Expect Mixed Results';

		return $text;

	}
	public function calculateSynastryPotency()
	{
		$this->_SynastryPotency['LIFE_LONG'] = 0;
		$this->_SynastryPotency['TUNING'] = 0;
		$this->_SynastryPotency['OTHERS'] = 0;

		foreach( $this->_SynastryAspectDetails as $partner_planet => $base_planets )
		{
			
			foreach( $base_planets as $base_planet_name => $base_planet )
			{
				if( $partner_planet == 'ASC' )
				{
					$multiplier = 1;
					if( $base_planet_name == 'Sun' ||
					    $base_planet_name == 'Moon' ||
					    $base_planet_name == 'ASC' )
						$multiplier = 2;

					$this->_SynastryPotency['LIFE_LONG'] += $base_planet['score']*$multiplier;
				} else if( $partner_planet == 'Sun' || $partner_planet == 'Moon' )
				{
					$this->_SynastryPotency['TUNING'] += $base_planet['score'];
				} else $this->_SynastryPotency['OTHERS'] += $base_planet['score'];
			}
		}

	}
	public function showAspectQuality( $planet, $aspectedBy )
	{
		$potential = $this->_Potency[$planet] + $this->_Potency[$aspectedBy];
		$text = '';
		if( $potential >= 10 && $potential < 25 )
			$text = 'Capable';
		else if( $potential <= -10 && $potential > -25)
			$text = 'Capable';
		else if( $potential <= -25 || $potential >= 25 )
			$text = 'Powerful';
		else if( $potential > -10 && $potential < 10 )
			$text = 'Weak and Ineffective';

		return $text;

	}
	// Following function calculates how partner is affected by you
	public function calculateSynastry( $partner_chart )
	{
		$planets = $partner_chart->getPlanets();
		$houses = $partner_chart->getHouses();
		$reverse_aspects = array_merge( AstroData::$REVERSE_DRISHTI,
				   		array ( 'Trines' => array(5,9), 'Problems' => array(6,8,12) ) );

		$planets['ASC'] = $houses['ASC'];

		$SynastryAspects = array();
		$SynastryAspectsDetails = array();
		$all_planets = array_merge( AstroData::$GOOD_PLANETS, AstroData::$BAD_PLANETS, array( 'ASC' ) );

		foreach( $all_planets as $p )
		{
			$reference = $planets[$p]['fulldegree'];
			$pointHouseDegree = $this->deltaDegrees( 15, $reference );
			
			foreach( $all_planets as $pp )
			{
				if( $pp == 'ASC' )
				{
					$planet_name = 'Jupiter';
					$planetInHouse = $this->inHouseRelativeTo( $pointHouseDegree,
						       $this->_ChartInfo['house'][$pp]['fulldegree'] );
				}
				else
				{
					$planet_name = $pp;
					$planetInHouse = $this->inHouseRelativeTo( $pointHouseDegree,
						       $this->_ChartInfo['planet'][$pp]['fulldegree'] );
				}
			
				if( in_array($planetInHouse, $reverse_aspects[$planet_name] ) ||
				    in_array($planetInHouse, $reverse_aspects['Trines'] ) )
				{
					$houseAspectDegree = (12 - ($planetInHouse - 1)) * 30;
					if( !isset( $SynastryAspects[$p] ) )
					{
						$SynastryAspects[$p] = array();
						$SynastryAspectsDetails[$p] = array();
					}

					$aspect_type = AstroData::$ASPECT_NAME[$houseAspectDegree];

					$SynastryAspects[$p][] = $pp;
					$SynastryAspectsDetails[$p][$pp] = array( 'aspect_type' => $aspect_type );
				}
				if( in_array($planetInHouse, $reverse_aspects['Problems'] ) )
				{
					if( !isset( $SynastryAspects[$p] ) )
					{
						$SynastryAspects[$p] = array();
						$SynastryAspectsDetails[$p] = array();
					}
					$SynastryAspects[$p][] = $pp;
					$SynastryAspectsDetails[$p][$pp] = array( 'aspect_type' => 'Problems' );
				}


			}


		}
		$this->_SynastryAspects = $SynastryAspects;
		$this->_SynastryAspectDetails = $SynastryAspectsDetails;
		$this->calculateSynastryAspectScore();
		$this->calculateSynastryPotency();
//		var_dump( $this->_SynastryAspects, $this->_SynastryAspectDetails, $this->_SynastryPotency );
	}
	private function referenceFrom( $planets, $asc )
	{
		$planets['ASC'] = $asc;

		$this->_Aspects = array();
		$all_planets = array_merge( AstroData::$GOOD_PLANETS, AstroData::$BAD_PLANETS, array( 'ASC' ) );

		foreach( $all_planets as $p )
		{
			$reference = $planets[$p]['fulldegree'];

//	Calculate house start fulldegree for a given planet.
			$pointHouseDegree = $this->deltaDegrees( 15, $reference );
			
			foreach( $all_planets as $pp )
			{
				if( $p == $pp || $pp == 'ASC' )
					continue;

// Find relative house position of two points/planets.
// Here we are trying to find position of planet $pp from another planet $p. 
				$planetInHouse = $this->inHouseRelativeTo( $pointHouseDegree, $planets[$pp]['fulldegree']);

// Reverse Drishti is just another way of finding aspects of planets.
// For e.g. if Saturn is located in 4th house or 11th house from Moon, it will cast its aspect on Moon.
				if( in_array($planetInHouse, AstroData::$REVERSE_DRISHTI[$pp] ) )
				{
					$houseAspectDegree = (12 - ($planetInHouse - 1)) * 30;
					if( !isset( $this->_Aspects[$p] ) )
					{
						$this->_Aspects[$p] = array();
						$this->_AspectDetails[$p] = array();
					}

					$aspect_type = AstroData::$ASPECT_NAME[$houseAspectDegree];

					$this->_Aspects[$p][] = $pp;
					$this->_AspectDetails[$p][$pp] = array( 'aspect_type' => $aspect_type );

					if( $pp == 'Sun' )
					{
						if( $aspect_type == AstroData::$ASPECT_NAME[0] )
								$this->_isCombust[$p] = -1;
						else if( $aspect_type == AstroData::$ASPECT_NAME[180] )
								$this->_isCombust[$p] = 1;
						else $this->_isCombust[$p] = 0;
					}
				}

			}


		}
		//$Houses = $this->setupHouses( $pointHouseDegree );
		//var_dump( $this->_isCombust, $this->_AspectDetails );


	
	}
	private function referenceFromHouse( $planets, $asc, $house )
	{
		$planets['ASC'] = $asc;

		$houses = $this->setupHouses( $asc['fulldegree'] );

		$this->_Aspects = array();
		$all_planets = array_merge( AstroData::$GOOD_PLANETS, AstroData::$BAD_PLANETS, array( 'ASC' ) );

		$reference = $houses[$house];

//	Calculate house start fulldegree for a given house.
		$pointHouseDegree = $this->deltaDegrees( 15, $reference );
			
		foreach( $all_planets as $pp )
		{
// Find relative house position of two points/planets.
// Here we are trying to find position of planet $p from house $h
			$planetInHouse = $this->inHouseRelativeTo( $pointHouseDegree, $planets[$pp]['fulldegree']);

// Reverse Drishti is just another way of finding aspects of planets.
// For e.g. if Saturn is located in 4th house or 11th house from Moon, it will cast its aspect on Moon.
			if( in_array($planetInHouse, AstroData::$REVERSE_DRISHTI[$pp] ) )
			{
				$houseAspectDegree = (12 - ($planetInHouse - 1)) * 30;
				if( !isset( $this->_Aspects[$house] ) )
				{
					$this->_Aspects[$house] = array();
					$this->_AspectDetails[$house] = array();
				}

				$aspect_type = AstroData::$ASPECT_NAME[$houseAspectDegree];

				$this->_Aspects[$house][] = $pp;
				$this->_AspectDetails[$house][$pp] = array( 'aspect_type' => $aspect_type );

				if( $pp == 'Sun' )
				{
					if( $aspect_type == AstroData::$ASPECT_NAME[0] )
						$this->_isCombust[$house] = -1;
					else if( $aspect_type == AstroData::$ASPECT_NAME[180] )
						$this->_isCombust[$house] = 1;
					else $this->_isCombust[$house] = 0;
				}
			}
		}	
	}
	private function referenceFromPartner( $planets, $reference, $target )
	{
		$houses = $this->setupHouses( $reference );

		$this->_Aspects = array();
		$all_planets = array_merge( AstroData::$GOOD_PLANETS, AstroData::$BAD_PLANETS, array( 'ASC' ) );

//	Calculate house start fulldegree for a given house.
		$pointHouseDegree = $this->deltaDegrees( 15, $reference );
			
		foreach( $all_planets as $pp )
		{
// Find relative house position of two points/planets.
// Here we are trying to find position of planet $p from house $h
			$planetInHouse = $this->inHouseRelativeTo( $pointHouseDegree, $planets[$pp]['fulldegree']);

// Reverse Drishti is just another way of finding aspects of planets.
// For e.g. if Saturn is located in 4th house or 11th house from Moon, it will cast its aspect on Moon.
			if( in_array($planetInHouse, AstroData::$REVERSE_DRISHTI[$pp] ) )
			{
				$houseAspectDegree = (12 - ($planetInHouse - 1)) * 30;
				if( !isset( $this->_Aspects[$target] ) )
				{
					$this->_Aspects[$target] = array();
					$this->_AspectDetails[$target] = array();
				}

				$aspect_type = AstroData::$ASPECT_NAME[$houseAspectDegree];

				$this->_Aspects[$target][] = $pp;
				$this->_AspectDetails[$target][$pp] = array( 'aspect_type' => $aspect_type );

				if( $pp == 'Sun' )
				{
					if( $aspect_type == AstroData::$ASPECT_NAME[0] )
						$this->_isCombust[$target] = -1;
					else if( $aspect_type == AstroData::$ASPECT_NAME[180] )
						$this->_isCombust[$target] = 1;
					else $this->_isCombust[$target] = 0;
				}
			}
		}	
	}
	private function setupHouses( $reference )
	{
		$house = array();
		for($i = 12; $i > 0; $i--)
		{
			$house[$i] = $this->deltaDegrees( (360 - 30*($i-1)), $reference );
		}
		return $house;
	}
	private function getZodiacSign( $degree )
	{
		$sign_number = floor( $degree/30 );
		return AstroData::$ZODIAC_SIGN_NAME[$sign_number];
	}
	private function calculateExaltationStrength( $planet, $fulldegree )
	{
		$debiliation = $this->modDegree( AstroData::$EXALTATION[$planet] - 180 );

		$step1 = $this->modDegree( $fulldegree - $debiliation );

		if( $step1 > 180 )
			$step1 = 360 - $step1;

		$step2 = $step1/18;

		return $step2;
	}	
	private function calculateRashiStrength( $planet, $sign )
	{
		if( AstroData::$MOOL_TRIKONA[$planet] == $sign )
			return 7;
	}
	private function calculatePlanetaryExchange()
	{
	}
	private function getAuspicousPlanets( $ASC )
	{
		return AstroData::$LAGNA_GOOD_BAD[$ASC];
	}
	private function getZodiacSignLord( $zodiac_sign )
	{
		return AstroData::$ZODIAC_SIGNS_LORD[$zodiac_sign];
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
	private function inHouseRelativeTo( $ref, $transitPoint )
	{
		$deltaDegrees = $this->deltaDegrees( $ref, $transitPoint );
		$deltaHouse = (int)($deltaDegrees/30);
		$deltaHouse += 1;
		return $deltaHouse;
	}

	private function deltaDegrees( $ref, $transitPoint )
	{
		$deltaDegrees = $transitPoint - $ref;
		$deltaDegrees = $this->modDegree($deltaDegrees);
		return $deltaDegrees;
	}

	private function modDegree($degree)
	{
		if( $degree < 0 )
		{
			$degree += 360;
		}
		return $degree;
	}
}?>
