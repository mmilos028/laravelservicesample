<?php

namespace App\Models\Postgres;

use App\Helpers\NumberHelper;
use Illuminate\Support\Facades\DB;

use App\Helpers\ErrorHelper;
use App\Helpers\ErrorConstants;

class TicketModel
{

    private static $DEBUG = false;

    public static function updateStatusTicketWin($ticketDetails){
        $ticket_status = 4;

        if(self::$DEBUG){
            $message = "TicketModel::updateStatusTicketWin >>> SELECT * from tomboladb.tombola.update_status_ticket_win(
            :p_session_id = {$ticketDetails['session_id']}, :p_ticket_id_in = {$ticketDetails['ticket_id']}, :p_ticket_status_in = {$ticket_status})";
            ErrorHelper::writeInfo($message, $message);
        }

        try {
            DB::connection('pgsql')->beginTransaction();

            $statement_string = "SELECT * from tomboladb.tombola.update_status_ticket_win(:p_session_id, :p_ticket_id_in, :p_ticket_status_in)";
            $fn_result = DB::connection('pgsql')->select(
                $statement_string,
                [
                    'p_session_id' => $ticketDetails['session_id'],
                    'p_ticket_id_in' => $ticketDetails['ticket_id'],
                    'p_ticket_status_in' => $ticket_status
                ]
            );

            $status_out = $fn_result[0]->p_status_out;

            DB::connection('pgsql')->commit();

            if ($status_out == "1"){
                return [ 'status' => "OK", 'true_status' => $status_out, "message" => "Successful"];
            }
            if($status_out == "-4"){
                return [ 'status' => "OK", 'true_status' => $status_out, "message" => "Payout time expired", "error_code" => ErrorConstants::$PAYOUT_TIME_EXPIRED];
            }
            else{
                $message = implode(" ", [
                        "TicketModel::updateStatusTicketWin(" . print_r($ticketDetails, true) . ") <br />",
                        "tomboladb.tombola.update_status_ticket_win(:p_session_id = {$ticketDetails['session_id']}, :p_ticket_id_in = {$ticketDetails['ticked_id']}, :p_ticket_status_in = {$ticket_status}, :p_status_out = {$fn_result[0]->p_status_out}) <br />",
                    ]
                );
                ErrorHelper::writeError($message, $message);
                return ['status' => "NOK", 'true_status' => $status_out, "message" => "Unsuccessful"];
            }
        }catch(\PDOException $ex1){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                    "TicketModel::updateStatusTicketWin(" . print_r($ticketDetails, true) . ") <br />",
                    "tomboladb.tombola.update_status_ticket_win(:p_session_id = {$ticketDetails['session_id']}, :p_ticket_id_in = {$ticketDetails['ticked_id']}, :p_ticket_status_in = {$ticket_status}, :p_status_out = {$fn_result[0]->p_status_out}) <br />",
                    $ex1->getMessage()
                ]
            );
            ErrorHelper::writeError($message, $message);
            return ["status"=>"NOK"];
        }catch(\Exception $ex2){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                    "TicketModel::updateStatusTicketWin(" . print_r($ticketDetails, true) . ") <br />",
                    "tomboladb.tombola.update_status_ticket_win(:p_session_id = {$ticketDetails['session_id']}, :p_ticket_id_in = {$ticketDetails['ticked_id']}, :p_ticket_status_in = {$ticket_status}, :p_status_out = {$fn_result[0]->p_status_out}) <br />",
                    $ex2->getMessage()
                ]
            );
            ErrorHelper::writeError($message, $message);
            return ["status"=>"NOK"];
        }
    }

    public static function saveTicket($ticketDetails){

        if(self::$DEBUG){
            $message = "TicketModel::saveTicket >>> SELECT * from tomboladb.tombola.save_ticket(
            :p_session_id_in = {$ticketDetails['session_id']}, :p_subject_id_in = {$ticketDetails['user_id']}, :p_combination_in = {$ticketDetails['combination']}, 
            :p_number_of_tickets_in = {$ticketDetails['number_of_tickets']}, :p_sum_bet_in = {$ticketDetails['sum_bet']}, :p_possible_win = {$ticketDetails['possible_win']},
            :p_min_possible_win = {$ticketDetails['min_possible_win']}, :cur_draw_id_out, :p_credits_out, :p_serial_number_out, :p_ticket_datetime_out, :p_logged_subject_name_out,
            :p_barcode_out, :p_language_out, :p_city_out, :p_address_out, :p_commercial_address_out, :p_local_jp_code, :p_global_jp_code, :p_payout_expire_time, :p_error_code_out, :p_error_msg_out
            )";
            ErrorHelper::writeInfo($message, $message);
        }

        try {
            DB::connection('pgsql')->beginTransaction();

            $statement_string = "SELECT * from tomboladb.tombola.save_ticket(:p_session_id_in, :p_subject_id_in, :p_combination_in, :p_number_of_tickets_in, :p_sum_bet_in, :p_possible_win, :p_min_possible_win)";
            //:cur_draw_id_out, :p_credits_out, :p_serial_number_out, :p_ticket_datetime_out, :p_logged_subject_name_out, :p_barcode_out, :p_language_out, :p_city_out, :p_address_out, :p_commercial_address_out, :p_local_jp_code, :p_global_jp_code, :p_payout_expire_time, :p_error_code_out, :p_error_msg_out)';
            $fn_result = DB::connection('pgsql')->select(
                $statement_string,
                [
                    'p_session_id_in' => $ticketDetails['session_id'],
                    'p_subject_id_in' => $ticketDetails['user_id'],
                    'p_combination_in' => $ticketDetails['combination'],
                    'p_number_of_tickets_in' => $ticketDetails['number_of_tickets'],
                    'p_sum_bet_in' => $ticketDetails['sum_bet'],
                    'p_possible_win' => $ticketDetails['possible_win'],
                    'p_min_possible_win' => $ticketDetails['min_possible_win']
                ]
            );

            $cursor_name = $fn_result[0]->cur_draw_id_out;

            $cur_draw_id_result = DB::connection('pgsql')->select("fetch all in {$cursor_name}");
            $list_draws = [];
            foreach($cur_draw_id_result as $cur){
                $list_draws[] = [
                    'order_num' => $cur->order_num
                ];
            }

            DB::connection('pgsql')->commit();

            if ($fn_result[0]->p_error_code_out == "1"){
                $credits = $fn_result[0]->p_credits_out;
                $serial_number = $fn_result[0]->p_serial_number_out;
                $ticket_datetime = $fn_result[0]->p_ticket_datetime_out;
                $logged_subject_name = $fn_result[0]->p_logged_subject_name_out;
                $barcode = $fn_result[0]->p_barcode_out;
                $language = $fn_result[0]->p_language_out;
                $city = $fn_result[0]->p_city_out;
                $address = $fn_result[0]->p_address_out;
                $commercial_address = $fn_result[0]->p_commercial_address_out;
                $local_jp_code = $fn_result[0]->p_local_jp_code;
                $global_jp_code = $fn_result[0]->p_global_jp_code;
                $payout_expire_time = $fn_result[0]->p_payout_expire_time;

                return [
                    'status' => "OK",
                    "credits" => NumberHelper::convert_double($credits),
                    "credits_formatted"=>NumberHelper::format_double($credits),
                    "serial_number" => $serial_number,
                    "ticket_datetime" => $ticket_datetime,
                    "logged_subject_name" => $logged_subject_name,
                    "barcode" => $barcode,
                    "language" => $language,
                    "city" => $city,
                    "address" => $address,
                    "commercial_address" => $commercial_address,
                    "local_jp_code" => $local_jp_code,
                    "global_jp_code" => $global_jp_code,
                    "payout_expire_time" => $payout_expire_time,
                    "list_draws" => $list_draws
                ];
            }

            //if($fn_result[0]->p_status_out == "-3" && $fn_result[0]->p_error_code_out == "-20003"){
            if($fn_result[0]->p_error_code_out == "-20003"){
                $message = implode(" ", [
                    "TicketModel::saveTicket(" . print_r($ticketDetails, true) . ") DRAW MODEL IS INACTIVE <br />",
                    "tomboladb.tombola.save_ticket(
                    :p_session_id_in = {$ticketDetails['session_id']}, :p_subject_id_in = {$ticketDetails['user_id']}, :p_combination_in = {$ticketDetails['combination']}, 
                    :p_number_of_tickets_in = {$ticketDetails['number_of_tickets']}, :p_sum_bet_in = {$ticketDetails['sum_bet']}, :p_possible_win = {$ticketDetails['possible_win']},
                    :p_min_possible_win = {$ticketDetails['min_possible_win']}, :cur_draw_id_out, :p_credits_out = {$fn_result[0]->p_credits_out}, :p_serial_number_out = {$fn_result[0]->p_serial_number_out}, 
                    :p_ticket_datetime_out = {$fn_result[0]->p_ticket_datetime_out}, :p_logged_subject_name_out = {$fn_result[0]->p_logged_subject_name_out},
                    :p_barcode_out = {$fn_result[0]->p_barcode_out}, :p_language_out = {$fn_result[0]->p_language_out}, :p_city_out = {$fn_result[0]->p_city_out}, :p_address_out = {$fn_result[0]->p_address_out},
                    :p_commercial_address_out = {$fn_result[0]->p_commercial_address_out}, :p_local_jp_code = {$fn_result[0]->p_local_jp_code}, :p_global_jp_code = {$fn_result[0]->p_global_jp_code}, :p_payout_expire_time = {$fn_result[0]->p_payout_expire_time}, 
                    :p_error_code_out = {$fn_result[0]->p_error_code_out}, :p_error_msg_out = {$fn_result[0]->p_error_msg_out}, :p_status_out = {$fn_result[0]->p_status_out}) <br />",
                    ]
                );
                ErrorHelper::writeError($message, $message);
                return [ 'status' => "NOK", "message" => "Draw model is inactive", "error_code" => ErrorConstants::$DRAW_MODEL_IS_INACTIVE ];
            }

            //else if($fn_result[0]->p_status_out == "-1" && $fn_result[0]->p_error_code_out == "-20002"){
            else if($fn_result[0]->p_error_code_out == "-20002"){
                $message = implode(" ", [
                    "TicketModel::saveTicket(" . print_r($ticketDetails, true) . ") NEXT_DRAW_IS_NOT_DEFINED <br />",
                    "tomboladb.tombola.save_ticket(
                    :p_session_id_in = {$ticketDetails['session_id']}, :p_subject_id_in = {$ticketDetails['user_id']}, :p_combination_in = {$ticketDetails['combination']}, 
                    :p_number_of_tickets_in = {$ticketDetails['number_of_tickets']}, :p_sum_bet_in = {$ticketDetails['sum_bet']}, :p_possible_win = {$ticketDetails['possible_win']},
                    :p_min_possible_win = {$ticketDetails['min_possible_win']}, :cur_draw_id_out, :p_credits_out = {$fn_result[0]->p_credits_out}, :p_serial_number_out = {$fn_result[0]->p_serial_number_out}, 
                    :p_ticket_datetime_out = {$fn_result[0]->p_ticket_datetime_out}, :p_logged_subject_name_out = {$fn_result[0]->p_logged_subject_name_out},
                    :p_barcode_out = {$fn_result[0]->p_barcode_out}, :p_language_out = {$fn_result[0]->p_language_out}, :p_city_out = {$fn_result[0]->p_city_out}, :p_address_out = {$fn_result[0]->p_address_out},
                    :p_commercial_address_out = {$fn_result[0]->p_commercial_address_out}, :p_local_jp_code = {$fn_result[0]->p_local_jp_code}, :p_global_jp_code = {$fn_result[0]->p_global_jp_code}, :p_payout_expire_time = {$fn_result[0]->p_payout_expire_time}, 
                    :p_error_code_out = {$fn_result[0]->p_error_code_out}, :p_error_msg_out = {$fn_result[0]->p_error_msg_out}, :p_status_out = {$fn_result[0]->p_status_out}) <br />",
                    ]
                );
                ErrorHelper::writeError($message, $message);
                return [ 'status' => "NOK", "message" => "Next draw is not defined", "error_code" => ErrorConstants::$NEXT_DRAW_IS_NOT_DEFINED ];
            }

            //else if($fn_result[0]->p_status_out == "-1" && $fn_result[0]->p_error_code_out == "-20100"){
            else if($fn_result[0]->p_error_code_out == "-20100"){
                $message = implode(" ", [
                    "TicketModel::saveTicket(" . print_r($ticketDetails, true) . ") UNHANDLED_EXCEPTION <br />",
                    "tomboladb.tombola.save_ticket(
                    :p_session_id_in = {$ticketDetails['session_id']}, :p_subject_id_in = {$ticketDetails['user_id']}, :p_combination_in = {$ticketDetails['combination']}, 
                    :p_number_of_tickets_in = {$ticketDetails['number_of_tickets']}, :p_sum_bet_in = {$ticketDetails['sum_bet']}, :p_possible_win = {$ticketDetails['possible_win']},
                    :p_min_possible_win = {$ticketDetails['min_possible_win']}, :cur_draw_id_out, :p_credits_out = {$fn_result[0]->p_credits_out}, :p_serial_number_out = {$fn_result[0]->p_serial_number_out}, 
                    :p_ticket_datetime_out = {$fn_result[0]->p_ticket_datetime_out}, :p_logged_subject_name_out = {$fn_result[0]->p_logged_subject_name_out},
                    :p_barcode_out = {$fn_result[0]->p_barcode_out}, :p_language_out = {$fn_result[0]->p_language_out}, :p_city_out = {$fn_result[0]->p_city_out}, :p_address_out = {$fn_result[0]->p_address_out},
                    :p_commercial_address_out = {$fn_result[0]->p_commercial_address_out}, :p_local_jp_code = {$fn_result[0]->p_local_jp_code}, :p_global_jp_code = {$fn_result[0]->p_global_jp_code}, :p_payout_expire_time = {$fn_result[0]->p_payout_expire_time}, 
                    :p_error_code_out = {$fn_result[0]->p_error_code_out}, :p_error_msg_out = {$fn_result[0]->p_error_msg_out}, :p_status_out = {$fn_result[0]->p_status_out}) <br />",
                    ]
                );
                ErrorHelper::writeError($message, $message);
                return [ 'status' => "NOK", "message" => "Unhandled exception", "error_code" => ErrorConstants::$UNHANDLED_EXCEPTION ];
            }

            else{
                $message = implode(" ", [
                    "TicketModel::saveTicket(" . print_r($ticketDetails, true) . ") <br />",
                    "tomboladb.tombola.save_ticket(
                    :p_session_id_in = {$ticketDetails['session_id']}, :p_subject_id_in = {$ticketDetails['user_id']}, :p_combination_in = {$ticketDetails['combination']}, 
                    :p_number_of_tickets_in = {$ticketDetails['number_of_tickets']}, :p_sum_bet_in = {$ticketDetails['sum_bet']}, :p_possible_win = {$ticketDetails['possible_win']},
                    :p_min_possible_win = {$ticketDetails['min_possible_win']}, :cur_draw_id_out, :p_credits_out = {$fn_result[0]->p_credits_out}, :p_serial_number_out = {$fn_result[0]->p_serial_number_out}, 
                    :p_ticket_datetime_out = {$fn_result[0]->p_ticket_datetime_out}, :p_logged_subject_name_out = {$fn_result[0]->p_logged_subject_name_out},
                    :p_barcode_out = {$fn_result[0]->p_barcode_out}, :p_language_out = {$fn_result[0]->p_language_out}, :p_city_out = {$fn_result[0]->p_city_out}, :p_address_out = {$fn_result[0]->p_address_out},
                    :p_commercial_address_out = {$fn_result[0]->p_commercial_address_out}, :p_local_jp_code = {$fn_result[0]->p_local_jp_code}, :p_global_jp_code = {$fn_result[0]->p_global_jp_code}, :p_payout_expire_time = {$fn_result[0]->p_payout_expire_time},
                     :p_error_code_out = {$fn_result[0]->p_error_code_out}, :p_error_msg_out = {$fn_result[0]->p_error_msg_out}, :p_status_out = {$fn_result[0]->p_status_out}) <br />",
                    ]
                );
                ErrorHelper::writeError($message, $message);
                return [ 'status' => "NOK" ];
            }
        }catch(\PDOException $ex1){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                    "TicketModel::saveTicket(" . print_r($ticketDetails, true) . ") <br />",
                    "tomboladb.tombola.save_ticket(
                    :p_session_id_in = {$ticketDetails['session_id']}, :p_subject_id_in = {$ticketDetails['user_id']}, :p_combination_in = {$ticketDetails['combination']}, 
                    :p_number_of_tickets_in = {$ticketDetails['number_of_tickets']}, :p_sum_bet_in = {$ticketDetails['sum_bet']}, :p_possible_win = {$ticketDetails['possible_win']},
                    :p_min_possible_win = {$ticketDetails['min_possible_win']}, :cur_draw_id_out, :p_credits_out = {$fn_result[0]->p_credits_out}, :p_serial_number_out = {$fn_result[0]->p_serial_number_out}, 
                    :p_ticket_datetime_out = {$fn_result[0]->p_ticket_datetime_out}, :p_logged_subject_name_out = {$fn_result[0]->p_logged_subject_name_out},
                    :p_barcode_out = {$fn_result[0]->p_barcode_out}, :p_language_out = {$fn_result[0]->p_language_out}, :p_city_out = {$fn_result[0]->p_city_out}, :p_address_out = {$fn_result[0]->p_address_out},
                     :p_commercial_address_out = {$fn_result[0]->p_commercial_address_out}, :p_local_jp_code = {$fn_result[0]->p_local_jp_code}, :p_global_jp_code = {$fn_result[0]->p_global_jp_code}, :p_payout_expire_time = {$fn_result[0]->p_payout_expire_time}, 
                     :p_error_code_out = {$fn_result[0]->p_error_code_out}, :p_error_msg_out = {$fn_result[0]->p_error_msg_out}) <br />",
                    $ex1->getMessage()
                ]
            );
            ErrorHelper::writeError($message, $message);
            return ["status"=>"NOK"];
        }catch(\Exception $ex2){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                    "TicketModel::saveTicket(" . print_r($ticketDetails, true) . ") <br />",
                    "tomboladb.tombola.save_ticket(
                    :p_session_id_in = {$ticketDetails['session_id']}, :p_subject_id_in = {$ticketDetails['user_id']}, :p_combination_in = {$ticketDetails['combination']}, 
                    :p_number_of_tickets_in = {$ticketDetails['number_of_tickets']}, :p_sum_bet_in = {$ticketDetails['sum_bet']}, :p_possible_win = {$ticketDetails['possible_win']},
                    :p_min_possible_win = {$ticketDetails['min_possible_win']}, :cur_draw_id_out, :p_credits_out = {$fn_result[0]->p_credits_out}, :p_serial_number_out = {$fn_result[0]->p_serial_number_out}, 
                    :p_ticket_datetime_out = {$fn_result[0]->p_ticket_datetime_out}, :p_logged_subject_name_out = {$fn_result[0]->p_logged_subject_name_out},
                    :p_barcode_out = {$fn_result[0]->p_barcode_out}, :p_language_out = {$fn_result[0]->p_language_out}, :p_city_out = {$fn_result[0]->p_city_out}, :p_address_out = {$fn_result[0]->p_address_out},
                     :p_commercial_address_out = {$fn_result[0]->p_commercial_address_out}, :p_local_jp_code = {$fn_result[0]->p_local_jp_code}, :p_global_jp_code = {$fn_result[0]->p_global_jp_code}, :p_payout_expire_time = {$fn_result[0]->p_payout_expire_time}, 
                     :p_error_code_out = {$fn_result[0]->p_error_code_out}, :p_error_msg_out = {$fn_result[0]->p_error_msg_out})<br />",
                    $ex2->getMessage()
                ]
            );
            ErrorHelper::writeError($message, $message);
            return ["status"=>"NOK"];
        }
    }

    /**
     * @param array $ticketDetails
     * @return array
     */
    public static function saveTemporaryTicket($ticketDetails){

        if(self::$DEBUG){
            $message = "TicketModel::saveTemporaryTicket >>> SELECT * from tomboladb.tombola.save_temp_ticket(
            :p_session_id_in = {$ticketDetails['session_id']}, :p_combination_in = {$ticketDetails['combination']}, 
            :p_number_of_tickets_in = {$ticketDetails['number_of_tickets']}, :p_sum_bet_in = {$ticketDetails['sum_bet']}, :p_jackpot_in = {$ticketDetails['jackpot']},
            :p_temp_order_number_out, :p_serial_number_out, :p_barcode_out, :p_error_code_out, :p_error_msg_out
            )";
            ErrorHelper::writeInfo($message, $message);
        }

        try {
            DB::connection('pgsql')->beginTransaction();

            $statement_string = 'SELECT * from tomboladb.tombola.save_temp_ticket(:p_session_id_in, :p_combination_in, :p_number_of_tickets_in, :p_sum_bet_in, :p_jackpot_in)';
            $fn_result = DB::connection('pgsql')->select(
                $statement_string,
                [
                    'p_session_id_in' => $ticketDetails['session_id'],
                    'p_combination_in' => $ticketDetails['combination'],
                    'p_number_of_tickets_in' => $ticketDetails['number_of_tickets'],
                    'p_sum_bet_in' => $ticketDetails['sum_bet'],
                    'p_jackpot_in' => $ticketDetails['jackpot']
                ]
            );
            DB::connection('pgsql')->commit();

            $temp_order_number = $fn_result[0]->p_temp_order_number_out;
            $serial_number = $fn_result[0]->p_serial_number_out;
            $barcode = $fn_result[0]->p_barcode_out;
            $error_code = $fn_result[0]->p_error_code_out;
            $error_msg = $fn_result[0]->p_error_msg_out;

            if ($fn_result[0]->p_status_out == "1"){
                return ['status' => "OK", "temporary_order_number" => $temp_order_number, "serial_number" => $serial_number, "barcode" => $barcode];
            }else{
                $message = implode(" ", [
                        "TicketModel::saveTemporaryTicket(" . print_r($ticketDetails, true) . ") <br />",
                        "SELECT * from tomboladb.tombola.save_temp_ticket(
                            :p_session_id_in = {$ticketDetails['session_id']}, :p_combination_in = {$ticketDetails['combination']}, 
                            :p_number_of_tickets_in = {$ticketDetails['number_of_tickets']}, :p_sum_bet_in = {$ticketDetails['sum_bet']}, :p_jackpot_in = {$ticketDetails['jackpot']},
                            :p_temp_order_number_out = {$fn_result[0]->p_temp_order_number_out}, :p_serial_number_out = {$fn_result[0]->p_serial_number_out}, 
                            :p_barcode_out = {$fn_result[0]->p_barcode_out}, :p_error_code_out = {$fn_result[0]->p_error_code_out}, :p_error_msg_out = {$fn_result[0]->p_error_msg_out}) <br />",
                    ]
                );
                ErrorHelper::writeError($message, $message);
                return ['status' => "NOK", "error_code" => $error_code, "error_message" => $error_msg];
            }
        }catch(\PDOException $ex1){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                    "TicketModel::saveTemporaryTicket(" . print_r($ticketDetails, true) . ") <br />",
                    "SELECT * from tomboladb.tombola.save_temp_ticket(
                        :p_session_id_in = {$ticketDetails['session_id']}, :p_combination_in = {$ticketDetails['combination']}, 
                        :p_number_of_tickets_in = {$ticketDetails['number_of_tickets']}, :p_sum_bet_in = {$ticketDetails['sum_bet']}, :p_jackpot_in = {$ticketDetails['jackpot']},
                        :p_temp_order_number_out = {$fn_result[0]->p_temp_order_number_out}, :p_serial_number_out = {$fn_result[0]->p_serial_number_out}, 
                        :p_barcode_out = {$fn_result[0]->p_barcode_out}, :p_error_code_out = {$fn_result[0]->p_error_code_out}, :p_error_msg_out = {$fn_result[0]->p_error_msg_out}) <br />",
                    $ex1->getMessage()
                ]
            );
            ErrorHelper::writeError($message, $message);
            return ["status"=>"NOK"];
        }catch(\Exception $ex2){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                    "TicketModel::saveTemporaryTicket(" . print_r($ticketDetails, true) . ") <br />",
                    "SELECT * from tomboladb.tombola.save_temp_ticket(
                        :p_session_id_in = {$ticketDetails['session_id']}, :p_combination_in = {$ticketDetails['combination']}, 
                        :p_number_of_tickets_in = {$ticketDetails['number_of_tickets']}, :p_sum_bet_in = {$ticketDetails['sum_bet']}, :p_jackpot_in = {$ticketDetails['jackpot']},
                        :p_temp_order_number_out = {$fn_result[0]->p_temp_order_number_out}, :p_serial_number_out = {$fn_result[0]->p_serial_number_out}, 
                        :p_barcode_out = {$fn_result[0]->p_barcode_out}, :p_error_code_out = {$fn_result[0]->p_error_code_out}, :p_error_msg_out = {$fn_result[0]->p_error_msg_out}) <br />",
                    $ex2->getMessage()
                ]
            );
            ErrorHelper::writeError($message, $message);
            return ["status"=>"NOK"];
        }
    }

    /**
     * @param $details
     * @return array
     */
    public static function getTicketDetailsWithBarcode($details){
        try{

            if(self::$DEBUG){
                $message = "TicketModel::getTicketDetailsWithBarcode >>> SELECT * from tomboladb.tombola.get_ticket_details_from_barcode(:p_session_id = {$details['session_id']}, :p_ticket_barcode = {$details['ticket_barcode']}, 'cur_result_out', 'cur_combinations_out')";
                ErrorHelper::writeInfo($message, $message);
            }

            DB::connection('pgsql')->beginTransaction();
            $statement_string = 'SELECT * from tomboladb.tombola.get_ticket_details_from_barcode(:p_session_id, :p_ticket_barcode)';

            $fn_result = DB::connection('pgsql')->select($statement_string,
                array(
                    'p_session_id' => $details['session_id'],
                    'p_ticket_barcode' => $details['ticket_barcode']
                )
            );

            $cursor_name1 = $fn_result[0]->cur_result_out;
            $cur_result1 = DB::connection('pgsql')->select("fetch all in {$cursor_name1}");

            $cursor_name2 = $fn_result[0]->cur_combinations_out;
            $cur_result2 = DB::connection('pgsql')->select("fetch all in {$cursor_name2}");

            $status_out = $fn_result[0]->p_status_out;

            DB::connection('pgsql')->commit();

            return [
                "status" => "OK",
                "status_out" => $status_out,
                "ticket_result"=> $cur_result1,
                "combinations_result"=>$cur_result2
            ];
        }catch(\PDOException $ex1){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                "TicketModel::getTicketDetailsWithBarcode(backoffice_session_id = {$details['session_id']}, ticket_barcode={$details['ticket_barcode']}) <br />\n\n",
                "tomboladb.tombola.get_ticket_details_from_barcode(:p_session_id = {$details['session_id']}, :p_ticket_barcode = {$details['ticket_barcode']}) <br />\n\n",
                $ex1->getMessage(),
            ]);
            ErrorHelper::writeError($message, $message);
            return [
                "status" => "NOK"
            ];
        }catch(\Exception $ex2){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                "TicketModel::getTicketDetailsWithBarcode(backoffice_session_id = {$details['session_id']}, ticket_barcode={$details['ticket_barcode']}) <br />\n\n",
                "tomboladb.tombola.get_ticket_details_from_barcode(:p_session_id = {$details['session_id']}, :p_ticket_barcode = {$details['ticket_barcode']}) <br />\n\n",
                $ex2->getMessage(),
            ]);
            ErrorHelper::writeError($message, $message);
            return [
                "status" => "NOK"
            ];
        }
    }

    /**
     * @param $details
     * @return array
     */
    public static function getLastTicketForSubject($details){
        try{

            if(self::$DEBUG){
                $message = "TicketModel::getLastTicketForSubject >>> SELECT tomboladb.tombola.get_last_ticket_for_subject(:p_session_id = {$details['session_id']}, :p_subject_id = {$details['user_id']}, 'cur_result_out', 'cur_combinations_out')";
                ErrorHelper::writeInfo($message, $message);
            }

            DB::connection('pgsql')->beginTransaction();
            $statement_string = 'SELECT * from tomboladb.tombola.get_last_ticket_for_subject(:p_session_id, :p_subject_id)';

            $fn_result = DB::connection('pgsql')->select($statement_string,
                array(
                    'p_session_id' => $details['session_id'],
                    'p_subject_id' => $details['user_id']
                )
            );

            $cursor_name1 = $fn_result[0]->cur_result_out;
            $cur_result1 = DB::connection('pgsql')->select("fetch all in {$cursor_name1}");

            $cursor_name2 = $fn_result[0]->cur_combinations_out;
            $cur_result2 = DB::connection('pgsql')->select("fetch all in {$cursor_name2}");

            DB::connection('pgsql')->commit();

            return [
                "status" => "OK",
                "ticket_result" => $cur_result1,
                "combinations_result" => $cur_result2
            ];
        }catch(\PDOException $ex1){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                "TicketModel::getLastTicketForSubject(backoffice_session_id = {$details['session_id']}, user_id = {$details['user_id']}) <br />\n\n",
                "tomboladb.tombola.get_last_ticket_for_subject(:p_session_id = {$details['session_id']}, :p_subject_id = {$details['user_id']}) <br />\n\n",
                $ex1->getMessage(),
            ]);
            ErrorHelper::writeError($message, $message);
            return [
                "status" => "NOK"
            ];
        }catch(\Exception $ex2){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                "TicketModel::getLastTicketForSubject(backoffice_session_id = {$details['session_id']}, user_id = {$details['user_id']}) <br />\n\n",
                "tomboladb.tombola.get_last_ticket_for_subject(:p_session_id = {$details['session_id']}, :p_subject_id = {$details['user_id']}) <br />\n\n",
                $ex2->getMessage(),
            ]);
            ErrorHelper::writeError($message, $message);
            return [
                "status" => "NOK"
            ];
        }
    }

    /**
     * @param $details
     * @return array
     */
    public static function getListPreviousDraws($details){
        try{

            if(self::$DEBUG){
                $message = "TicketModel::getListPreviousDraws >>> SELECT * from tomboladb.tombola.list_previous_draws(:p_session_id_in = {$details['session_id']}, :p_draw_id = {$details['draw_id']}, :p_number_of_rows = {$details['per_page']}, 'cur_result_out')";
                ErrorHelper::writeInfo($message, $message);
            }

            DB::connection('pgsql')->beginTransaction();
            $statement_string = 'SELECT * from tomboladb.tombola.list_previous_draws(:p_session_id_in, :p_draw_id, :p_number_of_rows)';

            $fn_result = DB::connection('pgsql')->select($statement_string,
                array(
                    'p_session_id_in' => $details['session_id'],
                    'p_draw_id' => $details['draw_id'],
                    'p_number_of_rows' => $details['per_page']
                )
            );

            $cursor_name1 = $fn_result[0]->cur_result_out;
            $cur_result1 = DB::connection('pgsql')->select("fetch all in {$cursor_name1}");

            DB::connection('pgsql')->commit();

            return [
                "status" => "OK",
                "draw_result"=>$cur_result1
            ];
        }catch(\PDOException $ex1){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                "TicketModel::getListPreviousDraws(session_id = {$details['session_id']}, draw_id = {$details['draw_id']}, per_page={$details['per_page']}) <br />\n\n",
                "tomboladb.tombola.getListPreviousDraws(:p_session_id_in = {$details['session_id']}, :p_draw_id = {$details['draw_id']}, :p_number_of_rows = {$details['per_page']}) <br />\n\n",
                $ex1->getMessage(),
            ]);
            ErrorHelper::writeError($message, $message);
            return [
                "status" => "NOK"
            ];
        }catch(\Exception $ex2){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                "TicketModel::getListPreviousDraws(session_id = {$details['session_id']}, draw_id = {$details['draw_id']}, per_page={$details['per_page']}) <br />\n\n",
                "tomboladb.tombola.getListPreviousDraws(:p_session_id_in = {$details['session_id']}, :p_draw_id = {$details['draw_id']}, :p_number_of_rows = {$details['per_page']}) <br />\n\n",
                $ex2->getMessage(),
            ]);
            ErrorHelper::writeError($message, $message);
            return [
                "status" => "NOK"
            ];
        }
    }

    /**
     * @param $details
     * @return array
     */
    public static function cancelTicket($details){
        try{

            if(self::$DEBUG){
                $message = "TicketModel::cancelTicket >>> SELECT tomboladb.tombola.cancel_ticket(:p_barcode = {$details['barcode']}, :p_session_id_in = {$details['session_id']}, :p_pincode_in = {$details['cashier_pincode']})";
                ErrorHelper::writeInfo($message, $message);
            }

            DB::connection('pgsql')->beginTransaction();
            $statement_string = 'SELECT * from tomboladb.tombola.cancel_ticket(:p_barcode, :p_session_id_in, :p_pincode_in)';

            $fn_result = DB::connection('pgsql')->select($statement_string,
                array(
                    'p_barcode' => $details['barcode'],
                    'p_session_id_in'=> $details['session_id'],
                    'p_pincode_in'=> $details['cashier_pincode']
                )
            );

            $status_out = $fn_result[0]->p_status_out;

            DB::connection('pgsql')->commit();

            if(self::$DEBUG){
                $message = "TicketModel::cancelTicket >>> SELECT tomboladb.tombola.cancel_ticket(:p_barcode = {$details['barcode']}, :p_session_id_in = {$details['session_id']}, :p_pincode_in = {$details['cashier_pincode']}) Status OUT = {$status_out}";
                ErrorHelper::writeInfo($message, $message);
            }

            return [
                "status" => "OK",
                "status_out"=>$status_out
            ];
        }catch(\PDOException $ex1){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                "TicketModel::cancelTicket(session_id = {$details['session_id']}, barcode={$details['barcode']}, cashier_pincode={$details['cashier_pincode']}) <br />\n\n",
                "tomboladb.tombola.cancel_ticket(:p_barcode = {$details['barcode']}, :p_session_id_in = {$details['session_id']}, :p_pincode_in = {$details['cashier_pincode']}) <br />\n\n",
                $ex1->getMessage(),
            ]);
            ErrorHelper::writeError($message, $message);
            return [
                "status" => "NOK"
            ];
        }catch(\Exception $ex2){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                "TicketModel::cancelTicket(session_id = {$details['session_id']}, barcode={$details['barcode']}, cashier_pincode={$details['cashier_pincode']}) <br />\n\n",
                "tomboladb.tombola.cancel_ticket(:p_barcode = {$details['barcode']}, :p_session_id_in = {$details['session_id']}, :p_pincode_in = {$details['cashier_pincode']}) <br />\n\n",
                $ex2->getMessage(),
            ]);
            ErrorHelper::writeError($message, $message);
            return [
                "status" => "NOK"
            ];
        }
    }

    /**
     * @param $details
     * @return array
     */
    public static function printTicket($details){
        try{

            if(self::$DEBUG){
                $message = "TicketModel::printTicket >>> SELECT * from tomboladb.tombola.print_ticket(:p_ticket_id_in = {$details['ticket_id']}, :p_session_id = {$details['session_id']})";
                ErrorHelper::writeInfo($message, $message);
            }

            DB::connection('pgsql')->beginTransaction();
            $statement_string = 'SELECT * from tomboladb.tombola.print_ticket(:p_ticket_id_in, :p_session_id)';

            $fn_result = DB::connection('pgsql')->select($statement_string,
                array(
                    'p_ticket_id_in' => $details['ticket_id'],
                    'p_session_id'=> $details['session_id']
                )
            );

            $status_out = $fn_result[0]->p_status_out;

            DB::connection('pgsql')->commit();

            return [
                "status" => "OK",
                "status_out"=>$status_out
            ];
        }catch(\PDOException $ex1){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                "TicketModel::printTicket(session_id = {$details['session_id']}, ticket_id={$details['ticket_id']}) <br />\n\n",
                "tomboladb.tombola.print_ticket(:p_ticket_id_in = {$details['ticket_id']}, :p_session_id = {$details['session_id']}) <br />\n\n",
                $ex1->getMessage(),
            ]);
            ErrorHelper::writeError($message, $message);
            return [
                "status" => "NOK"
            ];
        }catch(\Exception $ex2){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                "TicketModel::printTicket(session_id = {$details['session_id']}, ticket_id={$details['ticket_id']}) <br />\n\n",
                "tomboladb.tombola.print_ticket(:p_ticket_id_in = {$details['ticket_id']}, :p_session_id = {$details['session_id']}) <br />\n\n",
                $ex2->getMessage(),
            ]);
            ErrorHelper::writeError($message, $message);
            return [
                "status" => "NOK"
            ];
        }
    }

    public static function saveTicketShift($ticketDetails){

        if(self::$DEBUG){
            $message = "TicketModel::saveTicketShift >>> SELECT * from tomboladb.tombola.save_ticket_shift(
            :p_session_id_in = {$ticketDetails['session_id']}, :p_subject_id_in = {$ticketDetails['user_id']}, :p_combination_in = {$ticketDetails['combination']}, 
            :p_number_of_tickets_in = {$ticketDetails['number_of_tickets']}, :p_sum_bet_in = {$ticketDetails['sum_bet']}, :p_possible_win = {$ticketDetails['possible_win']},
            :p_min_possible_win = {$ticketDetails['min_possible_win']}, :cur_barcodes_out, :p_error_code_out, :p_error_msg_out
            )";
            ErrorHelper::writeInfo($message, $message);
        }

        try {
            DB::connection('pgsql')->beginTransaction();

            $statement_string = "SELECT * from tomboladb.tombola.save_ticket_shift(:p_session_id_in, :p_subject_id_in, :p_combination_in, :p_number_of_tickets_in, :p_sum_bet_in, :p_possible_win, :p_min_possible_win)";
            //:cur_draw_id_out, :p_credits_out, :p_serial_number_out, :p_ticket_datetime_out, :p_logged_subject_name_out, :p_barcode_out, :p_language_out, :p_city_out, :p_address_out, :p_commercial_address_out, :p_local_jp_code, :p_global_jp_code, :p_payout_expire_time, :p_error_code_out, :p_error_msg_out)';
            $fn_result = DB::connection('pgsql')->select(
                $statement_string,
                [
                    'p_session_id_in' => $ticketDetails['session_id'],
                    'p_subject_id_in' => $ticketDetails['user_id'],
                    'p_combination_in' => $ticketDetails['combination'],
                    'p_number_of_tickets_in' => $ticketDetails['number_of_tickets'],
                    'p_sum_bet_in' => $ticketDetails['sum_bet'],
                    'p_possible_win' => $ticketDetails['possible_win'],
                    'p_min_possible_win' => $ticketDetails['min_possible_win']
                ]
            );

            $cursor_name = $fn_result[0]->cur_barcodes_out;

            $cur_barcodes_result = DB::connection('pgsql')->select("fetch all in {$cursor_name}");
            $list_barcodes = [];
            foreach($cur_barcodes_result as $cur){
                $list_barcodes[] = [
                    'barcode' => $cur->barcode
                ];
            }

            DB::connection('pgsql')->commit();

            if ($fn_result[0]->p_error_code_out == "1"){

                return [
                    'status' => "OK",
                    'list_barcodes' => $list_barcodes
                ];
            }

            //if($fn_result[0]->p_status_out == "-3" && $fn_result[0]->p_error_code_out == "-20003"){
            if($fn_result[0]->p_error_code_out == "-20003"){
                $message = implode(" ", [
                    "TicketModel::saveTicketShift(" . print_r($ticketDetails, true) . ") DRAW MODEL IS INACTIVE <br />",
                    "tomboladb.tombola.save_ticket_shift(
                    :p_session_id_in = {$ticketDetails['session_id']}, :p_subject_id_in = {$ticketDetails['user_id']}, :p_combination_in = {$ticketDetails['combination']}, 
                    :p_number_of_tickets_in = {$ticketDetails['number_of_tickets']}, :p_sum_bet_in = {$ticketDetails['sum_bet']}, :p_possible_win = {$ticketDetails['possible_win']},
                    :p_min_possible_win = {$ticketDetails['min_possible_win']}, :cur_barcodes_out, :p_error_code_out = {$fn_result[0]->p_error_code_out} ) <br />",
                    ]
                );
                ErrorHelper::writeError($message, $message);
                return [ 'status' => "NOK", "message" => "Draw model is inactive", "error_code" => ErrorConstants::$DRAW_MODEL_IS_INACTIVE ];
            }

            //else if($fn_result[0]->p_status_out == "-1" && $fn_result[0]->p_error_code_out == "-20002"){
            else if($fn_result[0]->p_error_code_out == "-20002"){
                $message = implode(" ", [
                    "TicketModel::saveTicketShift(" . print_r($ticketDetails, true) . ") NEXT_DRAW_IS_NOT_DEFINED <br />",
                    "tomboladb.tombola.save_ticket_shift(
                    :p_session_id_in = {$ticketDetails['session_id']}, :p_subject_id_in = {$ticketDetails['user_id']}, :p_combination_in = {$ticketDetails['combination']}, 
                    :p_number_of_tickets_in = {$ticketDetails['number_of_tickets']}, :p_sum_bet_in = {$ticketDetails['sum_bet']}, :p_possible_win = {$ticketDetails['possible_win']},
                    :p_min_possible_win = {$ticketDetails['min_possible_win']}, :cur_barcodes_out, :p_error_code_out = {$fn_result[0]->p_error_code_out} ) <br />",
                    ]
                );
                ErrorHelper::writeError($message, $message);
                return [ 'status' => "NOK", "message" => "Next draw is not defined", "error_code" => ErrorConstants::$NEXT_DRAW_IS_NOT_DEFINED ];
            }

            //else if($fn_result[0]->p_status_out == "-1" && $fn_result[0]->p_error_code_out == "-20100"){
            else if($fn_result[0]->p_error_code_out == "-20100"){
                $message = implode(" ", [
                    "TicketModel::saveTicketShift(" . print_r($ticketDetails, true) . ") UNHANDLED_EXCEPTION <br />",
                    "tomboladb.tombola.save_ticket_shift(
                    :p_session_id_in = {$ticketDetails['session_id']}, :p_subject_id_in = {$ticketDetails['user_id']}, :p_combination_in = {$ticketDetails['combination']}, 
                    :p_number_of_tickets_in = {$ticketDetails['number_of_tickets']}, :p_sum_bet_in = {$ticketDetails['sum_bet']}, :p_possible_win = {$ticketDetails['possible_win']},
                    :p_min_possible_win = {$ticketDetails['min_possible_win']}, :cur_barcodes_out, :p_error_code_out = {$fn_result[0]->p_error_code_out} ) <br />",
                    ]
                );
                ErrorHelper::writeError($message, $message);
                return [ 'status' => "NOK", "message" => "Unhandled exception", "error_code" => ErrorConstants::$UNHANDLED_EXCEPTION ];
            }

            else{
                $message = implode(" ", [
                    "TicketModel::saveTicketShift(" . print_r($ticketDetails, true) . ") <br />",
                    "tomboladb.tombola.save_ticket_shift(
                    :p_session_id_in = {$ticketDetails['session_id']}, :p_subject_id_in = {$ticketDetails['user_id']}, :p_combination_in = {$ticketDetails['combination']}, 
                    :p_number_of_tickets_in = {$ticketDetails['number_of_tickets']}, :p_sum_bet_in = {$ticketDetails['sum_bet']}, :p_possible_win = {$ticketDetails['possible_win']},
                    :p_min_possible_win = {$ticketDetails['min_possible_win']}, :cur_barcodes_out, :p_error_code_out = {$fn_result[0]->p_error_code_out} ) <br />",
                    ]
                );
                ErrorHelper::writeError($message, $message);
                return [ 'status' => "NOK" ];
            }
        }catch(\PDOException $ex1){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                    "TicketModel::saveTicketShift(" . print_r($ticketDetails, true) . ") <br />",
                    "tomboladb.tombola.save_ticket_shift(
                    :p_session_id_in = {$ticketDetails['session_id']}, :p_subject_id_in = {$ticketDetails['user_id']}, :p_combination_in = {$ticketDetails['combination']}, 
                    :p_number_of_tickets_in = {$ticketDetails['number_of_tickets']}, :p_sum_bet_in = {$ticketDetails['sum_bet']}, :p_possible_win = {$ticketDetails['possible_win']},
                    :p_min_possible_win = {$ticketDetails['min_possible_win']}, :cur_barcodes_out ) <br />",
                    $ex1->getMessage()
                ]
            );
            ErrorHelper::writeError($message, $message);
            return ["status"=>"NOK"];
        }catch(\Exception $ex2){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                    "TicketModel::saveTicketShift(" . print_r($ticketDetails, true) . ") <br />",
                    "tomboladb.tombola.save_ticket_shift(
                    :p_session_id_in = {$ticketDetails['session_id']}, :p_subject_id_in = {$ticketDetails['user_id']}, :p_combination_in = {$ticketDetails['combination']}, 
                    :p_number_of_tickets_in = {$ticketDetails['number_of_tickets']}, :p_sum_bet_in = {$ticketDetails['sum_bet']}, :p_possible_win = {$ticketDetails['possible_win']},
                    :p_min_possible_win = {$ticketDetails['min_possible_win']}, :cur_barcodes_out ) <br />",
                    $ex2->getMessage()
                ]
            );
            ErrorHelper::writeError($message, $message);
            return ["status"=>"NOK"];
        }
    }

    public static function payoutTicket($ticketDetails){
        if(self::$DEBUG){
            $message = "TicketModel::payoutTicket >>> SELECT * from tomboladb.tombola.payout_ticket(
            :p_barcode = {$ticketDetails['barcode']}, :p_session_id_in = {$ticketDetails['session_id']}, 
            :p_pincode_in = {$ticketDetails['cashier_pincode']}, :p_bet, :p_withdraw, :p_subject_name, :p_transaction_id, :p_currency, :p_language, :p_status_out)";
            ErrorHelper::writeInfo($message, $message);
        }

        try {
            DB::connection('pgsql')->beginTransaction();

            $statement_string = "SELECT * from tomboladb.tombola.payout_ticket(:p_barcode, :p_session_id_in, :p_pincode_in)";
            $fn_result = DB::connection('pgsql')->select(
                $statement_string,
                [
                    'p_barcode' => $ticketDetails['barcode'],
                    'p_session_id_in' => $ticketDetails['session_id'],
                    'p_pincode_in' => $ticketDetails['cashier_pincode']
                ]
            );

            $status_out = $fn_result[0]->p_status_out;

            DB::connection('pgsql')->commit();

            if ($status_out == "1"){
                return [
                    'status' => "OK",
                    'barcode' => $ticketDetails['barcode'],
                    'bet' => $fn_result[0]->p_bet,
                    'withdraw' => $fn_result[0]->p_withdraw,
                    'subject_name' => $fn_result[0]->p_subject_name,
                    'transaction_id' => $fn_result[0]->p_transaction_id,
                    'currency' => $fn_result[0]->p_currency,
                    'language' => $fn_result[0]->p_language
                ];
            }
            else{
                $message = implode(" ", [
                        "TicketModel::payoutTicket(" . print_r($ticketDetails, true) . ") <br />",
                        "tomboladb.tombola.payout_ticket(:p_barcode = {$ticketDetails['barcode']}, :p_session_id_in = {$ticketDetails['session_id']}, :p_pincode_in = {$ticketDetails['cashier_pincode']}, :p_status_out = {$status_out}) <br />",
                    ]
                );
                ErrorHelper::writeError($message, $message);
                return ['status' => "NOK"];
            }
        }catch(\PDOException $ex1){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                    "TicketModel::payoutTicket(" . print_r($ticketDetails, true) . ") <br />",
                    "tomboladb.tombola.payout_ticket(:p_barcode = {$ticketDetails['barcode']}, :p_session_id_in = {$ticketDetails['session_id']}, :p_pincode_in = {$ticketDetails['cashier_pincode']}) <br />",
                    $ex1->getMessage()
                ]
            );
            ErrorHelper::writeError($message, $message);
            return ["status"=>"NOK"];
        }catch(\Exception $ex2){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                    "TicketModel::payoutTicket(" . print_r($ticketDetails, true) . ") <br />",
                    "tomboladb.tombola.payout_ticket(:p_barcode = {$ticketDetails['barcode']}, :p_session_id_in = {$ticketDetails['session_id']}, :p_pincode_in = {$ticketDetails['cashier_pincode']}) <br />",
                    $ex2->getMessage()
                ]
            );
            ErrorHelper::writeError($message, $message);
            return ["status"=>"NOK"];
        }
    }
}
