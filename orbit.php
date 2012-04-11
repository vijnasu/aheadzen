<?php  
 class Orbit
 {		
 	private $N;
 	private $i;
 	private $w;
 	private $M;
 	private $L;
 	private $e;
 	private $a;
 	private $x;
 	private $y;
 	private $r;
 	private $v;
 	private $E;
 	private $xeclip;
 	private $yeclip;
 	private $zeclip;
 	private $longitude;
	private $is_radian = false; 

	public function __get($key)
	{
        return $this->{$key};
    }
	
	public function __set($key, $value)
	{
		$this->{$key} = $value;
    }
	public function mod2pi($var = null)
	{
		if(is_null($var))
		{
			foreach($this as $key => $value)
			{
				$this->{$key} = $this->_mod2pi($value);
			}
		}
		else $this->{$var} = $this->_mod2pi($this->{$var});
	}
	public function getLongitude()
	{
		return rad2deg($this->longitude);
	}
	public function setRadian($var = null)
	{
		if(!$this->is_radian)
		{
			foreach($this as $key => $value)
			{
				switch ($key)
				{
					case 'e':
					case 'a':
					case 'is_radian':
					case 'x':
					case 'y':
					case 'r':
					case 'E':
						break;
					default:
						$this->{$key} = deg2rad($value);
					
				}
			}
			$this->is_radian = true;
		}
	}
	private	function absFloor($val)
	{
		if ($val >= 0.0) return floor($val);
		else return ceil($val);
	}
	
	private function _mod2pi($angle)
	{
		$b = $angle/360.0;
		$a = 360.0*($b - $this->absFloor($b));
		if ($a < 0) $a = 360.0 + $a;
		return $a;
	}
 }
?>