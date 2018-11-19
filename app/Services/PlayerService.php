<?php

namespace App\Services;

use App\Helpers\NumberHelper;
use App\Helpers\ErrorHelper;
use App\Models\Postgres\PlayerModel;
use App\Helpers\IPAddressHelper;
use App\Helpers\ErrorConstants;

/**
 * Class PlayerService
 * @package App\Services
 */
class PlayerService
{

    /**
     * @param $phpObject
     * @return array
     */
    public static function changePassword($phpObject){
        if(strlen($phpObject->session_id) == 0 || strlen($phpObject->user_id) == 0){
            $detected_ip_address = IPAddressHelper::getDetectedIPAddress();
            $message = "PlayerService::changePassword(" . print_r($phpObject, true) . ") <br /> Detected IP Address = {$detected_ip_address}";
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
            'backoffice_session_id' => $phpObject->session_id,
            'user_id' => $phpObject->user_id,
            'password' => $phpObject->password
        );
        $resultChangePassword = PlayerModel::changePassword($details);
        if($resultChangePassword['status'] !== 'OK'){
            return array(
                "status" => 'NOK',
                "operation" => $phpObject->operation,
            );
        }
        return array(
            "status" => $resultChangePassword['status'],
            "operation" => $phpObject->operation,
            "session_id"=>$phpObject->session_id
        );
    }

    /**
     * @param $phpObject
     * @return array
     */
    public static function getCredits($phpObject){
        if(strlen($phpObject->session_id) == 0 || strlen($phpObject->user_id) == 0){
            $detected_ip_address = IPAddressHelper::getDetectedIPAddress();
            $message = "PlayerService::getCredits(" . print_r($phpObject, true) . ") <br /> Detected IP Address = {$detected_ip_address}";
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
            'session_id' => $phpObject->session_id,
            'user_id' => $phpObject->user_id
        );
        $resultGetCredits = PlayerModel::getCredits($details);
        if($resultGetCredits['status'] !== 'OK'){
            return array(
                "status" => 'NOK',
                "operation" => $phpObject->operation,
            );
        }
        return array(
            "status" => 'OK',
            "operation" => $phpObject->operation,
            "session_id"=>$phpObject->session_id,
            "status_out" => $resultGetCredits['status_out'],
            "credits" => NumberHelper::convert_double($resultGetCredits['credits']),
            "credits_formatted" => NumberHelper::format_double($resultGetCredits['credits'])
        );
    }

    /**
     * @param $phpObject
     * @return array
     */
    public static function playerInformation($phpObject){
        if(strlen($phpObject->session_id) == 0 || strlen($phpObject->user_id) == 0 ){
            $detected_ip_address = IPAddressHelper::getDetectedIPAddress();
            $message = "PlayerService::playerInformation(" . print_r($phpObject, true) . ") <br /> Detected IP Address = {$detected_ip_address}";
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
            "player_id" => $phpObject->user_id
        );
        $resultPlayerInformation = PlayerModel::playerInformation($details);
        if($resultPlayerInformation['status'] !== 'OK'){
            return array(
                "status" => 'NOK',
                "operation" => $phpObject->operation,
            );
        }
        return array(
            "status" => 'OK',
            "operation" => $phpObject->operation,
            "details" => array(
                "user_id" => $resultPlayerInformation['user']['user_id'],
                "username" => $resultPlayerInformation['user']['username'],
                "first_name" => $resultPlayerInformation['user']['first_name'],
                "last_name" => $resultPlayerInformation['user']['last_name'],
                "email" => $resultPlayerInformation['user']['email'],
                "registration_date" => $resultPlayerInformation['user']['registration_date'],
                "subject_type" => $resultPlayerInformation['user']['subject_type'],
                "active" => $resultPlayerInformation['user']['active'],
                "language" => $resultPlayerInformation['user']['language'],
                "parent_id"=> $resultPlayerInformation['user']['parent_id'],
                "parent_username" => $resultPlayerInformation['user']['parent_username'],
                "address"=> $resultPlayerInformation['user']['address'],
                "address2" => $resultPlayerInformation['user']['commercial_address'],
                "city"=> $resultPlayerInformation['user']['city'],
                "country_code"=> $resultPlayerInformation['user']['country_code'],
                "country_name"=> $resultPlayerInformation['user']['country_name'],
                "mobile_phone"=> $resultPlayerInformation['user']['mobile_phone'],
                "post_code"=> $resultPlayerInformation['user']['post_code'],
            )
        );
    }

    /**
     * @param $phpObject
     * @return array
     */
    public static function createNewPlayer($phpObject){
        if(strlen($phpObject->session_id) == 0 || strlen($phpObject->user_id) == 0 || strlen($phpObject->username) == 0
            || strlen($phpObject->currency) == 0 || strlen($phpObject->password) == 0 || strlen($phpObject->country) == 0 || strlen($phpObject->language) == 0){
            $detected_ip_address = IPAddressHelper::getDetectedIPAddress();
            $message = "PlayerService::createNewPlayer(" . print_r($phpObject, true) . ") <br /> Detected IP Address = {$detected_ip_address}";
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

        $hashed_password = $phpObject->password;

        $user = array(
            'username'=>$phpObject->username,
            'password'=>$hashed_password,
            'email'=>$phpObject->email,
            'first_name'=>$phpObject->first_name,
            'last_name'=>$phpObject->last_name,
            'currency'=>$phpObject->currency,
            'parent_name' => $phpObject->parent_name,
            'registered_by' => $phpObject->registered_by,
            //'parent_name'=>$resultPersonalInformation['user']['username'],
            //'registered_by'=>$resultPersonalInformation['user']['username'],
            'subject_type_id'=>config("constants.PLAYER_TYPE_ID"),
            'player_type_name'=>config("constants.PLAYER_TYPE_NAME"),
            'language'=>$phpObject->language,
            'address'=>$phpObject->address,
            'city'=>$phpObject->city,
            'country'=>$phpObject->country,
            'mobile_phone'=>$phpObject->mobile_phone,
            'post_code'=>$phpObject->post_code,
            'commercial_address'=>$phpObject->address2
        );

        $resultInsertPlayerInformation = PlayerModel::createUser($user);
        if($resultInsertPlayerInformation['status'] == "OK"){
            return array(
                "status" => 'OK',
                "operation" => $phpObject->operation,
                "user_id" => $resultInsertPlayerInformation['subject_id']
            );
        }else{
            $error = ErrorConstants::getErrorMessage(ErrorConstants::$GENERAL_ERROR);
            if($resultInsertPlayerInformation["message"] == "EMAIL NOT AVAILABLE"){
                $error = ErrorConstants::getErrorMessage(ErrorConstants::$EMAIL_NOT_AVAILABLE);
            }
            if($resultInsertPlayerInformation["message"] == "USERNAME NOT AVAILABLE"){
                $error = ErrorConstants::getErrorMessage(ErrorConstants::$USERNAME_NOT_AVAILABLE);
            }

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
