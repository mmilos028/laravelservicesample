<?php

namespace App\Models\Postgres;

use Illuminate\Support\Facades\DB;

use App\Helpers\ErrorHelper;

class PlayerModel
{

    private static $DEBUG = false;


     /**
     * @param array $user
     * @return array
     */
    public static function createUser($user){

        if(self::$DEBUG){
            $message = "PlayerModel::createUser >>> SELECT tomboladb.core.create_subject(:p_username_in = '{$user['username']}', :p_password_in = '{$user['password']}',
            :p_first_name_in = '{$user['first_name']}', :p_last_name_in = '{$user['last_name']}', :p_currency_in = '{$user['currency']}',
            :p_parent_name_in = '{$user['parent_name']}', :p_registered_by_in = '{$user['registered_by']}', :p_subject_dtype_id_in = {$user['subject_type_id']},
            :p_player_dtype_in = '{$user['player_type_name']}', :p_language_in = '{$user['language']}', :p_email_in = '{$user['email']}, :p_city = {$user['city']},
            :p_country = {$user['country']}, :p_mobile_phone = {$user['mobile_phone']}, :p_post_code = {$user['post_code']}, :p_commercial_address = {$user['commercial_address']})'";
            ErrorHelper::writeInfo($message, $message);
        }

        try{
            DB::connection('pgsql')->beginTransaction();
            $statement_string = 'SELECT tomboladb.core.create_subject(:p_username_in, :p_password_in, :p_first_name_in, :p_last_name_in, :p_currency_in, :p_parent_name_in, :p_registered_by_in, :p_subject_dtype_id_in, :p_player_dtype_in, :p_language_in, :p_email_in, :p_address, :p_city, :p_country, :p_mobile_phone, :p_post_code, :p_commercial_address)';
            $fn_result = DB::connection('pgsql')->select(
                $statement_string,
                [
                    'p_username_in'=>$user['username'],
                    'p_password_in'=>$user['password'],
                    'p_first_name_in'=>$user['first_name'],
                    'p_last_name_in'=>$user['last_name'],
                    'p_currency_in'=>$user['currency'],
                    'p_parent_name_in'=>$user['parent_name'],
                    'p_registered_by_in'=>$user['registered_by'],
                    'p_subject_dtype_id_in'=>$user['subject_type_id'],
                    'p_player_dtype_in'=>$user['player_type_name'],
                    'p_language_in'=>$user['language'],
                    'p_email_in'=>$user['email'],
                    'p_address'=>$user['address'],
                    'p_city'=>$user['city'],
                    'p_country'=>$user['country'],
                    'p_mobile_phone'=>$user['mobile_phone'],
                    'p_post_code'=>$user['post_code'],
                    'p_commercial_address' => $user['commercial_address']
                ]
            );
            DB::connection('pgsql')->commit();
			if($fn_result[0]->create_subject >= 1)
			{
				return ['status'=>"OK", 'subject_id'=>$fn_result[0]->create_subject];
			}
            if($fn_result[0]->create_subject == "-1")
			{
                return ['status'=>"NOK", "message" => "GENERAL_ERROR"];
            }
			else if($fn_result[0]->create_subject == "-2")				
			{
				return ['status'=>"NOK", "message" => "EMAIL NOT AVAILABLE"];
			}
			else if($fn_result[0]->create_subject == "-3")
			{
				return ['status'=>"NOK", "message" => "USERNAME NOT AVAILABLE"];		
			}else{
			    $message = implode(" ", [
                        "PlayerModel::createUser(" . print_r($user, true) . ") <br />",
                        "tomboladb.core.create_subject(:p_username_in = '{$user['username']}', :p_password_in = '{$user['password']}',
                        :p_first_name_in = '{$user['first_name']}', :p_last_name_in = '{$user['last_name']}', :p_currency_in = '{$user['currency']}',
                        :p_parent_name_in = '{$user['parent_name']}', :p_registered_by_in = '{$user['registered_by']}', :p_subject_dtype_id_in = {$user['subject_type_id']},
                        :p_player_dtype_in = '{$user['player_type_name']}', :p_language_in = '{$user['language']}', :p_email_in = '{$user['email']}, :p_city = {$user['city']},
                        :p_country = {$user['country']}, :p_mobile_phone = {$user['mobile_phone']}, :p_post_code = {$user['post_code']}, :p_commercial_address = {$user['commercial_address']}, status = {$fn_result[0]->create_subject}) <br />",
                    ]
                );
                ErrorHelper::writeError($message, $message);
			    return ["status"=>"NOK"];
            }
        }catch(\PDOException $ex1){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                    "PlayerModel::createUser(" . print_r($user, true) . ") <br />",
                    "tomboladb.core.create_subject(:p_username_in = '{$user['username']}', :p_password_in = '{$user['password']}',
                    :p_first_name_in = '{$user['first_name']}', :p_last_name_in = '{$user['last_name']}', :p_currency_in = '{$user['currency']}',
                    :p_parent_name_in = '{$user['parent_name']}', :p_registered_by_in = '{$user['registered_by']}', :p_subject_dtype_id_in = {$user['subject_type_id']},
                    :p_player_dtype_in = '{$user['player_type_name']}', :p_language_in = '{$user['language']}', :p_email_in = '{$user['email']}, :p_city = {$user['city']},
                    :p_country = {$user['country']}, :p_mobile_phone = {$user['mobile_phone']}, :p_post_code = {$user['post_code']}, :p_commercial_address = {$user['commercial_address']}) <br />",
                    $ex1->getMessage()
                ]
            );
            ErrorHelper::writeError($message, $message);
            return ["status"=>"NOK"];
        }catch(\Exception $ex2){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                    "PlayerModel::createUser(" . print_r($user, true) . ") <br />",
                    "tomboladb.core.create_subject(:p_username_in = '{$user['username']}', :p_password_in = '{$user['password']}',
                    :p_first_name_in = '{$user['first_name']}', :p_last_name_in = '{$user['last_name']}', :p_currency_in = '{$user['currency']}',
                    :p_parent_name_in = '{$user['parent_name']}', :p_registered_by_in = '{$user['registered_by']}', :p_subject_dtype_id_in = {$user['subject_type_id']},
                    :p_player_dtype_in = '{$user['player_type_name']}', :p_language_in = '{$user['language']}', :p_email_in = '{$user['email']}, :p_city = {$user['city']},
                    :p_country = {$user['country']}, :p_mobile_phone = {$user['mobile_phone']}, :p_post_code = {$user['post_code']}, :p_commercial_address = {$user['commercial_address']}) <br />",
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
    public static function playerInformation($details){
        try{

            if(self::$DEBUG){
                $message = "PlayerModel::terminalInformation >>> SELECT tomboladb.core.get_subjects_details(:p_subject_id_in = {$details['player_id']}, 'cur_result_out')";
                ErrorHelper::writeInfo($message, $message);
            }

            DB::connection('pgsql')->beginTransaction();
            $statement_string = 'SELECT * from tomboladb.core.get_subjects_details(:p_subject_id_in)';

            $fn_result = DB::connection('pgsql')->select($statement_string,
                array('p_subject_id_in'=>$details['player_id'])
            );
            $cursor_name = $fn_result[0]->cur_result_out;

            $cur_result = DB::connection('pgsql')->select("fetch all in {$cursor_name}");

            DB::connection('pgsql')->commit();

            return [
                "status" => "OK",
                "user" => [
                    "user_id" => $cur_result[0]->subject_id,
                    "username" => $cur_result[0]->username,
                    "first_name" => $cur_result[0]->first_name,
                    "last_name" => $cur_result[0]->last_name,
                    "email" => $cur_result[0]->email,
                    "registration_date" => $cur_result[0]->registration_date,
                    "subject_type" => $cur_result[0]->subject_dtype,
                    "active" => $cur_result[0]->subject_state,
                    "language" => $cur_result[0]->language,
                    "parent_id"=> $cur_result[0]->parent_id,
                    "parent_username" => $cur_result[0]->parent_username,
                    "address"=> $cur_result[0]->address,
                    "city"=>$cur_result[0]->city,
                    "country_code"=>$cur_result[0]->country_id,
                    "country_name"=>$cur_result[0]->country_name,
                    "post_code"=>$cur_result[0]->post_code,
                    "mobile_phone"=>$cur_result[0]->mobile_phone
                ]
            ];
        }catch(\PDOException $ex1){
            DB::connection('pgsql')->rollBack();
            $message = implode(" " , [
                "PlayerModel::playerInformation(player_id = {$details['player_id']}) <br />",
                "tomboladb.core.get_subjects_details(:p_subject_id_in = {$details['player_id']}, 'cur_result_out') <br />",
                $ex1->getMessage()
            ]);
            ErrorHelper::writeError($message, $message);
            return [
                "status" => "NOK",
                "result" => null
            ];
        }catch(\Exception $ex2){
            DB::connection('pgsql')->rollBack();
            $message = implode(" " , [
                "PlayerModel::playerInformation(player_id = {$details['player_id']}) <br />",
                "tomboladb.core.get_subjects_details(:p_subject_id_in = {$details['player_id']}, 'cur_result_out') <br />",
                $ex2->getMessage()
            ]);
            ErrorHelper::writeError($message, $message);
            return [
                "status" => "NOK",
                "result" => null
            ];
        }
    }

    /**
     * @param array $user
     * @return array
     */
    public static function updatePlayer($user){
        try{

            if(self::$DEBUG){
                $message = "PlayerModel::updatePlayer >>> SELECT tomboladb.core.update_subject(:p_subject_id_in = {$user['user_id']},
				:p_username_in = {$user['username']},
				:p_first_name_in = {$user['first_name']}, :p_last_name_in = {$user['last_name']},
                :p_currency_in = null, :p_email_in = {$user['email']}, :p_edited_by_in = {$user['edited_by']}, :p_player_dtype_in = {$user['player_type_name']},
                :p_subject_state_in = {$user['active']}, :p_language_in = {$user['language']}, :p_city = {$user['city']}, :p_country = {$user['country']},
                :p_mobile_phone = {$user['mobile_phone']}, :p_post_code = {$user['post_code']}, :p_commercial_address = {$user['commercial_address']})";
                ErrorHelper::writeInfo($message, $message);
            }

            DB::connection('pgsql')->beginTransaction();

            $statement_string = 'SELECT tomboladb.core.update_subject(:p_subject_id_in, :p_username_in, :p_first_name_in, :p_last_name_in, :p_currency_in, :p_email_in, :p_edited_by_in, :p_player_dtype_in, :p_subject_state_in, :p_language_in, :p_address, :p_city, :p_country, :p_mobile_phone, :p_post_code, :p_commercial_address)';
            $fn_result = DB::connection('pgsql')->select(
                $statement_string,
                [
                    'p_subject_id_in'=>$user['user_id'],
					'p_username_in'=>$user['username'],
                    'p_first_name_in'=>$user['first_name'],
                    'p_last_name_in'=>$user['last_name'],
                    'p_currency_in'=>null,
                    'p_email_in'=>$user['email'],
                    'p_edited_by_in'=>$user['edited_by'],
                    'p_player_dtype_in'=>$user['player_type_name'],
                    'p_subject_state_in'=>$user['active'],
                    'p_language_in'=>$user['language'],
                    'p_address'=>$user['address'],
                    'p_city'=>$user['city'],
                    'p_country'=>$user['country'],
                    'p_mobile_phone'=>$user['mobile_phone'],
                    'p_post_code'=>$user['post_code'],
                    'p_commercial_address' => $user['commercial_address']
                ]
            );
            DB::connection('pgsql')->commit();
            if($fn_result[0]->update_subject >= 1)
			{
				return ['status'=>"OK", 'subject_id'=>$fn_result[0]->update_subject];
			}
            if($fn_result[0]->update_subject == "-1")
			{
                return ['status'=>"NOK", "message" => "GENERAL_ERROR"];
            }
			else if($fn_result[0]->update_subject == "-2")				
			{
				return ['status'=>"NOK", "message" => "EMAIL NOT AVAILABLE"];
			}
			else if($fn_result[0]->update_subject == "-3")
			{
				return ['status'=>"NOK", "message" => "USERNAME NOT AVAILABLE"];		
			}else{
                $message = implode(" ", [
                        "PlayerModel::updatePlayer(" . print_r($user, true) . ") <br />",
                        "tomboladb.core.update_subject(:p_subject_id_in = {$user['user_id']},
                        :p_username_in = {$user['username']},
                        :p_first_name_in = {$user['first_name']}, :p_last_name_in = {$user['last_name']},
                        :p_currency_in = null, :p_email_in = {$user['email']}, :p_edited_by_in = {$user['edited_by']}, :p_player_dtype_in = {$user['player_type_name']},
                        :p_subject_state_in = {$user['active']}, :p_language_in = {$user['language']}, :p_city = {$user['city']}, :p_country = {$user['country']},
                        :p_mobile_phone = {$user['mobile_phone']}, :p_post_code = {$user['post_code']}, :p_commercial_address = {$user['commercial_address']}, status = {$fn_result[0]->update_subject}) <br />",
                    ]
                );
                ErrorHelper::writeError($message, $message);
                return ["status"=>"NOK"];
            }
        }catch(\PDOException $ex1){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                    "PlayerModel::updatePlayer(" . print_r($user, true) . ") <br />",
                    "tomboladb.core.update_subject(:p_subject_id_in = {$user['user_id']},
                    :p_username_in = {$user['username']},
                    :p_first_name_in = {$user['first_name']}, :p_last_name_in = {$user['last_name']},
                    :p_currency_in = null, :p_email_in = {$user['email']}, :p_edited_by_in = {$user['edited_by']}, :p_player_dtype_in = {$user['player_type_name']},
                    :p_subject_state_in = {$user['active']}, :p_language_in = {$user['language']}, :p_city = {$user['city']}, :p_country = {$user['country']},
                    :p_mobile_phone = {$user['mobile_phone']}, :p_post_code = {$user['post_code']}, :p_commercial_address = {$user['commercial_address']}) <br />",
                    $ex1->getMessage()
                ]
            );
            ErrorHelper::writeError($message, $message);
            return ["status"=>"NOK"];
        }catch(\Exception $ex2){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                    "PlayerModel::updatePlayer(" . print_r($user, true) . ") <br />",
                    "tomboladb.core.update_subject(:p_subject_id_in = {$user['user_id']},
                    :p_username_in = {$user['username']},
                    :p_first_name_in = {$user['first_name']}, :p_last_name_in = {$user['last_name']},
                    :p_currency_in = null, :p_email_in = {$user['email']}, :p_edited_by_in = {$user['edited_by']}, :p_player_dtype_in = {$user['player_type_name']},
                    :p_subject_state_in = {$user['active']}, :p_language_in = {$user['language']}, :p_city = {$user['city']}, :p_country = {$user['country']},
                    :p_mobile_phone = {$user['mobile_phone']}, :p_post_code = {$user['post_code']}, :p_commercial_address = {$user['commercial_address']}) <br />",
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
    public static function changePassword($details){

        //$hashed_password = Hash::make($details['password']);
        $hashed_password = $details['password'];
        if(self::$DEBUG){
            $message = "PlayerModel::changePassword >>> SELECT tomboladb.core.change_password(:p_session_id_in = {$details['backoffice_session_id']}, :p_subject_id_in = {$details['user_id']}, :p_password_in = {$details['password']})";
            ErrorHelper::writeInfo($message, $message);
        }

        try{
            DB::connection('pgsql')->beginTransaction();

            $statement_string = 'SELECT tomboladb.core.change_password(:p_session_id_in, :p_subject_id_in, :p_password_in)';
            $fn_result = DB::connection('pgsql')->select(
                $statement_string,
                [
                    'p_session_id_in'=>$details['backoffice_session_id'],
                    'p_subject_id_in'=>$details['user_id'],
                    'p_password_in'=>$hashed_password,
                ]
            );
            DB::connection('pgsql')->commit();
            //dd($fn_result[0]->update_subject);
            if($fn_result[0]->change_password == 1){
                return ['status'=>"OK"];
            }else{
                $message = implode(" ", [
                        "PlayerModel::changePassword(" . print_r($details, true) . ") <br />",
                        "tomboladb.core.change_password(:p_session_id_in = {$details['backoffice_session_id']}, :p_subject_id_in = {$details['user_id']}, :p_password_in = {$details['password']}, status = {$fn_result[0]->change_password}) <br />",
                    ]
                );
                ErrorHelper::writeError($message, $message);
                return ['status'=>"NOK"];
            }
        }catch(\PDOException $ex1){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                    "PlayerModel::changePassword(" . print_r($details, true) . ") <br />",
                    "tomboladb.core.change_password(:p_session_id_in = {$details['backoffice_session_id']}, :p_subject_id_in = {$details['user_id']}, :p_password_in = {$details['password']}) <br />",
                    $ex1->getMessage()
                ]
            );
            ErrorHelper::writeError($message, $message);
            return ["status"=>"NOK"];
        }catch(\Exception $ex2){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                    "PlayerModel::changePassword(" . print_r($details, true) . ") <br />",
                    "tomboladb.core.change_password(:p_session_id_in = {$details['backoffice_session_id']}, :p_subject_id_in = {$details['user_id']}, :p_password_in = {$details['password']}) <br />",
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
    private static function setServiceKeyForTerminal($details){
        
        if(self::$DEBUG){
            $message = "PlayerModel::setServiceKeyForTerminal >>> SELECT tomboladb.core.set_service_key_for_terminal(:p_terminal_id = {$details['terminal_id']}, :p_status_out)";
            ErrorHelper::writeInfo($message, $message);
        }

        try{
            DB::connection('pgsql')->beginTransaction();

            $statement_string = 'SELECT * from tomboladb.tombola.set_service_key_for_terminal(:p_terminal_id)';
            $fn_result = DB::connection('pgsql')->select(
                $statement_string,
                [
                    'p_terminal_id'=>$details['terminal_id']
                ]
            );
            DB::connection('pgsql')->commit();
            // dd($fn_result[0]->update_subject);
            if($fn_result[0]->p_status_out == 1){
                return ['status'=>"OK"];
            }else{
                $message = implode(" ", [
                        "PlayerModel::setServiceKeyForTerminal(terminal_id = {$details['terminal_id']}) <br />",
                        "tomboladb.tombola.set_service_key_for_terminal(:p_terminal_id = {$details['terminal_id']}, :p_status_out = {$fn_result[0]->p_status_out}) <br />",
                    ]
                );
                ErrorHelper::writeError($message, $message);
                return ['status'=>"NOK"];
            }
        }catch(\PDOException $ex1){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                    "PlayerModel::setServiceKeyForTerminal(terminal_id = {$details['terminal_id']}) <br />",
                    "tomboladb.tombola.set_service_key_for_terminal(:p_terminal_id = {$details['terminal_id']}) <br />",
                    $ex1->getMessage()
                ]
            );
            ErrorHelper::writeError($message, $message);
            return ["status"=>"NOK"];
        }catch(\Exception $ex2){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                    "PlayerModel::setServiceKeyForTerminal(terminal_id = {$details['terminal_id']}) <br />",
                    "tomboladb.tombola.set_service_key_for_terminal(:p_terminal_id = {$details['terminal_id']}) <br />",
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
    private static function listTerminalMaschineKeyAndCodes($details){
        try{

            if(self::$DEBUG){
                $message = "SELECT * from tomboladb.tombola.get_maschine_key_and_code( :p_session_id_in = {$details['backoffice_session_id']}, 
				:p_terminal_id = {$details['terminal_id']}, :p_maschine_key = -1, 'cur_result_out' )";
                ErrorHelper::writeInfo($message, $message);
            }
			
			$maschine_key = -1;

            DB::connection('pgsql')->beginTransaction();
            $statement_string = "SELECT * from tomboladb.tombola.get_maschine_key_and_code(:p_terminal_id, :p_maschine_key)";
            $fn_result = DB::connection('pgsql')->select(
                $statement_string,
                [
                    "p_terminal_id" => $details['terminal_id'],
					"p_maschine_key" => $maschine_key
                ]
            );

            $cursor_name = $fn_result[0]->cur_result_out;
            $cursor_result = DB::connection('pgsql')->select("fetch all in {$cursor_name};");

            DB::connection('pgsql')->commit();
            return [
                "status" => "OK",
                "list_terminal_maschine_key_and_codes" => $cursor_result
            ];
        }catch(\PDOException $ex1){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                    "PlayerModel::listTerminalMaschineKeyAndCodes(session_id = {$details['session_id']}, terminal_id = {$details['terminal_id']}) <br />",
                    "tomboladb.tombola.get_maschine_key_and_code( :p_session_id_in = {$details['session_id']}, 
				        :p_terminal_id = {$details['terminal_id']}, :p_maschine_key = -1, 'cur_result_out' ) <br />",
                    $ex1->getMessage()
                ]
            );
            ErrorHelper::writeError($message, $message);
            return [
                "status" => "NOK",
                "list_terminal_maschine_key_and_codes" => []
            ];
        }catch(\Exception $ex2){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                    "PlayerModel::listTerminalMaschineKeyAndCodes(session_id = {$details['session_id']}, terminal_id = {$details['terminal_id']}) <br />",
                    "tomboladb.tombola.get_maschine_key_and_code(:p_session_id_in = {$details['session_id']}, 
                        :p_terminal_id = {$details['terminal_id']}, :p_maschine_key = -1, 'cur_result_out' ) <br />",
                    $ex2->getMessage()
                ]
            );
            ErrorHelper::writeError($message, $message);
            return [
                "status" => "NOK",
                "list_terminal_maschine_key_and_codes" => []
            ];
        }
    }
	
	/**
     * @param $details
     * @return array
     */
    private static function checkServiceCode($details){
        try{

            if(self::$DEBUG){
                $message = "PlayerModel::checkServiceCode, session_id = {$details['session_id']}, SELECT * from tomboladb.tombola.check_service_code(:p_service_code = {$details['service_code']}, :p_maschine_key = {$details['maschine_key']}, :p_status_out)";
                ErrorHelper::writeInfo($message, $message);
            }

            DB::connection('pgsql')->beginTransaction();
            $statement_string = "SELECT * from tomboladb.tombola.check_service_code(:p_service_code, :p_maschine_key)";
            $fn_result = DB::connection('pgsql')->select(
                $statement_string,
                [
                    "p_service_code" => $details['service_code'],
					"p_maschine_key" => $details['maschine_key']
                ]
            );

            DB::connection('pgsql')->commit();
									
			return [ 'status' => "OK", 'check_status' => $fn_result[0]->p_status_out ];
			
        }catch(\PDOException $ex1){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                    "PlayerModel::checkServiceCode(session_id = {$details['session_id']}, service_code = {$details['service_code']}, maschine_key={$details['maschine_key']}) <br />",
                    "tomboladb.tombola.check_service_code(:p_service_code = {$details['service_code']}, :p_maschine_key = {$details['maschine_key']}, :p_status_out) <br />",
                    $ex1->getMessage()
                ]
            );
            ErrorHelper::writeError($message, $message);
            return [
                "status" => "NOK",
                
            ];
        }catch(\Exception $ex2){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                    "PlayerModel::checkServiceCode(session_id = {$details['session_id']}, service_code = {$details['service_code']}, maschine_key={$details['maschine_key']}) <br />",
                    "tomboladb.tombola.check_service_code(:p_service_code = {$details['service_code']}, :p_maschine_key = {$details['maschine_key']}, :p_status_out) <br />",
                    $ex2->getMessage()
                ]
            );
            ErrorHelper::writeError($message, $message);
            return [
                "status" => "NOK",
            ];
        }
    }

    /**
     * @param $details
     * @return array
     */
    public static function getCredits($details){
        try{

            if(self::$DEBUG){
                $message = "PlayerModel::getCredits, session_id = {$details['session_id']}, subject_id = {$details['user_id']}, SELECT * from tomboladb.tombola.get_credits(:p_session_id = {$details['session_id']}, :p_subject_id_in = {$details['user_id']})";
                ErrorHelper::writeInfo($message, $message);
            }

            DB::connection('pgsql')->beginTransaction();
            $statement_string = "SELECT * from tomboladb.tombola.get_credits(:p_session_id, :p_subject_id_in)";
            $fn_result = DB::connection('pgsql')->select(
                $statement_string,
                [
                    "p_session_id" => $details['session_id'],
                    "p_subject_id_in" => $details['user_id']
                ]
            );

            DB::connection('pgsql')->commit();

            return [
                'status' => "OK",
                'credits' => $fn_result[0]->p_credits_out,
                'status_out' => $fn_result[0]->p_status_out
            ];

        }catch(\PDOException $ex1){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                    "PlayerModel::getCredits(" . print_r($details, true) .") <br />",
                    "tomboladb.tombola.get_credits(:p_session_id = {$details['session_id']}, :p_subject_id_in = {$details['user_id']}) <br />",
                    $ex1->getMessage()
                ]
            );
            ErrorHelper::writeError($message, $message);
            return [
                "status" => "NOK",
            ];
        }catch(\Exception $ex2){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                    "PlayerModel::getCredits(" . print_r($details, true) .") <br />",
                    "tomboladb.tombola.get_credits(:p_session_id = {$details['session_id']}, :p_subject_id_in = {$details['user_id']}) <br />",
                    $ex2->getMessage()
                ]
            );
            ErrorHelper::writeError($message, $message);
            return [
                "status" => "NOK",
            ];
        }
    }
}
