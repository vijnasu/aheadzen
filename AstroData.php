<?php
// Business tier class that manages all Astrology data

class AstroData
{
	public static $NAKSHATRA = array('Ashvini', 'Bharani', 'Kritika', 'Rohini', 'Mrigashira', 'Ardra', 'Punarvasu', 'Pushya', 'Ashlesha', 'Magha', 'Purva Phalguni', 'Uttara Phalguni', 'Hasta', 'Chitra', 'Swati', 'Vishakha', 'Anuradha', 'Jyeshtha', 'Mula', 'Purva Ashadha', 'Uttara Ashadha', 'Shravan', 'Dhanistha', 'Shatabhishaj', 'Purva Bhadrapad', 'Uttara Bhadrapad', 'Revati');
	public static $YOGA = array('Vishkambha', 'Priti', 'Ayushman', 'Saubhagya', 'Shobhana', 'Atiganda', 'Sukarman', 'Dhriti', 'Shula', 'Ganda', 'Vriddhi', 'Dhruva', 'Vyaghata', 'Harshana', 'Vajra', 'Siddhi', 'Vyatipata', 'Varigha', 'Parigha', 'Shiva', 'Siddha', 'Sadhya', 'Shubha', 'Shukla', 'Brahma', 'Mahendra', 'Vaidhriti');
	public static $TITHI = array( 1 => 'Shukla Prathamai 1', 'Shukla Dwitiya 2', 'Shukla Tritiya 3', 'Shukla Chaturthi 4', 'Shukla Panchami 5', 'Shukla Shashti 6', 'Shukla Saptami 7', 'Shukla Ashtami 8', 'Shukla Navami 9', 'Shukla Dasami 10', 'Shukla Ekadashi 11', 'Shukla Dwadasi 12', 'Shukla Trayodasi 13', 'Shukla Chaturdashi 14', 'Poornima Full', 'Krishna Prathamai 1', 'Krishna Dwitiya 2', 'Krishna Tritiya 3', 'Krishna Chaturthi 4', 'Krishna Panchami 5', 'Krishna Shashti 6', 'Krishna Saptami 7', 'Krishna Ashtami 8', 'Krishna Navami 9', 'Krishna Dasami 10', 'Krishna Ekadashi 11', 'Krishna Dwadasi 12', 'Krishna Trayodasi 13', 'Krishna Chaturdashi 14', 'Amavasya New');
	public static $VARA = array('Sun-Sunday', 'Moon-Monday', 'Mars-Tuesday', 'Mercury-Wednesday', 'Jupiter-Thursday', 'Venus-Friday', 'Saturn-Saturday');
	public static $KARANA = array('Kimstugna-L10', 'Bhava-Su', 'Bhaalava-Mo', 'Kaulava-Ma', 'Taitula-Me', 'Garija-Ju', 'Vanija-Ve', 'Vishti-Sa', 'Bhava-Su', 'Bhaalava-Mo', 'Kaulava-Ma', 'Taitula-Me', 'Garija-Ju', 'Vanija-Ve', 'Vishti-Sa', 'Bhava-Su', 'Bhaalava-Mo', 'Kaulava-Ma', 'Taitula-Me', 'Garija-Ju', 'Vanija-Ve', 'Vishti-Sa', 'Bhava-Su', 'Bhaalava-Mo', 'Kaulava-Ma', 'Taitula-Me', 'Garija-Ju', 'Vanija-Ve', 'Vishti-Sa', 'Bhava-Su', 'Bhaalava-Mo', 'Kaulava-Ma', 'Taitula-Me', 'Garija-Ju', 'Vanija-Ve', 'Vishti-Sa', 'Bhava-Su', 'Bhaalava-Mo', 'Kaulava-Ma', 'Taitula-Me', 'Garija-Ju', 'Vanija-Ve', 'Vishti-Sa', 'Bhava-Su', 'Bhaalava-Mo', 'Kaulava-Ma', 'Taitula-Me', 'Garija-Ju', 'Vanija-Ve', 'Vishti-Sa', 'Bhava-Su', 'Bhaalava-Mo', 'Kaulava-Ma', 'Taitula-Me', 'Garija-Ju', 'Vanija-Ve', 'Vishti-Sa', 'Shakuni-L1', 'Chatushpada-L4', 'Naaga-L7');
	public static $GRAHA = array('Sun', 'Moon', 'Mars', 'Mercury', 'Jupiter', 'Venus', 'Saturn', 'Rahu', 'Ketu'); //Normal Graha Sequence (consta Sequence)
	public static $PLANET_BY_ID = array('Ascendant' => 0, 'Sun' => 1, 'Moon' => 2, 'Mars' => 5, 'Mercury' => 3, 'Jupiter' => 6, 'Venus' => 4, 'Saturn' => 7, 'Rahu' => 20, 'Ketu' => 21); //Normal Graha Sequence (consta Sequence)
	public static $CAUGHADIYA = array('Udvega-Su', 'Chara-Ve', 'Laabha-Me', 'Amrit-Mo', 'Kaala-Sa', 'Shubha-Ju', 'Roga-Ma');
	public static $MUHURTHA = array('Rudra-Ardra', 'Ahi-Aslesha', 'Mitra-Anuradha', 'Pitri-Magha', 'Vasu-Dhanishtha', 'Ambu-Purvashadha', 'Visvadeva-Uttarashadha', 'Abhijit/Vidhi-Abhijit', 'Vidhata/Satamuki-Rohini', 'Puruhuta-Jyeshtha', 'Indragni/Vahni-Visakha', 'Nirriti/Naktancara-Mula', 'constuna/Udakanatha-Satabhisha', 'Aryaman-Uttaraphalguni', 'Bhaga-Purvaphalguni', 'Girisa-Ardra', 'Ajapada-Purvabhadrapada', 'Ahirbudhnya-Uttarabhadrapada', 'Pushan-Revati', 'Asvi-Asvini', 'Yama-Bharani', 'Agni-Krittika', 'Vidhaatri-Rohini', 'Chanda-Mrigasira', 'Aditi-Punarvasu', 'Jiiva-Pushya', 'Vishnu-Sravana', 'Arka-Hasta', 'Tvashtri-Chitra', 'Maruta-Svati');
	public static $WEEKDAYS = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
	public static $HORA = array('Sun', 'Venus', 'Mercury', 'Moon', 'Saturn', 'Jupiter', 'Mars');
	public static $GULIKACHAKRA = array('Kaala', '-', 'Mrityu', 'Ardhaprahara', 'Yamaghanta', '-', 'Gulika', '-');
	public static $asRashi = array('Mesha', 'Vrishabha', 'Mithuna', 'Karka', 'Simha', 'Kanya', 'Tula', 'Vrishchika', 'Dhanura', 'Makara', 'Kumbha', 'Meena');
//	public static $MAANDIGUNANKA  =  array(26/30, 22/30, 18/30, 14/30, 10/30, 6/30, 2/30, 30/30); //In Weekday order; Take 5th for night time
	public static $KAALACHAKRA = array(0, 2, 4, 3, 5, 6, 1, 7); //Kaalachakra sequence
	public static $KAALACHAKRA_START = array(0, 6, 1, 3, 2, 4, 5);
	public static $CAUGHADIA_START = array(0, 3, 6, 2, 5, 1, 4);
	public static $VIMSHOTTARI = array(0, 1, 2, 7, 4, 6, 3, 8, 5); //Vimshottari Sequence
	public static $ORDER = array(0, 3, 6, 2, 5, 1, 4);
	public static $NAKSHATRA_SIZE = 13.3333333333333; //Nakshatra Size in degrees.
	public static $SAMVATSARA = array( 'Prabhava', 'Vibhava', 'Shukla', 'Pramodoota', 'Prajothpatti', 'Āngirasa', 'Shrīmukha', 'Baāva', 'Yuva', 'Dhātru', 'Īshconsta', 'Bahudhānya', 'Pramāthi', 'Vikrama', 'Vrusha', 'Chitrabhānu', 'Svabhānu', 'Tārana', 'Pārthiva', 'Vyaya', 'Sarvajith', 'Sarvadhāri', 'Virodhi', 'Vikruta', 'Khara', 'Nandana', 'Vijaya', 'Jaya', 'Manmatha', 'Durmukhi', 'Hevilambi', 'Vilambi', 'Vikāri', 'Shārconsti', 'Plava', 'Shubhakrutha', 'Shobhakrutha', 'Krodhi', 'Vishvāvasu', 'Parābhava', 'Plavanga', 'Kīlaka', 'Saumya', 'Sādhārana', 'Virodhikrutha', 'Paridhāvi', 'Pramādeecha', 'Ānanda', 'Rākshasa', 'Anala', 'Pingala', 'Kālayukthi', 'Siddhārthi', 'Raudra', 'Durmathi', 'Dundubhi', 'Rudhirodgāri', 'Raktākshi', 'Krodhana', 'Akshaya' );
	public static $RAHU_KAAL = array(0.875, 0.125, 0.75, 0.5, 0.625, 0.375, 0.25);
	public static $GULIKA_KAAL = array(0.75, 0.625, 0.5, 0.375, 0.25, 0.125, 0);
	public static $YAMA_GHANTAKA = array(0.5, 0.375, 0.25, 0.125, 0, 0.75, 0.625 );
	public static $ASPECTS = array("Saturn" => array(0,180,60,270),"Mars" => array(0,180,90,210),"Rahu"=>array(0),"Ketu"=>array(0),"Jupiter"=>array(0,180,120,240));
	public static $ZODIAC_SIGN_NAME = array('Aries', 'Taurus', 'Gemini', 'Cancer', 'Leo', 'Virgo', 'Libra', 'Scorpio', 'Sagittarius', 'Capricorn', 'Aquarius', 'Pisces');
	public static $ZODIAC_SIGNS_LORD = array('Aries' => 'Mars', 'Taurus' => 'Venus', 'Gemini' => 'Mercury', 'Cancer' => 'Moon', 'Leo' => 'Sun', 'Virgo' => 'Mercury', 'Libra' => 'Venus', 'Scorpio' => 'Mars', 'Sagittarius' => 'Jupiter', 'Capricorn' => 'Saturn', 'Aquarius' => 'Saturn', 'Pisces' => 'Jupiter');

