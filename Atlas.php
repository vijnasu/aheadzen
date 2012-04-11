<?php
// Business tier class that manages Atlas functionality
class Atlas
{
  // Returns customer_id and password for customer with email $email
  public static function GetCountries()
  {
	  global $wpdb;

	// Execute the query
    return $wpdb->get_results('SELECT * FROM country ORDER BY country_name', ARRAY_A);
  }

  public static function GetCountryByIp()
  {
	global $wpdb;
	$clientIP = $_SERVER['REMOTE_ADDR'];

	// Execute the query
	return $wpdb->get_var("SELECT i.country FROM ip2nation i WHERE i.ip < INET_ATON('$clientIP') ORDER BY i.ip DESC LIMIT 0,1");
  }
  public static function GetCityByIp()
  {
	global $wpdb;
	$clientIP = $_SERVER['REMOTE_ADDR'];
	//$clientIP = '117.196.228.184';

	// Execute the query
	return $wpdb->get_row("SELECT atlas.name, atlas.latitude, atlas.longitude, atlas.timezone, country.country_name, geo_region.region_name FROM geo_blocks JOIN geo_location ON geo_location.location_id = geo_blocks.location_id LEFT JOIN atlas ON atlas.asciiname = geo_location.city LEFT JOIN country ON country.country_code = atlas.country_code LEFT JOIN geo_region ON (geo_region.region_code = CONCAT(atlas.country_code, '.', atlas.admin1 )) WHERE INET_ATON('$clientIP') BETWEEN geo_blocks.ip_start AND geo_blocks.ip_end LIMIT 1", ARRAY_A);
  }

  public static function GetCityById($place_id)
  {
	global $wpdb;

	// Execute the query
	return $wpdb->get_row("SELECT atlas.name, atlas.latitude, atlas.longitude, atlas.timezone, country.country_name, geo_region.region_name FROM atlas LEFT JOIN country ON country.country_code = atlas.country_code LEFT JOIN geo_region ON (geo_region.region_code = CONCAT(atlas.country_code, '.', atlas.admin1 )) WHERE atlas.place_id = $place_id LIMIT 1", ARRAY_A);
  }
  public static function SetDefaultCity( $place_id = '', $user_id = '' )
  {
		global $wpdb;

		if( empty($user_id) )
	    {
			$current_user = wp_get_current_user();
		    $user_id = $current_user->ID;
		}
		$profileuser = get_userdata($user_id);

		if( empty( $place_id ) )
	    {
			var_dump( $profileuser->current_location );
			if( empty( $profileuser->current_location) )
				$row = Atlas::GetCityByIp();
		}
		else $row = Atlas::GetCityById($place_id);

		if( empty( $row ) )
			return false;

		$column = serialize( Atlas::SetupLocationData( $row ) );
		update_usermeta( $user_id, 'current_location', $column );
		return $column;
  }
  public static function GetCountryByCode($code)
  {
		global $wpdb;
		
		// Execute the query
		return $wpdb->get_var("SELECT country_name FROM country WHERE country_code='$code'");
  }

