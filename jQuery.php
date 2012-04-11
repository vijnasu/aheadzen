<?php
// Business tier class that manages all jQuery usage at PHP end

class jQuery
{
	public static function getPlanetInfoToolTip( $info )
	{
		$html = array();
		$html[] = sprintf( '<span class="planet_tooltip">%s</span>', $info['planet'] );
		$html[] = '<div class="tooltip">';
		$html[] = sprintf( '<div class="tooltip_title">%s - %s</div>', $info['planet'], $info['longitude'] );
		$html[] = '<div class="tooltip_content"><table margin="0">';
		$html[] = sprintf( '<tr><td class="firstcell">Position: </td><td class="datacell">%d<sup>%s</sup> House</td></tr>', $info['position'], jQuery::ordinal( $info['position'] ) );

		if( count( $info['lordship'] ) == 1 )
		{
			$html[] = sprintf( '<tr><td class="firstcell">Lord: </td><td class="datacell">%d<sup>%s</sup> House</td></tr>', $info['lordship'][0], jQuery::ordinal( $info['lordship'][0] ) );
		}
		else
		{
			$html[] = sprintf( '<tr><td class="firstcell">Lord: </td><td class="datacell">%d<sup>%s</sup> and %d<sup>%s</sup> Houses</td></tr>', $info['lordship'][0], jQuery::ordinal( $info['lordship'][0] ), $info['lordship'][1], jQuery::ordinal( $info['lordship'][1] ) );
		}
		$html[] = sprintf( '<tr><td class="firstcell">Potency: </td><td class="datacell">%s</td></tr>', $info['potency'] );
		$html[] = sprintf( '<tr><td class="firstcell"></td><td class="firstcell"><a target="_blank" href="%s#%s">more info &raquo;</a></td></tr>', get_linkTO( 'birth-chart/report/' ),$info['planet'] );
		$html[] = '</table></div></div>';
		$html = join('', $html);
		return $html;
	}
	public static function ordinal($n) {
	    return gmdate("S", (((abs($n) + 9) % 10) + ((abs($n / 10) % 10) == 1) * 10) * 86400);
	}

}?>
