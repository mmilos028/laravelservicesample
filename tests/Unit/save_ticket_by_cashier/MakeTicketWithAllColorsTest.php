<?php

namespace Tests\Unit\save_ticket_by_cashier;

use Tests\TestCase;
use GuzzleHttp\Client;

class MakeTicketWithAllColorsTest extends TestCase
{
    public static $shared_data = array();

    private function getRandomCashierTerminalAccount(){

        $username = getenv('CASHIER_TEST_USERNAME');
        $password = getenv('CASHIER_TEST_PASSWORD');
        $cashier_pincode = getenv('CASHIER_SERVICE_CODE');

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

        $account = $this->getRandomCashierTerminalAccount();
        self::$shared_data['username'] = $account['username'];
        self::$shared_data['password'] = $account['password'];
        self::$shared_data['cashier_pincode'] = $account['cashier_pincode'];
        self::$shared_data['ticket'] = array();

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

        self::$shared_data['session_id'] = $response_data['session_id'];
        self::$shared_data['user_details'] = $response_data['user_details'];

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

    //save ticket - ALL COLORS
    /*public function testSaveTicketInformationAllColors(){
        $url = getenv('WEB_SERVICE_URL') . '/rest/json';
        $client = new Client(['headers' => [ 'Content-Type' => 'application/json' ]]);
        
        $combination = "COMB1=6:black:0.2;COMB2=6:red:0.2;COMB3=6:green:0.2;COMB4=6:blue:0.2;COMB5=6:purple:0.2;COMB6=6:brown:0.2;COMB7=6:yellow:0.2;COMB8=6:orange:0.2;";

        $data = array(
            'operation' => 'save-ticket-information',
            'session_id' => self::$shared_data['session_id'],
            'user_id' => self::$shared_data['user_details']['user_id'],
            'combination' => $combination,
            'number_of_tickets'=> 1,
            'sum_bet' => 1.6,
            'possible_win' => 2000,
            'min_possible_win' => 0.2,
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
        $randomInt = random_int(0, 10);
        if($randomInt >= 2) {
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
    }*/


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

        $this->assertArrayHasKey('status', $response_data);
        $this->assertArrayHasKey('operation', $response_data);
        $this->assertEquals("OK", $response_data['status']);
        $this->assertEquals("1", $response_data['status_out']);
    }
}
