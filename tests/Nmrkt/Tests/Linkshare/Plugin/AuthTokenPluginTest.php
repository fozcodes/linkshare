<?php
/**
 * Created by PhpStorm.
 * User: ian
 * Date: 12/18/14
 * Time: 5:27 PM
 */


namespace Nmrkt\Tests\CommissionJunction\Plugin;

use Nmrkt\Tests\ClientTestCase;
use Nmrkt\CommissionJunction\Plugin\AuthTokenPlugin as Plugin;

class AuthTokenPluginTest extends ClientTestCase
{

    public function setup()
    {
        parent::setup();
    }

    private function recurseForMethodName($event, $plugin)
    {
        if (is_array($event[0])) {
            foreach($event as $e) {
                $this->recurseForMethodName($e, $plugin);
            }
        } else {
            $this->assertTrue(method_exists($plugin, $event[0]), $event[0] . ' method does not exist');
        }
    }

    public function testCanGetEvents()
    {
        $plugin = new Plugin($this->auth_token);
        $this->assertTrue(is_array($plugin->getEvents()));
    }

    public function testMethodsExistInGetEvents()
    {
        $plugin = new Plugin($this->auth_token);
        foreach($plugin->getEvents() as $event) {
            $this->recurseForMethodName($event, $plugin);
        }
        $this->assertTrue(is_array($plugin->getEvents()));
    }

    public function testAuthTokenIsAddedToHeader()
    {
        //add the mock to fake a response
        $this->addClientMock(new \GuzzleHttp\Stream\Stream(fopen(RESOURCE_PATH . '/commission-detail-response.xml', 'r')));

        //get the mocked subscriber from parent and attach
        $this->cj_client->getEmitter()->attach($this->getMockObject());

        $this->cj_client->get('/');

        $history = $this->getHistoryObject();

        $request = $history->getLastRequest();

        $this->assertEquals($this->auth_token, $request->getHeader('authorization'));

    }
}