<?php

namespace App\Models\Postgres;

use Illuminate\Support\Facades\DB;

use App\Helpers\ErrorHelper;

class TerminalModel
{

    private static $DEBUG = false;

    /**
     * @param array $details
     * @return array
     */
    public static function getSelfServiceTerminalName($details){
        if(self::$DEBUG){
            $message = "TerminalModel::getSelfServiceTerminalName >>> SELECT * from tomboladb.tombola.get_selfservice_terminal_name(:p_mac_address = {$details['mac_address']})";
            ErrorHelper::writeInfo($message, $message);
        }

        try{
            DB::connection('pgsql')->beginTransaction();

            $statement_string = 'SELECT * from tomboladb.tombola.get_selfservice_terminal_name(:p_mac_address)';
            $fn_result = DB::connection('pgsql')->select(
                $statement_string,
                [
                    'p_mac_address'=>$details['mac_address']
                ]
            );
            DB::connection('pgsql')->commit();
            $terminal_name_out = $fn_result[0]->p_terminal_name;
            if(strlen($terminal_name_out) > 0){
                return ['status'=>"OK", "terminal_name"=>$terminal_name_out];
            }else{
                $message = implode(" ", [
                        "TerminalModel::getSelfServiceTerminalName(" . print_r($details, true) . ") <br />",
                        "tomboladb.tombola.get_selfservice_terminal_name(:p_mac_address = {$details['mac_address']}) <br />",
                    ]
                );
                ErrorHelper::writeError($message, $message);

                return ['status'=>"NOK"];
            }
        }catch(\PDOException $ex1){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                    "TerminalModel::getSelfServiceTerminalName(" . print_r($details, true) . ") <br />",
                    "tomboladb.tombola.get_selfservice_terminal_name(:p_mac_address = {$details['mac_address']}) <br />",
                    $ex1->getMessage()
                ]
            );
            ErrorHelper::writeError($message, $message);
            return ["status"=>"NOK"];
        }catch(\Exception $ex2){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                    "TerminalModel::getSelfServiceTerminalName(" . print_r($details, true) . ") <br />",
                    "tomboladb.tombola.get_selfservice_terminal_name(:p_mac_address = {$details['mac_address']}) <br />",
                    $ex2->getMessage()
                ]
            );
            ErrorHelper::writeError($message, $message);
            return ["status"=>"NOK"];
        }
    }
}
