<?php

namespace App\Models\Postgres;

use Illuminate\Support\Facades\DB;

use App\Helpers\ErrorHelper;

class ShiftCashierModel
{

    private static $DEBUG = false;

    /**
     * @param $details
     * @return array
     */
    public static function listCollectorShiftReport($details){
        try{

            if(self::$DEBUG){
                $message = "App\Models\Postgres\ShiftCashierModel::listCollectorShiftReport >>> 
                SELECT tomboladb.core.collectors_shift_report(:p_session_id_in = {$details['session_id']}, :p_collector_id = {$details['collector_id']}, :p_cashier_id = {$details['cashier_id']}, :p_service_code = {$details['service_code']})";
                ErrorHelper::writeInfo($message, $message);
            }

            DB::connection('pgsql')->beginTransaction();
            $statement_string = 'SELECT * from tomboladb.core.collectors_shift_report(:p_session_id_in, :p_collector_id, :p_cashier_id, :p_service_code)';

            $fn_result = DB::connection('pgsql')->select($statement_string,
                array(
                    'p_session_id_in' => $details['session_id'],
					'p_collector_id' => $details['collector_id'],
                    'p_cashier_id' => $details['cashier_id'],
                    'p_service_code' => $details['service_code']
                )
            );
            $collector_name = $fn_result[0]->p_collector_name;
            $last_collect_timestamp = $fn_result[0]->p_last_collect_tmstp;
            $cursor_name1 = $fn_result[0]->cur_report_list;
            $cur_result = DB::connection('pgsql')->select("fetch all in {$cursor_name1}");
            DB::connection('pgsql')->commit();
            return [
                "status" => "OK",
                "report"=>$cur_result,
                "collector_name" => $collector_name,
                "last_collect_date_time" => $last_collect_timestamp
            ];
        }catch(\PDOException $ex1){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                    "ShiftCashierModel::listCollectorShiftReport(session_id = {$details['session_id']}, collector_id = {$details['collector_id']}, cashier_id = {$details['cashier_id']}, service_code = {$details['service_code']}) <br />",
                    "tomboladb.core.collectors_shift_report(:p_session_id_in = {$details['session_id']}, :p_collector_id = {$details['collector_id']}, :p_cashier_id = {$details['cashier_id']}, :p_service_code = {$details['service_code']}) <br />",
                    $ex1->getMessage()
                ]
            );
            ErrorHelper::writeError($message, $message);
            return [
                "status" => "NOK",
                "report" => null,
                "collector_name" => null,
                "last_collect_date_time" => null
            ];
        }catch(\Exception $ex2){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                    "ShiftCashierModel::listCollectorShiftReport(session_id = {$details['session_id']}, collector_id = {$details['collector_id']}, cashier_id = {$details['cashier_id']}, service_code = {$details['service_code']}) <br />",
                    "tomboladb.core.collectors_shift_report(:p_session_id_in = {$details['session_id']}, :p_collector_id = {$details['collector_id']}, :p_cashier_id = {$details['cashier_id']}, :p_service_code = {$details['service_code']}) <br />",
                    $ex2->getMessage()
                ]
            );
            ErrorHelper::writeError($message, $message);
            return [
                "status" => "NOK",
                "report" => null,
                "collector_name" => null,
                "last_collect_date_time" => null
            ];
        }
    }

	/**
     * @param $details
     * @return array
     */
    public static function collectFromCashier($details){
        try{
            if(self::$DEBUG){
                $message = "App\Models\Postgres\ShiftCashierModel::collectFromCashier >>>
                SELECT tomboladb.tombola.collect_from_cashier(:p_session_id_in = {$details['session_id']}, :p_cashier_id = {$details['cashier_id']}, :p_collector_id = {$details['collector_id']},
                :p_amount = {$details['amount']}, :p_service_code = {$details['service_code']}, ':p_status_out')";
                ErrorHelper::writeInfo($message, $message);
            }
            DB::connection('pgsql')->beginTransaction();
            $statement_string = 'SELECT * from tomboladb.tombola.collect_from_cashier(:p_session_id_in, :p_cashier_id, :p_collector_id, :p_amount, :p_service_code)';
            $fn_result = DB::connection('pgsql')->select($statement_string,
                array(
                    'p_session_id_in' => $details['session_id'],
					'p_cashier_id' => $details['cashier_id'],
                    'p_collector_id' => $details['collector_id'],
                    'p_amount' => $details['amount'],
                    'p_service_code' => $details['service_code']
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
                    "ShiftCashierModel::collectFromCashier(session_id = {$details['session_id']}, cashier_id = {$details['cashier_id']}, collector_id = {$details['collector_id']}, amount = {$details['amount']}, service_code = {$details['service_code']}) <br />",
                    "tomboladb.tombola.collect_from_cashier(:p_session_id_in = {$details['session_id']}, :p_cashier_id = {$details['cashier_id']}, :p_collector_id = {$details['collector_id']}, :p_amount = {$details['amount']}, :p_service_code = {$details['service_code']}) <br />",
                    $ex1->getMessage()
                ]
            );
            ErrorHelper::writeError($message, $message);
            return [
                "status" => "NOK",
                "status_out" => null
            ];
        }catch(\Exception $ex2){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                    "ShiftCashierModel::collectFromCashier(session_id = {$details['session_id']}, cashier_id = {$details['cashier_id']}, collector_id = {$details['collector_id']}, amount = {$details['amount']}, service_code = {$details['service_code']}) <br />",
                    "tomboladb.tombola.collect_from_cashier(:p_session_id_in = {$details['session_id']}, :p_cashier_id = {$details['cashier_id']}, :p_collector_id = {$details['collector_id']}, :p_amount = {$details['amount']}, :p_service_code = {$details['service_code']}) <br />",
                    $ex2->getMessage()
                ]
            );
            ErrorHelper::writeError($message, $message);
            return [
                "status" => "NOK",
                "status_out" => null
            ];
        }
    }

    /**
     * @param $details
     * @return array
     */
    public static function listCashierShiftReport($details){
        try{

            if(self::$DEBUG){
                $message = "App\Models\Postgres\ShiftCashierModel::listCashierShiftReport >>> 
                SELECT tomboladb.core.cashier_shift_report(:p_session_id_in = {$details['session_id']}, :p_cashier_id = {$details['cashier_id']}, :p_shift_no = {$details['shift_number']}, 'cur_report_list')";
                ErrorHelper::writeInfo($message, $message);
            }

            DB::connection('pgsql')->beginTransaction();
            $statement_string = 'SELECT * from tomboladb.core.cashier_shift_report(:p_session_id_in, :p_cashier_id, :p_shift_no)';

            $fn_result = DB::connection('pgsql')->select($statement_string,
                array(
                    'p_session_id_in' => $details['session_id'],
					'p_cashier_id' => $details['cashier_id'],
                    'p_shift_no' => $details['shift_number']
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
                    "ShiftCashierModel::listCashierShiftReport(session_id = {$details['session_id']}, cashier_id = {$details['cashier_id']}, shift_number = {$details['shift_number']}) <br />",
                    "tomboladb.core.cashier_shift_report(:p_session_id_in = {$details['session_id']}, :p_cashier_id = {$details['cashier_id']}, :p_shift_no = {$details['shift_number']}) <br />",
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
                    "ShiftCashierModel::listCashierShiftReport(session_id = {$details['session_id']}, cashier_id = {$details['cashier_id']}, shift_number = {$details['shift_number']}) <br />",
                    "tomboladb.core.cashier_shift_report(:p_session_id_in = {$details['session_id']}, :p_cashier_id = {$details['cashier_id']}, :p_shift_no = {$details['shift_number']}) <br />",
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
}
