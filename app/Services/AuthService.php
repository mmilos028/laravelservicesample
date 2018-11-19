<?php

namespace App\Services;

use App\Helpers\DetectPlatformHelper;
use App\Helpers\PasswordHasherHelper;
use App\Models\Postgres\AuthModel;
use App\Models\Postgres\UserModel;
use App\Helpers\ErrorHelper;
use App\Helpers\ErrorConstants;
use App\Helpers\IPAddressHelper;

/**
 * Class AuthService
 * @package App\Services
 */
class AuthService
{

    /**
     * @param $phpObject
     * @return array
     */
    public static function loginSubject($phpObject){
        if(strlen($phpObject->username) == 0 || strlen($phpObject->password) == 0){
            $detected_ip_address = IPAddressHelper::getDetectedIPAddress();
            $message = "AuthService::loginSubject(" . print_r($phpObject, true) . ") <br /> Detected IP Address = {$detected_ip_address}";
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
        $ip_address = $phpObject->ip_address;
        if(strlen($ip_address) == 0){
            $ip_address = IPAddressHelper::getRealIPAddress();
        }

        $device_information = DetectPlatformHelper::getPlatformInformation($phpObject->device);
        $device = $device_information['device_information'];

        //$hashed_password = PasswordHasherHelper::make($phpObject->password);
        if($phpObject->subject_type_id == 5 || $phpObject->subject_type_id == ""){
            $hashed_password = PasswordHasherHelper::make($phpObject->password);
        }else {
            $hashed_password = $phpObject->password;
        }

        $details = array(
            "username" => $phpObject->username,
            "password" => $hashed_password,
            "ip_address" => $ip_address,
            "device" => $device,
            "subject_type_id" => $phpObject->subject_type_id
        );

        $resultAuth = AuthModel::loginSubject($details);
        if($resultAuth['status'] != 'OK'){
            return array(
                "status" => 'NOK',
                "operation" => $phpObject->operation
            );
        }
        $resultPersonalInformation = UserModel::personalInformation($resultAuth);
        if($resultPersonalInformation['status'] != 'OK'){
            return array(
                "status" => 'NOK',
                "operation" => $phpObject->operation
            );
        }

        //$affiliate_id, $ticket_status, $start_date, $end_date

        return array(
            "status" => $resultAuth['status'],
            "operation" => $phpObject->operation,
            "session_id"=>$resultAuth['session_id'],
            "status_out"=>$resultAuth['status_out'],
            "user_details" => array(
                "user_id" => $resultPersonalInformation['user']['user_id'],
                "username" => $resultPersonalInformation['user']['username'],
                "first_name" => $resultPersonalInformation['user']['first_name'],
                "last_name" => $resultPersonalInformation['user']['last_name'],
                "email" => $resultPersonalInformation['user']['email'],
                "registration_date" => $resultPersonalInformation['user']['registration_date'],
                "subject_type" => $resultPersonalInformation['user']['subject_type'],
                "active" => $resultPersonalInformation['user']['active'],
                "language" => $resultPersonalInformation['user']['language'],
                "parent_id"=> $resultPersonalInformation['user']['parent_id'],
                "affiliate_id" => $resultPersonalInformation['user']['affiliate_id'],
                "parent_username" => $resultPersonalInformation['user']['parent_username'],
                "address"=> $resultPersonalInformation['user']['address'],
                "commercial_address" => $resultPersonalInformation['user']['commercial_address'],
                "city"=> $resultPersonalInformation['user']['city'],
                "country_code"=> $resultPersonalInformation['user']['country_code'],
                "country_name"=> $resultPersonalInformation['user']['country_name'],
                "mobile_phone"=> $resultPersonalInformation['user']['mobile_phone'],
                "post_code"=> $resultPersonalInformation['user']['post_code'],
                "currency" => $resultPersonalInformation['user']['currency']
            ),
            //"list_currency"=> $resultPersonalInformation['list_currency']
        );
    }

    /**
     * @param $phpObject
     * @return array
     */
    public static function loginCashier($phpObject){
        if(strlen($phpObject->username) == 0 || strlen($phpObject->password) == 0 || strlen($phpObject->service_code) == 0){
            $detected_ip_address = IPAddressHelper::getDetectedIPAddress();
            $message = "AuthService::loginCashier(" . print_r($phpObject, true) . ") <br /> Detected IP Address = {$detected_ip_address}";
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
        $ip_address = $phpObject->ip_address;
        if(strlen($ip_address) == 0){
            $ip_address = IPAddressHelper::getRealIPAddress();
        }


        $device_information = DetectPlatformHelper::getPlatformInformation($phpObject->device);
        $device = $device_information['device_information'];

        $hashed_password = PasswordHasherHelper::make($phpObject->password);

        $details = array(
            "username" => $phpObject->username,
            "password" => $hashed_password,
            "ip_address" => $ip_address,
            "device" => $device,
            "service_code" => $phpObject->service_code,
            "subject_type_id" => $phpObject->subject_type_id
        );

        $resultAuth = AuthModel::loginCashier($details);
        if($resultAuth['status'] != 'OK'){
            return array(
                "status" => 'NOK',
                "operation" => $phpObject->operation,
            );
        }
        $resultPersonalInformation = UserModel::personalInformation($resultAuth);
        if($resultPersonalInformation['status'] != 'OK'){
            return array(
                "status" => 'NOK',
                "operation" => $phpObject->operation,
            );
        }

        //$affiliate_id, $ticket_status, $start_date, $end_date

        return array(
            "status" => $resultAuth['status'],
            "operation" => $phpObject->operation,
            "session_id"=>$resultAuth['session_id'],
            "status_out"=>$resultAuth['status_out'],
            "user_details" => array(
                "user_id" => $resultPersonalInformation['user']['user_id'],
                "username" => $resultPersonalInformation['user']['username'],
                "first_name" => $resultPersonalInformation['user']['first_name'],
                "last_name" => $resultPersonalInformation['user']['last_name'],
                "email" => $resultPersonalInformation['user']['email'],
                "registration_date" => $resultPersonalInformation['user']['registration_date'],
                "subject_type" => $resultPersonalInformation['user']['subject_type'],
                "active" => $resultPersonalInformation['user']['active'],
                "language" => $resultPersonalInformation['user']['language'],
                "parent_id"=> $resultPersonalInformation['user']['parent_id'],
                "affiliate_id" => $resultPersonalInformation['user']['affiliate_id'],
                "parent_username" => $resultPersonalInformation['user']['parent_username'],
                "address"=> $resultPersonalInformation['user']['address'],
                "commercial_address" => $resultPersonalInformation['user']['commercial_address'],
                "city"=> $resultPersonalInformation['user']['city'],
                "country_code"=> $resultPersonalInformation['user']['country_code'],
                "country_name"=> $resultPersonalInformation['user']['country_name'],
                "mobile_phone"=> $resultPersonalInformation['user']['mobile_phone'],
                "post_code"=> $resultPersonalInformation['user']['post_code'],
                "currency" => $resultPersonalInformation['user']['currency'],
                "shift_number" => $resultPersonalInformation['user']['shift_number']
            ),
            //"list_currency"=> $resultPersonalInformation['list_currency']
        );
    }

    /**
     * @param $phpObject
     * @return array
     */
    public static function logoutSubject($phpObject){
        if(strlen($phpObject->session_id) == 0){
            $detected_ip_address = IPAddressHelper::getDetectedIPAddress();
            $message = "AuthService::logoutSubject(" . print_r($phpObject, true) . ") <br /> Detected IP Address = {$detected_ip_address}";
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
        $resultLogout = AuthModel::logoutSubject($details);
        if($resultLogout['status'] != 'OK'){
            return array(
                "status" => 'NOK',
                "operation" => $phpObject->operation,
            );
        }
        return array(
            "status" => $resultLogout['status'],
            "operation" => $phpObject->operation,
            "status_out"=>$resultLogout['status_out'],
        );
    }

}
