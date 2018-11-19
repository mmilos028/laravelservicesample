<?php
namespace App\Helpers;

use Carbon\Carbon;

class DateTimeHelper {

    public static function returnDateFormatted($inputDateTime){
        return Carbon::parse($inputDateTime)->format('d-m-Y');
        //return Carbon::parse($inputDateTime)->formatLocalized('d-m-Y');
    }

    public static function returnTimeFormatted($inputDateTime){
        return Carbon::parse($inputDateTime)->format('H:i:s');
        //return Carbon::parse($inputDateTime)->formatLocalized('d-m-Y');
    }

	public static function returnDateTimeFormatted($inputDateTime){
        return Carbon::parse($inputDateTime)->format('d-m-Y H:i:s');
        //return Carbon::parse($inputDateTime)->formatLocalized('d-m-Y');
    }

    public static function returnCurrentDateTimeFormatted(){
        return Carbon::now()->format('d-M-Y H:i:s');
    }

    public static function returnCurrentDateFormatted(){
        return Carbon::now()->format('d-M-Y');
    }

    public static function returnCurrentTimeFormatted(){
        return Carbon::now()->format('H:i:s');
    }

    public static function differenceBetweenDates($inputDateTime){
        $carbonInputDateTime = Carbon::parse($inputDateTime);
        //return Carbon::now()->diffForHumans($carbonInputDateTime);
        $carbonCurrentDateTime = Carbon::now();
        $diffInDays = $carbonCurrentDateTime->diffInDays($carbonInputDateTime);
        $diffInHours = $carbonCurrentDateTime->diffInHours($carbonInputDateTime) % 24;
        $diffInMinutes = $carbonCurrentDateTime->diffInMinutes($carbonInputDateTime) % 60;
        $diffInSeconds = $carbonCurrentDateTime->diffInSeconds($carbonInputDateTime) % 60;

        $diffInDays = ($diffInDays < 10) ? "0" . $diffInDays : $diffInDays;
        $diffInHours = ($diffInHours < 10) ? "0" . $diffInHours : $diffInHours;
        $diffInMinutes = ($diffInMinutes < 10) ? "0" . $diffInMinutes : $diffInMinutes;
        $diffInSeconds = ($diffInSeconds < 10) ? "0" . $diffInSeconds : $diffInSeconds;

        //return implode(' ', array($diffInDays, implode(':', array($diffInHours, $diffInMinutes, $diffInSeconds))));
        return implode(':', array($diffInHours, $diffInMinutes, $diffInSeconds));
    }

    public static function returnFirstDayOfMonthDateFormatted(){
      //return date('01-M-Y', time());
      return Carbon::now()->format('01-M-Y');
    }

    public static function returnFirstDayOfMonthDateTimeFormatted(){
      //return date('01-M-Y 00:00', time());
      return Carbon::now()->format('01-M-Y 00:00');
    }
}