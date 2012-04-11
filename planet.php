<?php  
class Planet
{
	private $sun;
	private $moon;
	private $mercury;
	private $venus;
	private $mars;
	private $jupiter;
	private $saturn;
	private $uranus;
	private $neptune;
	private $pluto;
	private $rahu;
	private $ketu;
	private $ayanansh;

	public function __construct($d, $ayanansh = 0)
	{
		//$d = (GregorianToJD(4, 20, 1990) - 0.5)  - (GregorianToJD(1, 1, 2000) - 0.5);
		
		$oblecl = 23.4393 - 3.563*pow(10, -7)*$d;

		$this->ayanansh = $ayanansh;

		$this->sun = new Orbit();
		$this->sun->N = 0.0;
		$this->sun->i = 0.0;
		$this->sun->w = 282.9404 + 4.70935E-5*$d;
		$this->sun->M = 356.0470 + 0.9856002585*$d;
		$this->sun->L = $this->sun->w + $this->sun->M;
		$this->sun->e = 0.016709 - 1.151E-9*$d;
		$this->sun->a = 1.0;
		$this->sun->mod2pi();
		$this->sun->setRadian();
		
		$this->moon = new Orbit();
		$this->moon->N = 125.1228 - 0.0529538083*$d;
		$this->moon->i = 5.1454;
		$this->moon->w = 318.0634 + 0.1643573223*$d;
		$this->moon->M = 115.3654 + 13.0649929509*$d;
		$this->moon->e = 0.054900;
		$this->moon->a = 60.2666;
		$this->moon->mod2pi();
		$this->moon->setRadian();

		$this->mercury = new Orbit();
		$this->mercury->N = 48.3313 + 3.24587E-5*$d;
		$this->mercury->i = 7.0047 + 5.00E-8*$d;
		$this->mercury->w = 29.1241 + 1.01444E-5*$d;
		$this->mercury->M = 168.6562 + 4.0923344368*$d;
		$this->mercury->L = $this->mercury->w + $this->mercury->M;
		$this->mercury->e = 0.205635 - 5.59E-10*$d;
		$this->mercury->a = 0.387098;
		$this->mercury->mod2pi();
		$this->mercury->setRadian();
		
		$this->venus = new Orbit();
		$this->venus->N = 76.6799 + 2.46590E-5*$d;
		$this->venus->i = 3.3946 + 2.75E-8*$d;
		$this->venus->w = 54.8910 + 1.38374E-5*$d;
		$this->venus->M = 48.0052 + 1.6021302244*$d;
		$this->venus->L = $this->venus->w + $this->venus->M;
		$this->venus->e = 0.006773 - 1.302E-9*$d;
		$this->venus->a = 0.723330;
		$this->venus->mod2pi();
		$this->venus->setRadian();

		$this->mars = new Orbit();
		$this->mars->N = 49.5574 + 2.11081E-5*$d;
		$this->mars->i = 1.8497 - 1.78E-8*$d;
		$this->mars->w = 286.5016 + 2.92961E-5*$d;
		$this->mars->M = 18.6021 + 0.5240207766*$d;
		$this->mars->L = $this->mars->w + $this->mars->M;
		$this->mars->e = 0.093405 + 2.516E-9*$d;
		$this->mars->a = 1.523688;
		$this->mars->mod2pi();
		$this->mars->setRadian();
		
		$this->jupiter = new Orbit();
		$this->jupiter->N = 100.4542 + 2.76854E-5*$d;
		$this->jupiter->i = 1.3030 - 1.557E-7*$d;
		$this->jupiter->w = 273.8777 + 1.64505E-5*$d;
		$this->jupiter->M = 19.8950 + 0.0830853001*$d;
		$this->jupiter->L = $this->jupiter->w + $this->jupiter->M;
		$this->jupiter->e = 0.048498 + 4.469E-9*$d;
		$this->jupiter->a = 5.20256;
		$this->jupiter->mod2pi();
		$this->jupiter->setRadian();

		$this->saturn = new Orbit();
		$this->saturn->N = 113.6634 + 2.38980E-5*$d;
		$this->saturn->i = 2.4886 - 1.081E-7*$d;
		$this->saturn->w = 339.3939 + 2.97661E-5*$d;
		$this->saturn->M = 316.9670 + 0.0334442282*$d;
		$this->saturn->L = $this->saturn->w + $this->saturn->M;
		$this->saturn->e = 0.055546 - 9.499E-9*$d;
		$this->saturn->a = 9.55475;
		$this->saturn->mod2pi();
		$this->saturn->setRadian();

		$this->uranus = new Orbit();
		$this->uranus->N = 74.0005 + 1.3978E-5*$d;
		$this->uranus->i = 0.7733 + 1.9E-8*$d;
		$this->uranus->w = 96.6612 + 3.0565E-5*$d;
		$this->uranus->M = 142.5905 + 0.011725806*$d;
		$this->uranus->L = $this->uranus->w + $this->uranus->M;
		$this->uranus->e = 0.047318 + 7.45E-9*$d;
		$this->uranus->a = 19.18171 - 1.55E-8*$d;
		$this->uranus->mod2pi();
		$this->uranus->setRadian();


		$this->neptune = new Orbit();
		$this->neptune->N = 131.7806 + 3.0173E-5*$d;
		$this->neptune->i = 1.7700 - 2.55E-7*$d;
		$this->neptune->w = 272.8461 - 6.027E-6*$d;
		$this->neptune->M = 260.2471 + 0.005995147*$d;
		$this->neptune->L = $this->neptune->w + $this->neptune->M;
		$this->neptune->e = 0.008606 + 2.15E-9*$d;
		$this->neptune->a = 30.05826 + 3.313E-8*$d;
		$this->neptune->mod2pi();
		$this->neptune->setRadian();

		$this->pluto = new Orbit();
		$this->rahu = new Orbit();
		$this->ketu = new Orbit();

		foreach($this as $key => $value)
		{
			switch($key)
			{
				case 'ayanansh':
					break;
				case 'sun':
					$this->calcSun();
					break;
				case 'moon':
					$this->calcMoon();
					break;
				case 'pluto':
					$this->calcPluto($d);
					break;
				case 'rahu':
					$this->rahu->longitude = $this->mod2piRadian( $this->moon->N - $this->ayanansh );
					break;
				case 'ketu':
					$this->ketu->longitude = $this->mod2piRadian( $this->moon->N - pi()  - $this->ayanansh );
					break;
				default:
					$this->calcPlanet($key);
					break;
					
			}
		}

		$this->sun->longitude = $this->mod2piRadian( $this->sun->longitude - $this->ayanansh );
	}
	
