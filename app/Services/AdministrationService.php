<?php

namespace App\Services;

use App\Helpers\ErrorHelper;
use App\Helpers\ErrorConstants;
use App\Helpers\IPAddressHelper;
use App\Models\Postgres\AdministrationModel;

/**
 * Class AdministrationService
 * @package App\Services
 */
class AdministrationService
{

    /**
     * @param $phpObject
     * @return array
     */
    public static function listAffiliatesAndParameters($phpObject){
        if(strlen($phpObject->session_id) == 0){
            $detected_ip_address = IPAddressHelper::getDetectedIPAddress();
            $message = "AdministrationService::listAffiliatesAndParameters(" . print_r($phpObject, true) . ") <br /> Detected IP Address = {$detected_ip_address}";
            ErrorHelper::writeError($message, $message);
            $error = ErrorConstants::getErrorMessage(ErrorConstants::$MISSING_PARAMETERS);
            return array(
                "status" => 'NOK',
                "operation" => $phpObject->operation,
                "error_message" => $error["message_text"],
                "error_code" => $error["message_no"],
                "error_description" =>$error["message_description"]
            );
        }
        $details = array(
            "session_id" => $phpObject->session_id
        );

        $resultListAffiliatesAndParameters = AdministrationModel::listAffiliatesAndParameters($details);
        if($resultListAffiliatesAndParameters['status'] !== 'OK'){
            return array(
                "status" => 'NOK',
                "operation" => $phpObject->operation,
                "session_id" => $phpObject->session_id
            );
        }
        return array(
            "status" => 'OK',
            "operation" => $phpObject->operation,
            "session_id"=>$phpObject->session_id,
            "list_report" => $resultListAffiliatesAndParameters["list_report"]
        );
    }
}