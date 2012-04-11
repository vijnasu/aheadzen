<?php  

abstract class ChartMaker
{

	private $_color = array();
	private $_img;
	private $_houses;
	private $_width;
	private $_height;
	private $_signs = array();

	public function __construct(&$houses)
	{
		$this->_houses = $houses;
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

	abstract protected function makeChart();

	abstract public function getChart();

	}

?>