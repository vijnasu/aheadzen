<?php  

include 'ChartMaker.php';

class WesternChartMaker extends ChartMaker
{

	const BG_WESTERN = 'images/westernchart.png';
	const FONT_SIZE = 18;
	const FONT = "AstroGadget.ttf";
	const ANGLE_DIFF = 9;
	const TEXT_FONT_SIZE = 12;
	const TEXT_FONT = "C:\WINXP\Fonts\Arial.ttf";
	
	private	$_sign_radius;
	private $_planet_radius1;
	private $_planet_radius2;
	private $_planet_radius3;
	private $_center = array();	
	private $_char_map = array();
	private $_sign_xcoeff = array();
	private $_sign_ycoeff = array();

	public function __construct(&$houses)
	{
		$this->_houses = $houses;
		$this->_img = imagecreatefrompng( WesternChartMaker::BG_WESTERN);
		$this->_color['black'] = imagecolorallocate($this->_img, 0, 0, 0);
		$dim = getimagesize(WesternChartMaker::BG_WESTERN);
		$this->_width = $dim[0];	
		$this->_height = $dim[1];
		$this->_center[0] = $this->_width/2-10;
		$this->_center[1] = $this->_height/2+7;
		$this->_sign_radius = 0.9*$this->_width/2;
		$this->_planet_radius1 = 0.77*$this->_width/2;
		$this->_planet_radius2 = 0.64*$this->_width/2;
		$this->_planet_radius3 = 0.51*$this->_width/2;

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

		$this->_char_map = array(
		"aries" => "a",
		"taurus" => "b",
		"gemini" => "c",
		"cancer" => "d",
		"leo" => "e",
		"virgo" => "f",
		"libra" => "g",
		"scorpio" => "h",
		"sagittarius" => "i",
		"capricorn" => "j",
		"aquarius" => "k",
		"pisces" => "l",
		"sun" => "A",
		"moon" => "B",
		"mercury" => "C",
		"venus" => "D",
		"mars" => "E",
		"jupiter" => "F",
		"saturn" => "G",
		"uranus" => "H",
		"neptune" => "I",
		"pluto" => "K",
		"rahu" =>"L",
		"ketu" => "M"
		);	
		
		$this->_sign_xcoeff = array(
		-0.96,
		-0.71,
		-0.26,
		0.26,
		0.71,
		0.96,
		0.96,
		0.71,
		0.26,
		-0.26,
		-0.71,
		-0.96);

		$this->_sign_ycoeff = array(
		0.26,
		0.71,
		0.96,
		0.96,
		0.71,
		0.26,
		-0.26,
		-0.71,
		-0.96,
		-0.96,
		-0.71,
		-0.26);

	}

	protected function makeChart()
	{

			foreach($this->_houses as $house => $house_details)
			{
				if($house > 0)
				{

					if(array_key_exists('sign',$house_details))
					{
						$sign = $house_details["sign"];
					}
					else
					{
						$sign = null;
					}

					if(array_key_exists('Planet',$house_details))
					{
						$planet = $house_details["Planet"];
					}
					else
					{
						$sign = null;
					}

					$i = 1;
					$no_planets = count($planet);
					$planet_list = array_keys($planet);

					$x = $this->_center[0]+$this->_sign_xcoeff[$house-1]*$this->_sign_radius;
					$y = $this->_center[1]+$this->_sign_ycoeff[$house-1]*$this->_sign_radius;

					$char = $this->_char_map[strtolower($sign)];

					imagettftext($this->_img,WesternChartMaker::FONT_SIZE, 0, $x, $y, $this->_color['black'],WesternChartMaker::FONT, $char);

					$i = 0;
					$start = 5;
					$final_degree = 30;
					$offset = ($house-1)*30;

					for($count = 0;$count < 3;$count++)
					{
						$theta = $start;
						if($count == 0)
							$radius = $this->_planet_radius1;
						if($count == 1)
							$radius = $this->_planet_radius2;
						if($count == 2)
							$radius = $this->_planet_radius3;
					
						while($theta < $final_degree-$start && $i < $no_planets)
						{
							$x = $this->_center[0]-cos(deg2rad($offset+$theta))*$radius;
							$y = $this->_center[1]+sin(deg2rad($offset+$theta))*$radius;

							$char = $this->_char_map[strtolower($planet_list[$i])];
							imagettftext($this->_img,WesternChartMaker::FONT_SIZE, 0, $x, $y, $this->_color['black'], WesternChartMaker::FONT, $char);
							$i++;
							$theta +=WesternChartMaker::ANGLE_DIFF;
						}

						if($i == $no_planets)
							break;
					}
				}
			}
	}

	protected function makeTextChart()
	{

			foreach($this->_houses as $house => $house_details)
			{
				if($house > 0)
				{

					if(array_key_exists('sign',$house_details))
					{
						$sign = $house_details["sign"];
					}
					else
					{
						$sign = null;
					}

					if(array_key_exists('Planet',$house_details))
					{
						$planet = $house_details["Planet"];
					}
					else
					{
						$sign = null;
					}

					$i = 1;
					$no_planets = count($planet);
					$planet_list = array_keys($planet);

					$x = $this->_center[0]+$this->_sign_xcoeff[$house-1]*$this->_sign_radius;
					$y = $this->_center[1]+$this->_sign_ycoeff[$house-1]*$this->_sign_radius;

					$char = $this->_char_map[strtolower($sign)];

					imagettftext($this->_img,WesternChartMaker::FONT_SIZE, 0, $x, $y, $this->_color['black'],WesternChartMaker::FONT, $char);

			
				$i = 0;
				$start = 4;
				$final_degree = 30;
				$offset = ($house-1)*30;
				for($count = 0;$count < 3;$count++)
				{				
					
					if($count == 0)
					{
						$radius = $this->_planet_radius1;
						$diff = 4.5;
						$theta = $start;
					}
					if($count == 1)
					{
						$radius = $this->_planet_radius2-30;
						$diff = 8;
						$theta = $start+3;
					}
					if($count == 2)
						$radius = $this->_planet_radius3;
					
					while($theta < $final_degree && $i < $no_planets)
					{
						{
							$x = $this->_center[0]-cos(deg2rad($offset+$theta))*$radius+7;
							$y = $this->_center[1]+sin(deg2rad($offset+$theta))*$radius-5;

							$char = $this->_char_map[strtolower($planet_list[$i])];
							imagettftext($this->_img,WesternChartMaker::TEXT_FONT_SIZE,$offset+$theta-5 , $x, $y, $this->_color['black'], WesternChartMaker::TEXT_FONT	, $planet_list[$i]);
							$i++;
							$theta += $diff;
						}
					}
					if($i == $no_planets)
						break;
				}
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

public function getTextChart()
	{
			header('content-type: image/png');
			$this->makeTextChart();
			imagepng($this->_img);
 
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
$data = file_get_contents( "array2.txt" );
$houses = unserialize( $data ); 
$maker = new WesternChartMaker($houses);
$maker->getChart();


?>