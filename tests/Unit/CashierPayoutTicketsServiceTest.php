<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Postgres\TicketModel;
use GuzzleHttp\Client;

class CashierPayoutTicketsServiceTest extends TestCase
{
    public static $shared_data = array();

    public function setUp(){
        ini_set('error_reporting', E_ALL);
        ini_set('display_errors', '1');
        ini_set('display_startup_errors', '1');

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

        self::$shared_data['username'] = $username;
        self::$shared_data['password'] = $password;
        self::$shared_data['session_id'] = $response_data['session_id'];
        self::$shared_data['user_details'] = $response_data['user_details'];

        $this->assertArrayHasKey('status', $response_data);
        $this->assertArrayHasKey('session_id', $response_data);
        $this->assertArrayHasKey('user_details', $response_data);
        $this->assertEquals("OK", $response_data['status']);
        $this->assertNotEquals(-1, $response_data['session_id']);
    }

    public function testListPlayerTicketsAndPayout(){
        $this->assertTrue(true);
        $url = getenv('WEB_SERVICE_URL') . '/rest/json';
        $client = new Client(['headers' => [ 'Content-Type' => 'application/json' ]]);

        $data = array(
            'operation' => 'list-player-tickets',
            'session_id' => self::$shared_data['session_id'],
            'user_id' => self::$shared_data['user_details']['user_id']
            //'user_id' => -1
        );

        $response = $client->post($url, ['body' => json_encode($data)]);
        $this->assertEquals(200, $response->getStatusCode());
        $response_data = json_decode($response->getBody(), true);

        //print_r($response_data['list_report']);

        foreach($response_data['list_report'] as $item){
            //NOT PAID OUT and IS PRINTED and status is WIN NOT PAID-OUT
            if($item['payed_out'] == -1
                && $item['ticket_printed'] == 1
                && $item['ticket_status'] == 3
            ){
                $ticketDetails = [
                    'ticket_id' => $item['ticket_id'],
                    'barcode' => $item['barcode'],
                    'session_id' => self::$shared_data['session_id'],
                    'cashier_pincode' => self::$shared_data['cashier_pincode']
                ];
                $result = TicketModel::updateStatusTicketWin($ticketDetails);
                //$result = TicketModel::payoutTicket($ticketDetails);
                $this->printToConsole($result);
            }
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

        //fwrite(STDERR, print_r($response_data, TRUE));

        $this->assertArrayHasKey('status', $response_data);
        $this->assertArrayHasKey('operation', $response_data);
        $this->assertEquals("OK", $response_data['status']);
        $this->assertEquals("1", $response_data['status_out']);
    }

    private function printToConsole($details){
        fwrite(STDERR, print_r($details, TRUE));
    }
}
