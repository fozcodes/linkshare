<?php
/**
 * Created by PhpStorm.
 * User: ian
 * Date: 1/7/15
 * Time: 2:28 PM
 */

namespace Nmrkt\Tests\Linkshare\Client;

use Nmrkt\Tests\ClientTestCase;

use Nmrkt\Linkshare\Client\Events;

/**
 * Class EventsTest
 * @package Nmrkt\Tests\Linkshare\Client
 */
class EventsTest extends ClientTestCase
{

    /**
     * @var Nmrkt\Linkshare\Client\Events
     */
    protected $linkshare_client;

    /**
     *
     */
    public function setup()
    {
        $config = [
            'username' => 'nmrkt',
            'password' => 'password',
            'client_id' => 'your client id',
            'client_secret' => 'your client secret',
            'scope' => 'your scope(s)', // optional
        ];
        $this->linkshare_client = new Events($config);

        $this->linkshare_client->getEmitter()->attach($this->getHistoryObject());
    }

    /**
     *
     */
    public function testSetsBaseUrlToEvents()
    {
        $base_url = $this->linkshare_client->getBaseUrl();

        $this->assertEquals('https://api.rakutenmarketing.com/events/1.0/', $base_url);
    }

    /**
     *
     */
    public function testCanSetAndGetProcessStartDate()
    {
        $this->assertTrue(is_null($this->linkshare_client->getProcessDateStart()));

        $date = date('Y-m-d H:i:s');
        $this->linkshare_client->setProcessDateStart($date);
        $instance_psd = $this->linkshare_client->getProcessDateStart();

        $this->assertEquals($date, $instance_psd);
    }

    /**
     *
     */
    public function testGetTransactionsSetsRequestBodyCorrectly()
    {
        //add the mock to fake a response
        $this->addClientMock(new \GuzzleHttp\Stream\Stream(fopen(RESOURCE_PATH . '/linkshare-response.json', 'r')));

        //get the mocked subscriber from parent and attach
        $this->linkshare_client->getEmitter()->attach($this->getMockObject());

        $date = date('Y-m-d H:i:s');

        $this->linkshare_client->setProcessDateStart($date);
        $this->linkshare_client->setLimit(1000);


        $this->linkshare_client->getTransactions();

        $history = $this->getHistoryObject();

        $request = $history->getLastRequest();

        $this->assertEquals('https://api.rakutenmarketing.com/events/1.0/transactions?process_date_start='.str_replace(' ', '%20', $date).'&limit=1000', $request->getUrl());

    }


    /**
     * Not exactly a "unit" test, but screw it, I don't care, I wanna make sure that plugin is firing correctly.
     */
    public function testGetTransactionsHasCorrectHeaderFromOauthPlugin()
    {
        $oauth2Client = $this->linkshare_client->getOauth2Client();

        $this->addClientMock(new \GuzzleHttp\Stream\Stream(fopen(RESOURCE_PATH . '/linkshare-response-token.json', 'r')));

        $oauth2Client->getEmitter()->attach($this->getMockObject());

        $subscriber = $this->linkshare_client->getOauth2Subscriber();

        $this->linkshare_client->attachOauth2Subscriber($subscriber);

        //add the mock to fake a response
        $this->addClientMock(new \GuzzleHttp\Stream\Stream(fopen(RESOURCE_PATH . '/linkshare-response.json', 'r')));

        //get the mocked subscriber from parent and attach
        $this->linkshare_client->getEmitter()->attach($this->getMockObject());

        $this->linkshare_client->getTransactions();

        $history = $this->getHistoryObject();

        $request = $history->getLastRequest();

        //from /tests/Resources/linkshare-response-token.json
        $this->assertEquals('Bearer f14030791cb4989c551459f56276cab0', $request->getHeader('Authorization'));
    }

}