<?php
// Business tier class that manages customer accounts functionality
class PlanetInfo
{

	public function __get($key)
	{
        return self::getPlanetInfo( $p );
    }

	public static function printPlanetInfo( $p )
	{
		echo self::getPlanetInfo( $p );
	}
	public static function getPlanetInfo( $p )
	{
		$p = strtolower( $p );

		switch( $p )
		{
			case 'sun' :
				$text = 'The Sun in astrology represents Soul and is the source of all inspirations, creativity, consciousness, love, truth, nobility, expression and rationality. Sun teaches us to pursue a disciplined and performance oriented attitude. The position of Sun in your birth chart tells about your attitude, life purpose, likes and dislikes and style.';
				break;
			case 'moon' :
				$text = 'The Moon in astrology represents Mind and is the source all intuitions, motivations, emotions, happiness, bliss, beauty and practicality. Moon teaches us to have a thoughtful approach towards life through clarity and self control. The position of Moon in your birth chart tells about your deepest personal needs, basic habits and reactions, and the unconscious. ';
				break;
			case 'mercury' :
				$text = 'The Mercury in astrology represents Intellect and is the source of all curiosity, reasoning, skills, communication and mental processing. Mercury teaches us to make best use of our senses and expressions. The position of Mercury in your birth chart reveals your style of communication and various talents.';
				break;
			case 'venus' :
				$text = 'The Venus in astrology represents Satisfaction and is the source of all affections, attractions, pleasures, enjoyments and comforts. Venus teaches us to experience true love in its all forms and manifestations.  The position of Venus in your birth chart reveals connection of your heart with life experiences, harmony and peace.';
				break;
			case 'mars' :
				$text = 'The Mars in astrology represents Will and is the source of all action, energy, competition, fitness, well-being, sexuality and triumph. Mars teaches us to channelize our desires to achieve the significant.  The position of Mars in your birth chart provides information about your strengths, weakness, determination and ability to struggle.';
				break;
			case 'jupiter' :
				$text = 'The Jupiter in astrology represents Wisdom and is the source of all knowledge, understanding, possibilities, hope and excellence. Jupiter teaches us to adopt an optimistic and righteous approach towards life. The position on Jupiter in your birth chart tells about your education, intellectual depth, fame, opportunities and growth.';
				break;
			case 'rahu' :
				$text = 'The Rahu (or Moon\'s North Node) in astrology represents Desires and is the source of all things mysterious, obsessions and past-life events. Rahu teaches us to go along the life flow and embrace all experiences wholeheartedly. The position of Rahu in your birth chart reveals your deep cravings, passions and the extra ordinary.';
				break;
			case 'ketu' :
				$text = 'The Ketu (or Moon\'s South Node) in astrology represents Objectivity and is the source of all detachments, undoing and purity. Ketu teaches us to adopt changes and be flexible in our approach towards life. The position of Ketu in your birth chart represents areas that are in excess and need to be let go to have space for the new.';
				break;
			case 'saturn' :
				$text = 'The Saturn in astrology represents Structure and is the source of all laws of karma, limitations, eternity, immutability and perfection. Saturn teaches us the importance of hard work and patience. The position of Saturn in your birth chart tells us more about our personal limitations, detachments, fears and general difficulties.';
				break;
			case 'uranus' :
				$text = '';
				break;
			case 'neptune' :
				$text = '';
				break;
			case 'pluto' :
				$text = '';
				break;
		}
		return $text;
	}

}?>