  public static function GetPlace($city, $country)
  {
	  global $wpdb;

		// Execute the query
		return $wpdb->get_results("SELECT * FROM atlas WHERE MATCH (name,asciiname, alternatenames) AGAINST ('$city') AND country_code='$country' LIMIT 9", ARRAY_A);
  }
  public static function CitySearch($city, $country)
  {
	  global $link;

	  $query = "SELECT atlas.place_id, atlas.name, atlas.timezone, atlas.longitude, atlas.latitude, country.country_name, geo_region.region_name, MATCH (name) AGAINST ('*$city*' IN BOOLEAN MODE)*3 AS ra, MATCH (asciiname) AGAINST ('*$city*' IN BOOLEAN MODE)*2 AS rb, MATCH (alternatenames) AGAINST ('*$city*' IN BOOLEAN MODE) AS rc FROM atlas JOIN country ON country.country_code = atlas.country_code LEFT JOIN geo_region ON (geo_region.region_code = CONCAT(atlas.country_code, '.', atlas.admin1 )) WHERE (MATCH (name,asciiname, alternatenames) AGAINST ('*$city*' IN BOOLEAN MODE) OR name LIKE '%$city%') AND atlas.country_code = '$country' ORDER BY ra+rb+rc DESC, asciiname LIMIT 10";

	  $search = array();

		$getCities = mysql_query($query,$link);
		while( $content = mysql_fetch_array($getCities, MYSQL_ASSOC) )
		 {

			$search[] = Atlas::SetupLocationData( $content );
		 }
		return $search;
  }
  public static function SetupLocationData( $content )	
	{
		$content['timezone'] = array( 'timezone' => $content['timezone'] );
		$content = array_merge( $content, Atlas::getCoordinates($content) );

		$content = array_merge_recursive( $content, array('timezone' => Atlas::getTimeZone( time(), $content['timezone']['timezone'] ) ) );
		$content['timezone']['string'] = Atlas::getTimeZoneString($content['timezone']);
		
		$html = array();
		$html[] = $content['long']['degrees'] . '&deg;' . $content['long']['min'] . '&prime;' . $content['long']['direction'];
		$html[] = ' ';
		$html[] = $content['lat']['degrees'] . '&deg;' . $content['lat']['min'] . '&prime;' . $content['lat']['direction'];

		$content['location_string'] = join('', $html);
		
		$content['city_string'] = sprintf('%s, %s, %s <small class="autocomplete">%s UTC %s</small>', $content['name'], $content['region_name'], $content['country_name'], $content['location_string'], $content['timezone']['string'] );

		$content['city_string_home'] = sprintf('%s, %s, %s <small>%s UTC %s</small>', $content['name'], $content['region_name'], $content['country_name'], $content['location_string'], $content['timezone']['string'] );

		return $content;
	}
  public static function GetPlaceById($place_id)
  {
	  global $wpdb;

		// Execute the query
		return $wpdb->get_row("SELECT * FROM atlas WHERE place_id=$place_id", ARRAY_A);
  }
  	public static function getCoordinates($arr)
	{
		$data = array( 'long' => array(), 'lat' => array() );
		if(isset($arr['longitude']))
		{
			$data['long']['degrees'] = (int)abs($arr['longitude']);
			$data['long']['min'] = (int)((abs($arr['longitude']) - $data['long']['degrees'])*60);
			if($arr['longitude'] > 0)
				$data['long']['direction'] = 'E';
			else $data['long']['direction'] = 'W';
		}

		if(isset($arr['latitude']))
		{
			$data['lat']['degrees'] = (int)abs($arr['latitude']);
			$data['lat']['min'] = (int)((abs($arr['latitude']) - $data['lat']['degrees'])*60);
			if($arr['latitude'] > 0)
				$data['lat']['direction'] = 'N';
			else $data['lat']['direction'] = 'S';

		}

		return $data;
	}
	public static function getTimeZone($timeStamp, $tz)
	{
		$dtzone = new DateTimeZone( $tz );
		$dateTimeTaipei = new DateTime( '@' . $timeStamp, $dtzone);
		$timeOffset = $dtzone->getOffset($dateTimeTaipei);
		$timezone = array();

		$timezone['hours'] = (int)abs( $timeOffset/3600 );
		$timezone['min'] = (int)( ( abs( $timeOffset/3600 ) - $timezone['hours'] ) * 60 );
		
		if($timeOffset > 0)
			$timezone['direction'] = 'E';
		else $timezone['direction'] = 'W';

		return $timezone;
	}
	
	public static function getTimeZoneString($tz)
	{
		if($tz['direction'] == 'E')
			$operator = '+';
		else $operator = '-';
		
		$timezone =  $tz['hours'] + $tz['min']/60;
		return $operator . $timezone;
	}
}?>
