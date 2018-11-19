<?php
namespace App\Helpers;


class NumberHelper {

    public static function convert_double($inputDouble){
        return doubleval($inputDouble);
    }

    public static function format_double($inputDouble){
        return number_format($inputDouble, 2);
    }

    public static function convert_integer($inputInteger){
        return intval($inputInteger);
    }

    public static function format_integer($inputInteger){
        return number_format($inputInteger);
    }
}