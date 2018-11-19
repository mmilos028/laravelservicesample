<?php

namespace App\Services;

use App\Models\Postgres\ShiftCashierModel;
use App\Helpers\NumberHelper;
use App\Helpers\ErrorHelper;
use App\Helpers\ErrorConstants;
use App\Helpers\IPAddressHelper;

/**
 * Class ShiftCashierService
 * @package App\Services
 */
class ShiftCashierService
{

    /**
     * @param $phpObject
     * @return array
     */
    public static function listCollectorShiftReport($phpObject)
    {
        if(strlen($phpObject->session_id) == 0 || strlen($phpObject->collector_id) == 0 || strlen($phpObject->cashier_id) == 0 || strlen($phpObject->service_code) == 0){
            $detected_ip_address = IPAddressHelper::getDetectedIPAddress();
            $message = "ShiftCashierService::listCollectorShiftReport(" . print_r($phpObject, true) . ") <br /> Detected IP Address = {$detected_ip_address}";
            $error = ErrorConstants::getErrorMessage(ErrorConstants::$MISSING_PARAMETERS);
            ErrorHelper::writeError($message, $message);
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
            "collector_id" => $phpObject->collector_id,
            "cashier_id" => $phpObject->cashier_id,
            "service_code" => $phpObject->service_code,
        );

        $resultListCollectorShiftReport = ShiftCashierModel::listCollectorShiftReport($details);
        if($resultListCollectorShiftReport['status'] != 'OK'){
            return array(
                "status" => 'NOK',
                "operation" => $phpObject->operation,
            );
        }
        $report = array();
        foreach($resultListCollectorShiftReport['report'] as $rep){
            $report[] = array(
                "shift_number" => $rep->shift_no,
                "total" => NumberHelper::convert_double($rep->total),
                "total_formatted" => NumberHelper::format_double($rep->total),
                "end_balance" => NumberHelper::convert_double($rep->end_balance),
                "end_balance_formatted" => NumberHelper::format_double($rep->end_balance),
            );
        }

        return array(
            "status" => 'OK',
            "operation" => $phpObject->operation,
            "session_id" => $details['session_id'],
            "collector_id" => $details['collector_id'],
            "cashier_id" => $details['cashier_id'],
            "service_code" => $details['service_code'],
            "report" => $report,
            "collector_name" => $resultListCollectorShiftReport['collector_name'],
            "last_collect_date_time" => $resultListCollectorShiftReport['last_collect_date_time']
        );
    }

    /**
     * @param $phpObject
     * @return array
     */
    public static function listCashierShiftReport($phpObject)
    {
        if(strlen($phpObject->session_id) == 0 || strlen($phpObject->cashier_id) == 0 || strlen($phpObject->shift_number) == 0){
            $detected_ip_address = IPAddressHelper::getDetectedIPAddress();
            $message = "ShiftCashierService::listCashierShiftReport(" . print_r($phpObject, true) . ") <br /> Detected IP Address = {$detected_ip_address}";
            $error = ErrorConstants::getErrorMessage(ErrorConstants::$MISSING_PARAMETERS);
            ErrorHelper::writeError($message, $message);
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
            "cashier_id" => $phpObject->cashier_id,
            "shift_number" => $phpObject->shift_number
        );

        $resultListCashierShiftReport = ShiftCashierModel::listCashierShiftReport($details);
        if($resultListCashierShiftReport['status'] != 'OK'){
            return array(
                "status" => 'NOK',
                "operation" => $phpObject->operation,
            );
        }
        $report = array();
        foreach($resultListCashierShiftReport['report'] as $rep){
            $report[] = array(
                "shift_number" => $rep->shift_no,
                "start_balance" => NumberHelper::convert_double($rep->start_balance),
                "start_balance_formatted" => NumberHelper::format_double($rep->start_balance),
                "end_balance" => NumberHelper::convert_double($rep->end_balance),
                "end_balance_formatted" => NumberHelper::format_double($rep->end_balance),
                "shift_start_time" => $rep->shift_start_time,
                "shift_end_time" => $rep->shift_end_time,
                "sum_deposit" => NumberHelper::convert_double($rep->sum_deposit),
                "sum_deposit_formatted" => NumberHelper::format_double($rep->sum_deposit),
                "sum_payout" => NumberHelper::convert_double($rep->sum_payout),
                "sum_payout_formatted" => NumberHelper::format_double($rep->sum_payout),
                "sum_storno" => NumberHelper::convert_double($rep->sum_storno),
                "sum_storno_formatted" => NumberHelper::format_double($rep->sum_storno),
            );
        }

        return array(
            "status" => 'OK',
            "operation" => $phpObject->operation,
            "session_id" => $details['session_id'],
            "cashier_id" => $details['cashier_id'],
            "shift_number" => $details['shift_number'],
            "report" => $report,
        );
    }

    /**
     * @param $phpObject
     * @return array
     */
    public static function collectFromCashier($phpObject)
    {
        if(strlen($phpObject->session_id) == 0 || strlen($phpObject->cashier_id) == 0 || strlen($phpObject->collector_id) == 0 || strlen($phpObject->amount) == 0 || strlen($phpObject->service_code) == 0){
            $detected_ip_address = IPAddressHelper::getDetectedIPAddress();
            $message = "ShiftCashierService::collectFromCashier(" . print_r($phpObject, true) . ") <br /> Detected IP Address = {$detected_ip_address}";
            $error = ErrorConstants::getErrorMessage(ErrorConstants::$MISSING_PARAMETERS);
            ErrorHelper::writeError($message, $message);
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
            "cashier_id" => $phpObject->cashier_id,
            "collector_id" => $phpObject->collector_id,
            "amount" => $phpObject->amount,
            "service_code" => $phpObject->service_code
        );

        $resultCollectFromCashier = ShiftCashierModel::collectFromCashier($details);
        if($resultCollectFromCashier['status'] != 'OK'){
            return array(
                "status" => 'NOK',
                "operation" => $phpObject->operation,
            );
        }

        if($resultCollectFromCashier['status'] == 'OK' && $resultCollectFromCashier['status_out'] == "1") {
            return array(
                "status" => 'OK',
                "operation" => $phpObject->operation,
                "session_id" => $details['session_id'],
                "cashier_id" => $details['cashier_id'],
                "collector_id" => $details['collector_id'],
                "amount" => $details['amount'],
                "service_code" => $details['service_code'],
            );
        }else{
            return array(
                "status" => 'NOK',
                "operation" => $phpObject->operation,
                "session_id" => $details['session_id'],
                "cashier_id" => $details['cashier_id'],
                "collector_id" => $details['collector_id'],
                "amount" => $details['amount'],
                "service_code" => $details['service_code'],
            );
        }
    }
}