<?php
/**
 * Created by PhpStorm.
 * User: ian
 * Date: 1/7/15
 * Time: 1:58 PM
 */

namespace Nmrkt\Linkshare\Client;

use Nmrkt\Linkshare\Client as LinkshareClient;
use GuzzleHttp\Event\SubscriberInterface;
use GuzzleHttp\Query;

class Events extends LinkshareClient
{
    public function __construct($config = [])
    {
        parent::__construct('events', $config);
    }

    public function getTransactions($params = [])
    {
        $q_string = '';
        //stupid Linkshare dates have to only have spaces encoded, not ':'...
        foreach ($params as $k => $v) {
            $q_string .= '&';
            $q_string .= $k . '=' . str_replace(' ', '%20', $v);
        }

        $query = Query::fromString($q_string, false);

        $response = $this->get('transactions', [
            'query' => $query
        ]);

        return $response->json();

    }

}