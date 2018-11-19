<?php

namespace App\Services;

use App\Models\Postgres\TicketModel;
use App\Helpers\ErrorHelper;
use App\Helpers\ErrorConstants;
use App\Helpers\IPAddressHelper;

/**
 * Class TicketService
 * @package App\Services
 */
class TicketService
{

    /**
     * @param $phpObject
     * @return array
     */
    public static function updateStatusTicketWin($phpObject){
        if(strlen($phpObject->ticket_id) == 0 || strlen($phpObject->session_id) == 0){
            $detected_ip_address = IPAddressHelper::getDetectedIPAddress();
            $message = "TicketService::updateStatusTicketWin(" . print_r($phpObject, true) . ") <br /> Detected IP Address = {$detected_ip_address}";
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
        $ticketDetails = array(
            'session_id' => $phpObject->session_id,
            'ticket_id' => $phpObject->ticket_id
        );
        $resultSaveTicket = TicketModel::updateStatusTicketWin($ticketDetails);
        if($resultSaveTicket['status'] !== 'OK'){
            if($resultSaveTicket['error_code'] == ErrorConstants::$PAYOUT_TIME_EXPIRED){
                $error = ErrorConstants::getErrorMessage(ErrorConstants::$PAYOUT_TIME_EXPIRED);
                return array(
                    "status" => 'NOK',
                    "operation" => $phpObject->operation,
                    "session_id" => $phpObject->session_id,
                    "ticket_id" => $phpObject->ticket_id,
                    "true_status" => $resultSaveTicket['true_status'],
                    "message" => $resultSaveTicket['message'],

                    "error_message" => $error["message_text"],
                    "error_code" => $error["message_no"],
                    "error_description" => $error["message_description"]
                );
            }else {
                return array(
                    "status" => 'NOK',
                    "operation" => $phpObject->operation,
                    "session_id" => $phpObject->session_id,
                    "ticket_id" => $phpObject->ticket_id,
                    "true_status" => $resultSaveTicket['true_status'],
                    "message" => $resultSaveTicket['message']
                );
            }
        }
        return array(
            "status" => 'OK',
            "operation" => $phpObject->operation,
            "session_id"=>$phpObject->session_id,
            "ticket_id" => $phpObject->ticket_id,
            "true_status" => $resultSaveTicket['true_status'],
            "message" => $resultSaveTicket['message']
        );
    }


    public static function saveTicketInformation($phpObject){
        if(strlen($phpObject->session_id) == 0 || strlen($phpObject->user_id) == 0 ||
            strlen($phpObject->combination) == 0 || strlen($phpObject->number_of_tickets) == 0 ||
            strlen($phpObject->sum_bet) == 0 || strlen($phpObject->possible_win) == 0 || strlen($phpObject->min_possible_win) == 0
        ){
            $detected_ip_address = IPAddressHelper::getDetectedIPAddress();
            $message = "TicketService::saveTicketInformation(" . print_r($phpObject, true) . ") <br /> Detected IP Address = {$detected_ip_address}";
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
        $ticketDetails = array(
            'session_id' => $phpObject->session_id,
            'user_id' => $phpObject->user_id,
            'combination' => $phpObject->combination,
            'number_of_tickets' => $phpObject->number_of_tickets,
            'sum_bet' => $phpObject->sum_bet,
            'possible_win' => $phpObject->possible_win,
            'min_possible_win' => $phpObject->min_possible_win
        );
        $resultSaveTicket = TicketModel::saveTicket($ticketDetails);
        if($resultSaveTicket['status'] !== 'OK'){
            if($resultSaveTicket['error_code'] == ErrorConstants::$DRAW_MODEL_IS_INACTIVE){
               $error = ErrorConstants::getErrorMessage(ErrorConstants::$DRAW_MODEL_IS_INACTIVE);
                return array(
                    "status" => 'NOK',
                    "operation" => $phpObject->operation,
                    "error_message" => $error["message_text"],
                    "error_code" => $error["message_no"],
                    "error_description" =>$error["message_description"]
                );
            }
            else if($resultSaveTicket['error_code'] == ErrorConstants::$NEXT_DRAW_IS_NOT_DEFINED){
                $error = ErrorConstants::getErrorMessage(ErrorConstants::$NEXT_DRAW_IS_NOT_DEFINED);
                return array(
                    "status" => 'NOK',
                    "operation" => $phpObject->operation,
                    "error_message" => $error["message_text"],
                    "error_code" => $error["message_no"],
                    "error_description" =>$error["message_description"]
                );
            }
            else if($resultSaveTicket['error_code'] == ErrorConstants::$UNHANDLED_EXCEPTION){
                $error = ErrorConstants::getErrorMessage(ErrorConstants::$UNHANDLED_EXCEPTION);
                return array(
                    "status" => 'NOK',
                    "operation" => $phpObject->operation,
                    "error_message" => $error["message_text"],
                    "error_code" => $error["message_no"],
                    "error_description" =>$error["message_description"]
                );
            }else {
                return array(
                    "status" => 'NOK',
                    "operation" => $phpObject->operation,
                    "session_id" => $phpObject->session_id
                );
            }
        }
        return array(
            "status" => 'OK',
            "operation" => $phpObject->operation,
            "session_id"=>$phpObject->session_id,

            "credits" => $resultSaveTicket['credits'],
            "credits_formatted"=> $resultSaveTicket['credits_formatted'],
            "serial_number" => $resultSaveTicket['serial_number'],
            "ticket_datetime" => $resultSaveTicket['ticket_datetime'],
            "logged_subject_name" => $resultSaveTicket['logged_subject_name'],
            "barcode" => $resultSaveTicket['barcode'],
            "language" => $resultSaveTicket['language'],
            "city" => $resultSaveTicket['city'],
            "address" => $resultSaveTicket['address'],
            "commercial_address" => $resultSaveTicket['commercial_address'],
            "local_jp_code" => $resultSaveTicket['local_jp_code'],
            "global_jp_code" => $resultSaveTicket['global_jp_code'],
            "payout_expire_time" => $resultSaveTicket['payout_expire_time'],

            "list_draws" => $resultSaveTicket['list_draws']
        );
    }

    /**
     * @param $phpObject
     * @return array
     */
    public static function saveTemporaryTicketInformation($phpObject){
        if(strlen($phpObject->session_id) == 0 || strlen($phpObject->combination) == 0 || strlen($phpObject->number_of_tickets) == 0 ||
            strlen($phpObject->sum_bet) == 0 || strlen($phpObject->jackpot) == 0
        ){
            $detected_ip_address = IPAddressHelper::getDetectedIPAddress();
            $message = "TicketService::saveTemporaryTicketInformation(" . print_r($phpObject, true) . ") <br /> Detected IP Address = {$detected_ip_address}";
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
        $ticketDetails = array(
            'session_id' => $phpObject->session_id,
            'combination' => $phpObject->combination,
            'number_of_tickets' => $phpObject->number_of_tickets,
            'sum_bet' => $phpObject->sum_bet,
            'jackpot' => $phpObject->jackpot
        );
        $resultSaveTicket = TicketModel::saveTemporaryTicket($ticketDetails);
        if($resultSaveTicket['status'] !== 'OK'){
            if($resultSaveTicket['error_code'] == "-20101"){
                $error = ErrorConstants::getErrorMessage(ErrorConstants::$MAXIMUM_TEMPORARY_TICKET_NUMBER);
                return array(
                    "status" => 'NOK',
                    "operation" => $phpObject->operation,
                    "session_id" => $phpObject->session_id,
                    "error_message" => $error["message_text"],
                    "error_code" => $error["message_no"],
                    "error_description" =>$error["message_description"]
                );
            }else { //-20100
                $error = ErrorConstants::getErrorMessage(ErrorConstants::$GENERAL_ERROR);
                return array(
                    "status" => 'NOK',
                    "operation" => $phpObject->operation,
                    "session_id" => $phpObject->session_id,
                    "error_message" => $error["message_text"],
                    "error_code" => $error["message_no"],
                    "error_description" =>$error["message_description"]
                );
            }
        }
        return array(
            "status" => 'OK',
            "operation" => $phpObject->operation,
            "session_id"=>$phpObject->session_id,
            "temporary_order_number" => $resultSaveTicket['temporary_order_number'],
            "serial_number" => $resultSaveTicket['serial_number'],
            "barcode" => $resultSaveTicket['barcode']
        );
    }

    /**
     * @param $phpObject
     * @return array
     */
    public static function getTicketDetailsFromBarcode($phpObject){
        if(strlen($phpObject->session_id) == 0){
            $detected_ip_address = IPAddressHelper::getDetectedIPAddress();
            $message = "TicketService::getTicketDetailsFromBarcode(" . print_r($phpObject, true) . ") <br /> Detected IP Address = {$detected_ip_address}";
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

        if(strlen($phpObject->ticket_barcode) > 0 && !is_numeric($phpObject->ticket_barcode)){
            $detected_ip_address = IPAddressHelper::getDetectedIPAddress();
            $message = "TicketService::getTicketDetailsFromBarcode(" . print_r($phpObject, true) . ") <br /> Detected IP Address = {$detected_ip_address}";
            ErrorHelper::writeError($message, $message);

            return array(
                "status" => 'NOK',
                "operation" => $phpObject->operation,
            );
        }

        $details = array(
            "session_id" => $phpObject->session_id,
            "ticket_barcode" => $phpObject->ticket_barcode
        );

        $resultTicketDetails = TicketModel::getTicketDetailsWithBarcode($details);
        if($resultTicketDetails['status'] !== 'OK'){
            return array(
                "status" => 'NOK',
                "operation" => $phpObject->operation,
            );
        }

        $ticket_result_array = array();
        foreach($resultTicketDetails['ticket_result'] as $tr){
            $ticket_result_array[] = array(
                "payed_out" => $tr->payed_out,
                "ticket_status" => $tr->ticket_status,
                "order_num" => $tr->order_num,
                "rec_tmstp" => $tr->rec_tmstp,
                "ticket_printed" => $tr->ticket_printed,
                "payout_expire_time" => $tr->payout_expire_time,
                "barcode" => $tr->barcode,
                "cashier" => $tr->cashier,
                "player_username" => $tr->player_username,
                "address" => $tr->address,
                "city" => $tr->city,
                "local_jp_code" => $tr->local_jp_code,
                "global_jp_code" => $tr->global_jp_code,
                "local_win" => $tr->local_win,
                "global_win" => $tr->global_win,
                "commercial_address" => $tr->commercial_address,
                "language" => $tr->language,
                "no_of_printings" => $tr->no_of_printings,
                "win_paid_out" => $tr->win_paid_out,
                "max_possible_win" => $tr->max_possible_win,
                "min_possible_win" => $tr->min_possible_win,
                "sum_bet" => $tr->sum_bet,
                "payout_expired" => $tr->payout_expired,
                "payout_expired_time" => $tr->payout_expired_time,
                "payout_timestamp" => $tr->payout_timestamp
            );
        }

        $combinations_result_array = array();
        foreach($resultTicketDetails['combinations_result'] as $cr){
            $combinations_result_array[] = array(
                "combination_id" => $cr->combination_id,
                "combination_type" => $cr->combination_type,
                "combination_value" => $cr->combination_value,
                "bet" => $cr->bet,
                "win" => $cr->win,
                "ticket_id" => $cr->ticket_id,
                "order_num" => $cr->order_num,
            );
        }

        return array(
            "status" => 'OK',
            "status_out" => $resultTicketDetails['status_out'],
            "operation" => $phpObject->operation,
            "session_id" => $details['session_id'],
            "ticket_barcode" => $details['ticket_barcode'],
            "ticket_result" => $ticket_result_array,
            "combinations_result" => $combinations_result_array
        );
    }

    /**
     * @param $phpObject
     * @return array
     */
    public static function getLastTicketForUser($phpObject){
        if(strlen($phpObject->session_id) == 0){
            $detected_ip_address = IPAddressHelper::getDetectedIPAddress();
            $message = "TicketService::getLastTicketForUser(" . print_r($phpObject, true) . ") <br /> Detected IP Address = {$detected_ip_address}";
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
            "user_id" => $phpObject->user_id,
        );

        $resultTicketDetails = TicketModel::getLastTicketForSubject($details);
        if($resultTicketDetails['status'] !== 'OK'){
            return array(
                "status" => 'NOK',
                "operation" => $phpObject->operation,
                "session_id" => $details['session_id'],
                "user_id" => $details['user_id'],
            );
        }

        $ticket_result_array = array();
        foreach($resultTicketDetails['ticket_result'] as $tr){
            $ticket_result_array[] = array(
                "payed_out" => $tr->payed_out,
                "ticket_status" => $tr->ticket_status,
                "order_num" => $tr->order_num,
                "rec_tmstp" => $tr->rec_tmstp,
                "ticket_printed" => $tr->ticket_printed,
                "payout_expire_time" => $tr->payout_expire_time,
                "barcode" => $tr->barcode,
                "cashier" => $tr->cashier,
                "player_username" => $tr->player_username,
                "address" => $tr->address,
                "city" => $tr->city,
                "local_jp_code" => $tr->local_jp_code,
                "global_jp_code" => $tr->global_jp_code,
                "local_win" => $tr->local_win,
                "global_win" => $tr->global_win,
                "commercial_address" => $tr->commercial_address,
                "language" => $tr->language,
                "no_of_printings" => $tr->no_of_printings,
                "win_paid_out" => $tr->win_paid_out,
                "max_possible_win" => $tr->max_possible_win,
                "min_possible_win" => $tr->min_possible_win,
                "sum_bet" => $tr->sum_bet,
            );
        }

        $combinations_result_array = array();
        foreach($resultTicketDetails['combinations_result'] as $cr){
            $combinations_result_array[] = array(
                "combination_id" => $cr->combination_id,
                "combination_type" => $cr->combination_type,
                "combination_value" => $cr->combination_value,
                "bet" => $cr->bet,
                "win" => $cr->win,
                "ticket_id" => $cr->ticket_id,
                "order_num" => $cr->order_num,
            );
        }

        return array(
            "status" => 'OK',
            "operation" => $phpObject->operation,
            "session_id" => $details['session_id'],
            "user_id" => $details['user_id'],
            "ticket_result" => $ticket_result_array,
            "combinations_result" => $combinations_result_array
        );
    }

    /**
     * @param $phpObject
     * @return array
     */
    public static function getListPreviousDraws($phpObject){
        if(strlen($phpObject->session_id) == 0 || strlen($phpObject->draw_id) == 0 || strlen($phpObject->per_page) == 0){
            $detected_ip_address = IPAddressHelper::getDetectedIPAddress();
            $message = "TicketService::getListPreviousDraws(" . print_r($phpObject, true) . ") <br /> Detected IP Address = {$detected_ip_address}";
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
            "draw_id" => $phpObject->draw_id,
            "per_page" => $phpObject->per_page
        );

        $resultListPreviousDraws = TicketModel::getListPreviousDraws($details);
        if($resultListPreviousDraws['status'] !== 'OK'){
            return array(
                "status" => 'NOK',
                "operation" => $phpObject->operation,
            );
        }

        $draw_result_array = array();
        foreach($resultListPreviousDraws['draw_result'] as $tr){
            $draw_result_array[] = array(
                "order_num" => $tr->order_num,
                "chosen_numbers" => $tr->chosen_numbers,
                "stars" => $tr->stars,
                "global_jackpot_code" => $tr->global_jackpot_code,
                "local_jackpot_code" => $tr->local_jackpot_code,
                "draw_id" => $tr->draw_id
            );
        }

        return array(
            "status" => 'OK',
            "operation" => $phpObject->operation,
            "session_id" => $details['session_id'],
            "draw_id" => $details['draw_id'],
            "per_page" => $details['per_page'],
            "draw_result" => $draw_result_array
        );
    }

    /**
     * @param $phpObject
     * @return array
     */
    public static function cancelTicket($phpObject){
        if(strlen($phpObject->session_id) == 0 || strlen($phpObject->barcode) == 0 || strlen($phpObject->cashier_pincode) == 0){
            $detected_ip_address = IPAddressHelper::getDetectedIPAddress();
            $message = "TicketService::cancelTicket(" . print_r($phpObject, true) . ") <br /> Detected IP Address = {$detected_ip_address}";
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
            'barcode' => $phpObject->barcode,
            'cashier_pincode' =>  $phpObject->cashier_pincode
        );
        $resultCancelTicket = TicketModel::cancelTicket($details);
        if($resultCancelTicket['status'] == 'OK' && $resultCancelTicket['status_out'] == "1"){
            return array(
                "status" => 'OK',
                "operation" => $phpObject->operation,
                "session_id" => $phpObject->session_id,
                "barcode" => $phpObject->barcode,
            );
        }else if($resultCancelTicket['status'] == 'NOK'){
            $error = ErrorConstants::getErrorMessage(ErrorConstants::$GENERAL_ERROR);
            return array(
                "status" => 'NOK',
                "operation" => $phpObject->operation,
                "session_id" => $phpObject->session_id,
                "barcode" => $phpObject->barcode,
                "error_message" => $error["message_text"],
                "error_code" => $error["message_no"],
                "error_description" =>$error["message_description"]
            );
        }
        else {
            return array(
                "status" => 'NOK',
                "operation" => $phpObject->operation,
                "session_id" => $phpObject->session_id,
                "barcode" => $phpObject->barcode,
            );
        }
    }

    /**
     * @param $phpObject
     * @return array
     */
    public static function printTicket($phpObject){
        if(strlen($phpObject->session_id) == 0 || strlen($phpObject->ticket_id) == 0){
            $detected_ip_address = IPAddressHelper::getDetectedIPAddress();
            $message = "TicketService::printTicket(" . print_r($phpObject, true) . ") <br /> Detected IP Address = {$detected_ip_address}";
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
            'ticket_id' => $phpObject->ticket_id
        );
        $resultPrintTicket = TicketModel::printTicket($details);
        if($resultPrintTicket['status'] == 'OK' && $resultPrintTicket['status_out'] == "1"){
            return array(
                "status" => 'OK',
                "operation" => $phpObject->operation,
                "session_id" => $phpObject->session_id,
                "ticket_id" => $phpObject->ticket_id,
            );
        }else if($resultPrintTicket['status'] == 'NOK'){
            $error = ErrorConstants::getErrorMessage(ErrorConstants::$GENERAL_ERROR);
            return array(
                "status" => 'NOK',
                "operation" => $phpObject->operation,
                "session_id" => $phpObject->session_id,
                "ticket_id" => $phpObject->ticket_id,
                "error_message" => $error["message_text"],
                "error_code" => $error["message_no"],
                "error_description" =>$error["message_description"]
            );
        }
        else {
            return array(
                "status" => 'NOK',
                "operation" => $phpObject->operation,
                "session_id" => $phpObject->session_id,
                "ticket_id" => $phpObject->ticket_id,
            );
        }
    }

    public static function saveTicketShift($phpObject){
        if(strlen($phpObject->session_id) == 0 || strlen($phpObject->user_id) == 0 ||
            strlen($phpObject->combination) == 0 || strlen($phpObject->number_of_tickets) == 0 ||
            strlen($phpObject->sum_bet) == 0 || strlen($phpObject->possible_win) == 0 || strlen($phpObject->min_possible_win) == 0
        ){
            $detected_ip_address = IPAddressHelper::getDetectedIPAddress();
            $message = "TicketService::saveTicketShift(" . print_r($phpObject, true) . ") <br /> Detected IP Address = {$detected_ip_address}";
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
        $ticketDetails = array(
            'session_id' => $phpObject->session_id,
            'user_id' => $phpObject->user_id,
            'combination' => $phpObject->combination,
            'number_of_tickets' => $phpObject->number_of_tickets,
            'sum_bet' => $phpObject->sum_bet,
            'possible_win' => $phpObject->possible_win,
            'min_possible_win' => $phpObject->min_possible_win
        );
        $resultSaveTicket = TicketModel::saveTicketShift($ticketDetails);
        if($resultSaveTicket['status'] !== 'OK'){
            if($resultSaveTicket['error_code'] == ErrorConstants::$DRAW_MODEL_IS_INACTIVE){
               $error = ErrorConstants::getErrorMessage(ErrorConstants::$DRAW_MODEL_IS_INACTIVE);
                return array(
                    "status" => 'NOK',
                    "operation" => $phpObject->operation,
                    "error_message" => $error["message_text"],
                    "error_code" => $error["message_no"],
                    "error_description" =>$error["message_description"]
                );
            }
            else if($resultSaveTicket['error_code'] == ErrorConstants::$NEXT_DRAW_IS_NOT_DEFINED){
                $error = ErrorConstants::getErrorMessage(ErrorConstants::$NEXT_DRAW_IS_NOT_DEFINED);
                return array(
                    "status" => 'NOK',
                    "operation" => $phpObject->operation,
                    "error_message" => $error["message_text"],
                    "error_code" => $error["message_no"],
                    "error_description" =>$error["message_description"]
                );
            }
            else if($resultSaveTicket['error_code'] == ErrorConstants::$UNHANDLED_EXCEPTION){
                $error = ErrorConstants::getErrorMessage(ErrorConstants::$UNHANDLED_EXCEPTION);
                return array(
                    "status" => 'NOK',
                    "operation" => $phpObject->operation,
                    "error_message" => $error["message_text"],
                    "error_code" => $error["message_no"],
                    "error_description" =>$error["message_description"]
                );
            }else {
                return array(
                    "status" => 'NOK',
                    "operation" => $phpObject->operation,
                    "session_id" => $phpObject->session_id
                );
            }
        }
        return array(
            "status" => 'OK',
            "operation" => $phpObject->operation,
            "session_id"=>$phpObject->session_id,

            "list_barcodes" => $resultSaveTicket['list_barcodes']
        );
    }
}
