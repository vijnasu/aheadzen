<?php  

class NorthernChartMaker extends ChartMaker
{

	const LINE_SPACE = 15;
	const WORD_GAP = 10;
	const PLANET_FONT_SIZE = 12;
	const RAASHI_FONT_SIZE = 10;
	const CONTENT_MAXWIDTH = 55;
	
	private $_sign_xcoord = array();
	private $_sign_ycoord = array();
	private $_center_xcoord = array();
	private $_center_ycoord = array();
	private $_slope;
	private $_settings;


	public function __construct(&$houses, $settings = array () )
	{
		if( empty( $settings ) )
		{
			$this->_settings = array();
			$this->_settings['BG_IMG'] = dirname(__FILE__).'/static/NorthernChart.png';
			$this->_settings['FONT'] = dirname(__FILE__).'/static/ARIAL.TTF';
		}

		$this->_houses = $houses;
		$this->_img = imagecreatefrompng( $this->_settings['BG_IMG'] );
		$this->_color['black'] = imagecolorallocate($this->_img, 0, 0, 0);
		$dim = getimagesize($this->_settings['BG_IMG']);
		$this->_width = $dim[0];	
		$this->_height = $dim[1];
		$this->_slope = $this->_height/$this->_width;
		
		$this->_sign_xcoord = array(
			$this->_width/2-5,
			$this->_width/4-5,
			$this->_width/4-22,
			$this->_width/2-22,
			$this->_width/4-22,
			$this->_width/4-5,
			$this->_width/2-5,
			3*$this->_width/4-5,
			3*$this->_width/4+15,
			$this->_width/2+15,
			3*$this->_width/4+15,
			3*$this->_width/4-5);
		
		$this->_sign_ycoord = array(
			$this->_height/2-7,
			$this->_height/4-7,
			$this->_height/4+5,
			$this->_height/2+5,
			3*$this->_height/4+5,
			3*$this->_height/4+18,
			$this->_height/2+18,
			3*$this->_height/4+18,
			3*$this->_height/4+5,
			$this->_height/2+5,
			$this->_height/4+5,
			$this->_height/4-7);

		$this->_center_xcoord = array(
			$this->_width/2,
			$this->_width/4,
			0,
			$this->_width/4,
			0,
			$this->_width/4,
			$this->_width/2,
			3*$this->_width/4,
			$this->_width,
			3*$this->_width/4,
			$this->_width,
			3*$this->_width/4
			);
		$this->_center_ycoord = array(
			$this->_height/4,
			1.35*NorthernChartMaker::LINE_SPACE,
			$this->_height/4,
			$this->_height/2,
			3*$this->_height/4,
			$this->_height,
			3*$this->_height/4,
			$this->_height,
			3*$this->_height/4,
			$this->_height/2,
			$this->_height/4,
			1.35*NorthernChartMaker::LINE_SPACE);

		$this->_signs = array(
			"aries",
			"taurus",
			"gemini",
			"cancer",
			"leo",
			"virgo",
			"libra",
			"scorpio",
			"sagittarius",
			"capricorn",
			"aquarius",
			"pisces");

	}


