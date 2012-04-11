<?php  
 class Transit
 {
	private $_planet;
	public function __construct($planets)
	{
		$this->_planet = $planets;
	}
	public function getTransit($p1, $p2)
	 {
		$angle = $this->mod2pi( $this->_planet[$p1]['fulldegree'] - $this->_planet[$p2]['fulldegree'] );
		if ( $angle > 180 ) $angle = 360 - $angle;
		//return round($angle);
		//echo $angle;
		return $this->isTransit($angle, $p1, $p2);
	 }
	 private function isTransit($angle, $p1, $p2)
	 {
		 // Currently only 0, 60, 90, 120 and 180 are acceptable

		 $orb = 0.1;
/*		 if($p1 == 'Moon' || $p2 == 'Moon')
		 {
			 $orb = 0.2;
		 }*/
		 $type = $angle/30;
		 $dec = $type - (int)$type;
		 $type = (int)$type;

		 if( $dec <= $orb )
		 {
			 if( $type == 0 || $type == 2 || $type == 3 || $type == 4 || $type == 6)
			 {
				return $type*30;
			 }
		 }
		 else if( $dec >= 1 - $orb )
		 {
			 $type = $type + 1;
			if( $type == 0 || $type == 2 || $type == 3 || $type == 4 || $type == 6)
			 {
				return $type*30;
			 }
	 
		 }
		 else return NULL; 

	 }
	public function mod2pi($angle)
	{
		$b = $angle/360.0;
		$a = 360.0*($b - $this->absFloor($b));
		if ($a < 0) $a = 360.0 + $a;
		return $a;
	}
	private	function absFloor($val)
	{
		if ($val >= 0.0) return floor($val);
		else return ceil($val);
	}
 }
?>