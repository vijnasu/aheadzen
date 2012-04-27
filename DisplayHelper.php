<?php

function br()
{
	return "<br />";
}

function h5()
{
	return "<h5>" . implode(func_get_args()) . "</h5>";
}

function h4()
{
	return "<h4>" . implode(func_get_args()) . "</h4>";
}

function h3()
{
	return "<h3>" . implode(func_get_args()) . "</h3>";
}

function h2()
{
	return "<h2>" . implode(func_get_args()) . "</h2>";
}

function begin_ul()
{
	return "<ul>";
}

function end_ul()
{
	return "</ul>";
}

function li()
{
	return "<li>" . implode( func_get_args() ) . "</li>";
}

function ul( $array )
{
	$string = begin_ul();
	foreach ( $array as $item )
	{
		$string.= li( $item );
	}
	return $string . end_ul();
}

?>