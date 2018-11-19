<?php

namespace Tests\Unit;

use Tests\TestCase;
use GuzzleHttp\Client;

class CashierServiceTest extends TestCase
{
    public static $shared_data = array();

    public function setUp(){
        ini_set('error_reporting', E_ALL);
        ini_set('display_errors', '1');
        ini_set('display_startup_errors', '1');

        //self::$shared_data['username'] = 'D4-3D-7E-FD-D1-CB';
        //self::$shared_data['password'] = 'D4-3D-7E-FD-D1-CB';
        self::$shared_data['username'] = getenv('CASHIER_TEST_USERNAME');
        self::$shared_data['password'] = getenv('CASHIER_TEST_PASSWORD');
        self::$shared_data['cashier_pincode'] = getenv('CASHIER_SERVICE_CODE');

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

        $username = getenv('CASHIER_TEST_USERNAME');
        $password = getenv('CASHIER_TEST_PASSWORD');
        $service_code = getenv('CASHIER_SERVICE_CODE');

        $data = array(
            'operation'=> 'login-cashier',
            'username' => $username,
            'password' => $password,
            'service_code' => $service_code
        );

        $response = $client->post($url, ['body' => json_encode($data)]);

        $this->assertEquals(200, $response->getStatusCode());
        $response_data = json_decode($response->getBody(), true);

        //print_r($response_data);

        self::$shared_data['username'] = $username;
        self::$shared_data['password'] = $password;
        self::$shared_data['session_id'] = $response_data['session_id'];
        self::$shared_data['user_details'] = $response_data['user_details'];
        //fwrite(STDERR, print_r($response_data, TRUE));


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
        $this->assertEquals(self::$shared_data['username'], $response_data['user_details']['username']);
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

    public function testSaveTicketInformationAnonym1(){
        $url = getenv('WEB_SERVICE_URL') . '/rest/json';
        $client = new Client(['headers' => [ 'Content-Type' => 'application/json' ]]);

        $numbers = $this->getRandomNumbersForCombination();
        $combination = 'COMB1=1:' . $numbers[0] . ',' . $numbers[1] . ',' . $numbers[2] . ',' . $numbers[3] . ',' . $numbers[4] . ',' . $numbers[5] . ":1.00;";
        //$combination_fixed = 'COMB1=1:3,4,5,12,30,47:1.00;';

        $data = array(
            'operation' => 'save-ticket-information',
            'session_id' => self::$shared_data['session_id'],
            'user_id' => -1, //self::$shared_data['user_details']['user_id'],
            'combination' => $combination,
            'number_of_tickets'=> 1,
            'sum_bet' => 1,
            'possible_win' => 3000,
            'min_possible_win' => 1,
        );

        $response = $client->post($url, ['body' => json_encode($data)]);
        $this->assertEquals(200, $response->getStatusCode());
        $response_data = json_decode($response->getBody(), true);

        self::$shared_data['ticket']['barcode'] = $response_data['barcode'];
        self::$shared_data['ticket']['ticket_id'] = $response_data['barcode'];

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

    public function testSaveTicketInformationAnonym2(){
        $url = getenv('WEB_SERVICE_URL') . '/rest/json';
        $client = new Client(['headers' => [ 'Content-Type' => 'application/json' ]]);

        $numbers = $this->getRandomNumbersForCombination();
        $combination = 'COMB1=1:' . $numbers[0] . ',' . $numbers[1] . ',' . $numbers[2] . ',' . $numbers[3] . ',' . $numbers[4] . ',' . $numbers[5] . ":1.00;";

        $data = array(
            'operation' => 'save-ticket-information',
            'session_id' => self::$shared_data['session_id'],
            //'user_id' => self::$shared_data['user_details']['user_id'],
            'user_id' => -1,
            'combination' => $combination,
            'number_of_tickets'=> 1,
            'sum_bet' => 1,
            'possible_win' => 3000,
            'min_possible_win' => 1,
        );

        $response = $client->post($url, ['body' => json_encode($data)]);
        $this->assertEquals(200, $response->getStatusCode());
        $response_data = json_decode($response->getBody(), true);

        self::$shared_data['ticket']['barcode'] = $response_data['barcode'];
        self::$shared_data['ticket']['ticket_id'] = $response_data['barcode'];

        $this->assertArrayHasKey('status', $response_data);
        $this->assertArrayHasKey('operation', $response_data);
        $this->assertArrayHasKey('credits', $response_data);
        $this->assertArrayHasKey('credits_formatted', $response_data);
        $this->assertEquals("OK", $response_data['status']);

        //CANCEL TICKET
        /*
        $data = array(
            'operation' => 'cancel-ticket',
            'session_id' => self::$shared_data['session_id'],
            'barcode' => self::$shared_data['ticket']['barcode'],
            'cashier_pincode' => '-1'
        );

        $response = $client->post($url, ['body' => json_encode($data)]);
        $this->assertEquals(200, $response->getStatusCode());
        $response_data = json_decode($response->getBody(), true);

        print_r($response_data);

        $this->assertArrayHasKey('status', $response_data);
        $this->assertArrayHasKey('operation', $response_data);
        $this->assertArrayHasKey('barcode', $response_data);
        $this->assertArrayHasKey('session_id', $response_data);
        $this->assertEquals("OK", $response_data['status']);
        */

    }

    public function testSaveTicketInformationAnonym3(){
        $url = getenv('WEB_SERVICE_URL') . '/rest/json';
        $client = new Client(['headers' => [ 'Content-Type' => 'application/json' ]]);

        $numbers = $this->getRandomNumbersForCombination();
        $combination = 'COMB1=1:' . $numbers[0] . ',' . $numbers[1] . ',' . $numbers[2] . ',' . $numbers[3] . ',' . $numbers[4] . ',' . $numbers[5] . ":1.00;";
        $combination_fixed = 'COMB1=1:9,12,15,20,38,42:1.00;';

        $data = array(
            'operation' => 'save-ticket-information',
            'session_id' => self::$shared_data['session_id'],
            //'user_id' => self::$shared_data['user_details']['user_id'],
            'user_id' => -1,
            'combination' => $combination,
            'number_of_tickets'=> 1,
            'sum_bet' => 1,
            'possible_win' => 3000,
            'min_possible_win' => 1,
        );

        $response = $client->post($url, ['body' => json_encode($data)]);
        $this->assertEquals(200, $response->getStatusCode());
        $response_data = json_decode($response->getBody(), true);

        self::$shared_data['ticket']['barcode'] = $response_data['barcode'];
        self::$shared_data['ticket']['ticket_id'] = $response_data['barcode'];

        $this->assertArrayHasKey('status', $response_data);
        $this->assertArrayHasKey('operation', $response_data);
        $this->assertArrayHasKey('credits', $response_data);
        $this->assertArrayHasKey('credits_formatted', $response_data);
        $this->assertEquals("OK", $response_data['status']);

        //PRINT TICKET
        $data = array(
            'operation' => 'print-ticket',
            'session_id' => self::$shared_data['session_id'],
            'ticket_id' => $response_data['barcode']
        );

        $response = $client->post($url, ['body' => json_encode($data)]);
        $this->assertEquals(200, $response->getStatusCode());
        $response_data = json_decode($response->getBody(), true);

        //print_r($response_data);

        $this->assertArrayHasKey('status', $response_data);
        $this->assertArrayHasKey('operation', $response_data);
        $this->assertArrayHasKey('ticket_id', $response_data);
        $this->assertArrayHasKey('session_id', $response_data);
        $this->assertEquals("OK", $response_data['status']);
    }

    public function testSaveTicketInformationAnonym4(){
        $url = getenv('WEB_SERVICE_URL') . '/rest/json';
        $client = new Client(['headers' => [ 'Content-Type' => 'application/json' ]]);

        $numbers = $this->getRandomNumbersForCombination();
        $combination = 'COMB1=1:' . $numbers[0] . ',' . $numbers[1] . ',' . $numbers[2] . ',' . $numbers[3] . ',' . $numbers[4] . ',' . $numbers[5] . ":1.00;";
        $combination_fixed = 'COMB1=1:3,4,5,12,30,47:1.00;';

        $data = array(
            'operation' => 'save-ticket-information',
            'session_id' => self::$shared_data['session_id'],
            //'user_id' => self::$shared_data['user_details']['user_id'],
            'user_id' => -1,
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
        $this->assertArrayHasKey('credits', $response_data);
        $this->assertArrayHasKey('credits_formatted', $response_data);
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

    /*
    //from this cashier
    public function testSaveTicketInformation1(){
        $url = getenv('WEB_SERVICE_URL') . '/rest/json';
        $client = new Client(['headers' => [ 'Content-Type' => 'application/json' ]]);

        $numbers = $this->getRandomNumbersForCombination();
        $combination = 'COMB1=1:' . $numbers[0] . ',' . $numbers[1] . ',' . $numbers[2] . ',' . $numbers[3] . ',' . $numbers[4] . ',' . $numbers[5] . ":1.00;";
        $combination_fixed = 'COMB1=1:3,4,5,12,30,47:1.00;';

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
        self::$shared_data['ticket']['ticket_id'] = $response_data['serial_number'];
    }

    public function testPrintTicket1(){
        $url = getenv('WEB_SERVICE_URL') . '/rest/json';
        $client = new Client(['headers' => [ 'Content-Type' => 'application/json' ]]);

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

    public function testSaveTicketInformation2(){
        $url = getenv('WEB_SERVICE_URL') . '/rest/json';
        $client = new Client(['headers' => [ 'Content-Type' => 'application/json' ]]);

        $numbers = $this->getRandomNumbersForCombination();
        $combination = 'COMB1=1:' . $numbers[0] . ',' . $numbers[1] . ',' . $numbers[2] . ',' . $numbers[3] . ',' . $numbers[4] . ',' . $numbers[5] . ":1.00;";
        $combination_fixed = 'COMB1=1:3,4,5,12,30,47:1.00;';

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
        $this->assertArrayHasKey('credits', $response_data);
        $this->assertArrayHasKey('credits_formatted', $response_data);
        $this->assertEquals("OK", $response_data['status']);

        self::$shared_data['ticket']['barcode'] = $response_data['barcode'];
        self::$shared_data['ticket']['ticket_id'] = $response_data['serial_number'];
    }

    public function testSaveTicketInformation3(){
        $url = getenv('WEB_SERVICE_URL') . '/rest/json';
        $client = new Client(['headers' => [ 'Content-Type' => 'application/json' ]]);

        $numbers = $this->getRandomNumbersForCombination();
        $combination = 'COMB1=1:' . $numbers[0] . ',' . $numbers[1] . ',' . $numbers[2] . ',' . $numbers[3] . ',' . $numbers[4] . ',' . $numbers[5] . ":1.00;";
        $combination_fixed = 'COMB1=1:9,12,15,20,38,42:1.00;';

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
        $this->assertArrayHasKey('credits', $response_data);
        $this->assertArrayHasKey('credits_formatted', $response_data);
        $this->assertEquals("OK", $response_data['status']);

        self::$shared_data['ticket']['barcode'] = $response_data['barcode'];
        self::$shared_data['ticket']['ticket_id'] = $response_data['serial_number'];
    }

    public function testSaveTicketInformation4(){
        $url = getenv('WEB_SERVICE_URL') . '/rest/json';
        $client = new Client(['headers' => [ 'Content-Type' => 'application/json' ]]);

        $numbers = $this->getRandomNumbersForCombination();
        $combination = 'COMB1=1:' . $numbers[0] . ',' . $numbers[1] . ',' . $numbers[2] . ',' . $numbers[3] . ',' . $numbers[4] . ',' . $numbers[5] . ":1.00;";
        $combination_fixed = 'COMB1=1:3,4,5,12,30,47:1.00;';

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
        $this->assertArrayHasKey('credits', $response_data);
        $this->assertArrayHasKey('credits_formatted', $response_data);
        $this->assertEquals("OK", $response_data['status']);

        self::$shared_data['ticket']['barcode'] = $response_data['barcode'];
        self::$shared_data['ticket']['ticket_id'] = $response_data['serial_number'];
    }
    */

    /*public function testSaveTicketInformation(){
        $url = getenv('WEB_SERVICE_URL') . '/rest/json';
        $client = new Client(['headers' => [ 'Content-Type' => 'application/json' ]]);

        $data = array(
            'operation' => 'save-ticket-information',
            'session_id' => self::$shared_data['session_id'],
            'user_id' => self::$shared_data['user_details']['user_id'],
            'combination'=>"1,2,3,4,5,6",
            'number_of_tickets'=>'1',
            'sum_bet'=>'50.00',
            'jackpot'=>''
        );

        $response = $client->post($url, ['body' => json_encode($data)]);

        $this->assertEquals(200, $response->getStatusCode());
        $response_data = json_decode($response->getBody(), true);

        //fwrite(STDERR, print_r($response_data, TRUE));
        //dd($response_data);

        $this->assertArrayHasKey('status', $response_data);
        $this->assertArrayHasKey('operation', $response_data);
        $this->assertArrayHasKey('session_id', $response_data);
        $this->assertEquals("NOK", $response_data['status']);
    }*/

    /**
     * Test wrong operation logout
     */
    public function testWrongOperationLogout(){

        // create our http client (Guzzle)
        $url = getenv('WEB_SERVICE_URL') . '/rest/json';
        $client = new Client(['headers' => [ 'Content-Type' => 'application/json' ]]);

        $data = array(
            'operation'=> 'logout2',
            'session_id' => self::$shared_data['session_id'],
        );

        $response = $client->post($url, ['body' => json_encode($data)]);

        $this->assertEquals(200, $response->getStatusCode());
        $response_data = json_decode($response->getBody(), true);

        //fwrite(STDERR, print_r($response_data, TRUE));

        $this->assertArrayHasKey('status', $response_data);
        $this->assertArrayHasKey('error_message', $response_data);
        $this->assertEquals("NOK", $response_data['status']);
        $this->assertEquals("Invalid operation", $response_data['error_message']);
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
