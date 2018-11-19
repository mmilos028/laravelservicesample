<?php

namespace Tests\Unit;

use Tests\TestCase;
use GuzzleHttp\Client;

class TerminalServiceTest extends TestCase
{
    public static $shared_data = array();

    public function setUp(){
        ini_set('error_reporting', E_ALL);
        ini_set('display_errors', '1');
        ini_set('display_startup_errors', '1');

        //self::$shared_data['username'] = 'D4-3D-7E-FD-D1-CB';
        //self::$shared_data['password'] = 'D4-3D-7E-FD-D1-CB';
        self::$shared_data['username'] = getenv('TERMINAL_TEST_USERNAME');
        self::$shared_data['password'] = getenv('TERMINAL_TEST_PASSWORD');

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

        $username = getenv('TERMINAL_TEST_USERNAME');
        $password = getenv('TERMINAL_TEST_PASSWORD');

        $data = array(
            'operation'=> 'login',
            'username' => $username,
            'password' => $password
        );

        $response = $client->post($url, ['body' => json_encode($data)]);

        $this->assertEquals(200, $response->getStatusCode());
        $response_data = json_decode($response->getBody(), true);

        self::$shared_data['username'] = $username;
        self::$shared_data['password'] = $password;
        self::$shared_data['session_id'] = $response_data['session_id'];
        self::$shared_data['user_details'] = $response_data['user_details'];
        //fwrite(STDERR, print_r($response_data, TRUE));


        $this->assertArrayHasKey('status', $response_data);
        $this->assertArrayHasKey('session_id', $response_data);
        $this->assertArrayHasKey('user_details', $response_data);
        $this->assertEquals("OK", $response_data['status']);
        $this->assertNotEquals("-1", $response_data['session_id']);

    }

    public function testWrongLogin()
    {
        // create our http client (Guzzle)
        $url = getenv('WEB_SERVICE_URL') . '/rest/json';
        $client = new Client(['headers' => [ 'Content-Type' => 'application/json' ]]);

        $data = array(
            'operation'=> 'login',
            'username' => 'milos',
            'password' => 'milos'
        );

        $response = $client->post($url, ['body' => json_encode($data)]);

        $this->assertEquals(200, $response->getStatusCode());
        $response_data = json_decode($response->getBody(), true);

        $this->assertArrayHasKey('status', $response_data);
        $this->assertArrayHasKey('session_id', $response_data);
        $this->assertEquals("OK", $response_data['status']);
        $this->assertEquals("-1", $response_data['session_id']);

    }

    public function testWrongOperationLogin()
    {
        // create our http client (Guzzle)
        $url = getenv('WEB_SERVICE_URL') . '/rest/json';
        $client = new Client(['headers' => [ 'Content-Type' => 'application/json' ]]);

        $data = array(
            'operation'=> 'login2',
            'username' => 'milos',
            'password' => 'milos'
        );

        $response = $client->post($url, ['body' => json_encode($data)]);

        $this->assertEquals(200, $response->getStatusCode());
        $response_data = json_decode($response->getBody(), true);

        $this->assertArrayHasKey('status', $response_data);
        $this->assertArrayHasKey('error_message', $response_data);
        $this->assertEquals("NOK", $response_data['status']);
        $this->assertEquals("Invalid operation", $response_data['error_message']);

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

    public function testListPlayerTickets(){
        $url = getenv('WEB_SERVICE_URL') . '/rest/json';
        $client = new Client(['headers' => [ 'Content-Type' => 'application/json' ]]);

        $data = array(
            'operation' => 'list-player-tickets',
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
