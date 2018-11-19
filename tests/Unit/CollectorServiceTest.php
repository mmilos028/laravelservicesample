<?php

namespace Tests\Unit;

use Tests\TestCase;
use GuzzleHttp\Client;

class CollectorServiceTest extends TestCase
{
    public static $shared_data = array();

    public function setUp(){
        ini_set('error_reporting', E_ALL);
        ini_set('display_errors', '1');
        ini_set('display_startup_errors', '1');

        self::$shared_data['username'] = getenv('COLLECTOR_TEST_USERNAME');
        self::$shared_data['password'] = getenv('COLLECTOR_TEST_PASSWORD');

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

        $username = getenv('COLLECTOR_TEST_USERNAME');
        $password = getenv('COLLECTOR_TEST_PASSWORD');

        $data = array(
            'operation'=> 'login-cashier',
            'username' => $username,
            'password' => $password,
            'service_code' => '-1'
        );

        $response = $client->post($url, ['body' => json_encode($data)]);

        $this->assertEquals(200, $response->getStatusCode());
        $response_data = json_decode($response->getBody(), true);

        self::$shared_data['username'] = $username;
        self::$shared_data['password'] = $password;
        self::$shared_data['session_id'] = $response_data['session_id'];
        self::$shared_data['user_details'] = $response_data['user_details'];

        $this->assertArrayHasKey('status', $response_data);
        $this->assertArrayHasKey('session_id', $response_data);
        $this->assertArrayHasKey('user_details', $response_data);
        $this->assertEquals("OK", $response_data['status']);
        $this->assertNotEquals("-1", $response_data['session_id']);

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