	protected function makeChart()
	{



			foreach($this->_houses as $house => $house_details)
			{
				if($house > 0)
				{

				$sign_coordinates[0] = $this->_sign_xcoord[$house-1];
				$sign_coordinates[1] = $this->_sign_ycoord[$house-1];


				if(array_key_exists('sign',$house_details))
				{
					$sign = $house_details["sign"];
				}
				else
				{
					//print "cannot proceed further";
					$sign = null;
				}

				if(array_key_exists('Planet',$house_details))
				{
					$planet = $house_details["Planet"];
				}
				else
				{
					//print "cannot proceed further";
					$sign = null;
				}


//same viewability if ($house-1)%3 == 0  ---1,4,7,10
//same for 2 and 12
//-1*that of 2 and 12   6,8
//same for 3,5,9,11
	
 /// the text version of signs overlap with each other,hence should be avoided
  //* imagestring($image, 2, $sign_coordinates[0], $sign_coordinates[1],$sign, $black); 
  
				imagettftext($this->_img, NorthernChartMaker::RAASHI_FONT_SIZE, 0, $sign_coordinates[0], $sign_coordinates[1], $this->_color['black'], $this->_settings['FONT'], array_search(strtolower($sign),$this->_signs)+1);
	
				$i = 1;


				$no_planets = count($planet);
				$planet_list = array_keys($planet);

	//display of planets

				if(($house-1)%3 == 0)
				{
					$x = $this->_center_xcoord[$house-1]-NorthernChartMaker::CONTENT_MAXWIDTH/2;
					$y =	$this->_center_ycoord[$house-1]-$this->_height/4+NorthernChartMaker::CONTENT_MAXWIDTH/2*$this->_slope+NorthernChartMaker::LINE_SPACE;
					$start_y = NorthernChartMaker::CONTENT_MAXWIDTH/2*$this->_slope;
					$i = 0;
	
					while($y < $this->_center_ycoord[$house-1]+$this->_height/4-$start_y-15 && $i < $no_planets)
					{
						imagettftext($this->_img, NorthernChartMaker::PLANET_FONT_SIZE, 0, $x, $y, $this->_color['black'], $this->_settings['FONT'], $planet_list[$i]);
						$y += NorthernChartMaker::LINE_SPACE;
						$i++;
					}
					
				}
				elseif($house == 2 || $house == 12)
				{	

					$i = 0;
					for($count = 0; $count < 3;$count++)
					{
						if($count == 0)
						{
							$x = $this->_center_xcoord[$house-1]-NorthernChartMaker::CONTENT_MAXWIDTH/2;
							$y = $this->_center_ycoord[$house-1];
							$start_y = NorthernChartMaker::CONTENT_MAXWIDTH/2*$this->_slope+NorthernChartMaker::LINE_SPACE;
						}
						elseif($count == 1)
						{
							$x = $x - NorthernChartMaker::CONTENT_MAXWIDTH - NorthernChartMaker::WORD_GAP;
							$y = $this->_center_ycoord[$house-1];
							$start_y = (3*NorthernChartMaker::CONTENT_MAXWIDTH/2 + NorthernChartMaker::WORD_GAP)*$this->_slope;
						}
						elseif($count == 2)
						{
							$x = $this->_center_xcoord[$house-1] + NorthernChartMaker::CONTENT_MAXWIDTH/2 + NorthernChartMaker::WORD_GAP;
							$y = $this->_center_ycoord[$house-1];
							$start_y = (3*NorthernChartMaker::CONTENT_MAXWIDTH/2 + NorthernChartMaker::WORD_GAP)*$this->_slope;
						}

						while($y < $this->_center_ycoord[$house-1]+$this->_height/4-$start_y && $i < $no_planets)
						{

							imagettftext($this->_img, NorthernChartMaker::PLANET_FONT_SIZE, 0, $x, $y, $this->_color['black'], $this->_settings['FONT'], $planet_list[$i]);
							$y += NorthernChartMaker::LINE_SPACE;
							$i++;
						}

						if($i == $no_planets)
							break;

					}
				}

				elseif($house == 6 || $house == 8)
				{

					$i = 0;

					for($count = 0;$count < 3;$count++)
					{
						$start_y = 0;
						if($count == 0)
						{
							$x = $this->_center_xcoord[$house-1] - NorthernChartMaker::CONTENT_MAXWIDTH/2;
							$y = $this->_center_ycoord[$house-1]-$this->_height/4+$this->_slope*NorthernChartMaker::CONTENT_MAXWIDTH/2+8;
						}
						elseif($count == 1)
						{
							$x = $x - NorthernChartMaker::CONTENT_MAXWIDTH - NorthernChartMaker::WORD_GAP;
							$y = $this->_center_ycoord[$house-1]-$this->_height/4+$this->_slope*(3*NorthernChartMaker::CONTENT_MAXWIDTH/2 + NorthernChartMaker::WORD_GAP);
						}
						elseif($count == 2)
						{
							$x = $this->_center_xcoord[$house-1] + NorthernChartMaker::CONTENT_MAXWIDTH/2 + NorthernChartMaker::WORD_GAP;
							$y = $this->_center_ycoord[$house-1]-$this->_height/4+$this->_slope*(3*NorthernChartMaker::CONTENT_MAXWIDTH/2 + NorthernChartMaker::WORD_GAP);
						}

						while($y < $this->_center_ycoord[$house-1]-$start_y && $i < $no_planets)
						{
							imagettftext($this->_img, NorthernChartMaker::PLANET_FONT_SIZE, 0, $x, $y, $this->_color['black'], $this->_settings['FONT'], $planet_list[$i]);
							$y += NorthernChartMaker::LINE_SPACE;
							$i++;
						}
					
						if($i == $no_planets)
							break;

					}
				}


				elseif($house == 3 || $house == 5)
				{

					$x = $this->_center_xcoord[$house-1]+NorthernChartMaker::WORD_GAP;
					$y = $this->_center_ycoord[$house-1]-$this->_height/4+$this->_slope*(NorthernChartMaker::CONTENT_MAXWIDTH+NorthernChartMaker::WORD_GAP);
					
					$start_y = $this->_slope*NorthernChartMaker::CONTENT_MAXWIDTH;
					
					$i = 0;

					while($y < $this->_center_ycoord[$house-1]+$this->_height/4 && $i < $no_planets)
					{
						imagettftext($this->_img, NorthernChartMaker::PLANET_FONT_SIZE, 0, $x, $y, $this->_color['black'], $this->_settings['FONT'], $planet_list[$i]);
						$y += NorthernChartMaker::LINE_SPACE;
						$i++;
					}
				}
				else
				{
					$x = $this->_center_xcoord[$house-1]-NorthernChartMaker::CONTENT_MAXWIDTH-NorthernChartMaker::WORD_GAP+2;
					$y = $this->_center_ycoord[$house-1]-$this->_height/4+$this->_slope*(NorthernChartMaker::CONTENT_MAXWIDTH+NorthernChartMaker::LINE_SPACE);
					$start_y = $this->_slope*NorthernChartMaker::CONTENT_MAXWIDTH-NorthernChartMaker::LINE_SPACE;
					$i = 0;
					while($y < $this->_center_ycoord[$house-1]+$this->_height/4 && $i < $no_planets)
					{
						imagettftext($this->_img, NorthernChartMaker::PLANET_FONT_SIZE, 0, $x, $y, $this->_color['black'], $this->_settings['FONT'], $planet_list[$i]);

						$y += NorthernChartMaker::LINE_SPACE;
						$i++;
					}}
				}
			}
		}

