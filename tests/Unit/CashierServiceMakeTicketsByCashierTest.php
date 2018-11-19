<?php

namespace Tests\Unit;

use Tests\TestCase;
use GuzzleHttp\Client;

class CashierServiceMakeTicketsByCashierTest extends TestCase
{
    public static $shared_data = array();

    private function getRandomCashierTerminalAccount(){
        $randomInt = random_int(1, 1);

        $username = getenv('CASHIER_TEST_USERNAME');
        $password = getenv('CASHIER_TEST_PASSWORD');
        $cashier_pincode = getenv('CASHIER_SERVICE_CODE');

        /*switch($randomInt){
            case 1:
                $username = getenv('CASHIER_TEST_USERNAME');
                $password = getenv('CASHIER_TEST_PASSWORD');
                $cashier_pincode = getenv('CASHIER_SERVICE_CODE');
                break;
            case 2:
                $username = getenv('CASHIER1_TEST_USERNAME');
                $password = getenv('CASHIER1_TEST_PASSWORD');
                $cashier_pincode = getenv('CASHIER1_SERVICE_CODE');
                break;
            case 3:
                $username = getenv('CASHIER2_TEST_USERNAME');
                $password = getenv('CASHIER2_TEST_PASSWORD');
                $cashier_pincode = getenv('CASHIER2_SERVICE_CODE');
                break;
            case 4:
                $username = getenv('CASHIER3_TEST_USERNAME');
                $password = getenv('CASHIER3_TEST_PASSWORD');
                $cashier_pincode = getenv('CASHIER3_SERVICE_CODE');
                break;
            case 5:
                $username = getenv('CASHIER4_TEST_USERNAME');
                $password = getenv('CASHIER4_TEST_PASSWORD');
                $cashier_pincode = getenv('CASHIER4_SERVICE_CODE');
                break;
            default:
                $username = getenv('CASHIER_TEST_USERNAME');
                $password = getenv('CASHIER_TEST_PASSWORD');
                $cashier_pincode = getenv('CASHIER_SERVICE_CODE');
        }*/

        return array(
            "username" => $username,
            "password" => $password,
            "cashier_pincode" => $cashier_pincode
        );

    }

