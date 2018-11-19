<?php

namespace App\Models\Postgres;

use App\Helpers\ErrorHelper;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

/**
 * Class AuthModel
 * @package App\Models\Postgres
 */
class AuthModel
{

    private static $DEBUG = false;

    /**
     * @param array $details
     * @return array
     */
    public static function loginSubject($details){
        //$subject_type_id = config("constants.PLAYER_SUBJECT_TYPE_ID");
        $subject_type_id = $details['subject_type_id'] == "" ? config("constants.PLAYER_SUBJECT_TYPE_ID") : $details['subject_type_id'];
        $service_code = -1;

        if(self::$DEBUG){
            $message = "AuthModel::loginSubject >>> SELECT * from tombola.log_in_subject(:p_username_in = {$details['username']}, :p_password_in = {$details['password']}, 
            :p_subject_type_id = {$subject_type_id}, :p_ip_address = {$details['ip_address']}, :p_login_platform = {$details['device']}, :p_service_code = {$service_code})";
            ErrorHelper::writeInfo($message, $message);
        }

        try{
            DB::connection('pgsql')->beginTransaction();

            $statement_string = "SELECT * from tombola.log_in_subject(:p_username_in, :p_password_in, :p_subject_type_id, :p_ip_address, :p_login_platform, :p_service_code)";

            $result = DB::connection('pgsql')->select($statement_string,
                array(
                    'p_username_in'=>$details['username'],
                    'p_password_in'=>$details['password'],
                    'p_subject_type_id'=>$subject_type_id,
                    'p_ip_address' => $details['ip_address'],
                    'p_login_platform' => $details['device'],
                    'p_service_code' => $service_code
                )
            );

            DB::connection('pgsql')->commit();

            return array(
                "status"=>"OK",
                "session_id"=>$result[0]->p_session_id_out,
                "status_out"=>$result[0]->p_status_out,
            );

        }catch(QueryException $ex1){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                "AuthModel::loginSubject(username = {$details['username']}, password, ip_address = {$details['ip_address']}, device = {$details['device']}) <br />",
                "tombola.log_in_subject(:p_username_in = {$details['username']}, :p_password_in=, :p_subject_type_id={$subject_type_id}, :p_ip_address = {$details['ip_address']}, :p_login_platform={$details['device']}) <br />",
                $ex1->getMessage(),
                ]
            );
            ErrorHelper::writeError($message, $message);
            return array(
                "status" => "NOK",
                "exception_type"=>"QueryException",
                "error_message"=> $message
            );
        }catch(\PDOException $ex2){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                "AuthModel::loginSubject(username = {$details['username']}, password, ip_address = {$details['ip_address']}, device = {$details['device']}) <br />",
                "tombola.log_in_subject(:p_username_in = {$details['username']}, :p_password_in=, :p_subject_type_id={$subject_type_id}, :p_ip_address = {$details['ip_address']}, :p_login_platform={$details['device']}) <br />",
                $ex2->getMessage(),
                ]
            );
            ErrorHelper::writeError($message, $message);
            return array(
                "status"=>"NOK",
                "exception_type"=>"PDOException",
                "error_message"=> $message
            );
        }catch(\Exception $ex3){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                "AuthModel::loginSubject(username = {$details['username']}, password, ip_address = {$details['ip_address']}, device = {$details['device']}) <br />",
                "tombola.log_in_subject(:p_username_in = {$details['username']}, :p_password_in=, :p_subject_type_id={$subject_type_id}, :p_ip_address = {$details['ip_address']}, :p_login_platform={$details['device']}) <br />",
                $ex3->getMessage(),
                ]
            );
            ErrorHelper::writeError($message, $message);
            return array(
                "status"=>"NOK",
                "exception_type"=>"Exception",
                "error_message"=>$message);
        }
    }

    /**
     * @param array $details
     * @return array
     */
    public static function loginCashier($details){
        //$subject_type_id = config("constants.SHIFT_CASHIER_TYPE_ID");
        $subject_type_id = $details['subject_type_id'] == "" ? config("constants.SHIFT_CASHIER_TYPE_ID") : $details['subject_type_id'];

        if(self::$DEBUG){
            $message = "AuthModel::loginCashier >>> SELECT * from tombola.log_in_subject(:p_username_in = {$details['username']}, :p_password_in = {$details['password']}, 
            :p_subject_type_id = {$subject_type_id}, :p_ip_address = {$details['ip_address']}, :p_login_platform = {$details['device']}, :p_service_code = {$details['service_code']})";
            ErrorHelper::writeInfo($message, $message);
        }

        try{
            DB::connection('pgsql')->beginTransaction();

            $statement_string = "SELECT * from tombola.log_in_subject(:p_username_in, :p_password_in, :p_subject_type_id, :p_ip_address, :p_login_platform, :p_service_code)";

            $result = DB::connection('pgsql')->select($statement_string,
                array(
                    'p_username_in'=>$details['username'],
                    'p_password_in'=>$details['password'],
                    'p_subject_type_id'=>$subject_type_id,
                    'p_ip_address' => $details['ip_address'],
                    'p_login_platform' => $details['device'],
                    'p_service_code' => $details['service_code']
                )
            );

            DB::connection('pgsql')->commit();

            return array(
                "status"=>"OK",
                "session_id"=>$result[0]->p_session_id_out,
                "status_out"=>$result[0]->p_status_out,
            );

        }catch(QueryException $ex1){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                "AuthModel::loginCashier(username = {$details['username']}, password, ip_address = {$details['ip_address']}, device = {$details['device']}, service_code = {$details['service_code']}) <br />",
                "tombola.log_in_subject(:p_username_in = {$details['username']}, :p_password_in=, :p_subject_type_id={$subject_type_id}, :p_ip_address = {$details['ip_address']}, :p_login_platform={$details['device']}, :p_service_code={$details['service_code']}) <br />",
                $ex1->getMessage(),
                ]
            );
            ErrorHelper::writeError($message, $message);
            return array(
                "status" => "NOK",
                "exception_type"=>"QueryException",
                "error_message"=> $message
            );
        }catch(\PDOException $ex2){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                "AuthModel::loginCashier(username = {$details['username']}, password, ip_address = {$details['ip_address']}, device = {$details['device']}, service_code = {$details['service_code']}) <br />",
                "tombola.log_in_subject(:p_username_in = {$details['username']}, :p_password_in=, :p_subject_type_id={$subject_type_id}, :p_ip_address = {$details['ip_address']}, :p_login_platform={$details['device']}, :p_service_code={$details['service_code']}) <br />",
                $ex2->getMessage(),
                ]
            );
            ErrorHelper::writeError($message, $message);
            return array(
                "status"=>"NOK",
                "exception_type"=>"PDOException",
                "error_message"=> $message
            );
        }catch(\Exception $ex3){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                "AuthModel::loginCashier(username = {$details['username']}, password, ip_address = {$details['ip_address']}, device = {$details['device']}, service_code = {$details['service_code']}) <br />",
                "tombola.log_in_subject(:p_username_in = {$details['username']}, :p_password_in=, :p_subject_type_id={$subject_type_id}, :p_ip_address = {$details['ip_address']}, :p_login_platform={$details['device']}, :p_service_code={$details['service_code']}) <br />",
                $ex3->getMessage(),
                ]
            );
            ErrorHelper::writeError($message, $message);
            return array(
                "status"=>"NOK",
                "exception_type"=>"Exception",
                "error_message"=>$message);
        }
    }

    /**
     * @param $details
     * @return array
     */
    public static function logoutSubject($details){

        if(self::$DEBUG){
            $message = "AuthModel::logoutSubject >>> SELECT * from tombola.log_out_subject(:p_session_id_in = {$details['session_id']})";
            ErrorHelper::writeInfo($message, $message);
        }

        try{
            DB::connection('pgsql')->beginTransaction();
            $statement_string = "SELECT * from tombola.log_out_subject(:p_session_id)";
            $result = DB::connection('pgsql')->select($statement_string, array('p_session_id'=>$details['session_id']));
            DB::connection('pgsql')->commit();
            return array(
                "status"=>"OK",
                "status_out"=>$result[0]->p_status_out,
            );
        }catch(QueryException $ex1){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                "AuthModel::logoutSubject(session_id = {$details['session_id']}) <br />",
                "tombola.log_out_subject(:p_session_id = {$details['session_id']}) <br />",
                $ex1->getMessage()
            ]);
            ErrorHelper::writeError($message, $message);
            return array(
                "status" => "NOK",
                "exception_type" => "QueryException",
                "error_message"=> $message
            );
        }catch(\PDOException $ex2){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                "AuthModel::logoutSubject(session_id = {$details['session_id']}) <br />",
                "tombola.log_out_subject(:p_session_id = {$details['session_id']}) <br />",
                $ex2->getMessage()
            ]);
            ErrorHelper::writeError($message, $message);
            return array(
                "status"=>"NOK",
                "exception_type" => "PDOException",
                "error_message"=>$message
            );
        }catch(\Exception $ex3){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                "AuthModel::logoutSubject(session_id = {$details['session_id']}) <br />",
                "tombola.log_out_subject(:p_session_id = {$details['session_id']}) <br />",
                $ex3->getMessage()
            ]);
            ErrorHelper::writeError($message, $message);
            return array(
                "status"=>"NOK",
                "exception_type" => "Exception",
                "error_message"=>$message
            );
        }
    }

}
