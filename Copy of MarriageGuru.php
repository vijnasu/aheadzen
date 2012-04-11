<?php

	require_once 'orbit.php';
	require_once 'planet.php';
	require_once 'transit.php';
	require_once 'astroreport.php';
	require_once 'AspectsGenerator.php';
	require_once 'VimshottariDasha.php';
	require_once 'NavamshMaker.php';

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
		const LEAST_COUNT_COEFF = 30;
		const HOUSE_STRING = "house";
		const PERCENTAGE_ASPECT = 75;

		public function __construct($birth_data,$gender)
		{
			print '<pre>';
			$this->_birth_data = $birth_data;
			$birth_report = new AstroReport( $this->_birth_data );
			$this->_birth_planets = $birth_report->getPlanets();
			$this->_birth_houses = $birth_report->getHouses();
			
//			$this->_probability = array(1,0.96,0.77,0.85,0.98,0.68,0.7,0.59);
			$this->_probability = array(1,1,1,1,1,1,1,1);
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
//			var_dump($this->_birth_houses);
			$vivah_saham = $this->_lagna_lord_degree + $this->_seventh_lord_degree;

			if($vivah_saham > 360)
				$vivah_saham -= 360;

			$this->_vivah_saham = $vivah_saham;

			$navamsh = new NavamshMaker($this->_birth_houses,$this->_birth_planets);

			$this->_navamsh_planets = $navamsh->get_nplanets();
			$this->_navamsh_houses = $navamsh->get_nhouses();

			//var_dump($navamsh);
		//	var_dump($this->_navamsh_houses);
		//	var_dump($this->_navamsh_planets);
		
			$this->_navamsh_lagna = $this->_navamsh_houses[MarriageGuru::LAGNA_STRING][MarriageGuru::SIGN_STRING];
			$this->_navamsh_lagna_lord = $this->_raashi_lords[$this->_navamsh_lagna];

			$this->_navamsh_seventh_house = $this->_navamsh_houses[7][MarriageGuru::SIGN_STRING];
			$this->_navamsh_seventh_lord = $this->_raashi_lords[$this->_navamsh_seventh_house];

			print "\nvs =$vivah_saham ";
		}


		public function findMarriageDates()
		{
			$this->set_duration(18,45);	
			$output = array();
			$least_count = MarriageGuru::LEAST_COUNT_COEFF*AspectsGenerator::DAY_DIFF;
			$res = array(0,0,0,0,0,0,0,0);
			$output = array();
			$time = $this->_start_time;

			$end_time = $this->_end_time;
			$iter = 0;

			while($time <= $this->_end_time)
			{
				$iter++;

			//	$date = date(AspectsGenerator::DATE_FORMAT,$time);

//				print "\n\ndate = $date";
				$date = explode(":",date(AspectsGenerator::DATE_FORMAT,$time));
		//		$date = explode(":","1975:10:11");
				

				$data = $this->_start_data;
				
				$data['month'] = intval($date[1]);
				$data['year'] = intval($date[0]);
				$data['day'] = intval($date[2]);
				$data['hour'] = intval($date[3]);
				$data['min'] = intval($date[4]);
				$data['am_pm'] = $date[5];

		//		$time = mktime($data['hour'],$data['min'],0,$data['month'],$data['day'],$data['year']);
					
				$report = new AstroReport( $data );
				$time_planets = $report->getPlanets();
				$time_houses = $report->getHouses();
						

//				$res[0] = 	$this->executeRuleOne($time,$time_planets);

					
				$res[2] = $this->executeRuleThree($time_planets["Jupiter"][MarriageGuru::FULLDEGREE_STRING]);

				$res[3] = $this->executeRuleFour($time_planets["Jupiter"],$time_planets["Saturn"]);

//				$res[4] = $this->executeRuleFive($time_planets[$this->_lagna_lord][MarriageGuru::FULLDEGREE_STRING],$time_planets[$this->_seventh_lord][MarriageGuru::FULLDEGREE_STRING]);

				$res[5] = $this->executeRuleSix($time_planets["Jupiter"][MarriageGuru::FULLDEGREE_STRING]);
				$res[6] = $this->executeRuleNine($time_planets);
				$res[7] = $this->executeRuleTen($time_planets);

//				$res[8] = $this->executeRuleSeven($time_planets);

			//		$resEight = $this->executeRuleEight();
					$success = 0;
			//	var_dump($res);
						
					for($i = 0; $i < 9;$i++)
					{
						if($res[$i] == 1)
							$success++;
					}
					
					$count = count($output);

					if($success >= 4)
				{$count = count($output);
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

var_dump( $years );

foreach($years as $y => $frequency)
{
	
	$age = $y - $this->_birth_data['year'];
	
	echo "$age ( $y ) <br />";

	if( $startCount <= $resultCount )
		$startCount++;
	else break;
}


				print "\noutput is ";
	//			var_dump($output);
/*				$out = array();
				foreach($output as $result_data)
				{

					$date = explode(":",$result_data[0]);
					$data = $this->_start_data;
				
					$data['month'] = intval($date[1]);
					$data['year'] = intval($date[0]);
					$data['day'] = intval($date[2]);
					$data['hour'] = intval($date[3]);
					$data['min'] = intval($date[4]);
					$data['am_pm'] = $date[5];
				//	if($data['year'] == 1984)
					{
					$report = new AstroReport( $data );
					$time_planets = $report->getPlanets();
					$time_houses = $report->getHouses();
					$time = mktime($data['hour'],$data['min'],0,$data['month'],$data['day'],$data['year']);
					$res[0] = 	$this->executeRuleOne($time,$time_planets);
					$res[5] = $this->executeRuleSix($time_planets["Jupiter"][MarriageGuru::FULLDEGREE_STRING]);
					$res[6] = $this->executeRuleSeven($time_houses);
	
					$success = 0;

					if($res[0] == 1)
						$success++;
					if($res[5] == 1)
						$success++;
					if($res[6] >= 8)
						$success++;
						*/
//print "time planets are = ";
	//				var_dump($time_planets);
//					if($success >= 1)
//					{
//						$count = count($out);
//						$out[$count] = array($result_data[0],$success,$res[0],/*$result_data[2],$result_data[3],$result_data[4]*/$res[5],$res[6]);
//					}
//					}


//				}
			//	var_dump($output);				
				return $output;

		}



		private function set_duration($start_age,$end_age)
		{
			$this->_start_data = $this->_birth_data;
			$this->_end_data = $this->_birth_data;
			$this->_start_data['year'] += $start_age;
			$this->_end_data['year'] += $end_age;

			if($this->_end_data['year'] > 2037)
				$this->_end_data['year'] = 2037;
						
			print '<pre>';

			if(strcmp($this->_start_data['am_pm'],'am') == 0)
				$this->_start_time = mktime($this->_start_data['hour'],$this->_start_data['min'],0,$this->_start_data['month'],$this->_start_data['day'],$this->_start_data['year']);
			else
				$this->_start_time = mktime($this->_start_data['hour']+12,$this->_start_data['min'],0,$this->_start_data['month'],$this->_start_data['day'],$this->_start_data['year']);

			if(strcmp($this->_end_data['am_pm'],'am') == 0)
				$this->_end_time = mktime($this->_end_data['hour'],$this->_end_data['min'],0,$this->_end_data['month'],$this->_end_data['day'],$this->_end_data['year']);
			else
				$this->_end_time = mktime($this->_end_data['hour'],$this->_end_data['min'],0,$this->_end_data['month'],$this->_end_data['day'],$this->_end_data['year']);

			$start_time = $this->_start_time;
			$end_time = $this->_end_time;

			print "start time $start_time";

			//	print "end time $end_time";
			var_dump($end_time);
					
		}


		private function executeRuleOne($time,$time_planets)
			{
				$birthdata = $this->_birth_data;

				$bithDataTS = mktime($this->_birth_data['hour'],$this->_birth_data['min'],0,$this->_birth_data['month'],$this->_birth_data['day'],$this->_birth_data['year']);
				
				$moonFullDegree = $this->_birth_planets['Moon'][MarriageGuru::FULLDEGREE_STRING];
				
				$dashaGuru = new VimshottariDasha($moonFullDegree,$bithDataTS);
				
				$MDL = $dashaGuru->getDashaLord( $time, 1 );
				$ADL = $dashaGuru->getDashaLord( $time, 2 );
				

				print "\n MDL is $MDL";
				print "\n ADL is $ADL";
				$ll = $this->_lagna_lord;
				$sl = $this->_seventh_lord;
				print "\n lagna lord is $ll ";
				print "\n 7th lord is $sl ";
				$MDL_position = $time_planets[$MDL][MarriageGuru::FULLDEGREE_STRING];
				$MDL_sign = $time_planets[$MDL][MarriageGuru::SIGN_STRING];

				$ADL_position = $time_planets[$ADL][MarriageGuru::FULLDEGREE_STRING];
				$ADL_sign = $time_planets[$ADL][MarriageGuru::SIGN_STRING];
	
				foreach($this->_birth_houses as $house=>$data)
				{
					if(strcmp($data[MarriageGuru::SIGN_STRING],$MDL_sign) == 0)
						$MDL_house = $house;
					if(strcmp($data[MarriageGuru::SIGN_STRING],$ADL_sign) == 0)
						$ADL_house = $house;
				}
				
			//	var_dump($this->_navamsh_houses); 
				foreach($this->_navamsh_houses as $house=>$data)
				{
					if(strcmp($data[MarriageGuru::SIGN_STRING],$MDL_sign) == 0)
						$MDL_nhouse = $house;
					if(strcmp($data[MarriageGuru::SIGN_STRING],$ADL_sign) == 0)
						$ADL_nhouse = $house;
				}

				$lagna_lord_position = $this->_birth_planets[$this->_lagna_lord][MarriageGuru::FULLDEGREE_STRING];
				$seventh_lord_position = $this->_birth_planets[$this->_seventh_lord][MarriageGuru::FULLDEGREE_STRING];

			//	var_dump($this->_navamsh_planets[$this->_navamsh_lagna_lord]);
			//	var_dump($this->_navamsh_planets[$this->_navamsh_seventh_lord]);
				$navamsh_lagna_lord_position = $this->_navamsh_planets[$this->_navamsh_lagna_lord][MarriageGuru::FULLDEGREE_STRING];
				$navamsh_seventh_lord_position = $this->_navamsh_planets[$this->_navamsh_seventh_lord][MarriageGuru::FULLDEGREE_STRING];

				$res1 = 0;
				$res2 = 0;

				if(strcmp($MDL,'Sun') == 0 || strcmp($MDL,'Moon') == 0)
					$res1 = 1;

				if(strcmp($ADL,'Sun') == 0 || strcmp($ADL,'Moon') == 0)
					$res1 = 1;

				if($MDL_house == 1 || $MDL_house == 7)
				{print "mdl house in 1 or 7 ";$res1 = 1;}
				elseif(strcmp($MDL,$this->_lagna_lord) == 0 || strcmp($MDL,$this->_seventh_lord) == 0)
				{print "mdl is ll or 7l";$res1 = 1;}
				elseif($this->find_house_aspect($MDL,$MDL_house,1) == 1 || $this->find_house_aspect($MDL,$MDL_house,7) == 1)
				{print "mdl aspects with 1h or 7h";$res1 = 1;}
				elseif($this->find_aspect($MDL,$MDL_position,$lagna_lord_position) == 1 || $this->find_aspect($MDL,$MDL_position,$seventh_lord_position) == 1)
				{print "mdl aspects with ll or 7l";	$res1 = 1;}
				elseif($MDL_nhouse == 1 || $MDL_nhouse == 7)
				{print "mdl house in 1 or 7 in d9";	$res1 = 1;}
				elseif(strcmp($MDL,$this->_navamsh_lagna_lord) == 0 || strcmp($MDL,$this->_navamsh_seventh_lord) == 0)
				{print "mhl is ll or 7l in d9";	$res1 = 1;}
				elseif($this->find_house_aspect($MDL,$MDL_nhouse,1) == 1 || $this->find_house_aspect($MDL,$MDL_nhouse,7) == 1)
				{print "mdl aspects with 1h or 7h in d9";	$res1 = 1;}
				elseif($this->find_aspect($MDL,$MDL_position,$navamsh_lagna_lord_position) == 1 || $this->find_aspect($MDL,$MDL_position,$navamsh_seventh_lord_position) == 1)
				{print "mdl aspects with ll or 7l in d9";	$res1 = 1;}
				


				if($ADL_house == 1 || $ADL_house == 7)
				{print "adl house in 1 or 7 ";	$res2 = 1;}
				elseif(strcmp($ADL,$this->_lagna_lord) == 0 || strcmp($ADL,$this->_seventh_lord) == 0)
				{print "adl is ll or 7l";	$res2 = 1;}
				elseif($this->find_house_aspect($ADL,$ADL_house,1) == 1 || $this->find_house_aspect($ADL,$ADL_house,7) == 1)
					{print "adl aspects with 1h or 7h";$res2 = 1;}
				elseif($this->find_aspect($ADL,$ADL_position,$lagna_lord_position) == 1 || $this->find_aspect($ADL,$ADL_position,$seventh_lord_position) == 1)
				{print "adl aspects with ll or 7l";	$res2 = 1;}
				elseif($ADL_nhouse == 1 || $ADL_nhouse == 7)
				{print "adl house in 1 or 7 in d9";	$res2 = 1;}
				elseif(strcmp($ADL,$this->_navamsh_lagna_lord) == 0 || strcmp($ADL,$this->_navamsh_seventh_lord) == 0)
				{print "adl is ll or 7l in d9";	$res2 = 1;}
				elseif($this->find_house_aspect($ADL,$ADL_nhouse,1) == 1 || $this->find_house_aspect($ADL,$ADL_nhouse,7) == 1)
				{print "adl aspects with 1h or 7h in d9";	$res2 = 1;}
				elseif($this->find_aspect($ADL,$ADL_position,$navamsh_lagna_lord_position) == 1 || $this->find_aspect($ADL,$ADL_position,$navamsh_seventh_lord_position) == 1)
					{print "adl aspects with ll or 7l in d9";	$res2 = 1;}

				if($res1 == 1)
					print "proper position for MDL ";
				if($res2 == 1)
					print "proper position for ADL ";

				if($res1 == 1 && $res2 == 1)
					return 1;
				else
					return 0;
				
			}


		public function executeRuleThree($jupiter_position)
			{
//				print "\nJupiter position is $jupiter_position";
				$vivah_saham = $this->_vivah_saham;

				$pointHouseDegree = $this->deltaDegrees( 15, $vivah_saham );

				$tJupiterHouseMoon = $this->inHouseRelativeTo( $pointHouseDegree, $jupiter_position );

				if( in_array($tJupiterHouseMoon, array(1,5,7,9) ) )
					return 1;

			return 0;


			//	$res = $this->find_aspect("Jupiter",$jupiter_position,$this->_vivah_saham);
			//	return $res;
			}
		
		public function executeRuleFour($jupiter_data,$saturn_data)
			{

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

				//what aspects should be considered here
				if(strcmp($this->_lagna_lord,"Sun") == 0 ||strcmp($this->_lagna_lord,"Moon") == 0 || strcmp($this->_seventh_lord,"Sun") == 0 || strcmp($this->_seventh_lord,"Moon") == 0)
					return 1;

				$ll = $this->_lagna_lord;
		//		print "ll = $ll";
				$sl = $this->_seventh_lord;
		//		print "sl = $sl";
			

				$res1 = $this->find_aspect($this->_lagna_lord,$lagna_lord_position,$seventh_lord_position);
				$res2 = $this->find_aspect($this->_seventh_lord,$seventh_lord_position,$lagna_lord_position);

				if($res1 == 1 && $res2 == 1)
					return 1;
				else
					return 0;
			}

		public function executeRuleSix($jupiter_position)
			{
				if( $this->_gender == 'male' )
					$p = 'Venus';
				else $p = 'Mars';

				$planetHouseDegree = $this->deltaDegrees( 15, $this->_birth_planets[$p][MarriageGuru::FULLDEGREE_STRING] );

				$tJupiterHousePlanet = $this->inHouseRelativeTo( $planetHouseDegree, $jupiter_position );

				if( in_array($tJupiterHousePlanet, array(1,5,7,9) ) )
					return 1;

				return 0;


/*				$venus_point = $this->_birth_planets['Venus'][MarriageGuru::FULLDEGREE_STRING];
				$mars_point = $this->_birth_planets['Mars'][MarriageGuru::FULLDEGREE_STRING];
//				print "\n natal mars at $mars_point";
//				print "jupiter position is $jupiter_position";
				if(strcmp($this->_gender,"male") == 0)
					$res = $this->find_aspect("Jupiter",$jupiter_position,$venus_point);
				else
					$res = $this->find_aspect("Jupiter",$jupiter_position,$mars_point);

				return $res;*/
			}

		public function executeRuleSeven($transitPlanets)
			{
				$ascHouseDegree = $this->deltaDegrees( 15, $this->_birth_houses['ASC'][MarriageGuru::FULLDEGREE_STRING] );

			$tSunHouseASC = $this->inHouseRelativeTo( $ascHouseDegree, $transitPlanets['Sun'][MarriageGuru::FULLDEGREE_STRING] );
//var_dump( $transitPlanets);
			if( in_array($tSunHouseASC, array(1,7) ) )
				return 1;

			return 0;

				
		//	var_dump($time_houses);
/*				$count = 0;
				foreach($time_houses as $house_number => $house_data)
				{
					if($house_number == 1 ||$house_number == 2 || $house_number == 12 ||($house_number >= 6 && $house_number <= 8))
					{
						$count += count($house_data['Planet']);
					}
				}
			/*	foreach($time_planets as $planet => $planet_data)
				{
					$house_number = $planet_data[MarriageGuru::HOUSE_STRING];
					if($house_number == 1 ||$house_number == 2 || $house_number == 12 ||($house_number >= 6 && $house_number <= 8))
						$count++;
				}*/
				
//				return $count;
			/*	if($count >= 8)
					return 1;
				else 
					return 0;
			*/}

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

			$point = $this->_birth_houses['ASC'][MarriageGuru::FULLDEGREE_STRING] * 2 + 180 - $this->_birth_planets['Venus'][MarriageGuru::FULLDEGREE_STRING];
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
		private function find_aspect($planet,$position,$point)
			{

			//a bug is still present tht needs to be taken care of
				$degree1 = $position;
				$degree = $point;
				$degree_diff = $degree - $degree1;
				
				if($degree_diff < 0)
					$degree_diff += 360;

				if(strcmp($planet,"Saturn") == 0)
				{

//					print "\n degree diff + $degree_diff";
					$d1 = 6/5*(100 - MarriageGuru::PERCENTAGE_ASPECT);			 // $d <= 30
					if($d1 > 30)
						$d1 = 30;

					$d2 = 6/5*MarriageGuru::PERCENTAGE_ASPECT - 60;				//30<$d <= 60
					if($d2 < 30)
						$d2 = 30;
					$d3 = 100 - 2/5*MarriageGuru::PERCENTAGE_ASPECT;			//60<$d <= 90
					if($d3 > 90)
						$d3 = 90;

					$d4 = 6/5*(MarriageGuru::PERCENTAGE_ASPECT + 50);			// 90<$d <= 180
					if($d4 < 90)
						$d4 = 90;

					$d5 = 180 + 6/5*(100 - MarriageGuru::PERCENTAGE_ASPECT);		//180<$d <= 240
					if($d5 > 240)
						$d5 = 240;
					
					$d6 = 3/5*MarriageGuru::PERCENTAGE_ASPECT + 210;				//240<$D<=270
					if($d6 < 240)
						$d6 = 240;

					$d7 = 330 - 3/5*MarriageGuru::PERCENTAGE_ASPECT;				//270<$d<=300
					if($d7 > 300)
						$d7 = 300;

					$d8 = 180 + 6/5*(MarriageGuru::PERCENTAGE_ASPECT + 50);		//300<$d
					if($d8 < 300)
						$d8 = 300;
	//				print "\n d8 = $d8 , d6 = $d6 , d7 = $d7";

					if($degree_diff <= $d1)
					{/* print "yes less than d1";*/	return 1;}
					elseif($degree_diff >= $d2 && $degree_diff <= $d3)
					{/* print "yes b/w d2 and d3";	*/	return 1;}
					elseif($degree_diff >= $d4 && $degree_diff <= $d5)
					{/* print "yes b/w d4 and d5";	*/	return 1;}
					elseif($degree_diff >= $d6 && $degree_diff <= $d7)
					{/* print "yes b/w d6 and d7";	*/	return 1;}
					elseif($degree_diff >= $d8 && $degree_diff <= 360)
					{/* print "yes b/w d8 and 360"; */	return 1;}

				}
				elseif(strcmp($planet,"Jupiter") == 0)
				{
					$d1 = 6/5*(100 - MarriageGuru::PERCENTAGE_ASPECT);			 // $d <= 90
					if($d1 > 90)
						$d1 = 90;

					$d2 = 80 + 2/5*MarriageGuru::PERCENTAGE_ASPECT;				//90<$d <= 120
					if($d2 < 90)
						$d2 = 90;
					$d3 = 240 - 6/5*MarriageGuru::PERCENTAGE_ASPECT;			//120<$d <= 150
					if($d3 > 150)
						$d3 = 150;

					$d4 = 6/5*(MarriageGuru::PERCENTAGE_ASPECT + 50);			// 150<$d <= 180
					if($d4 < 150)
						$d4 = 150;

					$d5 = 180 + 6/5*(100 - MarriageGuru::PERCENTAGE_ASPECT);		//180<$d <= 210
					if($d5 > 210)
						$d5 = 210;
					
					$d6 = 6/5*MarriageGuru::PERCENTAGE_ASPECT + 120;				//210<$D<=240
					if($d6 < 210)
						$d6 = 210;

					$d7 = 280 - 2/5*MarriageGuru::PERCENTAGE_ASPECT;				//240<$d<=270
					if($d7 > 270)
						$d5 = 270;

					$d8 = 180 + 6/5*(MarriageGuru::PERCENTAGE_ASPECT + 50);		//270<$d
					if($d8 < 270)
						$d8 = 270;

					if($degree_diff <= $d1)
						return 1;
					elseif($degree_diff >= $d2 && $degree_diff <= $d3)
						return 1;
					elseif($degree_diff >= $d4 && $degree_diff <= $d5)
						return 1;
					elseif($degree_diff >= $d6 && $degree_diff <= $d7)
						return 1;
					elseif($degree_diff >= $d8 && $degree_diff <= 360)
						return 1;

				}
				elseif(strcmp($planet,"Mars") == 0)
				{
					$d1 = 6/5*(100 - MarriageGuru::PERCENTAGE_ASPECT);			 // $d <= 60
					if($d1 > 60)
						$d1 = 60;

					$d2 = 30 + 3/5*MarriageGuru::PERCENTAGE_ASPECT;				//60<$d <= 90
					if($d2 < 90)
						$d2 = 90;
					$d3 = 150 - 3/5*MarriageGuru::PERCENTAGE_ASPECT;			//90<$d <= 120
					if($d3 > 120)
						$d3 = 120;

					$d4 = 6/5*(MarriageGuru::PERCENTAGE_ASPECT + 50);			// 120<$d <= 180
					if($d4 < 120)
						$d4 = 120;

					$d5 = 210;													//180<$d <= 210
					if($d5 > 210)
						$d5 = 210;
					
					$d6 = 270 - 3/5*MarriageGuru::PERCENTAGE_ASPECT ;				//210<$D<=240
					if($d6 > 240)
						$d6 = 240;

					$d7 = 300 - 6/5*MarriageGuru::PERCENTAGE_ASPECT;				//240<$d<=270
					if($d7 > 270)
						$d5 = 270;

					$d8 = 180 + 6/5*(MarriageGuru::PERCENTAGE_ASPECT + 50);		//270<$d
					if($d8 < 270)
						$d8 = 270;

					if($degree_diff <= $d1)
						return 1;
					elseif($degree_diff >= $d2 && $degree_diff <= $d3)
						return 1;
					elseif($degree_diff >= $d4 && $degree_diff <= $d6)
						return 1;
					elseif($degree_diff >= 240 && $degree_diff <= $d7)
						return 1;
					elseif($degree_diff >= $d8 && $degree_diff <= 360)
						return 1;

				}
				else{
					$d1 = 6/5*(100 - MarriageGuru::PERCENTAGE_ASPECT);			 // $d < 90
					$d2 = 6/5*(MarriageGuru::PERCENTAGE_ASPECT + 50);			 // 90<$d <= 180
					$d3 = 180 + 6/5*(100 - MarriageGuru::PERCENTAGE_ASPECT);	 //180<$d <= 270
					$d4 = 180 + 6/5*(MarriageGuru::PERCENTAGE_ASPECT + 50);

				if($degree_diff <= $d1)
					return 1;
				elseif($degree_diff >= $d2 && $degree_diff <= $d3)
					return 1;
				elseif($degree_diff >= $d4)
					return 1;

				}

				return 0;

		}	

		private function find_house_aspect($planet,$planet_house,$house_number)
		{
			if(strcmp($planet,"Jupiter") == 0)
			{
				if($house_number == 1)
				{
					if($planet_house == 1)	
						return 1;
					if($planet_house == 5)	
						return 1;
					if($planet_house == 7)	
						return 1;
					if($planet_house == 9)	
						return 1;
					else
						return 0;
				}

				elseif($house_number == 7)
				{
					if($planet_house == 1)	
						return 1;
					if($planet_house == 3)	
						return 1;
					if($planet_house == 7)	
						return 1;
					if($planet_house == 11)	
						return 1;
					else
						return 0;
				}

			}
			elseif(strcmp($planet,"Saturn") == 0)
			{
				if($house_number == 1)
				{
					if($planet_house == 1)	
						return 1;
					if($planet_house == 3)	
						return 1;
					if($planet_house == 7)	
						return 1;
					if($planet_house == 10)	
						return 1;
					else
						return 0;
				}

				elseif($house_number == 7)
				{
					if($planet_house == 1)	
						return 1;
					if($planet_house == 4)	
						return 1;
					if($planet_house == 7)	
						return 1;
					if($planet_house == 9)	
						return 1;
					else
						return 0;
				}

			}

		}

	}


?>