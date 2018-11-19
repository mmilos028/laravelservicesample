<?php

namespace App\Models\Postgres;

use Illuminate\Support\Facades\DB;

use App\Helpers\ErrorHelper;

class IntegrationModel
{

    private static $DEBUG = false;

     /**
     * @param array $details
     * @return array
     */
    public static function integratePlayerHierarchy($details){

        if(self::$DEBUG){
            $message = "IntegrationModel::integratePlayerHierarchy >>> SELECT * from tomboladb.core.integrate_player_hierarchy(
            :p_username_in = {$details['username']}, 
            :p_subject_path = {$details['subject_path']}, :p_currency_in = {$details['currency']}, :p_mac_address = {$details['mac_address']}, 
            :p_player_type = {$details['player_type']}, :p_ip_address = {$details['ip_address']}, :p_login_platform = {$details['login_platform']}, :p_credits = {$details['credits']})";
            ErrorHelper::writeInfo($message, $message);
        }

        try{
            DB::connection('pgsql')->beginTransaction();
            $statement_string = 'SELECT * from tomboladb.core.integrate_player_hierarchy(:p_username_in, :p_subject_path, :p_currency_in, :p_mac_address, :p_player_type, :p_ip_address, :p_login_platform, :p_credits)';
            $fn_result = DB::connection('pgsql')->select(
                $statement_string,
                [
                    'p_username_in' => $details['username'],
                    'p_subject_path' => $details['subject_path'],
                    'p_currency_in' => $details['currency'],
                    'p_mac_address' => $details['mac_address'],
                    'p_player_type' => $details['player_type'],
                    'p_ip_address' => $details['ip_address'],
                    'p_login_platform' => $details['login_platform'],
                    'p_credits' => $details['credits']
                ]
            );
            DB::connection('pgsql')->commit();

            $session_id_out = $fn_result[0]->p_session_id_out;
            $status_out = $fn_result[0]->p_status_out;

            return [
                'status'=>'OK',
                'session_id_out' => $session_id_out,
                'status_out' => $status_out,
            ];
        }catch(\PDOException $ex1){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                "IntegrationModel::integratePlayerHierarchy(" . print_r($details, true) . ") <br />",
                "IntegrationModel::integratePlayerHierarchy >>> SELECT * from tomboladb.core.integrate_player_hierarchy(
                :p_username_in = {$details['username']}, 
                :p_subject_path = {$details['subject_path']}, :p_currency_in = {$details['currency']}, :p_mac_address = {$details['mac_address']}, 
                :p_player_type = {$details['player_type']}, :p_ip_address = {$details['ip_address']}, :p_login_platform = {$details['login_platform']}, :p_credits = {$details['credits']}) <br />",
                $ex1->getMessage()
            ]
            );
            ErrorHelper::writeError($message, $message);
            return ["status"=>"NOK"];
        }catch(\Exception $ex2){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                "IntegrationModel::integratePlayerHierarchy(" . print_r($details, true) . ") <br />",
                "IntegrationModel::integratePlayerHierarchy >>> SELECT * from tomboladb.core.integrate_player_hierarchy(
                :p_username_in = {$details['username']}, 
                :p_subject_path = {$details['subject_path']}, :p_currency_in = {$details['currency']}, :p_mac_address = {$details['mac_address']}, 
                :p_player_type = {$details['player_type']}, :p_ip_address = {$details['ip_address']}, :p_login_platform = {$details['login_platform']}, :p_credits = {$details['credits']}) <br />",
                $ex2->getMessage()
            ]
            );
            ErrorHelper::writeError($message, $message);
            return ["status"=>"NOK"];
        }
    }

    /**
     * @param array $details
     * @return array
     */
    public static function setIntegrationCredits($details){

        if(self::$DEBUG){
            $message = "IntegrationModel::setIntegrationCredits >>> SELECT * from tomboladb.tombola.set_credits(
            :p_username = {$details['username']}, :p_credits = {$details['credits']})";
            ErrorHelper::writeInfo($message, $message);
        }

        try{
            DB::connection('pgsql')->beginTransaction();
            $statement_string = 'SELECT * from tomboladb.tombola.set_credits(:p_username, :p_credits)';
            $fn_result = DB::connection('pgsql')->select(
                $statement_string,
                [
                    'p_username'=>$details['username'],
                    'p_credits'=>$details['credits'],
                ]
            );
            DB::connection('pgsql')->commit();

            $status_out = $fn_result[0]->p_status_out;

            return ['status'=>"OK", "status_out" => $status_out];
        }catch(\PDOException $ex1){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                "IntegrationModel::setIntegrationCredits(" . print_r($details, true) . ") <br />",
                "IntegrationModel::setIntegrationCredits >>> SELECT * from tomboladb.tombola.set_credits(
                :p_username = {$details['username']}, :p_credits = {$details['credits']}, :p_status_out) <br />",
                $ex1->getMessage()
            ]
            );
            ErrorHelper::writeError($message, $message);
            return ["status"=>"NOK"];
        }catch(\Exception $ex2){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                "IntegrationModel::setIntegrationCredits(" . print_r($details, true) . ") <br />",
                "IntegrationModel::setIntegrationCredits >>> SELECT * from tomboladb.tombola.set_credits(
                :p_username = {$details['username']}, :p_credits = {$details['credits']}, :p_status_out) <br />",
                $ex2->getMessage()
            ]
            );
            ErrorHelper::writeError($message, $message);
            return ["status"=>"NOK"];
        }
    }

    /**
     * @param array $details
     * @return array
     */
    public static function getPendingWinsForIntegration($details){

        if(self::$DEBUG){
            $message = "IntegrationModel::getPendingWinsForIntegration >>> SELECT * from core.get_pending_wins(
            :p_player_id = {$details['player_id']}, :p_sum_win)";
            ErrorHelper::writeInfo($message, $message);
        }

        try{
            DB::connection('pgsql')->beginTransaction();
            $statement_string = 'SELECT * from core.get_pending_wins(:p_player_id)';
            $fn_result = DB::connection('pgsql')->select(
                $statement_string,
                [
                    'p_player_id'=>$details['player_id'],
                ]
            );
            DB::connection('pgsql')->commit();

            $sum_win = $fn_result[0]->p_sum_win;

            return ['status'=>"OK", "sum_win" => $sum_win];
        }catch(\PDOException $ex1){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                "IntegrationModel::getPendingWinsForIntegration(" . print_r($details, true) . ") <br />",
                "IntegrationModel::getPendingWinsForIntegration >>> SELECT * from core.get_pending_wins(
                :p_player_id = {$details['player_id']}, :p_sum_win) <br />",
                $ex1->getMessage()
            ]
            );
            ErrorHelper::writeError($message, $message);
            return ["status"=>"NOK"];
        }catch(\Exception $ex2){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                "IntegrationModel::getPendingWinsForIntegration(" . print_r($details, true) . ") <br />",
                "IntegrationModel::getPendingWinsForIntegration >>> SELECT * from core.get_pending_wins(
                :p_player_id = {$details['player_id']}, :p_sum_win) <br />",
                $ex2->getMessage()
            ]
            );
            ErrorHelper::writeError($message, $message);
            return ["status"=>"NOK"];
        }
    }
}
