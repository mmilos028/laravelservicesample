<?php

namespace Tests\Unit\save_ticket_by_player;

use Tests\TestCase;
use GuzzleHttp\Client;

class MakeTicketWithRandomNumbersTest extends TestCase
{
    public static $shared_data = array();

    private function getRandomCashierTerminalAccount(){

        $username = getenv('PLAYER_TEST_USERNAME');
        $password = getenv('PLAYER_TEST_PASSWORD');

        return array(
            "username" => $username,
            "password" => $password,
        );

    }

    public function setUp(){
        ini_set('error_reporting', E_ALL);
        ini_set('display_errors', '1');
        ini_set('display_startup_errors', '1');

        $account = $this->getRandomCashierTerminalAccount();
        self::$shared_data['username'] = $account['username'];
        self::$shared_data['password'] = $account['password'];
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

        $data = array(
            'operation'=> 'login',
            'username' => self::$shared_data['username'],
            'password' => self::$shared_data['password'],
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
        $randomInt = random_int(0, 10);
        if($randomInt >= 4) {
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

        $this->assertArrayHasKey('status', $response_data);
        $this->assertArrayHasKey('operation', $response_data);
        $this->assertEquals("OK", $response_data['status']);
        $this->assertEquals("1", $response_data['status_out']);
    }
}