    public function setUp(){
        ini_set('error_reporting', E_ALL);
        ini_set('display_errors', '1');
        ini_set('display_startup_errors', '1');

        /*
        self::$shared_data['username'] = getenv('CASHIER_TEST_USERNAME');
        self::$shared_data['password'] = getenv('CASHIER_TEST_PASSWORD');
        self::$shared_data['cashier_pincode'] = getenv('CASHIER_SERVICE_CODE');
        self::$shared_data['ticket'] = array();
        */
        $account = $this->getRandomCashierTerminalAccount();
        self::$shared_data['username'] = $account['username'];
        self::$shared_data['password'] = $account['password'];
        self::$shared_data['cashier_pincode'] = $account['cashier_pincode'];
        self::$shared_data['ticket'] = array();

        //dd(self::$shared_data);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testLogin()
    {
        // create our http client (Guzzle)
        $url = getenv('WEB_SERVICE_URL') . '/rest/json';
        $client = new Client(['headers' => [ 'Content-Type' => 'application/json' ]]);

        /*
        $username = getenv('CASHIER_TEST_USERNAME');
        $password = getenv('CASHIER_TEST_PASSWORD');
        $service_code = getenv('CASHIER_SERVICE_CODE');
        */

        $account = $this->getRandomCashierTerminalAccount();
        self::$shared_data['username'] = $account['username'];
        self::$shared_data['password'] = $account['password'];
        self::$shared_data['cashier_pincode'] = $account['cashier_pincode'];

        $data = array(
            'operation'=> 'login-cashier',
            'username' => self::$shared_data['username'],
            'password' => self::$shared_data['password'],
            'service_code' => self::$shared_data['cashier_pincode'],
        );

        $response = $client->post($url, ['body' => json_encode($data)]);

        $this->assertEquals(200, $response->getStatusCode());
        $response_data = json_decode($response->getBody(), true);

        //print_r($response_data);

        self::$shared_data['session_id'] = $response_data['session_id'];
        self::$shared_data['user_details'] = $response_data['user_details'];
        //fwrite(STDERR, print_r($response_data, TRUE));

        if($response_data['session_id'] == -1){
            print_r(self::$shared_data);
            print_r("TEST USERNAME: " . self::$shared_data['username']);
            print_r("TEST PASSWORD: " . self::$shared_data['password']);
            print_r($response_data['user_details']);
        }

        $this->assertArrayHasKey('status', $response_data);
        $this->assertArrayHasKey('session_id', $response_data);
        $this->assertArrayHasKey('user_details', $response_data);
        $this->assertEquals("OK", $response_data['status']);
        $this->assertNotEquals(-1, $response_data['session_id']);
    }

    public function testChangePassword(){
        $url = getenv('WEB_SERVICE_URL') . '/rest/json';
        $client = new Client(['headers' => [ 'Content-Type' => 'application/json' ]]);

        $data = array(
            'operation' => 'change-password',
            'session_id' => self::$shared_data['session_id'],
            'user_id' => self::$shared_data['user_details']['user_id'],
            'password' => self::$shared_data['password']

        );

        $response = $client->post($url, ['body' => json_encode($data)]);
        $this->assertEquals(200, $response->getStatusCode());
        $response_data = json_decode($response->getBody(), true);

        $this->assertArrayHasKey('status', $response_data);
        $this->assertArrayHasKey('operation', $response_data);
        $this->assertEquals("OK", $response_data['status']);
        $this->assertEquals(self::$shared_data['session_id'], $response_data['session_id']);
    }

    public function testPersonalInformation(){
        $url = getenv('WEB_SERVICE_URL') . '/rest/json';
        $client = new Client(['headers' => [ 'Content-Type' => 'application/json' ]]);

        $data = array(
            'operation' => 'personal-information',
            'session_id' => self::$shared_data['session_id'],
        );

        $response = $client->post($url, ['body' => json_encode($data)]);
        $this->assertEquals(200, $response->getStatusCode());
        $response_data = json_decode($response->getBody(), true);

        $this->assertArrayHasKey('status', $response_data);
        $this->assertArrayHasKey('operation', $response_data);
        $this->assertArrayHasKey('user_details', $response_data);
        $this->assertArrayHasKey('list_currency', $response_data);
        $this->assertEquals("OK", $response_data['status']);
        //$this->assertEquals(self::$shared_data['username'], $response_data['user_details']['username']);
    }

    public function testUpdatePersonalInformation(){
        $url = getenv('WEB_SERVICE_URL') . '/rest/json';
        $client = new Client(['headers' => [ 'Content-Type' => 'application/json' ]]);

        $data = array(
            'operation' => 'personal-information',
            'session_id' => self::$shared_data['session_id'],
        );

        $response = $client->post($url, ['body' => json_encode($data)]);
        $this->assertEquals(200, $response->getStatusCode());
        $response_personal_information = json_decode($response->getBody(), true);

        $data = array(
            'operation' => 'update-personal-information',
            'session_id' => self::$shared_data['session_id'],
            'user_id' => self::$shared_data['user_details']['user_id'],
            'first_name' => $response_personal_information['user_details']['first_name'],
            'last_name' => $response_personal_information['user_details']['last_name'],
            'currency' => $response_personal_information['user_details']['currency'],
            'email' => $response_personal_information['user_details']['email'],
            'address' => $response_personal_information['user_details']['address'],
            'commercial_address' => $response_personal_information['user_details']['commercial_address'],
            'city' => $response_personal_information['user_details']['city'],
            'mobile_phone' => $response_personal_information['user_details']['mobile_phone'],
            'post_code' => $response_personal_information['user_details']['post_code'],
        );

        $response = $client->post($url, ['body' => json_encode($data)]);
        $this->assertEquals(200, $response->getStatusCode());
        $response_data = json_decode($response->getBody(), true);

        $this->assertArrayHasKey('status', $response_data);
        $this->assertArrayHasKey('operation', $response_data);
        $this->assertArrayHasKey('session_id', $response_data);
        $this->assertEquals("OK", $response_data['status']);
    }

    public function testListPlayerTickets(){
        $url = getenv('WEB_SERVICE_URL') . '/rest/json';
        $client = new Client(['headers' => [ 'Content-Type' => 'application/json' ]]);

        $data = array(
            'operation' => 'list-player-tickets',
            'session_id' => self::$shared_data['session_id'],
            'user_id' => self::$shared_data['user_details']['user_id']
        );

        $response = $client->post($url, ['body' => json_encode($data)]);
        $this->assertEquals(200, $response->getStatusCode());
        $response_data = json_decode($response->getBody(), true);

        $this->assertArrayHasKey('status', $response_data);
        $this->assertArrayHasKey('operation', $response_data);
        $this->assertArrayHasKey('list_report', $response_data);
        $this->assertArrayHasKey('list_combinations', $response_data);
        $this->assertEquals("OK", $response_data['status']);
    }

    public function testListMoneyTransactions(){
        $url = getenv('WEB_SERVICE_URL') . '/rest/json';
        $client = new Client(['headers' => [ 'Content-Type' => 'application/json' ]]);

        $data = array(
            'operation' => 'list-player-money-transactions',
            'session_id' => self::$shared_data['session_id'],
            'user_id' => self::$shared_data['user_details']['user_id'],
            'start_date' => getenv('START_DATE'),
            'end_date' => getenv('END_DATE'),
        );

        $response = $client->post($url, ['body' => json_encode($data)]);
        $this->assertEquals(200, $response->getStatusCode());
        $response_data = json_decode($response->getBody(), true);

        $this->assertArrayHasKey('status', $response_data);
        $this->assertArrayHasKey('operation', $response_data);
        $this->assertArrayHasKey('report_list_transactions', $response_data);
        $this->assertArrayHasKey('report_list_transactions_total', $response_data);
        $this->assertEquals("OK", $response_data['status']);
    }

    public function testListPlayerLoginHistory(){
        $url = getenv('WEB_SERVICE_URL') . '/rest/json';
        $client = new Client(['headers' => [ 'Content-Type' => 'application/json' ]]);

        $data = array(
            'operation' => 'list-player-login-history',
            'session_id' => self::$shared_data['session_id'],
            'user_id' => self::$shared_data['user_details']['user_id'],
            'start_date' => getenv('START_DATE'),
            'end_date' => getenv('END_DATE'),
        );

        $response = $client->post($url, ['body' => json_encode($data)]);
        $this->assertEquals(200, $response->getStatusCode());
        $response_data = json_decode($response->getBody(), true);

        $this->assertArrayHasKey('status', $response_data);
        $this->assertArrayHasKey('operation', $response_data);
        $this->assertArrayHasKey('list_report', $response_data);
        $this->assertEquals("OK", $response_data['status']);
    }

    public function testListPlayerHistory(){
        $url = getenv('WEB_SERVICE_URL') . '/rest/json';
        $client = new Client(['headers' => [ 'Content-Type' => 'application/json' ]]);

        $data = array(
            'operation' => 'list-player-history',
            'session_id' => self::$shared_data['session_id'],
            'player_id' => self::$shared_data['user_details']['user_id'],
            'start_date' => getenv('START_DATE'),
            'end_date' => getenv('END_DATE'),
        );

        $response = $client->post($url, ['body' => json_encode($data)]);
        $this->assertEquals(200, $response->getStatusCode());
        $response_data = json_decode($response->getBody(), true);

        $this->assertArrayHasKey('status', $response_data);
        $this->assertArrayHasKey('operation', $response_data);
        $this->assertArrayHasKey('list_report', $response_data);
        $this->assertArrayHasKey('list_report', $response_data);
        $this->assertEquals("OK", $response_data['status']);
    }

    public function testGetLastTicketForUser(){
        $url = getenv('WEB_SERVICE_URL') . '/rest/json';
        $client = new Client(['headers' => [ 'Content-Type' => 'application/json' ]]);

        $data = array(
            'operation' => 'get-last-ticket-for-user',
            'session_id' => self::$shared_data['session_id'],
            'user_id' => self::$shared_data['user_details']['user_id'],
        );

        $response = $client->post($url, ['body' => json_encode($data)]);
        $this->assertEquals(200, $response->getStatusCode());
        $response_data = json_decode($response->getBody(), true);

        $this->assertArrayHasKey('status', $response_data);
        $this->assertArrayHasKey('operation', $response_data);
        $this->assertArrayHasKey('ticket_result', $response_data);
        $this->assertArrayHasKey('combinations_result', $response_data);
        $this->assertEquals("OK", $response_data['status']);
    }

    public function testPlayerCredits(){
        $url = getenv('WEB_SERVICE_URL') . '/rest/json';
        $client = new Client(['headers' => [ 'Content-Type' => 'application/json' ]]);

        $data = array(
            'operation' => 'get-credits',
            'session_id' => self::$shared_data['session_id'],
            'user_id' => self::$shared_data['user_details']['user_id'],
        );

        $response = $client->post($url, ['body' => json_encode($data)]);
        $this->assertEquals(200, $response->getStatusCode());
        $response_data = json_decode($response->getBody(), true);

        $this->assertArrayHasKey('status', $response_data);
        $this->assertArrayHasKey('operation', $response_data);
        $this->assertArrayHasKey('credits', $response_data);
        $this->assertArrayHasKey('credits_formatted', $response_data);
        $this->assertEquals("OK", $response_data['status']);
    }

    private function getRandomNumbersForCombination(){
        $i = 1;
        $numbers = array();
        while($i <= 6){
            $randNumber = random_int(1, 48);
            if(!in_array($randNumber, $numbers)) {
                $numbers[] = $randNumber;
                $i++;
            }
        }
        return $numbers;
    }


    //save ticket WITH RANDOM SELECTED NUMBERS
    public function testSaveTicketInformationRandomNumbers(){
        $url = getenv('WEB_SERVICE_URL') . '/rest/json';
        $client = new Client(['headers' => [ 'Content-Type' => 'application/json' ]]);

        $numbers = $this->getRandomNumbersForCombination();
        $combination = 'COMB1=1:' . $numbers[0] . ',' . $numbers[1] . ',' . $numbers[2] . ',' . $numbers[3] . ',' . $numbers[4] . ',' . $numbers[5] . ":1.00;";

        $data = array(
            'operation' => 'save-ticket-information',
            'session_id' => self::$shared_data['session_id'],
            'user_id' => self::$shared_data['user_details']['user_id'],
            'combination' => $combination,
            'number_of_tickets'=> 1,
            'sum_bet' => 1,
            'possible_win' => 3000,
            'min_possible_win' => 1,
        );

        $response = $client->post($url, ['body' => json_encode($data)]);
        $this->assertEquals(200, $response->getStatusCode());
        $response_data = json_decode($response->getBody(), true);

        $this->assertArrayHasKey('status', $response_data);
        $this->assertArrayHasKey('operation', $response_data);
        $this->assertArrayHasKey('session_id', $response_data);
        $this->assertArrayHasKey('credits', $response_data);
        $this->assertArrayHasKey('credits_formatted', $response_data);
        $this->assertArrayHasKey('serial_number', $response_data);
        $this->assertArrayHasKey('ticket_datetime', $response_data);
        $this->assertArrayHasKey('logged_subject_name', $response_data);
        $this->assertArrayHasKey('barcode', $response_data);
        $this->assertArrayHasKey('language', $response_data);
        $this->assertArrayHasKey('city', $response_data);
        $this->assertArrayHasKey('address', $response_data);
        $this->assertArrayHasKey('commercial_address', $response_data);
        $this->assertArrayHasKey('local_jp_code', $response_data);
        $this->assertArrayHasKey('global_jp_code', $response_data);
        $this->assertArrayHasKey('list_draws', $response_data);
        $this->assertEquals("OK", $response_data['status']);

        self::$shared_data['ticket']['barcode'] = $response_data['barcode'];
        self::$shared_data['ticket']['ticket_id'] = $response_data['barcode'];

        //PRINT TICKET
        $data = array(
            'operation' => 'print-ticket',
            'session_id' => self::$shared_data['session_id'],
            'ticket_id' => self::$shared_data['ticket']['ticket_id']
        );

        $response = $client->post($url, ['body' => json_encode($data)]);
        $this->assertEquals(200, $response->getStatusCode());
        $response_data = json_decode($response->getBody(), true);

        $this->assertArrayHasKey('status', $response_data);
        $this->assertArrayHasKey('operation', $response_data);
        $this->assertArrayHasKey('ticket_id', $response_data);
        $this->assertArrayHasKey('session_id', $response_data);
        $this->assertEquals("OK", $response_data['status']);
    }

    /**
     * Test Logout
     *
     * @return void
     */
    public function testLogout()
    {
        // create our http client (Guzzle)
        $url = getenv('WEB_SERVICE_URL') . '/rest/json';
        $client = new Client(['headers' => [ 'Content-Type' => 'application/json' ]]);

        $data = array(
            'operation'=> 'logout',
            'session_id' => self::$shared_data['session_id']
        );

        $response = $client->post($url, ['body' => json_encode($data)]);

        $this->assertEquals(200, $response->getStatusCode());
        $response_data = json_decode($response->getBody(), true);

        //fwrite(STDERR, print_r($response_data, TRUE));

        $this->assertArrayHasKey('status', $response_data);
        $this->assertArrayHasKey('operation', $response_data);
        $this->assertEquals("OK", $response_data['status']);
        $this->assertEquals("1", $response_data['status_out']);
    }
}
