<?php

namespace App\Services;

use App\Helpers\ErrorHelper;
use App\Models\Postgres\TerminalModel;
use App\Helpers\IPAddressHelper;
use App\Helpers\ErrorConstants;

/**
 * Class PlayerService
 * @package App\Services
 */
class TerminalService
{

    /**
     * @param $phpObject
     * @return array
     */
    public static function getSelfServiceTerminalName($phpObject){
        if(strlen($phpObject->mac_address) == 0){
            $detected_ip_address = IPAddressHelper::getDetectedIPAddress();
            $message = "TerminalService::getSelfServiceTerminalName(" . print_r($phpObject, true) . ") <br /> Detected IP Address = {$detected_ip_address}";
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
            'mac_address' => $phpObject->mac_address
        );
        $resultSelfServiceTerminalName = TerminalModel::getSelfServiceTerminalName($details);
        if($resultSelfServiceTerminalName['status'] !== 'OK'){
            return array(
                "status" => 'NOK',
                "operation" => $phpObject->operation
            );
        }
        return array(
            "status" => $resultSelfServiceTerminalName['status'],
            "operation" => $phpObject->operation,
            "mac_address"=>$phpObject->mac_address,
            "terminal_name"=>$resultSelfServiceTerminalName['terminal_name']
        );
    }
}
