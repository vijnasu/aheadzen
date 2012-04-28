<?php
// Business tier class that analyzes a birth chart for many things

$path = dirname( __FILE__ );

class AnalyzeKutas
{
	private $_self;
	private $_partner;
	private $_male_planets;
	private $_female_planets;
	private $_male_nakshatra_index;
	private $_female_nakshatra_index;
	public $male_nakshatra;
	public $female_nakshatra;
	public $male_dosha;
	public $female_dosha;
	public $nadiKutaScore;
	public $male_moon_sign_lord;
	public $female_moon_sign_lord;
	public $rashiKutaScore;
	public $male_gana;
	public $female_gana;
	public $ganaKutaScore;
	public $m2f_moon_sign_lord_relationship;
	public $f2m_moon_sign_lord_relationship;
	public $grahaMaitriScore;
	public $male_yoni;
	public $female_yoni;
	public $male_yoni_sex;
	public $female_yoni_sex;
	public $yoniKutaScore;
	public $dinaRemainder;
	public $dinaKutaScore;
	public $male_rashi;
	public $female_rashi;
	public $vasyaKutaScore;
	public $male_varna;
	public $female_varna;
	public $varnaKutaScore;
	public $totalKutaScore;
	public $male_kuja_dosha;
	public $female_kuja_dosha;
	public $kujaDosha = 'fail';

	public function __construct($male_chart, $female_chart)
	{
		$this->_self = $male_chart;
		$this->_partner = $female_chart;
		$this->_male_planets = $this->_self->getPlanets();
		$this->_female_planets = $this->_partner->getPlanets();
		$this->male_moon_sign_lord = AstroData::$ZODIAC_SIGNS_LORD[$this->_male_planets['Moon']['sign']];
		$this->female_moon_sign_lord = AstroData::$ZODIAC_SIGNS_LORD[$this->_female_planets['Moon']['sign']];
	}
	public function prepareKutaReport() {
	       $this->calculateMoonNakshatras();
	       $this->calculateNadiKutaScore();
	       $this->calculateRashiKutaScore();
	       $this->calculateGanaKutaScore();
	       $this->calculateGrahaMaitriScore();
	       $this->calculateYoniKutaScore();
	       $this->calculateDinaKutaScore();
	       $this->calculateVasyaKutaScore();
	       $this->calculateVarnaKutaScore();
	       $this->calculateKujaDosha();

	       $this->totalKutaScore = $this->nadiKutaScore + $this->rashiKutaScore +
	       $this->ganaKutaScore + $this->grahaMaitriScore +
	       $this->yoniKutaScore + $this->dinaKutaScore +
	       $this->vasyaKutaScore + $this->varnaKutaScore;
	}
	private function calculateNadiKutaScore()
	{
		$this->male_dosha = $this->doshaFromNakshatraIndex( $this->_male_nakshatra_index );
		$this->female_dosha = $this->doshaFromNakshatraIndex( $this->_female_nakshatra_index );

		if ( $this->male_dosha == $this->female_dosha )
		   $this->nadiKutaScore = 0;
		else
		   $this->nadiKutaScore = 8;
	}
	private function doshaFromNakshatraIndex( $index )
	{
		switch ( $index ) {
		       case 2:
		       case 3:
		       case 8:
		       case 9:
		       case 14:
		       case 15:
		       case 20:
		       case 21:
		       case 26:
		       	    return "Kapha";
		       case 1:
		       case 4:
		       case 7:
		       case 10:
		       case 13:
		       case 16:
		       case 19:
		       case 22:
		       case 25:
		       	    return "Pitta";
		       case 0:
		       case 5:
		       case 6:
		       case 11:
		       case 12:
		       case 17:
		       case 18:
		       case 23:
		       case 24:
		       	    return "Vata";
		}
	}

