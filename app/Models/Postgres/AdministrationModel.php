<?php

namespace App\Models\Postgres;

use Illuminate\Support\Facades\DB;
use App\Helpers\ErrorHelper;

class AdministrationModel
{

    private static $DEBUG = false;

    /**
     * @param $details
     * @return array
     */
    public static function listAffiliatesAndParameters($details){

        if(self::$DEBUG){
            $message = "AdministrationModel::listAffiliatesAndParameters >>> SELECT core.list_aff_and_parameters(:p_session_id_in = {$details['session_id']}, cur_result_out, p_status_out)";
            ErrorHelper::writeInfo($message, $message);
        }

        try {
            DB::connection('pgsql')->beginTransaction();

            $statement_string = "SELECT * from tomboladb.core.list_aff_and_parameters(:p_session_id_in)";
            $fn_result = DB::connection('pgsql')->select(
                $statement_string,
                [
                    'p_session_id_in' => $details['session_id']
                ]
            );

            $cursor_name = $fn_result[0]->cur_result_out;
            $cur_result = DB::connection('pgsql')->select("fetch all in {$cursor_name}");

            $list_report = [];
            foreach($cur_result as $cur){
                $list_report[] = [
                    'parameter_id' => $cur->parameter_id,
                    'affiliate_id' => $cur->aff_id,
                    'currency' => $cur->currency,
                    'value' => $cur->value,
                    'affiliate_parameter_value_id' => $cur->aff_parameter_value_id,
                    'parameter_name' => $cur->parameter_name
                ];
            }

            DB::connection('pgsql')->commit();

            if ($fn_result[0]->p_status_out == "1"){

                return [
                    "status" => "OK",
                    "list_report" => $list_report
                ];
            }else{
                $message = "AdministrationModel::listAffiliatesAndParameters >>> SELECT tomboladb.core.list_aff_and_parameters(:p_session_id_in = {$details['session_id']}, cur_result_out, p_status_out)";
                ErrorHelper::writeError($message, $message);
                return [ 'status' => "NOK" ];
            }
        }catch(\PDOException $ex1){
            DB::connection('pgsql')->rollBack();
            $message = implode(" " , [
                "AdministrationModel::listAffiliatesAndParameters(session_id = {$details['session_id']}) <br />",
                "tomboladb.core.list_aff_and_parameters(:p_session_id_in = {$details['session_id']}, cur_result_out, p_status_out) <br />",
                $ex1->getMessage()
            ]);
            ErrorHelper::writeError($message, $message);
            return ["status"=>"NOK"];
        }catch(\Exception $ex2){
            DB::connection('pgsql')->rollBack();
            $message = implode(" " , [
                "AdministrationModel::listAffiliatesAndParameters(session_id = {$details['session_id']}) <br />",
                "tomboladb.core.list_aff_and_parameters(:p_session_id_in = {$details['session_id']}, cur_result_out, p_status_out) <br />",
                $ex2->getMessage()
            ]);
            ErrorHelper::writeError($message, $message);
            return ["status"=>"NOK"];
        }
    }
}
