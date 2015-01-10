<?php
/**
 * Created by PhpStorm.
 * User: ian
 * Date: 12/18/14
 * Time: 1:33 PM
 */

namespace Nmrkt\Tests\Linkshare;

use Nmrkt\Linkshare\Client as Client;
use Nmrkt\Tests\ClientTestCase;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\ClientException;

class ClientTest extends ClientTestCase
{

    public function setup()
    {
        parent::setup();

    }

    public function testClientIsGuzzleClient()
    {
        $this->assertTrue(is_a($this->linkshare_client, 'GuzzleHttp\Client'));
    }

    public function testBaseUrlIsSetCorrectly()
    {
        $this->linkshare_client = new Client('endpoint');

        $baseUrl = $this->linkshare_client->getBaseUrl();

        $this->assertEquals('https://api.rakutenmarketing.com/endpoint/1.0/', $baseUrl);
    }


    public function testConfigIsSetCorrectlyInConstructor()
    {
        $config = [
            'username' => 'myname',
            'password' => 'pwd',
            'client_id' => 'jdiuv989djf',
            'client_secret' => 'jsopiuiwe09te',
            'scope' => 'scope!'
        ];
        $this->linkshare_client = new Client('endpoint', $config);

        $set_config = $this->linkshare_client->getOauthConfig();

        $this->assertEquals($config, $set_config);
    }

    public function testOauthPluginCanBeAddedAsSubscriber()
    {
        $this->linkshare_client = new Client('endpoint');

        $this->linkshare_client->getOauth2Client();
        $this->linkshare_client->getClientCredentialsGrantType();
        $subscriber = $this->linkshare_client->getOauth2Subscriber();

        $this->linkshare_client->attachOauth2Subscriber($subscriber);

        $before_subscibers = $this->linkshare_client->getEmitter()->listeners('before');

        $this->assertTrue(is_a($before_subscibers[0][0], 'Nmrkt\GuzzleOAuth2\OAuth2Subscriber'));

        $error_subscibers = $this->linkshare_client->getEmitter()->listeners('error');

        $this->assertTrue(is_a($error_subscibers[0][0], 'Nmrkt\GuzzleOAuth2\OAuth2Subscriber'));

    }

}
