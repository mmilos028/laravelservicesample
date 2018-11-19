<?php

namespace App\Http\Controllers\Rest\Json;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\AuthService;
use App\Services\TicketService;
use App\Services\UserService;
use App\Services\PlayerService;
use App\Services\TerminalService;
use App\Services\PlayerReportService;
use App\Services\AdministrationService;
use App\Services\ShiftCashierService;
use App\Services\SessionService;
use App\Services\IntegrationService;

use App\Helpers\ErrorConstants;

class IndexController extends Controller
{
    /**
     *
     */
    public function __construct()
    {
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function methodNotAllowed(Request $request)
    {
        if(env("WEB_SERVICE_SHOW_DOCUMENTATION")) {
            return view(
                'index'
            );
        }else {
            $error = ErrorConstants::getErrorMessage(ErrorConstants::$METHOD_NOT_ALLOWED);
            return response()->json([
                "status" => "NOK",
                "error_message" => $error["message_text"],
                "error_code" => $error["message_no"],
                "error_description" =>$error['message_description']
            ]);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request){

        try {
            $json_message = $request->getContent();

            $json_object = json_decode($json_message);
            if(json_last_error() != JSON_ERROR_NONE){
                $error = ErrorConstants::getErrorMessage(ErrorConstants::$INVALID_REQUEST);
                return response()->json([
                    "status" => "NOK",
                    "error_message" => $error["message_text"],
                    "error_code" => $error["message_no"],
                    "error_description" => $error["message_description"],
                    "request"=> $json_message,
                    "json_error_message" => json_last_error()
                ]);
            }
            if(!isset($json_object->operation)){
                $error = ErrorConstants::getErrorMessage(ErrorConstants::$INVALID_REQUEST);
                return response()->json([
                    "status" => "NOK",
                    "error_message" => $error["message_text"],
                    "error_code" => $error["message_no"],
                    "error_description" => $error["message_description"],
                    "request"=> $json_message
                ]);
            }

            switch(strtolower($json_object->operation)){
                //AUTH SERVICE
                case 'test':
                    return
                        response()->json(
                            array(
                                "status" => 'NOK',
                                "operation" => "test"
                            )
                        );
                    break;
                case 'login':
                    return
                        response()->json(
                            AuthService::loginSubject($json_object)
                        );
                    break;
                case 'login-cashier':
                    return
                        response()->json(
                            AuthService::loginCashier($json_object)
                        );
                    break;
                case 'logout':
                    return
                        response()->json(
                            AuthService::logoutSubject($json_object)
                        );
                    break;
                //USER SERVICE
                case 'change-password':
                    return
                        response()->json(
                            UserService::changePassword($json_object)
                        );
                case 'personal-information':
                    return
                        response()->json(
                            UserService::personalInformation($json_object)
                        );
                case 'update-personal-information':
                    return
                        response()->json(
                            UserService::updatePersonalInformation($json_object)
                        );
                /*case 'player-information':
                    return
                        response()->json(
                            PlayerService::playerInformation($json_object)
                        );
                case 'player-change-password':
                    return
                        repsonse()->json(
                          PlayerService::changePassword($json_object)
                        );*/
                //PLAYER SERVICE
                case 'get-credits':
                    return
                        response()->json(
                            PlayerService::getCredits($json_object)
                        );
                case 'create-new-player':
                    return
                        response()->json(
                            PlayerService::createNewPlayer($json_object)
                        );
                //TERMINAL SERVICE
                case 'get-self-service-terminal-name':
                    return
                        response()->json(
                            TerminalService::getSelfServiceTerminalName($json_object)
                        );
                //PLAYER REPORT SERVICE
                case 'list-player-tickets':
                    return
                        response()->json(
                            PlayerReportService::listPlayerTickets($json_object)
                        );
                case 'list-player-money-transactions':
                    return
                        response()->json(
                            PlayerReportService::listMoneyTransactions($json_object)
                        );
                case 'list-player-login-history':
                    return
                        response()->json(
                            PlayerReportService::listPlayerLoginHistory($json_object)
                        );
                case 'list-player-history':
                    return
                        response()->json(
                            PlayerReportService::listPlayerHistory($json_object)
                        );
                //TICKET SERVICE
                case 'save-ticket-information':
                    return
                        response()->json(
                            TicketService::saveTicketInformation($json_object)
                        );
                case 'save-ticket-shift':
                    return
                        response()->json(
                            TicketService::saveTicketShift($json_object)
                        );
                case 'save-temporary-ticket-information':
                    return
                        response()->json(
                            TicketService::saveTemporaryTicketInformation($json_object)
                        );
                case 'get-ticket-details-from-barcode':
                    return
                        response()->json(
                            TicketService::getTicketDetailsFromBarcode($json_object)
                        );
                case 'get-last-ticket-for-user':
                    return
                        response()->json(
                            TicketService::getLastTicketForUser($json_object)
                        );
                case 'list-previous-draws':
                    return
                        response()->json(
                            TicketService::getListPreviousDraws($json_object)
                        );
                case 'cancel-ticket':
                    return
                        response()->json(
                            TicketService::cancelTicket($json_object)
                        );
                case 'update-status-ticket-win':
                    return
                        response()->json(
                            TicketService::updateStatusTicketWin($json_object)
                        );
                case 'print-ticket':
                    return
                        response()->json(
                            TicketService::printTicket($json_object)
                        );
                //ADMINISTRATION SERVICE
                case 'list-affiliates-and-parameters':
                    return
                        response()->json(
                            AdministrationService::listAffiliatesAndParameters($json_object)
                        );
                //SHIFT-CASHIER SERVICE
                case 'list-collector-shift-report':
                    return
                        response()->json(
                            ShiftCashierService::listCollectorShiftReport($json_object)
                        );
                case 'list-cashier-shift-report':
                    return
                        response()->json(
                            ShiftCashierService::listCashierShiftReport($json_object)
                        );
                case 'collect-from-cashier':
                    return
                        response()->json(
                            ShiftCashierService::collectFromCashier($json_object)
                        );
                //SESSION SERVICE
                case 'get-game-draw-session-id':
                    return
                        response()->json(
                            SessionService::getGameDrawSession($json_object)
                        );
                //INTEGRATION SERVICE
                case 'integrate-player-hierarchy':
                    return
                        response()->json(
                            IntegrationService::integratePlayerHierarchy($json_object)
                        );
                case 'post-integration-transaction':
                    return
                        response()->json(
                            IntegrationService::postIntegrationTransaction($json_object)
                        );
                case 'set-integration-credits':
                    return
                        response()->json(
                            IntegrationService::setIntegrationCredits($json_object)
                        );
                case 'get-pending-wins-for-integration':
                    return
                        response()->json(
                            IntegrationService::getPendingWinsForIntegration($json_object)
                        );
                default:
                    $error = ErrorConstants::getErrorMessage(ErrorConstants::$INVALID_OPERATION);
                    return
                        response()->json([
                            "status" => "NOK",
                            "error_message" => $error["message_text"],
                            "error_code" => $error["message_no"],
                            "error_description" => $error["message_description"],
                        ]);
            }

        }catch(\RuntimeException $ex1){
            $error = ErrorConstants::getErrorMessage(ErrorConstants::$GENERAL_ERROR);
            return response()->json([
                "status" => "NOK",
                "exception_message"=> $ex1->getMessage(),
                "error_message" => $error["message_text"],
                "error_code" => $error["message_no"],
                "error_description" => $error["message_description"],
            ]);
        }catch(\Exception $ex2){
            $error = ErrorConstants::getErrorMessage(ErrorConstants::$GENERAL_ERROR);
            return response()->json([
                "status" => "NOK",
                "exception_message"=> $ex2->getMessage(),
                "error_message" => $error["message_text"],
                "error_code" => $error["message_no"],
                "error_description" => $error["message_description"],
            ]);
        }
    }
}
