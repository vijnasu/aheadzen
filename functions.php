<?php  
function getPlanetIdByName($name)
{
	$pid = array( 'Sun' => 1, 'Moon' => 2, 'Mercury' => 3, 'Venus' => 4, 'Mars' => 5, 'Jupiter' => 6, 'Saturn' => 7, 'Neptune' => 8, 'Uranus' => 9, 'Pluto' => 10, 'ASC' => 11,'Rahu'  => 20,  'Ketu'=> 21 );
	return $pid[$name];
}

function getBirthReportSQL( $planets, $asc_data )
{
	$query = array();
	$query[] = 'SELECT CONCAT(content_type, planet_id, object_id) AS id, content_type, planet_id, object_id, content FROM birth_report WHERE ';
	// First include ASC
	$query[] = '(content_type = 2 AND object_id = ' . $asc_data['sign_number'] . ' AND planet_id = 11)';
	foreach( $planets as $planet => $data )
	{
		if( $planet == 'Ketu' )
			continue;

		$planetId = getPlanetIdByName($planet);
		$h1 = $data['house'];
		$h2 = $data['sign_number'];

		$query[] = ' OR ';
		$query[] = "(content_type = 1 AND object_id = $h1 AND planet_id = $planetId)";
		$query[] = ' OR ';
		$query[] = "(content_type = 2 AND object_id = $h2 AND planet_id = $planetId)";
	}

	return join( '', $query );

}

function getHouseBySignNumber($sign, $houses)
{
	$house = $sign - $houses['ASC']['sign_number'] + 1;
	if( $house < 1 )
	{
		$house += 12;
	}
	return $house;
}
function modDegree($degree)
{
	if( $degree < 0 )
	{
		$degree += 360;
	}
	return $degree;
}

function confirmReport(&$reportdata)
{
	$current_user = wp_get_current_user();
	$user_id = $current_user->ID;
	$profileuser = get_userdata($user_id);

	if( isset( $profileuser->birth_data ) )
	{
		$reportdata = unserialize( $profileuser->birth_data );
	}

	return validateAndSaveLocation( $user_id, 'birth_data', $reportdata );
}

function validateAndSaveLocation($user_id, $meta_key, $reportdata = array() )
{
	$errors = new WP_Error();
	$reportdata = array_merge( array('timezone' => array(), 'longitude' => array(), 'latitude' => array() ), $reportdata );
	$reportdata['timezone']['hours']= (int)( $_POST['tz_hours'] );
	$reportdata['timezone']['min']	= (int)( $_POST['tz_min'] );
	$reportdata['longitude']['degrees']	= (int)( $_POST['lon_degrees'] );
	$reportdata['longitude']['min']	= (int)( $_POST['lon_min'] );
	$reportdata['latitude']['degrees']	= (int)( $_POST['lat_degrees'] );
	$reportdata['latitude']['min']	= (int)( $_POST['lat_min'] );

	if ( isset( $_POST['e_w_tz'] ) )
		$reportdata['timezone']['direction'] = sanitize_text_field( $_POST['e_w_tz'] );

	if ( isset( $_POST['n_s'] ) )
		$reportdata['latitude']['direction'] = sanitize_text_field( $_POST['n_s'] );

	if ( isset( $_POST['e_w'] ) )
		$reportdata['longitude']['direction'] = sanitize_text_field( $_POST['e_w'] );

	if ( !is_numeric( $_POST['tz_hours'] ) || $reportdata['timezone']['hours'] < 0 || $reportdata['timezone']['hours'] > 24 )
		$errors->add( 'tz_hours', __( '<strong>ERROR</strong>: Please enter a valid timezone hour value.' ), array( 'form-field' => 'tz_hours' ) );

	if ( empty( $reportdata['timezone']['direction'] ) || !in_array( strtoupper($reportdata['timezone']['direction']), array('E', 'W') ) )
		$errors->add( 'e_w_tz', __( '<strong>ERROR</strong>: Please select either East or West for timezone.' ), array( 'form-field' => 'e_w_tz' ) );

	if ( empty( $reportdata['longitude']['direction'] ) || !in_array( strtoupper($reportdata['longitude']['direction']), array('E', 'W') ) )
		$errors->add( 'e_w', __( '<strong>ERROR</strong>: Please select either E or W for longitude.' ), array( 'form-field' => 'e_w' ) );

	if ( empty( $reportdata['latitude']['direction'] ) || !in_array( strtoupper($reportdata['latitude']['direction']), array('N', 'S') ) )
		$errors->add( 'n_s', __( '<strong>ERROR</strong>: Please select either N or S for latitude.' ), array( 'form-field' => 'n_s' ) );

	if ( !is_numeric( $_POST['tz_min'] ) || $reportdata['timezone']['min'] < 0 || $reportdata['timezone']['min'] > 59 )
		$errors->add( 'tz_min', __( '<strong>ERROR</strong>: Please enter a valid timezone minute value.' ), array( 'form-field' => 'tz_min' ) );

	if ( !is_numeric( $_POST['lon_min'] ) || $reportdata['longitude']['min'] < 0 || $reportdata['longitude']['min'] > 59 )
		$errors->add( 'lon_min', __( '<strong>ERROR</strong>: Please enter a valid longitude minute value.' ), array( 'form-field' => 'lon_min' ) );
	
	if ( !is_numeric( $_POST['lat_min'] ) || $reportdata['latitude']['min'] < 0 || $reportdata['latitude']['min'] > 59 )
		$errors->add( 'lat_min', __( '<strong>ERROR</strong>: Please enter a valid latitude minute value.' ), array( 'form-field' => 'lat_min' ) );

	if ( !is_numeric( $_POST['lon_degrees'] ) || $reportdata['longitude']['degrees'] < 0 || $reportdata['longitude']['degrees'] > 180 )
		$errors->add( 'lon_degrees', __( '<strong>ERROR</strong>: Please enter a valid longitude degree value.' ), array( 'form-field' => 'lon_degrees' ) );

	if ( !is_numeric( $_POST['lat_degrees'] ) || $reportdata['latitude']['degrees'] < 0 || $reportdata['latitude']['degrees'] > 90 )
		$errors->add( 'lat_degrees', __( '<strong>ERROR</strong>: Please enter a valid latitude degree value.' ), array( 'form-field' => 'lat_degrees' ) );

	if ( !$errors->get_error_code() ) {

		$reportdata['has_all_info'] = true;
		update_usermeta( $user_id, $meta_key, serialize( $reportdata ) );
		wp_mail('admin@ask-oracle.com', 'Saved current location', $reportdata['city_string_home']);
	}

	return $errors;
}

