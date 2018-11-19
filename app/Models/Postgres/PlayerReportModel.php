<?php

namespace App\Models\Postgres;

use Illuminate\Support\Facades\DB;

use App\Helpers\ErrorHelper;

class PlayerReportModel
{

    private static $DEBUG = false;

    /**
     * @param $details
     * @return array
     */
    public static function listSubjectMoneyTransactions($details){
        try{

            if(self::$DEBUG){
                $message = "App\Models\Postgres\PlayerReportModel::listSubjectMoneyTransactions >>> 
                SELECT tomboladb.tombola.list_subjects_money_transactions(:p_session_id_in = {$details['session_id']}, :p_subject_id_in = {$details['subject_id']}, 
                :p_start_date = {$details['start_date']}, :p_end_date = {$details['end_date']}, :p_page_number = {$details['page_number']}, :p_number_of_results = {$details['number_of_results']})";
                ErrorHelper::writeInfo($message, $message);
            }

            DB::connection('pgsql')->beginTransaction();
            $statement_string = 'SELECT * from tomboladb.tombola.list_subjects_money_transactions(:p_session_id_in, :p_subject_id_in, :p_start_date, :p_end_date, :p_page_number, :p_number_of_results)';

            $fn_result = DB::connection('pgsql')->select($statement_string,
                array(
                    'p_session_id_in' => $details['session_id'],
					'p_subject_id_in' => $details['subject_id'],
                    'p_start_date' => $details['start_date'],
                    'p_end_date' => $details['end_date'],
                    'p_page_number' => $details['page_number'],
                    'p_number_of_results' => $details['number_of_results'],
                )
            );
            $cursor_name1 = $fn_result[0]->cur_result_out;

            $cur_result = DB::connection('pgsql')->select("fetch all in {$cursor_name1}");

            $cursor_name2 = $fn_result[0]->cur_list_transactions;

            $cur_list_transactions = DB::connection('pgsql')->select("fetch all in {$cursor_name2}");

            DB::connection('pgsql')->commit();

            return [
                "status" => "OK",
                "report_list_transactions_rows_count" => $fn_result[0]->p_rows_count,
                "report_total"=>$cur_result,
                "report_list_transactions" => $cur_list_transactions
            ];
        }catch(\PDOException $ex1){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                    "PlayerReportModel::listSubjectMoneyTransactions(session_id = {$details['session_id']}, subject_id = {$details['subject_id']}, start_date = {$details['start_date']}, end_date = {$details['end_date']}) <br />",
                    "tomboladb.tombola.list_subjects_money_transactions(:p_session_id_in = {$details['session_id']}, :p_subject_id_in = {$details['subject_id']}, 
                    :p_start_date = {$details['start_date']}, :p_end_date = {$details['end_date']}, :p_page_number = {$details['page_number']}, :p_number_of_results = {$details['number_of_results']}) <br />",
                    $ex1->getMessage()
                ]
            );
            ErrorHelper::writeError($message, $message);
            return [
                "status" => "NOK",
                "rows_count" => 0,
                "report_total" => null,
                "report_list_transactions" => null
            ];
        }catch(\Exception $ex2){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                    "PlayerReportModel::listSubjectMoneyTransactions(session_id = {$details['session_id']}, subject_id = {$details['subject_id']}, start_date = {$details['start_date']}, end_date = {$details['end_date']}) <br />",
                    "tomboladb.tombola.list_subjects_money_transactions(:p_session_id_in = {$details['session_id']}, :p_subject_id_in = {$details['subject_id']}, 
                    :p_start_date = {$details['start_date']}, :p_end_date = {$details['end_date']}, :p_page_number = {$details['page_number']}, :p_number_of_results = {$details['number_of_results']}) <br />",
                    $ex2->getMessage()
                ]
            );
            ErrorHelper::writeError($message, $message);
            return [
                "status" => "NOK",
                "rows_count" => 0,
                "report_total" => null,
                "report_list_transactions" => null
            ];
        }
    }
	
	/**
     * @param $details
     * @return array
     */
    public static function listPlayerTickets($details){
        try{
            if(self::$DEBUG){
                $message = "App\Models\Postgres\PlayerReportModel::listPlayerTickets >>> 
                SELECT tomboladb.tombola.get_player_tickets(:p_session_id_in = {$details['session_id']}, :p_player_id = {$details['user_id']}, 'cur_result_out')";
                ErrorHelper::writeInfo($message, $message);
            }
            DB::connection('pgsql')->beginTransaction();
            $statement_string = 'SELECT * from tomboladb.tombola.get_player_tickets(:p_session_id_in, :p_player_id)';
            $fn_result = DB::connection('pgsql')->select($statement_string,
                array(
                    'p_session_id_in' => $details['session_id'],
					'p_player_id' => $details['user_id'],
                )
            );
            $cursor_name = $fn_result[0]->cur_result_out;
            $cur_result = DB::connection('pgsql')->select("fetch all in {$cursor_name}");
            $cursor_name2 = $fn_result[0]->cur_combinations_out;
            $cur_combinations = DB::connection('pgsql')->select("fetch all in {$cursor_name2}");
            DB::connection('pgsql')->commit();
            return [
                "status" => "OK",
                "list_report"=>$cur_result,
                "list_combinations" => $cur_combinations
            ];
        }catch(\PDOException $ex1){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                    "PlayerReportModel::listPlayerTickets(session_id = {$details['session_id']}, user_id = {$details['user_id']}) <br />",
                    "tomboladb.tombola.get_player_tickets(:p_session_id_in = {$details['session_id']}, :p_player_id = {$details['user_id']}) <br />",
                    $ex1->getMessage()
                ]
            );
            ErrorHelper::writeError($message, $message);
            return [
                "status" => "NOK",
                "list_report" => null,
                "list_combinations" => null
            ];
        }catch(\Exception $ex2){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                    "PlayerReportModel::listPlayerTickets(session_id = {$details['session_id']}, user_id = {$details['user_id']}) <br />",
                    "tomboladb.tombola.get_player_tickets(:p_session_id_in = {$details['session_id']}, :p_player_id = {$details['user_id']}) <br />",
                    $ex2->getMessage()
                ]
            );
            ErrorHelper::writeError($message, $message);
            return [
                "status" => "NOK",
                "list_report" => null,
                "list_combinations" => null
            ];
        }
    }

    /**
     * @param $details
     * @return array
     */
    public static function listPlayerLoginHistory($details){
        try{

            if(self::$DEBUG){
                $message = "App\Models\Postgres\PlayerReportModel::listPlayerLoginHistory >>> 
                SELECT tomboladb.core.report_login_history(:p_session_id_in = {$details['session_id']}, :p_subject_id = {$details['subject_id']}, :p_start_date = {$details['start_date']}, :p_end_date = {$details['end_date']}, 'cur_report_list')";
                ErrorHelper::writeInfo($message, $message);
            }

            DB::connection('pgsql')->beginTransaction();
            $statement_string = 'SELECT * from tomboladb.core.report_login_history(:p_session_id_in, :p_subject_id, :p_start_date, :p_end_date)';

            $fn_result = DB::connection('pgsql')->select($statement_string,
                array(
                    'p_session_id_in' => $details['session_id'],
					'p_subject_id' => $details['subject_id'],
                    'p_start_date' => $details['start_date'],
                    'p_end_date' => $details['end_date']
                )
            );
            $cursor_name = $fn_result[0]->cur_report_list;
            $cur_result = DB::connection('pgsql')->select("fetch all in {$cursor_name}");
            DB::connection('pgsql')->commit();
            return [
                "status" => "OK",
                "report"=>$cur_result
            ];
        }catch(\PDOException $ex1){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                    "PlayerReportModel::listPlayerLoginHistory(session_id = {$details['session_id']}, subject_id = {$details['subject_id']}, start_date = {$details['start_date']}, end_date = {$details['end_date']}) <br />",
                    "tomboladb.core.report_login_history(:p_session_id_in = {$details['session_id']}, :p_subject_id = {$details['subject_id']}, :p_start_date = {$details['start_date']}, :p_end_date = {$details['end_date']}) <br />",
                    $ex1->getMessage()
                ]
            );
            ErrorHelper::writeError($message, $message);
            return [
                "status" => "NOK",
                "report" => null
            ];
        }catch(\Exception $ex2){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                    "PlayerReportModel::listPlayerLoginHistory(session_id = {$details['session_id']}, subject_id = {$details['subject_id']}, start_date = {$details['start_date']}, end_date = {$details['end_date']}) <br />",
                    "tomboladb.core.report_login_history(:p_session_id_in = {$details['session_id']}, :p_subject_id = {$details['subject_id']}, :p_start_date = {$details['start_date']}, :p_end_date = {$details['end_date']}) <br />",
                    $ex2->getMessage()
                ]
            );
            ErrorHelper::writeError($message, $message);
            return [
                "status" => "NOK",
                "report" => null
            ];
        }
    }

    /**
     * @param $details
     * @return array
     */
    public static function listPlayerHistory($details){
        try{

            if(self::$DEBUG){
                $message = "App\Models\Postgres\PlayerReportModel::listPlayerHistory >>> 
                SELECT tomboladb.tombola.report_player_history(:p_session_id_in = {$details['session_id']}, :p_player_id = {$details['player_id']}, 
                :p_start_date = {$details['start_date']}, :p_end_date = {$details['end_date']}, 
                :p_page_number = {$details['page_number']}, :p_number_of_results = {$details['number_of_results']},
                'cur_report_list')";
                ErrorHelper::writeInfo($message, $message);
            }

            DB::connection('pgsql')->beginTransaction();
            $statement_string = 'SELECT * from tomboladb.tombola.report_player_history(:p_session_id_in, :p_player_id, :p_start_date, :p_end_date, :p_page_number, :p_number_of_results)';

            $fn_result = DB::connection('pgsql')->select($statement_string,
                array(
                    'p_session_id_in' => $details['session_id'],
					'p_player_id' => $details['player_id'],
                    'p_start_date' => $details['start_date'],
                    'p_end_date' => $details['end_date'],
                    'p_page_number' => $details['page_number'],
                    'p_number_of_results' => $details['number_of_results']
                )
            );
            $cursor_name = $fn_result[0]->cur_report_list;
            $cur_result = DB::connection('pgsql')->select("fetch all in {$cursor_name}");
            DB::connection('pgsql')->commit();
            return [
                "status" => "OK",
                "report"=>$cur_result,
                "records_total" => $fn_result[0]->p_rows_count
            ];
        }catch(\PDOException $ex1){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                    "PlayerReportModel::listPlayerHistory(session_id = {$details['session_id']}, player_id = {$details['player_id']}, start_date = {$details['start_date']}, end_date = {$details['end_date']}, page_number = {$details['page_number']}, number_of_results = {$details['number_of_results']}) <br />",
                    "tomboladb.tombola.report_player_history(:p_session_id_in = {$details['session_id']}, :p_player_id = {$details['player_id']}, :p_start_date = {$details['start_date']}, :p_end_date = {$details['end_date']}, page_number = {$details['page_number']}, number_of_results = {$details['number_of_results']}) <br />",
                    $ex1->getMessage()
                ]
            );
            ErrorHelper::writeError($message, $message);
            return [
                "status" => "NOK",
                "report" => null,
                "records_total" => 0
            ];
        }catch(\Exception $ex2){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                    "PlayerReportModel::listPlayerHistory(session_id = {$details['session_id']}, player_id = {$details['player_id']}, start_date = {$details['start_date']}, end_date = {$details['end_date']}, page_number = {$details['page_number']}, number_of_results = {$details['number_of_results']}) <br />",
                    "tomboladb.tombola.report_player_history(:p_session_id_in = {$details['session_id']}, :p_player_id = {$details['player_id']}, :p_start_date = {$details['start_date']}, :p_end_date = {$details['end_date']}, page_number = {$details['page_number']}, number_of_results = {$details['number_of_results']}) <br />",
                    $ex2->getMessage()
                ]
            );
            ErrorHelper::writeError($message, $message);
            return [
                "status" => "NOK",
                "report" => null,
                "records_total" => 0
            ];
        }
    }
}
