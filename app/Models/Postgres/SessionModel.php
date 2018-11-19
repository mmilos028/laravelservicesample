<?php

namespace App\Models\Postgres;

use Illuminate\Support\Facades\DB;
use App\Helpers\ErrorHelper;

class SessionModel
{

    private static $DEBUG = false;

    /**
     * @param $details
     * @return array
     */
    public static function getGameDrawSession($details){

        if(self::$DEBUG){
            $message = "SessionModel::getGameDrawSession >>> SELECT * from tomboladb.tombola.get_game_draw_session(:p_session_id_in = {$details['session_id']}, :p_game_draw_session_id_out)";
            ErrorHelper::writeInfo($message, $message);
        }

        try {
            DB::connection('pgsql')->beginTransaction();

            $statement_string = "SELECT * from tomboladb.tombola.get_game_draw_session(:p_session_id)";
            $fn_result = DB::connection('pgsql')->select(
                $statement_string,
                [
                    'p_session_id' => $details['session_id']
                ]
            );

            DB::connection('pgsql')->commit();

            if ($fn_result[0]->p_game_draw_session_id_out != "-1"){

                return [
                    "status" => "OK",
                    "session_id" => $details['session_id'],
                    "game_draw_session_id" => $fn_result[0]->p_game_draw_session_id_out
                ];
            }else{
                $message = implode(" " , [
                    "SessionModel::getGameDrawSession(session_id = {$details['session_id']}) <br />",
                    "tomboladb.tombola.get_game_draw_session(:p_session_id_in = {$details['session_id']}, :p_game_draw_session_id_out = {$fn_result[0]->p_game_draw_session_id_out}) <br />",
                ]);
                ErrorHelper::writeError($message, $message);

                return [ 'status' => "NOK" ];
            }
        }catch(\PDOException $ex1){
            DB::connection('pgsql')->rollBack();
            $message = implode(" " , [
                "SessionModel::getGameDrawSession(session_id = {$details['session_id']}) <br />",
                "tomboladb.tombola.get_game_draw_session(:p_session_id_in = {$details['session_id']}, :p_game_draw_session_id_out) <br />",
                $ex1->getMessage()
            ]);
            ErrorHelper::writeError($message, $message);
            return ["status"=>"NOK"];
        }catch(\Exception $ex2){
            DB::connection('pgsql')->rollBack();
            $message = implode(" " , [
                "SessionModel::getGameDrawSession(session_id = {$details['session_id']}) <br />",
                "tomboladb.tombola.get_game_draw_session(:p_session_id_in = {$details['session_id']}, :p_game_draw_session_id_out) <br />",
                $ex2->getMessage()
            ]);
            ErrorHelper::writeError($message, $message);
            return ["status"=>"NOK"];
        }
    }
}
