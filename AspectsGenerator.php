<?php

	require_once 'orbit.php';
	require_once 'planet.php';
	require_once 'transit.php';
	require_once 'astroreport.php';

	class AspectsGenerator{

	const DATE_FORMAT = "Y:m:d:h:i:a";
	const DEGREE_STRING = "fulldegree";
	const DAY_DIFF = 86400;
	const SIGN_NUMBER_STRING = "sign_number";
	const SIGN_DEGREE = 30;
	const LEAST_COUNT_COEFF = 10;

	private $_birth_data = array();
	private $_start_data= array();
	private $_start_time;
	private $_end_time;
	private $_end_data = array();
	private $_aspect_planet = array();
	private $_aspect_types = array();
	private $_aspect_min_orb = array();
	private $_birth_planets;
	private $_birth_houses;
	private $_start_planets;
	private $_include_houses;

	public function __construct($birth_data, $include_houses = false)
		{

			$this->_birth_data = $birth_data;		
			
			$this->_aspect_planet = array("Saturn" => array(0,180,60,270),"Mars" => array(0,180,90,210),"Rahu"=>array(0),"Ketu"=>array(0),"Jupiter"=>array(0,180,120,240));

			$this->_aspect_degree = array(
								0=>"conjunction",
								180 => "opposition",
								60 => "sextile",
								90 => "square",
								120 => "trine"
								);
			$this->_include_houses = $include_houses;

		}


	//finds aspects for a planet and an aspect degree at a particular time

	private function find_planet_aspect($time,$time_planets,$aspect,$planet,$res)
		{

			$aspect_degree = $aspect;
			$date = date(AspectsGenerator::DATE_FORMAT,$time);
			foreach($this->_birth_planets as $birth_planet => $planet_data)
			{

				//print "\nsearching.....";

				$temp = $planet_data[AspectsGenerator::DEGREE_STRING];

				$degree = $planet_data[AspectsGenerator::DEGREE_STRING];
				$degree1 = $time_planets[$planet][AspectsGenerator::DEGREE_STRING];
				$degree_diff = $degree - $degree1; 

			
				if($degree_diff < -1)
					$degree_diff += 360;

				$aspect_info = $planet.":".$birth_planet.":".$aspect_degree;
	
				if(abs($degree_diff-$aspect_degree) < (1) )
				{
													
					$orb = 	$degree_diff-$aspect_degree;

					if(array_key_exists($aspect_info,$this->_aspect_min_orb))
					{	
						if(abs($orb) < $this->_aspect_min_orb[$aspect_info][0])
						{
							$this->_aspect_min_orb[$aspect_info] = array(abs($orb),$time);
						}
					}
					else
					{	
						$this->_aspect_min_orb[$aspect_info] = array(abs($orb),$time);
					}
						
				}
				elseif(array_key_exists($aspect_info,$this->_aspect_min_orb))
				{
					$aspect_time = $this->_aspect_min_orb[$aspect_info][1];
					$aspect_date = date(AspectsGenerator::DATE_FORMAT,$aspect_time);
					
					if(array_key_exists($aspect_date,$res))
					{
						$count = count($res[$aspect_date]);
						$res[$aspect_date][$count]  = array($planet,$birth_planet,$aspect_degree);
					}
					else
					{
						$res[$aspect_date][0] = array($planet,$birth_planet,$aspect_degree);
					}

					unset($this->_aspect_min_orb[$aspect_info]);
				}
				
			}

//House Aspects

			if ( $this->_include_houses )
			{
				foreach($this->_birth_houses as $house => $house_data)
				{

					if( in_array( $house, array( "MC", "ASC", 1 ) ) )
						continue;

					//print "\nsearching.....";

					$temp = $house_data[AspectsGenerator::DEGREE_STRING];
					$degree = $house_data[AspectsGenerator::DEGREE_STRING];
					$degree1 = $time_planets[$planet][AspectsGenerator::DEGREE_STRING];
					$degree_diff = $degree - $degree1; 

					if($degree_diff < -1)
						$degree_diff += 360;

					$aspect_info = $planet.":".$house.":".$aspect_degree;
		
					if(abs($degree_diff-$aspect_degree) < (1) )
					{
														
						$orb = 	$degree_diff-$aspect_degree;

						if(array_key_exists($aspect_info,$this->_aspect_min_orb))
						{	
							if(abs($orb) < $this->_aspect_min_orb[$aspect_info][0])
							{
								$this->_aspect_min_orb[$aspect_info] = array(abs($orb),$time);
							}
						}
						else
						{	
							$this->_aspect_min_orb[$aspect_info] = array(abs($orb),$time);
						}
							
					}
					elseif(array_key_exists($aspect_info,$this->_aspect_min_orb))
					{
						$aspect_time = $this->_aspect_min_orb[$aspect_info][1];
						$aspect_date = date(AspectsGenerator::DATE_FORMAT,$aspect_time);
						
						if(array_key_exists($aspect_date,$res))
						{
							$count = count($res[$aspect_date]);
							$res[$aspect_date][$count]  = array($planet,$house,$aspect_degree);
						}
						else
						{
							$res[$aspect_date][0] = array($planet,$house,$aspect_degree);
						}

						unset($this->_aspect_min_orb[$aspect_info]);
					}
					
				}
			}

//			var_dump($res);
			return $res;
		}


	private function start()
		{

			$least_count = AspectsGenerator::LEAST_COUNT_COEFF*AspectsGenerator::DAY_DIFF;
			$res = array();
			$output = array();
			$time = $this->_start_time;


			while($time <= $this->_end_time)
			{
				$date = date(AspectsGenerator::DATE_FORMAT,$time);
					
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
			
			
					//var_dump($time_planets);

					foreach($this->_aspect_planet as $planet => $aspect_types)
					{
						foreach($aspect_types as $aspect)
						{
							$output =	$this->find_planet_aspect($time,$time_planets,$aspect,$planet,$output);	
							//$output = array_merge($res,$output);
						}
					}

					$time += $least_count;
			
			}
			ksort($output);
			
			return $output;

		}
	

	//Date should be in date format specified in const above
	public function find_aspects($start_date,$end_date)
		{
			//print "inside aspect finder";
			$date = explode(":",$start_date);
			$this->_start_data = $this->_birth_data;
			$this->_start_data['day'] = intval($date[2]);
			$this->_start_data['month'] = intval($date[1]);
			$this->_start_data['year'] = intval($date[0]);
			$this->_start_data['hour'] = intval($date[3]);
			$this->_start_data['min'] = intval($date[4]);
			$this->_start_data['am_pm'] = $date[5];

			$date = explode(":",$end_date);
			$this->_end_data = $this->_birth_data;
			$this->_end_data['day'] = intval($date[2]);
			$this->_end_data['month'] = intval($date[1]);
			$this->_end_data['year'] = intval($date[0]);
			$this->_end_data['hour'] = intval($date[3]);
			$this->_end_data['min'] = intval($date[4]);
			$this->_end_data['am_pm'] = $date[5];

			if(strcmp($this->_start_data['am_pm'],'am') == 0)
				$this->_start_time = mktime($this->_start_data['hour'],$this->_start_data['min'],0,$this->_start_data['month'],$this->_start_data['day'],$this->_start_data['year']);
			else
				$this->_start_time = mktime($this->_start_data['hour']+12,$this->_start_data['min'],0,$this->_start_data['month'],$this->_start_data['day'],$this->_start_data['year']);

			if(strcmp($this->_end_data['am_pm'],'am') == 0)
				$this->_end_time = mktime($this->_end_data['hour'],$this->_end_data['min'],0,$this->_end_data['month'],$this->_end_data['day'],$this->_end_data['year']);
			else
				$this->_end_time = mktime($this->_end_data['hour']+12,$this->_end_data['min'],0,$this->_end_data['month'],$this->_end_data['day'],$this->_end_data['year']);
			

			$birth_report = new AstroReport( $this->_birth_data );
			$start_report = new AstroReport($this->_start_data);

			

			$this->_birth_planets = $birth_report->getPlanets();
			$birthHouses = $birth_report->getHouses();
			$this->_birth_planets['ASC'] = $birthHouses['ASC'];
			$this->_birth_houses = $birthHouses;
			
			$this->_start_planets = $start_report->getPlanets();
		//	var_dump($this->_start_planets);

			$output = array();
			$res = array();

			$output = $this->start();
			
			return $output;
		}

	}
		
?>