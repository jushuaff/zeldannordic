<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if(!function_exists('numberToColorHsl')){
    function numberToColorHsl($i, $start = 0, $end = 120) {
        $a = $i;
        $b = floor(($end - $start) * $a);
        $c = $b + $start;
        return "hsl(${c}, 100%, 55%)";
    }
}

if(!function_exists('versionAsset')) {
  function versionAsset($asset)
  {
    return base_url($asset)."?ver=".filemtime(FCPATH.$asset);
  }
}