		public function getChart()
		{

			header('content-type: image/png');
			$this->makeChart();
			imagepng($this->_img);
 
			//Clear up memory 
			imagedestroy($this->_img);
		}
		public function saveChart( $filename, $path )
		{
			$file_path_name = $path . '/' . $filename; 

//			header('content-type: image/png');
			$this->makeChart();
			imagepng($this->_img, $file_path_name);
 
			//Clear up memory 
			imagedestroy($this->_img);
		}
	}



$input = array(
				1=>	array(
				"sign" => "Libra",
				"Planet"=> array(
				"Neptune"=>1,
				"Mercury"=>2,
				"Ketu"=>3,
				"Saturn"=>4,
				"Uranus"=>5,
				"Jupiter"=>6,
				"Venus"=>7,
				"Moon"=>8,
				"Rahu"=>9)
				),
				2=>	array(
				"sign" => "Libra",
				"Planet"=> array(
				"Neptune"=>1,
				"Mercury"=>2,
				"Ketu"=>3,
				"Saturn"=>4,
				"Uranus"=>5,
				"Jupiter"=>6,
				"Venus"=>7,
				"Moon"=>8,
				"Rahu"=>9)
				),
				3=>	array(
				"sign" => "Libra",
				"Planet"=> array(
				"Neptune"=>1,
				"Mercury"=>2,
				"Ketu"=>3,
				"Saturn"=>4,
				"Uranus"=>5,
				"Jupiter"=>6,
				"Venus"=>7,
				"Moon"=>8,
				"Rahu"=>9)
				),
				4=>	array(
				"sign" => "Libra",
				"Planet"=> array(
				"Neptune"=>1,
				"Mercury"=>2,
				"Ketu"=>3,
				"Saturn"=>4,
				"Uranus"=>5,
				"Jupiter"=>6,
				"Venus"=>7,
				"Moon"=>8,
				"Rahu"=>9)
				),
				5=>	array(
				"sign" => "Libra",
				"Planet"=> array(
				"Neptune"=>1,
				"Mercury"=>2,
				"Ketu"=>3,
				"Saturn"=>4,
				"Uranus"=>5,
				"Jupiter"=>6,
				"Venus"=>7,
				"Moon"=>8,
				"Rahu"=>9)
				),
				6=>	array(
				"sign" => "Libra",
				"Planet"=> array(
				"Neptune"=>1,
				"Mercury"=>2,
				"Ketu"=>3,
				"Saturn"=>4,
				"Uranus"=>5,
				"Jupiter"=>6,
				"Venus"=>7,
				"Moon"=>8,
				"Rahu"=>9)
				),
				7=>	array(
				"sign" => "Libra",
				"Planet"=> array(
				"Neptune"=>1,
				"Mercury"=>2,
				"Ketu"=>3,
				"Saturn"=>4,
				"Uranus"=>5,
				"Jupiter"=>6,
				"Venus"=>7,
				"Moon"=>8,
				"Rahu"=>9)
				),
				8=>	array(
				"sign" => "Libra",
				"Planet"=> array(
				"Neptune"=>1,
				"Mercury"=>2,
				"Ketu"=>3,
				"Saturn"=>4,
				"Uranus"=>5,
				"Jupiter"=>6,
				"Venus"=>7,
				"Moon"=>8,
				"Rahu"=>9)
				),
				9=>	array(
				"sign" => "Libra",
				"Planet"=> array(
				"Neptune"=>1,
				"Mercury"=>2,
				"Ketu"=>3,
				"Saturn"=>4,
				"Uranus"=>5,
				"Jupiter"=>6,
				"Venus"=>7,
				"Moon"=>8,
				"Rahu"=>9)
				),
				10=>	array(
				"sign" => "Libra",
				"Planet"=> array(
				"Neptune"=>1,
				"Mercury"=>2,
				"Ketu"=>3,
				"Saturn"=>4,
				"Uranus"=>5,
				"Jupiter"=>6,
				"Venus"=>7,
				"Moon"=>8,
				"Rahu"=>9)
				),
				11=>	array(
				"sign" => "Libra",
				"Planet"=> array(
				"Neptune"=>1,
				"Mercury"=>2,
				"Ketu"=>3,
				"Saturn"=>4,
				"Uranus"=>5,
				"Jupiter"=>6,
				"Venus"=>7,
				"Moon"=>8,
				"Rahu"=>9)
				),
				12=>	array(
				"sign" => "Libra",
				"Planet"=> array(
				"Neptune"=>1,
				"Mercury"=>2,
				"Ketu"=>3,
				"Saturn"=>4,
				"Uranus"=>5,
				"Jupiter"=>6,
				"Venus"=>7,
				"Moon"=>8,
				"Rahu"=>9)
				)
				);
//$data = file_get_contents( "array2.txt" );
//$houses = unserialize( $data ); 
//$maker = new NorthernChartMaker($houses);
//$maker->saveChart('aaa.png', '../');


?>