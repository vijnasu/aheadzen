<?php

	require_once 'orbit.php';
	require_once 'planet.php';
	require_once 'transit.php';
	require_once 'astroreport.php';
	require_once 'NorthernChartMaker.php';

	class NavamshMaker{

	private $_birth_houses = array();
	private $_birth_planets = array();
	private $_navamsh_planets = array();
	private $_navamsh_houses = array();
	private $_sign = array();

	const DEGREE_STRING = "degree";
	const HOUSE_STRING = "house";
	const SIGN_NUMBER_STRING = "sign_number";
	const FULLDEGREE_STRING = "fulldegree";
	const SIGN_STRING = "sign";
	const PLANET_STRING = "Planet";

	public function __construct($birth_houses,$birth_planets)
		{
			$this->_birth_houses = $birth_houses;
			$this->_birth_planets = $birth_planets;
			$this->_sign = array(
				"Aries","Taurus","Gemini","Cancer","Leo","Virgo","Libra","Scorpio","Sagittarius","Capricorn","Aquarius","Pisces");
		}

	//return navamsh planets array
	public function get_nplanets()
		{
			print "in navamsh get_nplanets\n";


			foreach($this->_birth_planets as $birth_planet => $planet_data)
			{
				$degree = $planet_data[NavamshMaker::DEGREE_STRING];
				
				$navamsh_division = floor(($degree*9)/30);
				if($navamsh_division < ($degree*9)/30)
					$navamsh_division++;

				$completed_sign = $planet_data[NavamshMaker::SIGN_NUMBER_STRING] - 1;

				$navamsh_sign_number = ($completed_sign*9+$navamsh_division)%12;

				if($navamsh_sign_number == 0)
					$navamsh_sign_number = 12;

				$navamsh_degree = ($degree - (floor($degree*9/30)*30/9))*9;	

				$navamsh_fulldegree = (($navamsh_sign_number-1)*30)+$navamsh_degree;
				$navamsh_sign = $this->_sign[$navamsh_sign_number-1];

				
				$this->_navamsh_planets[$birth_planet] = array(
					NavamshMaker::FULLDEGREE_STRING => $navamsh_fulldegree,
					NavamshMaker::DEGREE_STRING => $navamsh_degree,
					NavamshMaker::SIGN_STRING => $navamsh_sign,
					NavamshMaker::SIGN_NUMBER_STRING => $navamsh_sign_number);

			}
			return $this->_navamsh_planets;
			
		}

	//returns navamsh planets houses
	public function get_nhouses()
		{
			$this->get_nplanets();

			$asc_degree = $this->_birth_houses['ASC'][NavamshMaker::DEGREE_STRING];
			$navamsh_division = floor(($asc_degree*9)/30);

			if($navamsh_division < ($asc_degree*9)/30)
					$navamsh_division++;

			$completed_sign = $this->_birth_houses['ASC'][NavamshMaker::SIGN_NUMBER_STRING] - 1;

			$navamsh_sign_number = ($completed_sign*9+$navamsh_division)%12;
			
			if($navamsh_sign_number == 0)
					$navamsh_sign_number = 12;

			$navamsh_degree = ($asc_degree - (floor($asc_degree*9/30)*30/9))*9;	

			$navamsh_fulldegree = (($navamsh_sign_number-1)*30)+$navamsh_degree;
			$navamsh_sign = $this->_sign[$navamsh_sign_number-1];

			print "lagna degree = $asc_degree";
			print "...... navamsha sign number = $navamsh_sign_number";
			print "....... comp  sign number = $completed_sign";

			$this->_navamsh_houses['ASC'] = array(
					NavamshMaker::FULLDEGREE_STRING => $navamsh_fulldegree,
					NavamshMaker::DEGREE_STRING => $navamsh_degree,
					NavamshMaker::SIGN_STRING => $navamsh_sign,
					NavamshMaker::SIGN_NUMBER_STRING => $navamsh_sign_number,
					NavamshMaker::PLANET_STRING => array());

			
			for($i = 1;$i <= 12;$i++)
			{
				if($i > 1)
					$navamsh_sign_number = ($navamsh_sign_number+1)%12;
					
					if($navamsh_sign_number == 0)
						$navamsh_sign_number = 12;
				
				$navamsh_sign = $this->_sign[$navamsh_sign_number-1];
				if($i == 1)
					print "...... navamsha sign number in 1st house = $navamsh_sign_number";

				$this->_navamsh_houses[$i] = array(
					NavamshMaker::SIGN_STRING => $navamsh_sign,
					NavamshMaker::SIGN_NUMBER_STRING => $navamsh_sign_number,
					NavamshMaker::PLANET_STRING => array());

			}

			foreach($this->_navamsh_planets as $planet => $planet_data)
			{
				$sign_number = $planet_data[NavamshMaker::SIGN_NUMBER_STRING];
				$asc_sign_number = $this->_navamsh_houses['ASC'][NavamshMaker::SIGN_NUMBER_STRING];
				$house_number = $sign_number-$asc_sign_number+1;

				if($house_number <= 0)
					$house_number += 12;
				
				$this->_navamsh_planets[$planet][NavamshMaker::HOUSE_STRING] = $house_number;
				$this->_navamsh_houses[$house_number][NavamshMaker::PLANET_STRING][$planet] = array(
					NavamshMaker::FULLDEGREE_STRING => $planet_data[NavamshMaker::FULLDEGREE_STRING],
					NavamshMaker::DEGREE_STRING => $planet_data[NavamshMaker::DEGREE_STRING],
					NavamshMaker::SIGN_STRING => $planet_data[NavamshMaker::SIGN_STRING],
					NavamshMaker::SIGN_NUMBER_STRING => $planet_data[NavamshMaker::SIGN_NUMBER_STRING]);

			}

		//	var_dump($this->_navamsh_houses);
	//		$maker1 = new NorthernChartMaker($this->_birth_houses);
	//		$maker1->saveChart('birthchart.png','../');
	//		$maker = new NorthernChartMaker($this->_navamsh_houses);
	//		$maker->saveChart('navamsh.png','../');
	return $this->_navamsh_houses;
		}

	}





					
?>