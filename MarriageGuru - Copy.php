<?php

	require_once 'orbit.php';
	require_once 'planet.php';
	require_once 'transit.php';
	require_once 'astroreport.php';
	require_once 'AspectsGenerator.php';

	class MarriageGuru{

		private $_birth_planets;
		private $_birth_houses;
		private $_raashi_lords = array();
		private $_vivah_saham;
		private $_lagna_lord;
		private $_seventh_lord;
		private $_lagna;
		private $_seventh_house;
		private $_gender;
		private $_start_time;
		private $_end_time;
		private $_start_data;
		private $_end_data;
		private $_birth_data;
		private $_result_three = array();
		private $_result_four = array();
		private $_result_five = array();
		private $_result_six = array();
		private $_result_seven = array();
		private $_rules_probability = array();
		private $_navamsh_houses;
		private $_navamsh_planets;
		private $_navamsh_lagna;
		private $_navamsh_lagna_lord;
		private $_navamsh_seventh_lord;
		private $_navamsh_seventh_house;
		private $_piya_milan_count;
		private $_probability = array();

		
		const FULLDEGREE_STRING = "fulldegree";
		const LAGNA_STRING = "ASC";
		const SIGN_STRING = "sign";
		const LEAST_COUNT_COEFF = 60;
		const HOUSE_STRING = "house";
		const PERCENTAGE_ASPECT = 75;

		public function __construct($birth_data,$gender)
		{
			$this->_birth_data = $birth_data;
			$birth_report = new AstroReport( $this->_birth_data );
			$this->_birth_planets = $birth_report->getPlanets();
			$this->_birth_houses = $birth_report->getHouses();
			
			$this->_raashi_lords = array(
				"Aries"=>"Mars",
				"Taurus" => "Venus",
				"Gemini" => "Mercury",
				"Cancer" => "Moon",
				"Leo" => "Sun",
				"Virgo" => "Mercury",
				"Libra" =>"Venus",
				"Scorpio" => "Mars",
				"Sagittarius" => "Jupiter",
				"Capricorn" => "Saturn",
				"Aquarius"=> "Saturn",
				"Pisces" => "Jupiter");

			$this->_gender = $gender;
			$this->_lagna = $this->_birth_houses[MarriageGuru::LAGNA_STRING][MarriageGuru::SIGN_STRING];
			$this->_lagna_lord = $this->_raashi_lords[$this->_lagna];

			$this->_seventh_house = $this->_birth_houses[7][MarriageGuru::SIGN_STRING];
			$this->_seventh_lord = $this->_raashi_lords[$this->_seventh_house];
			$this->_lagna_lord_degree = $this->_birth_planets[$this->_lagna_lord][MarriageGuru::FULLDEGREE_STRING];
			$this->_seventh_lord_degree = $this->_birth_planets[$this->_seventh_lord][MarriageGuru::FULLDEGREE_STRING];
			$vivah_saham = $this->_lagna_lord_degree + $this->_seventh_lord_degree;

			if($vivah_saham > 360)
				$vivah_saham -= 360;

			$this->_vivah_saham = $vivah_saham;

		}

		public function findMarriageDates()
		{
			$thisYear = date("Y");
			$thisYear = (int)$thisYear;
			$startAge = $thisYear - $this->_birth_data['year'] - 1;

			$yearData = $this->findYears();
			$years = $yearData[1];
			$months = array();

			foreach( $years as $y)
			{
				$months = $this->findMonths($y - 1, $y + 1, 7, $months);

			}
			arsort( $months, ksort($months) );

			$yr = array();

			foreach( $months as $m => $dump)
			{
				list($yearValue, $monthValue) = explode(":", $m);

				if( !isset( $yr[$yearValue] ) )
					$yr[$yearValue] = array($m, $dump);
				else
				{
					if( $yr[$yearValue][1] < $dump )
						$yr[$yearValue] = array($m, $dump);
				}

			}

			$output = array();

			foreach( $yearData[0] as $year => $rating )
			{
				list($yearValue, $monthValue) = explode(":", $yr[$year][0]);

				$range = array();
				$range[0] = date( "F, Y", mktime( 0, 0, 0, $monthValue - 2.5, 15, $year ) );
				$range[1] = date( "F, Y", mktime( 0, 0, 0, $monthValue + 2.5, 15, $year ) );

				$output[] = "$range[0] to $range[1]";

			}
			return $output;
		
		}
		
		public function findMonths( $current_age = 15, $end_age = 70, $least_count_days, $output = array() )
		{
			$this->set_duration($current_age,$end_age);	
			$least_count = $least_count_days*AspectsGenerator::DAY_DIFF;
			$time = $this->_start_time;

			$end_time = $this->_end_time;
			$iter = 0;

			while($time <= $this->_end_time)
			{
				$res = array();

				$iter++;

				$date = explode(":",date(AspectsGenerator::DATE_FORMAT,$time));
				

				$data = $this->_start_data;
				
				$data['month'] = intval($date[1]);
				$data['year'] = intval($date[0]);
				$data['day'] = intval($date[2]);
				$data['hour'] = intval($date[3]);
				$data['min'] = intval($date[4]);
				$data['am_pm'] = $date[5];

		
				$report = new AstroReport( $data );
				$time_planets = $report->getPlanets();
				$time_houses = $report->getHouses();
						

				$res['five'] = $this->executeRuleFive($time_planets[$this->_lagna_lord][MarriageGuru::FULLDEGREE_STRING],$time_planets[$this->_seventh_lord][MarriageGuru::FULLDEGREE_STRING]);

				$res['seven'] = $this->executeRuleSeven($time_planets);

	//			$res['eight'] = $this->executeRuleEight();

				$d = date(AspectsGenerator::DATE_FORMAT,$time);

				$m_y = explode(":", $d);
				$year_month = $m_y[0] . ':' . $m_y[1];
				
				$sum = $res['five'] + $res['seven'];

				if( $sum >= 2 )
				{
					if( !isset( $output[$year_month] ) )
						$output[$year_month] = 1;
					else $output[$year_month] += 1;
				}	

				$time += $least_count;

			}
			return $output;

		}
		public function findYears( $current_age = 15, $end_age = 70, $least_count_days = 60 )
		{
			$this->set_duration($current_age,$end_age);	
			$output = array();
			$least_count = $least_count_days*AspectsGenerator::DAY_DIFF;
			$res = array(0,0,0,0,0,0,0,0);
			$output = array();
			$time = $this->_start_time;

			$end_time = $this->_end_time;
			$iter = 0;

			while($time <= $this->_end_time)
			{
				$iter++;

				$date = explode(":",date(AspectsGenerator::DATE_FORMAT,$time));
				

				$data = $this->_start_data;
				
				$data['month'] = intval($date[1]);
				$data['year'] = intval($date[0]);
				$data['day'] = intval($date[2]);
				$data['hour'] = intval($date[3]);
				$data['min'] = intval($date[4]);
				$data['am_pm'] = $date[5];

		
				$report = new AstroReport( $data );
				$time_planets = $report->getPlanets();
				$time_houses = $report->getHouses();
						

	
				$res[2] = $this->executeRuleThree($time_planets["Jupiter"][MarriageGuru::FULLDEGREE_STRING]);

				$res[3] = $this->executeRuleFour($time_planets["Jupiter"],$time_planets["Saturn"]);

				$res[5] = $this->executeRuleSix($time_planets["Jupiter"][MarriageGuru::FULLDEGREE_STRING]);
				$res[6] = $this->executeRuleNine($time_planets);
				$res[7] = $this->executeRuleTen($time_planets);


					$success = 0;
						
					for($i = 0; $i < 9;$i++)
					{
						if($res[$i] == 1)
							$success++;
					}
					
					$count = count($output);

					if($success >= 4)
					{
						$count = count($output);
						$output[$count] = array(date(AspectsGenerator::DATE_FORMAT,$time),$success,$res[0],$res[2],$res[3],$res[4],$res[5],$res[6], $res[7], $res[8]);
					}
					$time += $least_count;

				}
				$years = array();
				
				foreach($output as $o)
				{
					$date = explode(":",$o[0]);
					$y = intval($date[0]);

					if( !isset( $years[$y] ) )
						$years[$y] = 1;
					else $years[$y] += 1;

				}
				arsort( $years );

				$resultCount = count( $years );
				$resultCount = (int)( $resultCount/3 );

				$startCount = 1;

				$statYears = $years;
				foreach($statYears as $y => $frequency)
				{
					$age = $y - $this->_birth_data['year'];
					$p = $this->getProbabilityByAge( $age );
					$statYears[$y] *= $p;
				}

				arsort( $statYears );

				$ageList = array();

				foreach($statYears as $y => $frequency)
				{
					
					$age = $y - $this->_birth_data['year'];
					
					$ageList[] = $age;

					if( $startCount <= $resultCount )
						$startCount++;
					else break;
				}

				$finalOutput = array( $statYears, $ageList );
				return $finalOutput;

		}



		private function set_duration($start_age,$end_age)
		{
			$this->_start_data = $this->_birth_data;
			$this->_end_data = $this->_birth_data;
			$this->_start_data['year'] += $start_age;
			$this->_end_data['year'] += $end_age;

			if($this->_end_data['year'] > 2037)
				$this->_end_data['year'] = 2037;
						
			if(strcmp($this->_start_data['am_pm'],'am') == 0)
				$this->_start_time = mktime($this->_start_data['hour'],$this->_start_data['min'],0,$this->_start_data['month'],$this->_start_data['day'],$this->_start_data['year']);
			else
				$this->_start_time = mktime($this->_start_data['hour']+12,$this->_start_data['min'],0,$this->_start_data['month'],$this->_start_data['day'],$this->_start_data['year']);

			if(strcmp($this->_end_data['am_pm'],'am') == 0)
				$this->_end_time = mktime($this->_end_data['hour'],$this->_end_data['min'],0,$this->_end_data['month'],$this->_end_data['day'],$this->_end_data['year']);
			else
				$this->_end_time = mktime($this->_end_data['hour'],$this->_end_data['min'],0,$this->_end_data['month'],$this->_end_data['day'],$this->_end_data['year']);					
		}

		private function executeRuleOne($time,$time_planets)
			{
			/*	Rule - Connection of Vimshottari Mahadasha, Antardasha lords with ASC, ASC Lord, 7H or 7L.
				Problem - Pruning of time periods has to be extensively applied. This knowledge is not yet available.
			*/
			
			}


		public function executeRuleThree($jupiter_position)
			{
			//	Rule - Transiting Jupiter's aspects on Vivah Saham = ASC fulldegree + 7H fulldegree - 360

				$vivah_saham = $this->_vivah_saham;

				$pointHouseDegree = $this->deltaDegrees( 15, $vivah_saham );

				$tJupiterHouseMoon = $this->inHouseRelativeTo( $pointHouseDegree, $jupiter_position );

				if( in_array($tJupiterHouseMoon, array(1,5,7,9) ) )
					return 1;

				return 0;
			}
		
		public function executeRuleFour($jupiter_data,$saturn_data)
		{
		//	Rule - Transiting Jupiter's and Saturn's aspect on ASC, ASC Lord, 7H and 7L

			$H7Degree = $this->_birth_houses['ASC'][MarriageGuru::FULLDEGREE_STRING] + 180;
			
			if( $H7Degree > 360 )
				$H7Degree -= 360;

			$houseDegree = array();
	
			$houseDegree['LL'] = $this->deltaDegrees( 15, $this->_lagna_lord_degree );
			$houseDegree['L7'] = $this->deltaDegrees( 15, $this->_seventh_lord_degree );
			$houseDegree['ASC'] = $this->deltaDegrees( 15, $this->_birth_houses['ASC'][MarriageGuru::FULLDEGREE_STRING] );
			$houseDegree['H7'] = $this->deltaDegrees( 15, $H7Degree );

			$tJupiterHouse = array();
			$tSaturnHouse = array();
			$resSaturn = 0;
			$resJupiter = 0;

			$jupiter_position = $jupiter_data[MarriageGuru::FULLDEGREE_STRING];
			$saturn_position = $saturn_data[MarriageGuru::FULLDEGREE_STRING];


			foreach( $houseDegree as $type => $degree )
			{
				$tJupiterHouse[$type] = $this->inHouseRelativeTo( $degree, $jupiter_position );
				$tSaturnHouse[$type] = $this->inHouseRelativeTo( $degree, $saturn_position );

				if( in_array($tSaturnHouse[$type], array(1,4,7,11)  ) )
					$resSaturn = 1;

				if( in_array($tJupiterHouse[$type], array(1,5,7,9)  ) )
					$resJupiter = 1;
			}

			if( $resSaturn == 1 && $resJupiter = 1  )
				return 1;

			return 0;

		}



		public function executeRuleFive($lagna_lord_position,$seventh_lord_position)
			{
			//	Rule - ASC Lord and 7L make a connection in transit. Both one way and two way. This can be used to pin point months.

				$res = 0;

				$LL = $this->_lagna_lord;
				$L7 = $this->_seventh_lord;

				$LLAspect = $this->getAspectArray( $LL );
				$L7Aspect = $this->getAspectArray( $L7 );
				
				$tLLHouseDegree = $this->deltaDegrees( 15, $lagna_lord_position );
				$t7LHouseLL = $this->inHouseRelativeTo( $tLLHouseDegree, $seventh_lord_position );

				$t7LHouseDegree = $this->deltaDegrees( 15, $seventh_lord_position );
				$tLLHouse7L = $this->inHouseRelativeTo( $t7LHouseDegree, $lagna_lord_position );
				
				if( in_array($t7LHouseLL, $LLAspect ) )
					$res = 1;
				if( in_array($tLLHouse7L, $L7Aspect ) )
					$res = 1;

				return $res;
			}

		public function executeRuleSix($jupiter_position)
		{
			//	Rule - Transiting Jupiter's aspect on natal Venus in male charts and natal Mars in female charts.

			if( $this->_gender == 'male' )
				$p = 'Venus';
			else $p = 'Mars';

			$planetHouseDegree = $this->deltaDegrees( 15, $this->_birth_planets[$p][MarriageGuru::FULLDEGREE_STRING] );

			$tJupiterHousePlanet = $this->inHouseRelativeTo( $planetHouseDegree, $jupiter_position );

			if( in_array($tJupiterHousePlanet, array(1,5,7,9) ) )
				return 1;

			return 0;

		}

		public function executeRuleSeven($transitPlanets)
		{
			//	Rule - Sun and/or most planets around ASC and 7H
			//  Possibility - May be use only Sun and aspect on LL and 7L could also be taken into account.

			$H7Degree = $this->_birth_houses['ASC'][MarriageGuru::FULLDEGREE_STRING] + 180;
			
			if( $H7Degree > 360 )
				$H7Degree -= 360;

			$houseDegree = array();
	
			$houseDegree['LL'] = $this->deltaDegrees( 15, $this->_lagna_lord_degree );
			$houseDegree['L7'] = $this->deltaDegrees( 15, $this->_seventh_lord_degree );
			$houseDegree['ASC'] = $this->deltaDegrees( 15, $this->_birth_houses['ASC'][MarriageGuru::FULLDEGREE_STRING] );
			$houseDegree['H7'] = $this->deltaDegrees( 15, $H7Degree );

			$tSunHouse = array();
			$resSun = 0;

			$sunPosition = $transitPlanets['Sun'][MarriageGuru::FULLDEGREE_STRING];

			foreach( $houseDegree as $type => $degree )
			{
				$tSunHouse[$type] = $this->inHouseRelativeTo( $degree, $sunPosition );

				if( in_array($tSunHouse[$type], array(1,7)  ) )
					$resSun = 1;
			}

			return $resSun;
		}

		public function executeRuleEight()
			{
				return 0;
			}

		private function executeRuleNine( $transitPlanets )
		{
			// This rule checks the aspect of Saturn and Jupiter on Moon. Any aspect yields a possibility.
			$moonHouseDegree = $this->deltaDegrees( 15, $this->_birth_planets['Moon'][MarriageGuru::FULLDEGREE_STRING] );

			$tJupiterHouseMoon = $this->inHouseRelativeTo( $moonHouseDegree, $transitPlanets['Jupiter'][MarriageGuru::FULLDEGREE_STRING] );

			$tSaturnHouseMoon = $this->inHouseRelativeTo( $moonHouseDegree, $transitPlanets['Saturn'][MarriageGuru::FULLDEGREE_STRING] );

			if( in_array($tSaturnHouseMoon, array(1,4,7,11)  ) || in_array($tJupiterHouseMoon, array(1,5,7,9) ) )
				return 1;

			return 0;
		}

		private function executeRuleTen( $transitPlanets )
		{
			//This one uses Arabic Parts of Marriage forumala = ASC + DESC - Venus

			$H7Degree = $this->_birth_houses['ASC'][MarriageGuru::FULLDEGREE_STRING] + 180;
			
			if( $H7Degree > 360 )
				$H7Degree -= 360;


			$point = $this->_birth_houses['ASC'][MarriageGuru::FULLDEGREE_STRING] + $H7Degree - $this->_birth_planets['Venus'][MarriageGuru::FULLDEGREE_STRING];

			if( $point < 0 )
				$point += 360;
			else if( $point > 360 )
				$point -= 360;
			//var_dump( $point );
			
			$pointHouseDegree = $this->deltaDegrees( 15, $point );

			$tJupiterHouseMoon = $this->inHouseRelativeTo( $pointHouseDegree, $transitPlanets['Jupiter'][MarriageGuru::FULLDEGREE_STRING] );

			$tSaturnHouseMoon = $this->inHouseRelativeTo( $pointHouseDegree, $transitPlanets['Saturn'][MarriageGuru::FULLDEGREE_STRING] );

			if( in_array($tSaturnHouseMoon, array(1,4,7,11)  ) || in_array($tJupiterHouseMoon, array(1,5,7,9) ) )
				return 1;

			return 0;

		}

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
		private function getAspectArray($name)
		{
			switch( $name )
			{
				case "Jupiter":
					$aspect = array( 1, 5, 7, 9 );
					break;
				case "Saturn":
					$aspect = array( 1, 4, 7, 11 );
					break;
				case "Mars":
					$aspect = array( 1, 6, 7, 10 );
					break;
				default:
					$aspect = array( 1, 7 );
					break;
			}
			return $aspect;
		}
		private function getProbabilityByAge( $age )
		{
			$prob = 0;
			if( $this->_gender == 'male' )
			{
				if( $age <= 20 )
					$prob = 0.02;
				else if( $age > 20 && $age <=25 )
					$prob = 0.23;
				else if( $age > 25 && $age <=30 )
					$prob = 0.30;
				else if( $age > 30 && $age <=40 )
					$prob = 0.15;
				else if( $age > 40 && $age <=45 )
					$prob = 0.10;
				else if( $age > 45 )
					$prob = 0.05;
			}
			else
			{
				if( $age <= 20 )
					$prob = 0.05;
				else if( $age > 20 && $age <=25 )
					$prob = 0.25;
				else if( $age > 25 && $age <=30 )
					$prob = 0.30;
				else if( $age > 30 && $age <=35 )
					$prob = 0.15;
				else if( $age > 35 && $age <=45 )
					$prob = 0.10;
				else if( $age > 45 )
					$prob = 0.05;
			}

			return $prob;
		}
	}


?>