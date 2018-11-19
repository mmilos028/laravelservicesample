<?php

namespace App\Http\Controllers\Rest\Xml;

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
            return response()->xml([
                "status" => "NOK",
                "error_message" => $error["message_text"],
                "error_code" => $error["message_no"],
                "error_description" =>$error['message_description']
            ]);
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request){
        try {
            $xml_message = $request->getContent();

            $xml_object = simplexml_load_string($xml_message);
            if(!isset($xml_object->operation)){
                $error = ErrorConstants::getErrorMessage(ErrorConstants::$INVALID_REQUEST);
                return response()->xml([
                    "status" => "NOK",
                    "error_message" => $error["message_text"],
                    "error_code" => $error["message_no"],
                    "error_description" => $error["message_description"],
                    "request"=> $xml_message
                ]);
            }

            switch(strtolower($xml_object->operation)){
                //AUTH SERVICE
                case 'test':
                    return
                        response()->xml(
                            array(
                                "status" => 'NOK',
                                "operation" => "test"
                            )
                        );
                    break;
                case 'login':
                    return
                        response()->xml(
                            AuthService::loginSubject($xml_object)
                        );
                case 'login-cashier':
                    return
                        response()->xml(
                            AuthService::loginCashier($xml_object)
                        );
                    break;
                case 'logout':
                    return
                        response()->xml(
                            AuthService::logoutSubject($xml_object)
                        );
                //USER SERVICE
                case 'change-password':
                    return
                        response()->xml(
                            UserService::changePassword($xml_object)
                        );
                case 'personal-information':
                    return
                        response()->xml(
                            UserService::personalInformation($xml_object)
                        );
                case 'update-personal-information':
                    return
                        response()->xml(
                            UserService::updatePersonalInformation($xml_object)
                        );
                /*case 'player-information':
                    return
                        response()->xml(
                            PlayerService::playerInformation($json_object)
                        );
                case 'player-change-password':
                    return
                        repsonse()->xml(
                          PlayerService::changePassword($json_object)
                        );*/
                //PLAYER SERVICE
                case 'get-credits':
                    return
                        response()->xml(
                            PlayerService::getCredits($xml_object)
                        );
                case 'create-new-player':
                    return
                        response()->xml(
                            PlayerService::createNewPlayer($xml_object)
                        );
                //TERMINAL SERVICE
                case 'get-self-service-terminal-name':
                    return
                        response()->xml(
                            TerminalService::getSelfServiceTerminalName($xml_object)
                        );
                //PLAYER REPORT SERVICE
                case 'list-player-tickets':
                    return
                        response()->xml(
                            PlayerReportService::listPlayerTickets($xml_object)
                        );
                case 'list-player-money-transactions':
                    return
                        response()->xml(
                            PlayerReportService::listMoneyTransactions($xml_object)
                        );
                case 'list-player-login-history':
                    return
                        response()->xml(
                            PlayerReportService::listPlayerLoginHistory($xml_object)
                        );
                case 'list-player-history':
                    return
                        response()->xml(
                            PlayerReportService::listPlayerHistory($xml_object)
                        );
                //TICKET SERVICE
                case 'save-ticket-information':
                    return
                        response()->xml(
                            TicketService::saveTicketInformation($xml_object)
                        );
                case 'save-ticket-shift':
                    return
                        response()->xml(
                            TicketService::saveTicketShift($xml_object)
                        );
                case 'save-temporary-ticket-information':
                    return
                        response()->xml(
                            TicketService::saveTemporaryTicketInformation($xml_object)
                        );
                case 'get-ticket-details-from-barcode':
                    return
                        response()->xml(
                            TicketService::getTicketDetailsFromBarcode($xml_object)
                        );
                case 'get-last-ticket-for-user':
                    return
                        response()->xml(
                            TicketService::getLastTicketForUser($xml_object)
                        );
                case 'list-previous-draws':
                    return
                        response()->xml(
                            TicketService::getListPreviousDraws($xml_object)
                        );
                case 'cancel-ticket':
                    return
                        response()->xml(
                            TicketService::cancelTicket($xml_object)
                        );
                case 'update-status-ticket-win':
                    return
                        response()->xml(
                            TicketService::updateStatusTicketWin($xml_object)
                        );
                case 'print-ticket':
                    return
                        response()->xml(
                            TicketService::printTicket($xml_object)
                        );
                //ADMINISTRATION SERVICE
                case 'list-affiliates-and-parameters':
                    return
                        response()->xml(
                            AdministrationService::listAffiliatesAndParameters($xml_object)
                        );
                //SHIFT-CASHIER SERVICE
                case 'list-collector-shift-report':
                    return
                        response()->xml(
                            ShiftCashierService::listCollectorShiftReport($xml_object)
                        );
                case 'list-cashier-shift-report':
                    return
                        response()->xml(
                            ShiftCashierService::listCashierShiftReport($xml_object)
                        );
                case 'collect-from-cashier':
                    return
                        response()->xml(
                            ShiftCashierService::collectFromCashier($xml_object)
                        );
                //SESSION SERVICE
                case 'get-game-draw-session-id':
                    return
                        response()->xml(
                            SessionService::getGameDrawSession($xml_object)
                        );
                //INTEGRATION SERVICE
                case 'integrate-player-hierarchy':
                    return
                        response()->xml(
                            IntegrationService::integratePlayerHierarchy($xml_object)
                        );
                case 'post-integration-transaction':
                    return
                        response()->xml(
                            IntegrationService::postIntegrationTransaction($xml_object)
                        );
                case 'set-integration-credits':
                    return
                        response()->xml(
                            IntegrationService::setIntegrationCredits($xml_object)
                        );
                case 'get-pending-wins-for-integration':
                    return
                        response()->xml(
                            IntegrationService::getPendingWinsForIntegration($xml_object)
                        );
                default:
                    $error = ErrorConstants::getErrorMessage(ErrorConstants::$INVALID_OPERATION);
                    return response()->xml([
                        "status" => "NOK",
                        "error_message" => $error["message_text"],
                        "error_code" => $error["message_no"],
                        "error_description" => $error["message_description"],
                    ]);
            }

        }catch(\RuntimeException $ex1){
            $error = ErrorConstants::getErrorMessage(ErrorConstants::$GENERAL_ERROR);
            return response()->xml([
                "status" => "NOK",
                "exception_message"=> $ex1->getMessage(),
                "error_message" => $error["message_text"],
                "error_code" => $error["message_no"],
                "error_description" => $error["message_description"],
            ]);
        }catch(\Exception $ex2){
            $error = ErrorConstants::getErrorMessage(ErrorConstants::$GENERAL_ERROR);
            return response()->xml([
                "status" => "NOK",
                "exception_message"=> $ex2->getMessage(),
                "error_message" => $error["message_text"],
                "error_code" => $error["message_no"],
                "error_description" => $error["message_description"],
            ]);
        }
    }
}
