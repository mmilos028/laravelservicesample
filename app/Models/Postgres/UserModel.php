<?php

namespace App\Models\Postgres;

use Illuminate\Support\Facades\DB;

use App\Helpers\ErrorHelper;

class UserModel
{

    private static $DEBUG = false;

    /**
     * @param $user_id
     * @return array
     */
    public static function userInformation($user_id){
        try{

            if(self::$DEBUG){
                $message = "UserModel::userInformation >>> SELECT * from tomboladb.core.get_subjects_details(:p_subject_id_in = {$user_id}, 'cur_result_out')";
                ErrorHelper::writeInfo($message, $message);
            }

            DB::connection('pgsql')->beginTransaction();
            $statement_string = 'SELECT * from tomboladb.core.get_subjects_details(:p_subject_id_in)';

            $fn_result = DB::connection('pgsql')->select($statement_string,
                array('p_subject_id_in'=>$user_id)
            );
            $cursor_name = $fn_result[0]->cur_result_out;
            $cur_result = DB::connection('pgsql')->select("fetch all in {$cursor_name}");
            $result = get_object_vars($cur_result[0]);
            DB::connection('pgsql')->commit();
            //print_r($result);
            return [
                "status" => "OK",
                "user" => [
                    "user_id" => $result['subject_id'],
                    "username" => $result['username'],
                    "first_name" => $result['first_name'],
                    "last_name" => $result['last_name'],
                    "email" => $result['email'],
                    "registration_date" => $result['registration_date'],
                    "subject_type" => $result['subject_dtype'],
                    "active" => $result['subject_state'],
                    "language" => $result['language'],
                    "parent_id"=> $result['parent_id'],
                    "parent_username" => $result['parent_username'],
                    "address"=> $result['address'],
                    "commercial_address" => $result['commercial_address'],
                    "city"=> $result['city'],
                    "country_code"=> $result['country_id'],
                    "country_name"=> $result['country_name'],
                    "mobile_phone"=> $result['mobile_phone'],
                    "post_code"=> $result['post_code'],
                ]
            ];
        }catch(\PDOException $ex1){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                "UserModel::userInformation(user_id = {$user_id}) <br />",
                "tomboladb.core.get_subjects_details(:p_subject_id_in = {$user_id}, 'cur_result_out') <br />",
                $ex1->getMessage()
            ]);
            ErrorHelper::writeError($message, $message);
            return [
                "status" => "NOK",
                "result" => null
            ];
        }catch(\Exception $ex2){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                "UserModel::userInformation(user_id = {$user_id}) <br />",
                "tomboladb.core.get_subjects_details(:p_subject_id_in = {$user_id}, 'cur_result_out') <br />",
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
     * @param $details
     * @return array
     */
    public static function personalInformation($details){
        try{

            if(self::$DEBUG){
                $message = "UserModel::personalInformation >>> SELECT * from tomboladb.core.get_subjects_details_from_session(:p_session_id_in = {$details['session_id']}, 'cur_result_out', 'cur_currency_out')";
                ErrorHelper::writeInfo($message, $message);
            }

            DB::connection('pgsql')->beginTransaction();
            $statement_string = 'SELECT * from tomboladb.core.get_subjects_details_from_session(:p_session_id_in)';

            $fn_result = DB::connection('pgsql')->select($statement_string,
                array(
                    'p_session_id_in'=>$details['session_id']
                )
            );
            $cursor_name = $fn_result[0]->cur_result_out;
            $cur_result = DB::connection('pgsql')->select("fetch all in {$cursor_name}");
            $cursor_currency_name = $fn_result[0]->cur_currency_out;
            //subject_id, currency fields
            $cur_currency = DB::connection('pgsql')->select("fetch all in {$cursor_currency_name}");
            DB::connection('pgsql')->commit();
            $list_currency = [];
            foreach($cur_currency as $cur){
                $list_currency[$cur->currency] = $cur->currency;
            }

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
                    "commercial_address" => $cur_result[0]->commercial_address,
                    "city"=>$cur_result[0]->city,
                    "country_code"=>$cur_result[0]->country_id,
                    "country_name"=>$cur_result[0]->country_name,
                    "mobile_phone"=>$cur_result[0]->mobile_phone,
                    "post_code"=>$cur_result[0]->post_code,
                    "currency"=>$cur_result[0]->currency,
                    "shift_number"=>$cur_result[0]->shift_no
                ],
                "list_currency" =>
                    $list_currency
            ];
        }catch(\PDOException $ex1){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                "UserModel::personalInformation(backoffice_session_id = {$details['session_id']}) <br />",
                "tomboladb.core.get_subjects_details_from_session(:p_session_id_in = {$details['session_id']}, 'cur_result_out', 'cur_currency_out') <br />",
                $ex1->getMessage()
            ]);
            ErrorHelper::writeError($message, $message);
            return [
                "status" => "NOK",
                "result" => null
            ];
        }catch(\Exception $ex2){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                "UserModel::personalInformation(backoffice_session_id = {$details['session_id']}) <br />",
                "tomboladb.core.get_subjects_details_from_session(:p_session_id_in = {$details['session_id']}, 'cur_result_out', 'cur_currency_out') <br />",
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
    public static function updateUser($user){
        try{

            if(self::$DEBUG){
                $message = "UserModel::updateUser >>> SELECT tomboladb.core.update_subject(:p_subject_id_in = {$user['user_id']},
				:p_username_in = {$user['username']},
				:p_first_name_in = {$user['first_name']}, :p_last_name_in = {$user['last_name']}, :p_currency_in = {$user['currency']},
                :p_email_in = {$user['email']}, :p_edited_by_in = {$user['edited_by']}, :p_player_dtype_in = {$user['subject_type_name']}, :p_subject_state_in = {$user['subject_state']}, :p_language_in = {$user['language']},
                :p_address = {$user['address']}, :p_city = {$user['city']}, :p_country = {$user['country']}, :p_mobile_phone = {$user['mobile_phone']}, :p_post_code = {$user['post_code']}, :p_commercial_address = {$user['commercial_address']})";
                ErrorHelper::writeInfo($message, $message);
            }

            DB::connection('pgsql')->beginTransaction();

            $statement_string = "SELECT tomboladb.core.update_subject(:p_subject_id_in, :p_username_in, :p_first_name_in, :p_last_name_in, :p_currency_in, :p_email_in, :p_edited_by_in, :p_player_dtype_in, :p_subject_state_in, :p_language_in, :p_address, :p_city, :p_country, :p_mobile_phone, :p_post_code, :p_commercial_address)";
            $fn_result = DB::connection('pgsql')->select(
                $statement_string,
                [
                    'p_subject_id_in'=>$user['user_id'],
					'p_username_in' => $user['username'],
                    'p_first_name_in'=>$user['first_name'],
                    'p_last_name_in'=>$user['last_name'],
                    'p_currency_in'=>null,
                    'p_email_in'=>$user['email'],
                    'p_edited_by_in'=>$user['edited_by'],
                    'p_player_dtype_in'=>$user['subject_type_name'],
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
			   return ['status'=>"NOK", "message" => "GENERAL_ERROR"];
            }
        }catch(\PDOException $ex1){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                    "UserModel::updateUser(" . print_r($user, true) . ") <br />",
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
                    "UserModel::updateUser(" . print_r($user, true) . ") <br />",
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

        if(self::$DEBUG){
            $message = "UserModel::changePassword >>> SELECT * from tomboladb.core.change_password(:p_session_id_in = {$details['backoffice_session_id']}, :p_subject_id_in = {$details['user_id']}, :p_password_in = {$details['password']})";
            ErrorHelper::writeInfo($message, $message);
        }

        try{
            DB::connection('pgsql')->beginTransaction();

            $statement_string = 'SELECT * from tomboladb.core.change_password(:p_session_id_in, :p_subject_id_in, :p_password_in)';
            $fn_result = DB::connection('pgsql')->select(
                $statement_string,
                [
                    'p_session_id_in'=>$details['backoffice_session_id'],
                    'p_subject_id_in'=>$details['user_id'],
                    'p_password_in'=>$details['password'],
                ]
            );
            DB::connection('pgsql')->commit();
            if($fn_result[0]->p_status_out == 1){
                return ['status'=>"OK"];
            }else{
                return ['status'=>"NOK"];
            }
        }catch(\PDOException $ex1){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                    "UserModel::changePassword(" . print_r($details, true) . ") <br />",
                    "tomboladb.core.change_password(:p_session_id_in = {$details['backoffice_session_id']}, :p_subject_id_in = {$details['user_id']}, :p_password_in = {$details['password']}) <br />",
                    $ex1->getMessage()
                ]
            );
            ErrorHelper::writeError($message, $message);
            return ["status"=>"NOK"];
        }catch(\Exception $ex2){
            DB::connection('pgsql')->rollBack();
            $message = implode(" ", [
                    "UserModel::changePassword(" . print_r($details, true) . ") <br />",
                    "tomboladb.core.change_password(:p_session_id_in = {$details['backoffice_session_id']}, :p_subject_id_in = {$details['user_id']}, :p_password_in = {$details['password']}) <br />",
                    $ex2->getMessage()
                ]
            );
            ErrorHelper::writeError($message, $message);
            return ["status"=>"NOK"];
        }
    }
}
