<?php
// Business tier class that analyzes a birth chart for Longevity

$path = dirname( __FILE__ );

class AnalyzeLongevity {
  private $_chart;

  public function __construct($chart) {
    $this->_chart = $chart;
  }

}?>