function validateReportData(&$reportdata)
{
	$current_user = wp_get_current_user();
	$user_id = $current_user->ID;
	$profileuser = get_userdata($user_id);

	$errors = new WP_Error();

	if( isset( $_GET['ConfirmLocation'] ) )
		return $errors;

	$reportdata = array();
	$reportdata['month']= (int)( $_POST['mm'] );
	$reportdata['day']	= (int)( $_POST['dd'] );
	$reportdata['year']	= (int)( $_POST['yyyy'] );
	$reportdata['hour']	= (int)( $_POST['hh'] );
	$reportdata['min']	= (int)( $_POST['min'] );
	$reportdata['sex']	= $_POST['sex'];

	if ( isset( $_POST['report_name'] ) )
		$reportdata['report_name'] = sanitize_text_field( $_POST['report_name'] );
	if ( isset( $_POST['city'] ) )
		$reportdata['city'] = sanitize_text_field( $_POST['city'] );
	if ( isset( $_POST['country'] ) )
		$reportdata['country'] = sanitize_text_field( $_POST['country'] );
	if ( isset( $_POST['amORpm'] ) )
		$reportdata['am_pm'] = sanitize_text_field( $_POST['amORpm'] );

	if ( empty( $reportdata['month'] ) || $reportdata['month'] < 1 || $reportdata['month'] > 12 )
		$errors->add( 'month', __( '<strong>ERROR</strong>: Please select a valid month.' ), array( 'form-field' => 'mm' ) );

	if ( empty( $reportdata['day'] ) || $reportdata['day'] < 1 || $reportdata['day'] > 31 )
		$errors->add( 'month', __( '<strong>ERROR</strong>: Please select a valid day.' ), array( 'form-field' => 'dd' ) );

	if ( empty( $reportdata['year'] ) || $reportdata['year'] < 1900 || $reportdata['year'] > 2013 )
		$errors->add( 'yyyy', __( '<strong>ERROR</strong>: Please enter a valid year.' ), array( 'form-field' => 'yyyy' ) );

	if ( empty( $reportdata['am_pm'] ) || !in_array( strtolower($reportdata['am_pm']), array('am', 'pm') ) )
		$errors->add( 'am_pm', __( '<strong>ERROR</strong>: Please select either AM or PM for birth time.' ), array( 'form-field' => 'amORpm' ) );

	if ( empty( $reportdata['sex'] ) || !in_array( strtolower($reportdata['sex']), array('male', 'female') ) )
		$errors->add( 'sex', __( '<strong>ERROR</strong>: Please select either Male or Female for Sex.' ), array( 'form-field' => 'sex' ) );

	if ( !is_numeric( $_POST['min'] ) || $reportdata['min'] < 0 || $reportdata['min'] > 59 )
		$errors->add( 'min', __( '<strong>ERROR</strong>: Please select a valid minute value.' ), array( 'form-field' => 'min' ) );

	if ( !is_numeric( $_POST['hh'] ) || $reportdata['hour'] < 0 || $reportdata['hour'] > 11 )
		$errors->add( 'hh', __( '<strong>ERROR</strong>: Please select a valid hour value.' ), array( 'form-field' => 'hh' ) );

	if ( empty( $reportdata['city'] ) )
		$errors->add( 'city', __( '<strong>ERROR</strong>: Please enter your birth city.' ), array( 'form-field' => 'city' ) );

	if ( empty( $reportdata['country'] ) )
		$errors->add( 'country', __( '<strong>ERROR</strong>: Please enter your birth country.' ), array( 'form-field' => 'country' ) );

	if ( !$errors->get_error_code() ) {
		update_usermeta( $user_id, 'birth_data', serialize( $reportdata ) );
	}
	return $errors;
}

function getBirthTS( $reportdata )
{
	if($reportdata['am_pm'] == 'pm')
			$birthtime = ($reportdata['hour']+12) . ':' . $reportdata['min'] . ':00';
		else $birthtime = $reportdata['hour'] . ':' . $reportdata['min'] . ':00';

		$birthDateTime = $reportdata['year'] . '-' . $reportdata['month'] . '-' . $reportdata['day'] . ' ' . $birthtime;
		return strtotime( $birthDateTime );
}

function printLongitude( $data )
{
	$degree = (int)$data['degree'];
	$min = (int)(($data['degree'] - $degree) * 60);

	echo $degree . ' ' . $data['sign'] . ' ' . $min;
}

function ordinal($n) {                                        
    return $n . '<sup style="font-size: 12px;">' . gmdate("S", (((abs($n) + 9) % 10) + ((abs($n / 10) % 10) == 1) * 10) * 86400) . '</sup>';
	}
?>