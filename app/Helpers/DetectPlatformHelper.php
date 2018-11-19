<?php
namespace App\Helpers;

class DetectPlatformHelper
{

    public static function getPlatformInformation($device)
    {
        if (strlen($device) == 0) {
            $Android = stripos($_SERVER['HTTP_USER_AGENT'], "Android");
            $iPhone = stripos($_SERVER['HTTP_USER_AGENT'], "iPhone");
            $iPad = stripos($_SERVER['HTTP_USER_AGENT'], "iPad");

            if ($Android || $iPhone || $iPad) {
                $device = config("constants.REST");
            } else {
                $device = config("constants.PC");
            }
        } else {
            $Android = stripos($device, "Android");
            $iPhone = stripos($device, "iPhone");
            $iPad = stripos($device, "iPad");

            if ($Android || $iPhone || $iPad) {
                $device = config("constants.REST");
            } else {
                $device = config("constants.PC");
            }
        }
        return array(
            "device_information" => $device
        );
    }

    public static function getPCPlatformInformation($device){
        return array(
            "device_information" => config("constants.PC")
        );
    }

    public static function getRestPlatformInformation($device){
        return array(
            "device_information" => config("constants.REST")
        );
    }
}