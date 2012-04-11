<?php
// Business tier class that manages customer accounts functionality
class IndexPage
{
	private $_Page = array();
	
	public function __construct()
	{
		if(isset($_SESSION['input']))
		{
			$this->_Page = $_SESSION['input'];
		}
	}

	public function __get($key)
	{
        if (array_key_exists($key, $this->_Page))
			echo $this->_Page[$key];
    }
	
	public function __set($key, $value)
	{
		$this->_Page[$key] = $value;
    }

	public function show_months( $m = '' )
	{
		$months = array("January",
						"February",
						"March",
						"April",
						"May",
						"June",
						"July",
						"August",
						"September",
						"October",
						"November",
						"December");
		if( !empty( $m ) )
			$selected = $m;

		echo '<select name="mm"><option value="0">--Month--</option>';

		for($i = 0; $i<12; $i++)
		{
			if( isset($selected) && $selected == $i+1 )
				echo '<option value="'. ($i+1) .'" selected="selected">' . $months[$i] . '</option>';
			else echo '<option value="' . ($i+1) . '">' . $months[$i] . '</option>';
		}
		echo '</select>';
	}

	public function show_days( $d = '' )
	{
		if( !empty( $d ) )
			$selected = $d;

		echo '<select name="dd"><option value="0">--Day--</option>';

		for($i = 1; $i<32; $i++)
		{
			if( isset($selected) && $selected == $i )
				echo '<option value="'. $i .'" selected="selected">' . $i . '</option>';
			else echo '<option value="' . $i . '">' . $i . '</option>';

		}
		echo '</select>';
	}

	public function show_hour( $hour = '' )
	{
		if( !empty( $hour ) )
			$selected = $hour;

		echo '<select name="hh"><option value="-1">--Hour--</option>';

		for($i = 0; $i<12; $i++)
		{
			if( isset($selected) && $selected == $i )
				echo '<option value="'. $i .'" selected="selected">' . $i . '</option>';
			else echo '<option value="' . $i . '">' . $i . '</option>';

		}
		echo '</select>';
	}

	public function show_am_pm( $ampm = '' )
	{
		if( !empty( $ampm ) )
			$selected = $ampm;

		echo '<select name="amORpm">';

		if( isset($selected) && $selected == 'am' )
			echo '<option value="am" selected="selected">AM</option>';
		else echo '<option value="am">AM</option>';

		if( isset($selected) && $selected == 'pm' )
			echo '<option value="pm" selected="selected">PM</option>';
		else echo '<option value="pm">PM</option>';

		echo '</select>';
	}

	public function show_minutes( $m = '' )
	{
		if( !empty( $m ) )
			$selected = $m;

		echo '<select name="min"><option value="-1">--Minutes--</option>';

		for($i = 0; $i<60; $i++)
		{
			if( isset($selected) && $selected == $i )
				echo '<option value="'. $i .'" selected="selected">' . $i . '</option>';
			else echo '<option value="' . $i . '">' . $i . '</option>';

		}
		echo '</select>';
	}

	public function show_country( $country = '' )
	{
		$countries = Atlas::GetCountries();
		
		if( !empty( $country ) )
			$selected = $country;
		else $selected = Atlas::GetCountryByIp();

		echo '<select name="country">';

		foreach($countries as $country)
		{
			if($country['country_code'] == $selected)
							echo '<option value="'. $country['country_code'] .'" selected="selected">' . $country['country_name'] . '</option>';
			else echo '<option value="'. $country['country_code'] .'">' . $country['country_name'] . '</option>';

		}
		echo '</select>';
	}
	public function show_country_in_profile( $name, $country = '' )
	{
		$countries = Atlas::GetCountries();
		
		if( !empty( $country ) )
			$selected = $country;
		else $selected = Atlas::GetCountryByIp();

		echo '<select name="' . $name . '" id="' . $name . '">';

		foreach($countries as $country)
		{
			if($country['country_code'] == $selected)
							echo '<option value="'. $country['country_code'] .'" selected="selected">' . $country['country_name'] . '</option>';
			else echo '<option value="'. $country['country_code'] .'">' . $country['country_name'] . '</option>';

		}
		echo '</select>';
	}

}?>
