<?php
/**
 * Created by PhpStorm.
 * User: ian
 * Date: 12/19/14
 * Time: 4:17 PM
 */

namespace Nmrkt\Tests;

use Nmrkt\Linkshare\Client;
use GuzzleHttp\Subscriber\Mock;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Subscriber\History;

abstract class ClientTestCase extends \PHPUnit_Framework_TestCase
{
    protected $linkshare_client;

    protected $mock_response_object;

    protected $request_history_object;

    protected $api_uri = 'endpoint';

    public function setup()
    {
        $this->linkshare_client = new Client($this->api_uri);
        //setup history subscriber
        $this->linkshare_client->getEmitter()->attach($this->getHistoryObject());
    }

    /**
     * @return Mock mock responder queue object
     */
    protected function getMockObject()
    {
        if (!is_a($this->mock_response_object, 'GuzzleHttp\Subscriber\Mock')) {

            $this->mock_response_object = new Mock();

        }
        return $this->mock_response_object;
    }

    /**
     * @return History request history subscriber
     */
    protected function getHistoryObject()
    {
        if (!is_a($this->request_history_object, 'GuzzleHttp\Subscriber\History')) {

            $this->request_history_object = new History();

        }
        return $this->request_history_object;
    }

    /**
     * Adds a mock repsonse to the response queue
     *
     * @param \GuzzleHttp\Stream\Stream $data Stream data object
     * @param int $response_code desired response code
     */
    protected function addClientMock($data, $response_code = 200)
    {
        //create a response with the data and response code
        $api_response = new Response($response_code);
        $api_response->setBody($data);

        $mock_response = $this->getMockObject();
        $mock_response->addResponse($api_response);
    }

}