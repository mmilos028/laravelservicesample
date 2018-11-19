<?php
namespace App\Helpers;

class ErrorConstants
{
    public static $GENERAL_ERROR = 0;
    public static $EMAIL_NOT_AVAILABLE = 1;
    public static $USERNAME_NOT_AVAILABLE = 2;
    public static $METHOD_NOT_ALLOWED = 3;
    public static $INVALID_REQUEST = 4;
    public static $INVALID_OPERATION = 5;
    public static $MISSING_PARAMETERS = 6;
    public static $MAXIMUM_TEMPORARY_TICKET_NUMBER = 7;
    public static $DRAW_MODEL_IS_INACTIVE = 8;
    public static $NEXT_DRAW_IS_NOT_DEFINED = 9;
    public static $UNHANDLED_EXCEPTION = 10;
    public static $PAYOUT_TIME_EXPIRED = 11;

    private static $messages = array(
        array(
            "message_no" => 0,
            "message_text" => "GENERAL_ERROR",
            "message_description" => "Unknown error occurred"
        ),
        array(
            "message_no" => 1,
            "message_text" => "EMAIL_NOT_AVAILABLE",
            "message_description" => "Email is not available, already taken in our database"
        ),
        array(
            "message_no" => 2,
            "message_text" => "USERNAME_NOT_AVAILABLE",
            "message_description" => "Username is not available, already taken in our database"
        ),
        array(
            "message_no" => 3,
            "message_text" => "Method not allowed",
            "message_description" => "Method is not allowed, not allowed service operation"
        ),
        array(
            "message_no" => 4,
            "message_text" => "Invalid request",
            "message_description" => "Invalid request, not a valid JSON message, missing operation field in JSON message"
        ),
        array(
            "message_no" => 5,
            "message_text" => "Invalid operation",
            "message_description" => "Invalid operation, operation field value not recognized in web service"
        ),
        array(
            "message_no" => 6,
            "message_text" => "MISSING_PARAMETERS",
            "message_description" => "One or more parameters are missing"
        ),
        array(
            "message_no" => 7,
            "message_text" => "MAXIMUM_TEMPORARY_TICKET_NUMBER",
            "message_description" => "Can not generate temp ticket. Ticket number has reached allowed maximum."
        ),
        array(
            "message_no" => 8,
            "message_text" => "DRAW_MODEL_IS_INACTIVE",
            "message_description" => "Draw model is inactive"
        ),
        array(
            "message_no" => 9,
            "message_text" => "NEXT_DRAW_IS_NOT_DEFINED",
            "message_description" => "Next draw is not defined"
        ),
        array(
            "message_no" => 10,
            "message_text" => "UNHANDLED_EXCEPTION",
            "message_description" => "Unhandled exception. Unknown error occurred in database"
        ),
        array(
            "message_no" => 11,
            "message_text" => "PAYOUT_TIME_EXPIRED",
            "message_description" => "Payout time expired"
        ),
    );

    public static function getErrorMessages(){
        return self::$messages;
    }

    public static function getErrorMessage($index){
        return self::$messages[$index];
    }


}
