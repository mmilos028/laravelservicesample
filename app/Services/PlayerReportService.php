<?php

namespace App\Services;

use App\Models\Postgres\PlayerReportModel;
use App\Helpers\NumberHelper;
use App\Helpers\ErrorHelper;
use App\Helpers\ErrorConstants;
use App\Helpers\IPAddressHelper;
use App\Helpers\DateTimeHelper;

/**
 * Class PlayerReportService
 * @package App\Services
 */
class PlayerReportService
{

    /**
     * @param $phpObject
     * @return array
     */
    public static function listMoneyTransactions($phpObject)
    {
        if(strlen($phpObject->user_id) == 0 || strlen($phpObject->session_id) == 0){
            $detected_ip_address = IPAddressHelper::getDetectedIPAddress();
            $message = "PlayerReportService::listMoneyTransactions(" . print_r($phpObject, true) . ") <br /> Detected IP Address = {$detected_ip_address}";
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
        $start_date = $phpObject->start_date;
        if(strlen($start_date) == 0){
            $start_date = DateTimeHelper::returnFirstDayOfMonthDateFormatted();
        }
        $end_date = $phpObject->end_date;
        if(strlen($end_date) == 0){
            $end_date = DateTimeHelper::returnCurrentDateFormatted();
        }
        $page_number = 1;
        if(strlen($phpObject->page_number) != 0){
            $page_number = $phpObject->page_number;
        }
        $number_of_results = config("constants.DEFAULT_RESULTS_PER_PAGE");
        if(strlen($phpObject->number_of_results) != 0){
            $number_of_results = $phpObject->number_of_results;
        }

        $details = array(
            "session_id" => $phpObject->session_id,
            "subject_id" => $phpObject->user_id,
            "start_date" => $start_date,
            "end_date" => $end_date,
            "page_number" => $page_number,
            "number_of_results" => $number_of_results
        );

        $resultListMoneyTransactions = PlayerReportModel::listSubjectMoneyTransactions($details);
        if($resultListMoneyTransactions['status'] != 'OK'){
            return array(
                "status" => 'NOK',
                "operation" => $phpObject->operation,
            );
        }
        $report_list_transactions = array();
        foreach($resultListMoneyTransactions['report_list_transactions'] as $rep){
            $report_list_transactions[] = array(
                "date_time" => $rep->rec_tmstp_formate,
                "amount" => NumberHelper::convert_double($rep->amount),
                "amount_formatted" => NumberHelper::format_double($rep->amount),
                "transaction_type" => $rep->transaction_type,
                "transaction_id" => $rep->transaction_id,
                "barcode" => $rep->barcode,
                "transaction_sign" => $rep->transaction_sign,
                "start_credits" => NumberHelper::convert_double($rep->start_credits),
                "start_credits_formatted" => NumberHelper::format_double($rep->start_credits),
                "end_credits" => NumberHelper::convert_double($rep->end_credits),
                "end_credits_formatted" => NumberHelper::format_double($rep->end_credits),
            );
        }

        $report_list_transactions_total = array();
        foreach($resultListMoneyTransactions['report_total'] as $rep){
            $report_list_transactions_total[] = array(
                "number_of_deposits" => NumberHelper::convert_integer($rep->no_of_deposits),
                "number_of_deposits_formatted" => NumberHelper::format_integer($rep->no_of_deposits),
                "sum_deposits" => NumberHelper::convert_double($rep->sum_deposits),
                "sum_deposits_formatted" => NumberHelper::format_double($rep->sum_deposits),
                "no_of_deactivated_tickets" => NumberHelper::convert_integer($rep->no_of_deactivated_tickets),
                "no_of_deactivated_tickets_formatted" => NumberHelper::format_integer($rep->no_of_deactivated_tickets),
                "sum_canceled_deposits" => NumberHelper::convert_double($rep->sum_canceled_deposits),
                "sum_canceled_deposits_formatted" => NumberHelper::format_double($rep->sum_canceled_deposits),
                "no_of_payed_out_tickets" => NumberHelper::convert_integer($rep->no_of_payed_out_tickets),
                "no_of_payed_out_tickets_formatted" => NumberHelper::format_integer($rep->no_of_payed_out_tickets),
                "sum_of_payed_out_tickets" => NumberHelper::convert_double($rep->sum_of_payed_out_tickets),
                "sum_of_payed_out_tickets_formatted" => NumberHelper::format_double($rep->sum_of_payed_out_tickets),
            );
        }

        return array(
            "status" => 'OK',
            "operation" => $phpObject->operation,
            "session_id" => $details['session_id'],
            "user_id" => $details['subject_id'],
            "start_date" => $start_date,
            "end_date" => $end_date,
            "page_number" => $page_number,
            "number_of_results" => $number_of_results,
            "report_list_transactions" => $report_list_transactions,
            "report_list_transactions_total" => $report_list_transactions_total,
            "report_list_transactions_rows_count" => $resultListMoneyTransactions['report_list_transactions_rows_count']
        );
    }

    /**
     * @param $phpObject
     * @return array
     */
    public static function listPlayerTickets($phpObject)
    {
        if(strlen($phpObject->user_id) == 0 || strlen($phpObject->session_id) == 0){
            $detected_ip_address = IPAddressHelper::getDetectedIPAddress();
            $message = "PlayerReportService::listPlayerTickets(" . print_r($phpObject, true) . ") <br /> Detected IP Address = {$detected_ip_address}";
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
            "user_id" => $phpObject->user_id
        );

        $resultListPlayerTickets = PlayerReportModel::listPlayerTickets($details);
        if($resultListPlayerTickets['status'] != 'OK'){
            return array(
                "status" => 'NOK',
                "operation" => $phpObject->operation,
            );
        }
        $list_report = array();
        foreach($resultListPlayerTickets['list_report'] as $ticket){
            $payed_out_string = ($ticket->payed_out == "1") ? "YES" : "NO";
            switch($ticket->ticket_status){
                case '-1':
                    $ticket_status_string = 'DEACTIVATED';
                    break;
                case '0':
                    $ticket_status_string = 'RESERVED';
                    break;
                case '1':
                    $ticket_status_string = 'PAID-IN';
                    break;
                case '2':
                    $ticket_status_string = 'WINNING';
                    break;
                case '3':
                    $ticket_status_string = 'WINNING NOT PAID-OUT';
                    break;
                case '4':
                    $ticket_status_string = 'PAID-OUT';
                    break;
                case '5':
                    $ticket_status_string = 'LOSING';
                    break;
                default:
                    $ticket_status_string = $ticket->ticket_status;
            }
            $ticket_printed_string = ($ticket->ticket_printed == 1) ? "YES" : "NO";
            $list_report[] = array(
                "ticket_id" => $ticket->ticket_id,
                //"serial_number" => $ticket->serial_number,
                "barcode" => $ticket->barcode . "",
                "payed_out" => $ticket->payed_out,
                "payed_out_formatted" => $payed_out_string,
                "ticket_status" => $ticket->ticket_status,
                "ticket_status_formatted" => $ticket_status_string,
                //"draw_id" => $ticket->draw_id,
                "ticket_printed" => $ticket->ticket_printed,
                "ticket_printed_formatted" => $ticket_printed_string,
                //"combination_type" => $ticket->combination_type,
                //"combination_value" => $ticket->combination_value,

                //"location_name" => $ticket->location_name,
                //"ticket_timestamp" => $ticket->ticket_timestamp,
                //"draw_order_num" => $ticket->draw_order_num,
                "draw_order_num" => $ticket->order_num,
                //"sum_bet_per_combination" => $ticket->sum_bet_per_combination,
                "min_possible_win" => NumberHelper::convert_double($ticket->min_possible_win),
                "min_possible_win_formatted" => NumberHelper::format_double($ticket->min_possible_win),
                "max_possible_win" => NumberHelper::convert_double($ticket->max_possible_win),
                "max_possible_win_formatted" => NumberHelper::format_double($ticket->max_possible_win),
                "global_jp_code" => $ticket->global_jp_code,
                "local_jp_code" => $ticket->local_jp_code,
                //"number_of_combinations" => NumberHelper::convert_integer($ticket->number_of_combinations),

                //"bet" => NumberHelper::convert_double($ticket->bet),
                //"win" => NumberHelper::convert_double($ticket->win)
                "player_username" => $ticket->player_username,
                "address" => $ticket->address,
                "address2" => $ticket->commercial_address,
                "city" => $ticket->city,
                "local_win" => NumberHelper::convert_double($ticket->local_win),
                "local_win_formatted" => NumberHelper::format_double($ticket->local_win),
                "global_win" => NumberHelper::convert_double($ticket->global_win),
                "global_win_formatted" => NumberHelper::format_double($ticket->global_win),
                "language" => $ticket->language,
                "no_of_printings" => NumberHelper::convert_integer($ticket->no_of_printings),
                "no_of_printings_formatted" => NumberHelper::format_integer($ticket->no_of_printings),
                "win_paid_out" => NumberHelper::convert_double($ticket->win_paid_out),
                "win_paid_out_formatted" => NumberHelper::format_double($ticket->win_paid_out),
                "date_time_formatted" => $ticket->rec_tmstp_formated,
                "sum_bet" => NumberHelper::convert_double($ticket->sum_bet),
                "sum_bet_formatted" => NumberHelper::format_double($ticket->sum_bet),
                "sum_win" => NumberHelper::convert_double($ticket->sum_win),
                "sum_win_formatted" => NumberHelper::format_double($ticket->sum_win),
            );
        }

        $list_combinations = array();
        foreach($resultListPlayerTickets['list_combinations'] as $comb){
            $list_combinations[] = array(
                "combination_id" => $comb->combination_id,
                "combination_type" => $comb->combination_type,
                "combination_value" => $comb->combination_value,
                "bet" => NumberHelper::convert_double($comb->bet),
                "bet_formatted" => NumberHelper::format_double($comb->bet),
                "win" => NumberHelper::convert_double($comb->win),
                "win_formatted" => NumberHelper::format_double($comb->win),
                "ticket_id" => $comb->ticket_id,
                "draw_order_num" => $comb->order_num,
                "number_of_combinations" => NumberHelper::convert_integer($comb->number_of_combinations),
                "number_of_combinations_formatted" => NumberHelper::format_integer($comb->number_of_combinations),
            );
        }

        return array(
            "status" => 'OK',
            "operation" => $phpObject->operation,
            "session_id" => $details['session_id'],
            "user_id" => $details['user_id'],
            "list_report" => $list_report,
            "list_combinations" => $list_combinations
        );
    }

    /**
     * @param $phpObject
     * @return array
     */
    public static function listPlayerLoginHistory($phpObject)
    {
        if(strlen($phpObject->user_id) == 0 || strlen($phpObject->session_id) == 0){
            $detected_ip_address = IPAddressHelper::getDetectedIPAddress();
            $message = "PlayerReportService::listPlayerLoginHistory(" . print_r($phpObject, true) . ") <br /> Detected IP Address = {$detected_ip_address}";
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

        $start_date = $phpObject->start_date;
        if(strlen($start_date) == 0){
            $start_date = DateTimeHelper::returnFirstDayOfMonthDateFormatted();
        }
        $end_date = $phpObject->end_date;
        if(strlen($end_date) == 0){
            $end_date = DateTimeHelper::returnCurrentDateFormatted();
        }

        $details = array(
            "session_id" => $phpObject->session_id,
            "subject_id" => $phpObject->user_id,
            "start_date" => $start_date,
            "end_date" => $end_date,
        );

        $resultListPlayerLoginHistory = PlayerReportModel::listPlayerLoginHistory($details);
        if($resultListPlayerLoginHistory['status'] != 'OK'){
            return array(
                "status" => 'NOK',
                "operation" => $phpObject->operation,
            );
        }
        $list_report = array();
        foreach($resultListPlayerLoginHistory['report'] as $item){
            $list_report[] = array(
                "session_id" => $item->session_id,
                "start_date_time" => $item->start_time_formated,
                "end_date_time" => $item->end_time_formated,
                "duration" => $item->duration,
                "ip_address" => $item->ip_address,
                "city" => $item->city,
                "country" => $item->country
            );
        }

        return array(
            "status" => 'OK',
            "session_id" => $details['session_id'],
            "user_id" => $details['subject_id'],
            "start_date" => $details['start_date'],
            "end_date" => $details['end_date'],
            "operation" => $phpObject->operation,
            "list_report" => $list_report,
        );
    }

    /**
     * @param $phpObject
     * @return array
     */
    public static function listPlayerHistory($phpObject)
    {
        if(strlen($phpObject->player_id) == 0 || strlen($phpObject->session_id) == 0){
            $detected_ip_address = IPAddressHelper::getDetectedIPAddress();
            $message = "PlayerReportService::listPlayerHistory(" . print_r($phpObject, true) . ") <br /> Detected IP Address = {$detected_ip_address}";
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

        $start_date = $phpObject->start_date;
        if(strlen($start_date) == 0){
            $start_date = DateTimeHelper::returnFirstDayOfMonthDateFormatted();
        }
        $end_date = $phpObject->end_date;
        if(strlen($end_date) == 0){
            $end_date = DateTimeHelper::returnCurrentDateFormatted();
        }
        $page_number = 1;
        if(strlen($phpObject->page_number) == 0){
            $page_number = 1;
        }else{
            $page_number = $phpObject->page_number;
        }
        $number_of_results = config("constants.DEFAULT_RESULTS_PER_PAGE");
        if(strlen($phpObject->number_of_results) == 0){
            $number_of_results = 1;
        }else{
            $number_of_results = $phpObject->number_of_results;
        }

        $details = array(
            "session_id" => $phpObject->session_id,
            "player_id" => $phpObject->player_id,
            "start_date" => $start_date,
            "end_date" => $end_date,
            "page_number" => $page_number,
            "number_of_results" => $number_of_results,
        );

        $resultListPlayerHistory = PlayerReportModel::listPlayerHistory($details);
        if($resultListPlayerHistory['status'] != 'OK'){
            return array(
                "status" => 'NOK',
                "operation" => $phpObject->operation,
            );
        }
        $list_report = array();
        foreach($resultListPlayerHistory['report'] as $item){
            $list_report[] = array(
                "transaction_id" => $item->transaction_id,
                "first_name" => $item->first_name,
                "last_name" => $item->last_name,
                "username" => $item->username,
                "transaction_type_id" => $item->transaction_type_id,
                "ticket_status" => $item->ticket_status,
                "amount" => $item->amount,
                "sum_win" => $item->sum_win,
                "end_credits" => $item->end_credits,
                "start_credits" => $item->start_credits,
                "rec_tmstp_formated" => $item->rec_tmstp_formated,
                "serial_number" => $item->serial_number,
                "barcode" => $item->barcode,
                "currency" => $item->currency,
                "transaction_type" => $item->transaction_type
            );
        }

        $total_pages = (ceil($resultListPlayerHistory['records_total'] / $number_of_results)) == 0 ? 1 : (ceil($resultListPlayerHistory['records_total'] / $number_of_results));

        return array(
            "status" => 'OK',
            "session_id" => $details['session_id'],
            "player_id" => $details['player_id'],
            "start_date" => $details['start_date'],
            "end_date" => $details['end_date'],
            "page_number" => $details['page_number'],
            "number_of_results" => $details['number_of_results'],
            "operation" => $phpObject->operation,
            "list_report" => $list_report,
            "total_pages" => $total_pages,
            "total_records" => $resultListPlayerHistory['records_total']
        );
    }
}