	//TODO: Add exception that moon sign lords are in 7th house from each other, then award full points
	private function calculateRashiKutaScore()
	{
					$house_check = $this->inHouseRelativeTo( $this->_female_planets['Moon']['fulldegree'], $this->_male_planets['Moon']['fulldegree'] );

		if ($this->male_moon_sign_lord == $this->female_moon_sign_lord ||
		   $this->planetaryFriends($this->male_moon_sign_lord, $this->female_moon_sign_lord))
		{
			$this->rashiKutaScore = 7;
		}
		else
		{
			$diff = $this->deltaDegrees( $this->_female_planets['Moon']['fulldegree'], $this->_male_planets['Moon']['fulldegree'] );
		/*	$diff = abs( $this->_male_planets['Moon']['fulldegree'] -
			      	     $this->_female_planets['Moon']['fulldegree'] );
			if ( $diff > 180 )
			{
				$diff = 360 - $diff;
			}*/
			if ( $diff > 165 && !in_array( $house_check, array( 6, 8, 12 )  ) )
		  	{
				$this->rashiKutaScore = 7;
			}
			else
			{
				$this->rashiKutaScore = 0;
			}
		}
	}
	private function planetaryFriends( $planet_one, $planet_two )
	{
		return in_array( $planet_two, AstroData::$FRIENDSHIPS[$planet_one]['Friends'] ) ||
		       in_array( $planet_one, AstroData::$FRIENDSHIPS[$planet_two]['Friends'] );
	}
	private function planetaryRelationship( $planet_one, $planet_two )
	{
		if ( in_array( $planet_two, AstroData::$FRIENDSHIPS[$planet_one]['Friends'] ) )
		   return 'Friend';
		elseif ( in_array( $planet_two, AstroData::$FRIENDSHIPS[$planet_one]['Neutrals'] ) )
		   return 'Neutral';
		elseif ( in_array( $planet_two, AstroData::$FRIENDSHIPS[$planet_one]['Enemies'] ) )
		   return 'Enemy';
	}
	private function calculateGanaKutaScore()
	{
		$this->male_gana = AstroData::$GANA[$this->male_nakshatra];
		$this->female_gana = AstroData::$GANA[$this->female_nakshatra];

		if ( $this->male_gana == $this->female_gana )
		{
			$this->ganaKutaScore = 6;
		}
		else
		{
			$list = array( $this->male_gana, $this->female_gana );
			if ( in_array( 'Deva', $list ) &&
			     in_array( 'Rakshasa', $list ) )
			{
				$this->ganaKutaScore = 0;
			}
			else
			{
				$this->ganaKutaScore = 3;
			}
		}
	}
	private function calculateGrahaMaitriScore()
	{
		$this->m2f_moon_sign_lord_relationship = $this->planetaryRelationship($this->male_moon_sign_lord,
										      $this->female_moon_sign_lord);
		$this->f2m_moon_sign_lord_relationship = $this->planetaryRelationship($this->female_moon_sign_lord,
										      $this->male_moon_sign_lord);
		$relationship_list = array( $this->m2f_moon_sign_lord_relationship,
		      	       	     	    $this->f2m_moon_sign_lord_relationship );
		if ( $this->male_moon_sign_lord == $this->female_moon_sign_lord ||
		     $relationship_list == array( 'Friend', 'Friend' ) )
		{
			$this->grahaMaitriScore = 5;
		}
		elseif ( in_array( 'Friend', $relationship_list ) &&
		       	 in_array( 'Neutral', $relationship_list ) )
		{
			$this->grahaMaitriScore = 4;
		}
		elseif ( $relationship_list == array( 'Neutral', 'Neutral' ) )
		{
			$this->grahaMaitriScore = 3;
		}
		elseif ( in_array( 'Friend', $relationship_list ) &&
		       	 in_array( 'Enemy', $relationship_list ) )
		{
			$this->grahaMaitriScore = 2;
		}
		elseif ( in_array( 'Enemy', $relationship_list ) &&
		       	 in_array( 'Neutral', $relationship_list ) )
		{
			$this->grahaMaitriScore = 1;
		}
		elseif ( $relationship_list == array( 'Enemy', 'Enemy' ) )
		{
			$this->grahaMaitriScore = 0;
		}
	}
	private function calculateYoniKutaScore()
	{
		$this->male_yoni = AstroData::$YONI[$this->male_nakshatra];
		$this->male_yoni_sex = AstroData::$YONI_SEX[$this->male_nakshatra];
		$this->female_yoni = AstroData::$YONI[$this->female_nakshatra];
		$this->female_yoni_sex = AstroData::$YONI_SEX[$this->female_nakshatra];

		$yoni_list = array( $this->male_yoni, $this->female_yoni );
		$yoni_sex_list = array( $this->male_yoni_sex, $this->female_yoni_sex );

		if ( ( in_array( 'Cow', $yoni_list ) &&
		       in_array( 'Tiger', $yoni_list ) ) ||
		     ( in_array( 'Elephant', $yoni_list ) &&
		       in_array( 'Lion', $yoni_list ) ) ||
		     ( in_array( 'Horse', $yoni_list ) &&
		       in_array( 'Buffalo', $yoni_list ) ) ||
		     ( in_array( 'Dog', $yoni_list ) &&
		       in_array( 'Hare', $yoni_list ) ) ||
		     ( in_array( 'Serpent', $yoni_list ) &&
		       in_array( 'Mongoose', $yoni_list ) ) ||
		     ( in_array( 'Monkey', $yoni_list ) &&
		       in_array( 'Goat', $yoni_list ) ) ||
		     ( in_array( 'Cat', $yoni_list ) &&
		       in_array( 'Rat', $yoni_list ) ) )
		{
			$this->yoniKutaScore = 0;
		}
		elseif ( in_array( 'Male', $yoni_sex_list ) &&
		       	 in_array( 'Female', $yoni_sex_list ) )
		{
			$this->yoniKutaScore = 4;
		}
		else
		{
			$this->yoniKutaScore = 0;
		}
	}
	private function calculateDinaKutaScore()
	{
		$relative = abs( $this->_male_nakshatra_index - $this->_female_nakshatra_index );
		$this->dinaRemainder = $relative % 9;
		switch ($this->dinaRemainder)
		{
			case 3:
			case 5:
			case 7:
			     $this->dinaKutaScore = 0;
			     break;
			default:
			     $this->dinaKutaScore = 3;
		}		
	}
	private function calculateVasyaKutaScore()
	{
		$this->male_rashi = $this->_male_planets['Moon']['sign'];
		$this->female_rashi = $this->_female_planets['Moon']['sign'];
		if ( in_array( $this->female_rashi, AstroData::$VASYA_RASHI[$this->male_rashi] ) &&
		     in_array( $this->female_rashi, AstroData::$VASYA_RASHI[$this->male_rashi] ) )
		{
			$this->vasyaKutaScore = 2;
		}
		elseif ( in_array( $this->female_rashi, AstroData::$VASYA_RASHI[$this->male_rashi] ) ||
		       	 in_array( $this->female_rashi, AstroData::$VASYA_RASHI[$this->male_rashi] ) )
		{
			$this->vasyaKutaScore = 1;
		}
		else
		{
			$this->vasyaKutaScore = 0;
		}
	}
	private function calculateVarnaKutaScore()
	{
		$this->male_varna = AstroData::$VARNA[$this->male_nakshatra];
		$this->female_varna = AstroData::$VARNA[$this->female_nakshatra];
		$male_varna_number = $this->numberFromVarna($this->male_varna);
		$female_varna_number = $this->numberFromVarna($this->female_varna);
		if ($male_varna_number >= $female_varna_number)
		{
			$this->varnaKutaScore = 1;
		}
		else
		{
			$this->varnaKutaScore = 0;
		}
	}
	private function numberFromVarna( $varna )
	{
		switch ( $varna )
		{
			case 'Brahmin':
			     return 3;
			case 'Kshatriya':
			     return 2;
			case 'Vaisya':
			     return 1;
			case 'Sudra':
			default:
			     return 0;
		}
	}
	private function calculateKujaDosha()
	{
		$this->male_kuja_dosha = $this->kujaDoshaCheck( $this->_male_planets );
		$this->female_kuja_dosha = $this->kujaDoshaCheck( $this->_female_planets );
		if ($this->male_kuja_dosha == $this->female_kuja_dosha ||
		   True // Replace with Mars Auspicious Check.
		   )
		{
			$this->kujaDosha = 'pass';
		}
	}
	private function kujaDoshaCheck( $planets )
	{
		$mars = $planets['Mars']['house'];
		$moon = $planets['Moon']['house'];
		$diff = $mars - $moon + 1;
		if ( $diff < 1 )
		{
			$diff = 13 - $moon + $mars;
		}
		if ( ( $mars == 2 || $mars == 4 || $mars == 7 ||
		       $mars == 8 || $mars == 12 ) &&
		     ( $diff == 2 || $diff == 4 || $diff == 7 ||
		       $diff == 8 || $diff == 12 ) )
		{
			return "True";
		}
		else
		{
			return "False";
		}
	}
	// Adapted from today.php
	private function calculateMoonNakshatras()
	{
		$this->_male_nakshatra_index = floor( $this->_male_planets['Moon']['fulldegree']/AstroData::$NAKSHATRA_SIZE );
		$this->_female_nakshatra_index = floor( $this->_female_planets['Moon']['fulldegree']/AstroData::$NAKSHATRA_SIZE );
		$this->male_nakshatra = AstroData::$NAKSHATRA[$this->_male_nakshatra_index];
		$this->female_nakshatra = AstroData::$NAKSHATRA[$this->_female_nakshatra_index];
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
	private function inHouseRelativeTo( $ref, $transitPoint )
	{
		$deltaDegrees = $this->deltaDegrees( $ref, $transitPoint );
		$deltaHouse = (int)($deltaDegrees/30);
		$deltaHouse += 1;
		return $deltaHouse;
	}

}?>