	public function __get($key)
	{
        return $this->{strtolower($key)}->getLongitude();
    }

	private function calcSun()
	{
		$this->anamolyE($this->sun);
		
		$this->sun->x = $this->getX($this->sun);
		$this->sun->y = $this->getY($this->sun);
		
		$this->sun->r = $this->get_r($this->sun);
		$this->sun->v = $this->mod2piRadian($this->get_v($this->sun));
	
		$this->sun->longitude = $this->mod2piRadian( $this->sun->v + $this->sun->w );
		
		$this->sun->xeclip = $this->sun->r * cos($this->sun->longitude);
		$this->sun->yeclip = $this->sun->r * sin($this->sun->longitude);
		$this->sun->zeclip = 0.0;
	}

	private function calcMoon()
	{
		$this->anamolyE($this->moon);
		
		$this->moon->x = $this->getX($this->moon);
		$this->moon->y = $this->getY($this->moon);
		
		$this->moon->r = $this->get_r($this->moon);
		$this->moon->v = $this->mod2piRadian($this->get_v($this->moon));
		
		$this->moon->xeclip = $this->get_xeclip($this->moon);
		$this->moon->yeclip = $this->get_yeclip($this->moon);
		$this->moon->zeclip = $this->get_zeclip($this->moon);
		
		$this->moon->longitude = $this->mod2piRadian( atan2($this->moon->yeclip, $this->moon->xeclip) - $this->ayanansh );
		
		//Tackle Perturbations    
		$Ls = $this->sun->longitude;
		$Lm = $this->moon->N + $this->moon->w + $this->moon->M;
		$Ms = $this->sun->M;
		$Mm = $this->moon->M;
		$D = $Lm - $Ls;
		$F = $Lm - $N;
		
		$perturbations = -1.274 * sin($Mm - 2*$D) + .658* sin(2*$D) - 0.186*sin($Ms);
		$this->moon->longitude = deg2rad($perturbations) + $this->moon->longitude;
	}