	public static $ASPECT_NAME = array(	0=>"conjunct",
										180 => "opposite",
										60 => "sextile",
										90 => "square",
										270 => "square",
										120 => "trine",
										240 => "trine",
										210 => "aspects",
										360 => "conjunct"
										);
	public static $REVERSE_DRISHTI = array("Sun" => array(1,7),"Moon" => array(1,7),"Mercury" => array(1,7),"Venus" => array(1,7),"Saturn" => array(1,4,7,11),"Mars" => array(1,6,7,10),"Rahu"=>array(1),"Ketu"=>array(1),"Jupiter"=>array(1,5,7,9),"ASC"=>array());
	public static $LAGNA_GOOD_BAD = array(
										'Aries' => array(
															'GOOD' => array( 'Mars', 'Sun', 'Jupiter', 'Moon' ),
															'BAD' => array( 'Saturn', 'Mercury', 'Venus' ),
															'KILLER' => array( 'Venus', 'Saturn', 'Mercury' ),
															'YOGAKARAKA' => array( 'Sun', 'Moon' )
														),

										'Taurus' => array(
															'GOOD' => array( 'Saturn', 'Sun', 'Mercury' ),
															'BAD' => array( 'Jupiter', 'Moon', 'Venus', 'Mars' ),
															'KILLER' => array( 'Mars', 'Jupiter', 'Venus' ),
															'YOGAKARAKA' => array( 'Saturn' )
														),

										'Gemini' => array(
															'GOOD' => array( 'Mercury', 'Venus', 'Saturn' ),
															'BAD' => array( 'Mars', 'Sun', 'Jupiter' ),
															'KILLER' => array( 'Moon' ),
															'YOGAKARAKA' => array( 'Mercury' )
														),

										'Cancer' => array(
															'GOOD' => array( 'Mars', 'Jupiter', 'Moon' ),
															'BAD' => array( 'Mercury', 'Venus',  'Saturn' ),
															'KILLER' => array( 'Saturn', 'Sun' ),
															'YOGAKARAKA' => array( 'Mars' )
														),

										'Leo' => array(
															'GOOD' => array( 'Mars', 'Jupiter', 'Sun' ),
															'BAD' => array( 'Saturn', 'Mercury', 'Venus', 'Moon' ),
															'KILLER' => array( 'Saturn', 'Mercury' ),
															'YOGAKARAKA' => array( 'Mars' )
														),

										'Virgo' => array(
															'GOOD' => array( 'Mercury', 'Venus' ),
															'BAD' => array( 'Mars', 'Moon', 'Jupiter', 'Sun' ),
															'KILLER' => array( 'Venus' ),
															'YOGAKARAKA' => array( 'Venus' )
														),

										'Libra' => array(
															'GOOD' => array( 'Mercury', 'Saturn', 'Moon', 'Venus' ),
															'BAD' => array( 'Mars', 'Sun', 'Jupiter' ),
															'KILLER' => array( 'Mars', 'Sun', 'Jupiter' ),
															'YOGAKARAKA' => array( 'Moon', 'Mercury' )
														),

										'Scorpio' => array(
															'GOOD' => array( 'Mars', 'Moon', 'Sun', 'Jupiter' ),
															'BAD' => array( 'Saturn', 'Mercury', 'Venus' ),
															'KILLER' => array( 'Saturn', 'Mercury', 'Venus' ),
															'YOGAKARAKA' => array( 'Moon', 'Sun' )
														),

										'Sagittarius' => array(
															'GOOD' => array( 'Mars', 'Sun', 'Mercury' ),
															'BAD' => array( 'Saturn', 'Moon', 'Jupiter', 'Venus' ),
															'KILLER' => array( 'Saturn', 'Venus', 'Jupiter' ),
															'YOGAKARAKA' => array( 'Mercury', 'Sun' )
														),

										'Capricorn' => array(
															'GOOD' => array( 'Venus', 'Mercury', 'Sun', 'Saturn' ),
															'BAD' => array( 'Mars', 'Moon', 'Jupiter' ),
															'KILLER' => array( 'Mars', 'Moon', 'Jupiter' ),
															'YOGAKARAKA' => array( 'Venus' )
														),

										'Aquarius' => array(
															'GOOD' => array( 'Venus', 'Saturn', 'Mercury' ),
															'BAD' => array( 'Mars', 'Moon', 'Jupiter' ),
															'KILLER' => array( 'Mars', 'Sun', 'Jupiter' ),
															'YOGAKARAKA' => array( 'Venus' )
														),

										'Pisces' => array(
															'GOOD' => array( 'Mars', 'Moon', 'Jupiter' ),
															'BAD' => array( 'Saturn', 'Mercury', 'Venus', 'Sun' ),
															'KILLER' => array( 'Saturn', 'Mercury' ),
															'YOGAKARAKA' => array( 'Mars', 'Jupiter' )
														)

										);
	public static $POSITION_GOOD_BAD = array( 'GOOD' => array(1,4,5,7,9,10), 'BAD' => array(2,3,6,8,11,12 ) );
	public static $GOOD_PLANETS = array( 'Jupiter', 'Venus', 'Moon', 'Sun' );
	public static $BAD_PLANETS = array( 'Saturn', 'Mars', 'Rahu', 'Ketu', 'Mercury'  );
	public static $EXALTATION = array( 'Jupiter' => 95, 'Venus' => 327, 'Moon' => 33, 'Sun' => 10, 'Saturn' => 200, 'Mars' => 297, 'Mercury' => 165, 'Rahu' => 45, 'Ketu' => 225  );
	public static $MOOL_TRIKONA = array( 'Jupiter' => 'Sagittarius', 'Venus' => 'Libra', 'Moon' => 'Taurus', 'Sun' => 'Leo', 'Saturn' => 'Aquarius', 'Mars' => 'Aries', 'Mercury' => 'Virgo', 'Rahu' => 'Gemini', 'Ketu' => 'Sagittarius' );
	public static $FRIENDSHIPS = array ( 'Sun' => array( 'Friends' => array( 'Moon', 'Mars', 'Jupiter' ),
	       	      		     	     	   'Neutrals' => array( 'Mercury' ),
						   'Enemies' => array( 'Venus', 'Saturn', 'Rahu', 'Ketu' ) ),
						'Moon' => array( 'Friends' => array( 'Sun', 'Mercury' ),
						       'Neutrals' => array( 'Mars', 'Jupiter', 'Venus', 'Saturn' ),
						       'Enemies' => array( 'Rahu', 'Ketu' ) ),
						'Mars' => array( 'Friends' => array( 'Sun', 'Moon', 'Jupiter' ),
						       'Neutrals' => array( 'Venus', 'Saturn', 'Rahu', 'Ketu' ),
						       'Enemies' => array( 'Mercury' ) ),
						'Mercury' => array( 'Friends' => array( 'Venus', 'Sun', 'Rahu', 'Ketu' ),
							  'Neutrals' => array( 'Mars', 'Jupiter', 'Saturn'),
							  'Enemies' => array( 'Moon' ) ),
						'Jupiter' => array( 'Friends' => array( 'Sun', 'Moon', 'Mars' ),
							  'Neutrals' => array( 'Saturn', 'Ketu', 'Rahu' ),
							  'Enemies' => array( 'Mercury', 'Venus' ) ),
						'Venus' => array( 'Friends' => array( 'Mercury', 'Saturn', 'Rahu', 'Ketu' ),
							'Neutrals' => array( 'Mars', 'Jupiter' ),
							'Enemies' => array( 'Sun', 'Moon' ) ),
						'Saturn' => array( 'Friends' => array( 'Mercury', 'Venus', 'Rahu', 'Ketu' ),
							 'Neutrals' => array( 'Jupiter' ),
							 'Enemies' => array( 'Sun', 'Moon', 'Mars' ) ),
						'Rahu' => array( 'Friends' => array( 'Mercury', 'Venus', 'Saturn', 'Ketu' ),
							 'Neutrals' => array( 'Mars', 'Jupiter'),
							 'Enemies' => array( 'Sun', 'Moon' ) ),
						'Ketu' => array( 'Friends' => array( 'Venus', 'Saturn', 'Mercury', 'Rahu' ),
							 'Neutrals' => array( 'Mars', 'Jupiter' ),
							 'Enemies' => array( 'Sun', 'Moon' ) ) );
	public static $GANA = array( 'Ashvini' => 'Deva',
	       	      	      	     'Bharani' => 'Manushya',
				     'Kritika' => 'Rakshasa',
				     'Rohini' => 'Manushya',
				     'Mrigashira' => 'Deva',
				     'Ardra' => 'Manushya',
				     'Punarvasu' => 'Deva',
				     'Pushya' => 'Deva',
				     'Ashlesha' => 'Rakshasa',
				     'Magha' => 'Rakshasa',
				     'Purva Phalguni' => 'Manushya',
				     'Uttara Phalguni' => 'Manushya',
				     'Hasta' => 'Deva',
				     'Chitra' => 'Rakshasa',
				     'Swati' => 'Deva',
				     'Vishakha' => 'Rakshasa',
				     'Anuradha' => 'Deva',
				     'Jyeshtha' => 'Rakshasa',
				     'Mula' => 'Rakshasa',
				     'Purva Ashadha' => 'Manushya',
				     'Uttara Ashadha' => 'Manushya',
				     'Shravan' => 'Deva',
				     'Dhanistha' => 'Rakshasa',
				     'Shatabhishaj' => 'Rakshasa',
				     'Purva Bhadrapad' => 'Manushya',
				     'Uttara Bhadrapad' => 'Manushya',
				     'Revati' => 'Deva');
	public static $YONI = array( 'Ashvini' => 'Horse',
	       	      	      	     'Bharani' => 'Elephant',
				     'Kritika' => 'Sheep',
				     'Rohini' => 'Serpent',
				     'Mrigashira' => 'Serpent',
				     'Ardra' => 'Dog',
				     'Punarvasu' => 'Cat',
				     'Pushya' => 'Sheep',
				     'Ashlesha' => 'Cat',
				     'Magha' => 'Rat',
				     'Purva Phalguni' => 'Rat',
				     'Uttara Phalguni' => 'Cow',
				     'Hasta' => 'Buffalo',
				     'Chitra' => 'Tiger',
				     'Swati' => 'Buffalo',
				     'Vishakha' => 'Tiger',
				     'Anuradha' => 'Hare',
				     'Jyeshtha' => 'Hare',
				     'Mula' => 'Dog',
				     'Purva Ashadha' => 'Monkey',
				     'Uttara Ashadha' => 'Mongoose',
				     'Shravan' => 'Monkey',
				     'Dhanistha' => 'Lion',
				     'Shatabhishaj' => 'Horse',
				     'Purva Bhadrapad' => 'Lion',
				     'Uttara Bhadrapad' => 'Cow',
				     'Revati' => 'Elephant');
	public static $YONI_SEX = array( 'Ashvini' => 'Male',
	       	      	      	  	 'Bharani' => 'Male',
				     	 'Kritika' => 'Female',
				     	 'Rohini' => 'Male',
				     	 'Mrigashira' => 'Female',
				     	 'Ardra' => 'Female',
				     	 'Punarvasu' => 'Female',
				     	 'Pushya' => 'Male',
				     	 'Ashlesha' => 'Male',
				     	 'Magha' => 'Male',
				     	 'Purva Phalguni' => 'Female',
				     	 'Uttara Phalguni' => 'Male',
				     	 'Hasta' => 'Female',
				     	 'Chitra' => 'Female',
				     	 'Swati' => 'Male',
				     	 'Vishakha' => 'Male',
				     	 'Anuradha' => 'Female',
				     	 'Jyeshtha' => 'Male',
				     	 'Mula' => 'Male',
				     	 'Purva Ashadha' => 'Male',
				     	 'Uttara Ashadha' => 'Male',
				     	 'Shravan' => 'Female',
				     	 'Dhanistha' => 'Female',
				     	 'Shatabhishaj' => 'Female',
				     	 'Purva Bhadrapad' => 'Male',
				     	 'Uttara Bhadrapad' => 'Female',
				     	 'Revati' => 'Female');
	public static $VASYA_RASHI = array( 'Aries' => array( 'Leo', 'Scorpio' ),
	       	      		    	    'Taurus' => array( 'Cancer', 'Libra' ),
					    'Gemini' => array( 'Virgo' ),
					    'Cancer' => array( 'Sagittarius', 'Scorpio' ),
					    'Leo' => array( 'Libra' ),
					    'Virgo' => array( 'Pisces', 'Gemini' ),
					    'Libra' => array( 'Capricorn', 'Virgo' ),
					    'Scorpio' => array( 'Cancer' ),
					    'Sagittarius' => array( 'Pisces' ),
					    'Capricorn' => array( 'Aries', 'Aquarius' ),
					    'Aquarius' => array( 'Aries' ),
					    'Pisces' => array( 'Capricorn' ) );
	public static $VARNA = array( 'Ashvini' => 'Brahmin',
	       	      	      	      'Bharani' => 'Kshatriya',
				      'Kritika' => 'Viasya',
				      'Rohini' => 'Sudra',
				      'Mrigashira' => 'Brahmin',
				      'Ardra' => 'Kshatriya',
				      'Punarvasu' => 'Viasya',
				      'Pushya' => 'Sudra',
				      'Ashlesha' => 'Brahmin',
				      'Magha' => 'Kshatriya',
				      'Purva Phalguni' => 'Viasya',
				      'Uttara Phalguni' => 'Sudra',
				      'Hasta' => 'Brahmin',
				      'Chitra' => 'Kshatriya',
				      'Swati' => 'Viasya',
				      'Vishakha' => 'Sudra',
				      'Anuradha' => 'Brahmin',
				      'Jyeshtha' => 'Kshatriya',
				      'Mula' => 'Viasyav',
				      'Purva Ashadha' => 'Sudra',
				      'Uttara Ashadha' => 'Brahmin',
				      'Shravan' => 'Kshatriya',
				      'Dhanistha' => 'Viasya',
				      'Shatabhishaj' => 'Sudra',
				      'Purva Bhadrapad' => 'Brahmin',
				      'Uttara Bhadrapad' => 'Kshatriya',
				      'Revati' => 'Viasya');
}?>
