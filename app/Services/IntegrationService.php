<?php

namespace App\Services;

use App\Helpers\ErrorHelper;
use App\Models\Postgres\IntegrationModel;
use App\Helpers\IPAddressHelper;
use App\Helpers\ErrorConstants;
use App\Helpers\DetectPlatformHelper;

/**
 * Class IntegrationService
 * @package App\Services
 */
class IntegrationService
{
    /**
     * @param $phpObject
     * @return array
     */
    public static function integratePlayerHierarchy($phpObject){
        if(strlen($phpObject->username) == 0 || strlen($phpObject->subject_path) == 0 || strlen($phpObject->currency) == 0){
            $detected_ip_address = IPAddressHelper::getDetectedIPAddress();
            $message = "IntegrationService::integratePlayerHierarchy(" . print_r($phpObject, true) . ") <br /> Detected IP Address = {$detected_ip_address}";
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

        $device_information = DetectPlatformHelper::getPlatformInformation($phpObject->login_platform);
        $device = $device_information['device_information'];

        $details = array(
            'username'=>$phpObject->username,
            'subject_path'=>$phpObject->subject_path,
            'currency'=>$phpObject->currency,
            'mac_address'=>$phpObject->mac_address,
            'player_type'=>$phpObject->player_type,
            'ip_address'=>$phpObject->ip_address,
            'login_platform' => $device,
            'credits' => $phpObject->credits
        );

        $resultIntegratePlayerHierarchy = IntegrationModel::integratePlayerHierarchy($details);
        if($resultIntegratePlayerHierarchy['status'] == "OK"){
            return array(
                "status" => 'OK',
                "operation" => $phpObject->operation,
                "status_out" => $resultIntegratePlayerHierarchy['status_out'],
                "session_id_out" => $resultIntegratePlayerHierarchy['session_id_out']
            );
        }else{
            $error = ErrorConstants::getErrorMessage(ErrorConstants::$GENERAL_ERROR);
            return array(
                "status" => 'NOK',
                "operation" => $phpObject->operation,
                "error_message" => $error['message_text'],
                "error_code" => $error['message_no'],
                "error_description" =>$error['message_description']
            );
        }
    }

    /**
     * @param $phpObject
     * @return array
     */
    public static function postIntegrationTransaction($phpObject){
        if(strlen($phpObject->player_id) == 0 || strlen($phpObject->amount) == 0 || strlen($phpObject->game_id) == 0){
            $detected_ip_address = IPAddressHelper::getDetectedIPAddress();
            $message = "IntegrationService::postIntegrationTransaction(" . print_r($phpObject, true) . ") <br /> Detected IP Address = {$detected_ip_address}";
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

        try {

            //$integrationURL = 'http://www.best200.com/onlinecasinoservice_dev/lucky-game-integration/post-transaction';
            $integrationURL = 'http://www.best200.com/onlinecasinoservice_dev/lucky-game-integration/post-transaction';
            //$integrationURL = "http:\//192.168.3.63\/onlinecasinoservice\/lucky-game-integration\/post-transaction";

            $fields = array(
                'player_id' => urlencode($phpObject->player_id),
                'amount' => urlencode($phpObject->amount),
                'game_id' => urlencode($phpObject->game_id),
                'transaction_type_id' => urlencode($phpObject->transaction_type_id),
                'game_session_id' => urlencode($phpObject->game_session_id),
            );

            $fields_string = "";
            foreach ($fields as $key => $value) {
                $fields_string .= $key . '=' . $value . '&';
            }
            rtrim($fields_string, '&');
            //start post init
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $integrationURL);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 300);
            curl_setopt($ch, CURLOPT_POST, count($fields));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
            //disable ssl verification
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD, "active:studio");
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Connection: keep-alive'
            ));

            $data = curl_exec($ch);

            if (curl_errno($ch)) {
                //there was an error sending post to void purchase player's transaction
                $error_message = curl_error($ch);
                $message = "Error while sending HTTP request for lucky 6 integration. <br /> Message: <br /> {$error_message}";
                ErrorHelper::writeError($message, $message);

                $error = ErrorConstants::getErrorMessage(ErrorConstants::$GENERAL_ERROR);
                return array(
                    "status" => 'NOK',
                    "operation" => $phpObject->operation,
                    "error_message" => $error['message_text'],
                    "error_code" => $error['message_no'],
                    "error_description" => $error['message_description']
                );
            } else {
                curl_close($ch);

                $json_response = json_decode($data, true);
                if ($json_response['status'] == "OK") {
                    return array(
                        "status" => 'OK',
                        "operation" => $phpObject->operation,
                        "transaction_id_out" => $json_response['transaction_id_out']
                    );
                } else {
                    $message = "Lucky 6 Integration Web service returns exception error code.";
                    ErrorHelper::writeError($message, $message);

                    $error = ErrorConstants::getErrorMessage(ErrorConstants::$GENERAL_ERROR);
                    return array(
                        "status" => 'NOK',
                        "operation" => $phpObject->operation,
                        "error_message" => $error['message_text'],
                        "error_code" => $error['message_no'],
                        "error_description" => $error['message_description']
                    );
                }
            }
        }catch(\Exception $ex){
            $message = $ex->getMessage();
            ErrorHelper::writeError($message, $message);

            $error = ErrorConstants::getErrorMessage(ErrorConstants::$GENERAL_ERROR);
            return array(
                "status" => 'NOK',
                "operation" => $phpObject->operation,
                "error_message" => $error['message_text'],
                "error_code" => $error['message_no'],
                "error_description" => $error['message_description']
            );
        }
    }

    /**
     * @param $phpObject
     * @return array
     */
    public static function setIntegrationCredits($phpObject){
        if(strlen($phpObject->username) == 0 || strlen($phpObject->credits) == 0){
            $detected_ip_address = IPAddressHelper::getDetectedIPAddress();
            $message = "IntegrationService::setIntegrationCredits(" . print_r($phpObject, true) . ") <br /> Detected IP Address = {$detected_ip_address}";
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
            'username'=>$phpObject->username,
            'credits'=>$phpObject->credits,
        );

        $resultSetIntegrationCredits = IntegrationModel::setIntegrationCredits($details);
        if($resultSetIntegrationCredits['status'] == "OK"){
            return array(
                "status" => 'OK',
                "operation" => $phpObject->operation,
                "status_out" => $resultSetIntegrationCredits['status_out']
            );
        }else{
            $error = ErrorConstants::getErrorMessage(ErrorConstants::$GENERAL_ERROR);
            return array(
                "status" => 'NOK',
                "operation" => $phpObject->operation,
                "error_message" => $error['message_text'],
                "error_code" => $error['message_no'],
                "error_description" =>$error['message_description']
            );
        }
    }

    /**
     * @param $phpObject
     * @return array
     */
    public static function getPendingWinsForIntegration($phpObject){
        if(strlen($phpObject->player_id) == 0){
            $detected_ip_address = IPAddressHelper::getDetectedIPAddress();
            $message = "IntegrationService::getPendingWinsForIntegration(" . print_r($phpObject, true) . ") <br /> Detected IP Address = {$detected_ip_address}";
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
            'player_id' => $phpObject->player_id,
        );

        $resultGetPendingWinsForIntegration = IntegrationModel::getPendingWinsForIntegration($details);
        if($resultGetPendingWinsForIntegration['status'] == "OK"){
            return array(
                "status" => 'OK',
                "operation" => $phpObject->operation,
                "sum_win" => $resultGetPendingWinsForIntegration['sum_win']
            );
        }else{
            $error = ErrorConstants::getErrorMessage(ErrorConstants::$GENERAL_ERROR);
            return array(
                "status" => 'NOK',
                "operation" => $phpObject->operation,
                "error_message" => $error['message_text'],
                "error_code" => $error['message_no'],
                "error_description" =>$error['message_description']
            );
        }
    }


}