	private function calcPluto($d)
	{
		$S  =   deg2rad(50.03  +  0.033459652*$d);
		$P  =  deg2rad(238.95  +  0.003968789*$d);
		$long_ecl = deg2rad( 238.9508  +  0.00400703*$d
					- 19.799 * sin($P)     + 19.848 * cos($P)
					+ 0.897 * sin(2*$P)    - 4.956 * cos(2*$P)
					+ 0.610 * sin(3*$P)    + 1.211 * cos(3*$P)
					- 0.341 * sin(4*$P)    - 0.190 * cos(4*$P)
					+ 0.128 * sin(5*$P)    - 0.034 * cos(5*$P)
					- 0.038 * sin(6*$P)    + 0.031 * cos(6*$P)
					+ 0.020 * sin($S-$P)    - 0.010 * cos($S-$P) );

		$lat_ecl =  deg2rad( -3.9082 - 5.453 * sin($P)     - 14.975 * cos($P)
					+ 3.527 * sin(2*$P)    + 1.673 * cos(2*$P)
					- 1.051 * sin(3*$P)    + 0.328 * cos(3*$P)
					+ 0.179 * sin(4*$P)    - 0.292 * cos(4*$P)
					+ 0.019 * sin(5*$P)    + 0.100 * cos(5*$P)
					- 0.031 * sin(6*$P)    - 0.026 * cos(6*$P)
                    + 0.011 * cos($S-$P) );

		$this->pluto->r = 40.72 + 6.68 * sin($P)       + 6.90 * cos($P)
						  - 1.18 * sin(2*$P)     - 0.03 * cos(2*$P)
						  + 0.15 * sin(3*$P)     - 0.14 * cos(3*$P);

		$this->pluto->xeclip = $this->pluto->r*cos($long_ecl) * cos($lat_ecl);
		$this->pluto->yeclip = $this->pluto->r*sin($long_ecl) * cos($lat_ecl);
		$this->pluto->zeclip = $this->pluto->r*sin($lat_ecl);
		
		$y = $this->pluto->yeclip + $this->sun->yeclip;
		$x = $this->pluto->xeclip + $this->sun->xeclip;
		
		$this->pluto->longitude = $this->mod2piRadian( atan2($y, $x) - $this->ayanansh );
	}

	private function calcPlanet($var)
	{
		$this->anamolyE($this->{$var});
		
		$this->{$var}->x = $this->getX($this->{$var});
		$this->{$var}->y = $this->getY($this->{$var});
		
		$this->{$var}->r = $this->get_r($this->{$var});
		$this->{$var}->v = $this->mod2piRadian($this->get_v($this->{$var}));
		
		$this->{$var}->xeclip = $this->get_xeclip($this->{$var});
		$this->{$var}->yeclip = $this->get_yeclip($this->{$var});
		$this->{$var}->zeclip = $this->get_zeclip($this->{$var});
		
		$y = $this->{$var}->yeclip + $this->sun->yeclip;
		$x = $this->{$var}->xeclip + $this->sun->xeclip;
		
		$this->{$var}->longitude = $this->mod2piRadian( atan2($y, $x) - $this->ayanansh );
	}

	
	private function getX($var)
	{
		return $var->a*(cos($var->E) - $var->e);
	}
	private function getY($var)
	{
		return $var->a*sin($var->E)*sqrt(1 - $var->e*$var->e );
	}
	private function get_r($var)
	{
		return sqrt($var->x*$var->x + $var->y*$var->y);
	}
	private function get_v($var)
	{
		return atan2($var->y, $var->x);
	}
	private function get_xeclip($var)
	{
		return $var->r * ( cos($var->N) * cos($var->v + $var->w) - sin($var->N) * sin($var->v + $var->w) * cos($var->i) );
	}
	private function get_yeclip($var)
	{
		return $var->r * ( sin($var->N) * cos($var->v + $var->w) + cos($var->N) * sin($var->v + $var->w) * cos($var->i) );
	}
	private function get_zeclip($var)
	{
		return $var->r * sin($var->v+$var->w) * sin($var->i);
	}

	private	function mod2pi(&$angle)
	{
		$b = $angle/360.0;
		$a = 360.0*($b - $this->absFloor($b));
		if ($a < 0) $a = 360.0 + $a;
		$angle = $a;
	}
	
	private	function mod2piRadian($angle)
	{	
		$value = deg2rad(360);
		$b = $angle/$value;
		$a = $value*($b - $this->absFloor($b));
		if ($a < 0) $a = $value + $a;
		return $a;
	}
	
	private	function absFloor($val)
	{
		if ($val >= 0.0) return floor($val);
		else return ceil($val);
	}
	
	private function anamolyE(&$var, $value = null)
	{
		if(is_null($value))
		{
			$E0 = $var->M + $var->e*sin($var->M)*(1 + $var->e*cos($var->M) ) ;
			$this->anamolyE($var, $E0);
		}
		else 
		{
			$E0 = $value - ($value - $var->e*sin($value) - $var->M )/( 1 - $var->e*cos($value));
			if(abs($E0 - $value) > 0.0005)
				$this->anamolyE($var, $E0);

			else 
				$var->E = $this->mod2piRadian($E0);
		}
	}
}
?>