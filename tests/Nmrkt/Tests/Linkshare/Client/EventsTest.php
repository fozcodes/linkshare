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

class EventsTest extends ClientTestCase
{

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

    public function testSetsBaseUrlToEvents()
    {
        $base_url = $this->linkshare_client->getBaseUrl();

        $this->assertEquals('https://api.rakutenmarketing.com/events/1.0/', $base_url);
    }

    public function testGetTransactionsSetsFullRequestCorrectly()
    {
        //block OAuth call with mock


        //add the mock to fake a response
        $this->addClientMock(new \GuzzleHttp\Stream\Stream(fopen(RESOURCE_PATH . '/linkshare-response.json', 'r')));

        //get the mocked subscriber from parent and attach
        $this->linkshare_client->getEmitter()->attach($this->getMockObject());

        $date = date('Y-m-d H:i:s');

        $params = [
            'process_start_date' => str_replace(' ', '%20', $date),
            'limit' => 1000
        ];

        $this->linkshare_client->getTransactions($params);

        $history = $this->getHistoryObject();

        $request = $history->getLastRequest();

        $this->assertEquals('https://api.rakutenmarketing.com/events/1.0/transactions?process_start_date='.str_replace(' ', '%20', $date).'&limit=1000', $request->getUrl());

    }

}