<?php

namespace App\Services;

use App\Models\Postgres\UserModel;
use App\Helpers\PasswordHasherHelper;
use App\Helpers\ErrorHelper;
use App\Helpers\ErrorConstants;
use App\Helpers\IPAddressHelper;

/**
 * Class UserService
 * @package App\Services
 */
class UserService
{

    /**
     * @param $phpObject
     * @return array
     */
    public static function changePassword($phpObject){
        if(strlen($phpObject->session_id) == 0 || strlen($phpObject->user_id) == 0 || strlen($phpObject->password) == 0){
            $detected_ip_address = IPAddressHelper::getDetectedIPAddress();
            $message = "UserService::changePassword(" . print_r($phpObject, true) . ") <br /> Detected IP Address = {$detected_ip_address}";
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
            'password' =>  PasswordHasherHelper::make($phpObject->password)
        );
        $resultChangePassword = UserModel::changePassword($details);
        if($resultChangePassword['status'] !== 'OK'){
            return array(
                "status" => 'NOK',
                "operation" => $phpObject->operation,
            );
        }
        return array(
            "status" => 'OK',
            "operation" => $phpObject->operation,
            "session_id"=>$phpObject->session_id
        );
    }

    /**
     * @param $phpObject
     * @return array
     */
    public static function personalInformation($phpObject){
        if(strlen($phpObject->session_id) == 0){
            $detected_ip_address = IPAddressHelper::getDetectedIPAddress();
            $message = "UserService::personalInformation(" . print_r($phpObject, true) . ") <br /> Detected IP Address = {$detected_ip_address}";
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
        $resultPersonalInformation = UserModel::personalInformation($details);
        if($resultPersonalInformation['status'] !== 'OK'){
            return array(
                "status" => 'NOK',
                "operation" => $phpObject->operation,
            );
        }
        return array(
            "status" => 'OK',
            "operation" => $phpObject->operation,
            "session_id" => $details['session_id'],
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
            "list_currency"=> $resultPersonalInformation['list_currency']
        );
    }

    /**
     * @param $phpObject
     * @return array
     */
    public static function updatePersonalInformation($phpObject){
        if(strlen($phpObject->session_id) == 0 || strlen($phpObject->user_id) == 0){
            $detected_ip_address = IPAddressHelper::getDetectedIPAddress();
            $message = "UserService::updatePersonalInformation(" . print_r($phpObject, true) . ") <br /> Detected IP Address = {$detected_ip_address}";
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
        $resultPersonalInformation = UserModel::personalInformation($details);
        if($resultPersonalInformation['status'] !== 'OK'){
            return array(
                "status" => 'NOK',
                "operation" => $phpObject->operation,
            );
        }
        $user = array(
            'user_id'=>$phpObject->user_id,
            'username'=>$resultPersonalInformation['user']['username'],
            'first_name'=>$phpObject->first_name,
            'last_name'=>$phpObject->last_name,
            'currency'=>$phpObject->currency,
            'email'=>$phpObject->email,
            'edited_by'=>$phpObject->user_id,
            'player_type'=>$phpObject->subject_type,
            'subject_state'=>$phpObject->account_active,
            'language'=>$phpObject->language,
            'address'=>$phpObject->address,
            'commercial_address'=>$phpObject->commercial_address,
            'city'=>$phpObject->city,
            'country'=>$phpObject->country_code,
            'mobile_phone'=>$phpObject->mobile_phone,
            'post_code'=>$phpObject->post_code,
        );

        $resultUpdatePersonalInformation = UserModel::updateUser($user);
        if($resultUpdatePersonalInformation['status'] !== 'OK'){
            return array(
                "status" => 'NOK',
                "operation" => $phpObject->operation,
            );
        }
        return array(
            "status" => 'OK',
            "operation" => $phpObject->operation,
            "session_id"=>$details['session_id']
        );
    }
}
