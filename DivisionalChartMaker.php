<?php

	require_once 'orbit.php';
	require_once 'planet.php';
	require_once 'transit.php';
	require_once 'astroreport.php';
	require_once 'NorthernChartMaker.php';

	class DivisionalChartMaker{

	private $_birth_houses;
	private $_birth_planets;
	private $_birth_zodiacs;
	private $_sign;
	private $_chart_planets;
	public $_chart_houses;
	const DEGREE_STRING = 'degree';
	const SIGN_NUMBER_STRING = 'sign_number';
	const SIGN_STRING = 'sign';
	const ASC_STRING = 'ASC';
	const PLANET_STRING = 'Planet';
	const FULLDEGREE_STRING = 'fulldegree';


	public function __construct($birth_houses,$birth_planets)
		{
		$this->_birth_houses = $birth_houses;
		$this->_birth_planets = $birth_planets;
		$this->_sign = array(
				"Aries","Taurus","Gemini","Cancer","Leo","Virgo","Libra","Scorpio","Sagittarius","Capricorn","Aquarius","Pisces");

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



		foreach($this->_sign as $index => $sign)
			{
				$this->_birth_zodiacs[$sign][DivisionalChartMaker::SIGN_NUMBER_STRING] = ($index+1);

			}

		$asc_data = $this->_birth_houses[DivisionalChartMaker::ASC_STRING];
		$asc_sign = $this->_birth_houses[DivisionalChartMaker::ASC_STRING][DivisionalChartMaker::SIGN_STRING];

	//	$this->_birth_zodiacs[$asc_sign][DivisionalChartMaker::PLANET_STRING][DivisionalChartMaker::ASC_STRING][DivisionalChartMaker::DEGREE_STRING] = $asc_data[DivisionalChartMaker::DEGREE_STRING];
//		$this->_birth_zodiacs[$asc_sign][DivisionalChartMaker::PLANET_STRING][DivisionalChartMaker::ASC_STRING][DivisionalChartMaker::FULLDEGREE_STRING] = $asc_data[DivisionalChartMaker::FULLDEGREE_STRING];


		foreach($this->_birth_planets as $planet =>$planet_data)
			{
				$sign = $planet_data[DivisionalChartMaker::SIGN_STRING];
				$this->_birth_zodiacs[$sign][DivisionalChartMaker::PLANET_STRING][$planet][DivisionalChartMaker::DEGREE_STRING] = $planet_data[DivisionalChartMaker::DEGREE_STRING];
				$this->_birth_zodiacs[$sign][DivisionalChartMaker::PLANET_STRING][$planet][DivisionalChartMaker::FULLDEGREE_STRING] = $planet_data[DivisionalChartMaker::FULLDEGREE_STRING];
			}

		}

	private function GetNewSign($chart_number,$sign_number,$degree,$division_number)
		{


			switch($chart_number)
			{

				case 2:{
							if($sign_number % 2 == 0)
							{	
								if($degree < 15)
									$new_sign = 4;
								else
									$new_sign = 5;
							}
							else
							{
								if($degree < 15)
									$new_sign = 5;
								else
									$new_sign = 4;

							}

						};
						break;
				case 3:{
							$offset = $division_number*4;

							$new_sign = $sign_number + $offset;
						};
						break;
				case 4:{
							$offset = $division_number*3;
							$new_sign = $sign_number + $offset;
						};
						break;
				case 5:{
							if(($sign_number -1)%3 == 0)
							{
								$new_sign = $sign_number+$division_number;
							}
							elseif(($sign_number -2)%3 == 0)
							{
								$new_sign = $sign_number+4+$division_number;
							}
							elseif(($sign_number)%3 == 0)
							{
								$new_sign = $sign_number+8+$division_number;
							}
					
						};
						break;
				case 6:{
							// Doesn't matches JHora
							if($sign_number % 2 == 0)
								$offset = 0;
							else
								$offset = 5;

							$new_sign = $offset+$division_number;

						};
						break;
				case 7:{
							if($sign_number % 2 == 1)
								$offset = $sign_number;
							else
								$offset = $sign_number+6;

							$new_sign = $offset + $division_number;
						};
						break;
				case 8:{
							if(($sign_number -1)%3 == 0)
							{
								$new_sign = 1+$division_number;
							}
							elseif(($sign_number -2)%3 == 0)
							{
								$new_sign = 9+$division_number;
							}
							elseif(($sign_number)%3 == 0)
							{
								$new_sign = 5+$division_number;
							}
						};
						break;
				case 9:{
							$completed_signs = $sign_number-1;
						};
						break;
				case 10:{	
							if($sign_number%2 == 1)
								$offset = $sign_number;
							else
								$offset = $sign_number+8;

							$new_sign = $offset + $division_number;

						};
						break;
				case 11:{
							$new_sign = 13 - $division_number;
							// Doesn't matches JHora
						};
						break;
				case 12:{
							$new_sign = $sign_number+ $division_number;
						};
						break;
				case 16:{
							if(($sign_number -1)%3 == 0)
							{
								$new_sign = 1+$division_number;
							}
							elseif(($sign_number -2)%3 == 0)
							{
								$new_sign = 5+$division_number;
							}
							elseif(($sign_number)%3 == 0)
							{
								$new_sign = 9+$division_number;
							}

						};
						break;
				case 20:{
							if(($sign_number -1)%3 == 0)
							{
								$new_sign = 1+$division_number;
							}
							elseif(($sign_number -2)%3 == 0)
							{
								$new_sign = 9+$division_number;
							}
							elseif(($sign_number)%3 == 0)
							{
								$new_sign = 5+$division_number;
							}
						};
						break;
				case 24:{
							if($sign_number % 2 == 1)
							{
								$new_sign = 5+ $division_number;
							}
							else
							{
								$new_sign = 4+ $division_number;
							}
						};
						break;
				case 27:{
							if(($sign_number - 1)%4 == 0)
							{
								//fiery signs
								$new_sign = 1+ $division_number;
							}
							elseif(($sign_number - 3)%4 == 0)
							{
								//airy sign
								$new_sign = 7+ $division_number;
							}
							elseif(($sign_number - 2)%4 == 0)
							{
								//earthy sign
								$new_sign = 4+ $division_number;
							}
							else
							{
								//watery sign
								$new_sign = 10+ $division_number;
							}
						};
						break;
				case 30:{
							if($sign_number % 2 == 1)
							{
								if($division_number < 5)
								{
									$new_sign = 1;
								}
								elseif($division_number < 10)
								{
									$new_sign = 11;
								}
								elseif($division_number < 18)
								{
									$new_sign = 9;
								}
								elseif($division_number < 25)
								{
									$new_sign = 3;
								}
								else
								{
									$new_sign = 7;
								}

							}
							else
							{
								if($division_number < 5)
								{
									$new_sign = 8;
								}
								elseif($division_number < 10)
								{
									$new_sign = 10;
								}
								elseif($division_number < 18)
								{
									$new_sign = 12;
								}
								elseif($division_number < 25)
								{
									$new_sign = 6;
								}
								else
								{
									$new_sign = 2;
								}
							}
						};
						break;
				case 40:{
							if($sign_number % 2 == 1)
							{
								$new_sign = 1 + $division_number;

							}
							else
							{
								$new_sign = 7 + $division_number;
							}
						};
						break;
				case 45:{
							if(($sign_number -1)%3 == 0)
							{
								//moveable sign
								$new_sign = 1+$division_number;
							}
							elseif(($sign_number -2)%3 == 0)
							{
								//fixed sign
								$new_sign = 5+$division_number;
							}
							elseif(($sign_number)%3 == 0)
							{
								//dual sign
								$new_sign = 9+$division_number;
							}
						};
						break;
				case 60:{
							$new_sign = $sign_number + floor(($degree*2))%12;
						};
						break;
				}

				return $new_sign;

		}

	public function CreateDivisionalChart($chart_number)
		{
		
			if($chart_number == 1)
			{
						$this->_chart_planets = $this->_birth_planets;
						$this->_chart_houses = $this->_birth_houses;
			}

			else
			{

				$degree = $this->_birth_houses['ASC'][DivisionalChartMaker::DEGREE_STRING];
				$sign_number = $this->_birth_houses['ASC'][DivisionalChartMaker::SIGN_NUMBER_STRING];
				$division = 30/$chart_number;
				$division_number = floor($degree/$division);
				$new_sign = $this->GetNewSign($chart_number,$sign_number,$degree,$division_number);

				$new_sign = $new_sign%12;
				if($new_sign == 0)
					$new_sign = 12;

				$new_degree = ($degree - $division_number*$division)*$chart_number;
				$fulldegree = ($new_sign-1*30)+$new_degree;

				$this->_chart_houses['ASC'] = array(
					DivisionalChartMaker::FULLDEGREE_STRING => $fulldegree,
					DivisionalChartMaker::DEGREE_STRING => $new_degree,
					DivisionalChartMaker::SIGN_STRING => $this->_sign[$new_sign-1],
					DivisionalChartMaker::SIGN_NUMBER_STRING => $new_sign,
					DivisionalChartMaker::PLANET_STRING => array());

			
				for($i = 1;$i <= 12;$i++)
				{
					if($i > 1)
					$new_sign = ($new_sign+1)%12;
					
					if($new_sign == 0)
						$new_sign = 12;
				
					$navamsh_sign = $this->_sign[$navamsh_sign_number-1];


					$this->_chart_houses[$i] = array(
						DivisionalChartMaker::SIGN_STRING => $this->_sign[$new_sign-1],
						DivisionalChartMaker::SIGN_NUMBER_STRING => $new_sign,
						DivisionalChartMaker::PLANET_STRING => array());

				}

				$asc_sign_number = $this->_chart_houses['ASC'][DivisionalChartMaker::SIGN_NUMBER_STRING];


				foreach($this->_birth_zodiacs as $zodiac => $zodiac_data)
				{

					if( empty( $zodiac_data[DivisionalChartMaker::PLANET_STRING] ) )
						continue;

					foreach($zodiac_data[DivisionalChartMaker::PLANET_STRING] as $planet =>$planet_data)
					{
						$sign_number = $zodiac_data[DivisionalChartMaker::SIGN_NUMBER_STRING];
						$degree = $planet_data[DivisionalChartMaker::DEGREE_STRING];
						
						$division = 30/$chart_number;

						$division_number = floor($degree/$division);

						
						$new_sign = $this->GetNewSign($chart_number,$sign_number,$degree,$division_number);

						$new_sign = $new_sign%12;
						if($new_sign == 0)
							$new_sign = 12;

						$new_degree = ($degree - $division_number*$division)*$chart_number;
						$this->_chart_planets[$planet][DivisionalChartMaker::DEGREE_STRING] = $new_degree;
						$this->_chart_planets[$planet][DivisionalChartMaker::SIGN_NUMBER_STRING] = $new_sign;
						$this->_chart_planets[$planet][DivisionalChartMaker::SIGN_STRING] = $this->_sign[$new_sign-1];
						$fulldegree = ($new_sign-1*30)+$new_degree;
						$this->_chart_planets[$planet][DivisionalChartMaker::FULLDEGREE_STRING] = $fulldegree;


						$house_number = $new_sign-$asc_sign_number+1;

						if( $house_number < 1 )
							$house_number += 12;

						$this->_chart_houses[$house_number][DivisionalChartMaker::PLANET_STRING][$planet] = array(
							DivisionalChartMaker::FULLDEGREE_STRING => $fulldegree,
							DivisionalChartMaker::DEGREE_STRING => $degree,
							DivisionalChartMaker::SIGN_STRING => $this->_sign[$new_sign-1],
							DivisionalChartMaker::SIGN_NUMBER_STRING => $new_sign);

			
					}
				}



			}



		}




	}





?>