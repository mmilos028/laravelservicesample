<?php

namespace App\Services;

use App\Models\Postgres\SessionModel;
use App\Helpers\ErrorHelper;
use App\Helpers\ErrorConstants;
use App\Helpers\IPAddressHelper;

/**
 * Class SessionService
 * @package App\Services
 */
class SessionService
{

    /**
     * @param $phpObject
     * @return array
     */
    public static function getGameDrawSession($phpObject){
        if(strlen($phpObject->session_id) == 0){
            $detected_ip_address = IPAddressHelper::getDetectedIPAddress();
            $message = "SessionService::getGameDrawSession(" . print_r($phpObject, true) . ") <br /> Detected IP Address = {$detected_ip_address}";
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
            "session_id" => $phpObject->session_id,
        );

        $resultSession = SessionModel::getGameDrawSession($details);
        if($resultSession['status'] != 'OK'){
            return array(
                "status" => 'NOK',
                "operation" => $phpObject->operation
            );
        }

        return array(
            "status" => $resultSession['status'],
            "operation" => $phpObject->operation,
            "session_id"=>$resultSession['session_id'],
            "game_draw_session_id"=>$resultSession['game_draw_session_id']
        );
    }
}
