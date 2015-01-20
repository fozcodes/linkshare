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
    /**
     * @var string
     *
     * formatted Y-m-d H:i:s
     */
    private $process_date_start;

    /**
     * @var string
     *
     * formatted Y-m-d H:i:s
     */
    private $process_date_end;

    /**
     * @var string
     *
     * formatted Y-m-d H:i:s
     */
    private $transaction_date_start;

    /**
     * @var string
     *
     * formatted Y-m-d H:i:s
     */
    private $transaction_date_end;

    /**
     * @var string | int
     */
    private $limit;

    /**
     * @var string | int
     */
    private $page;

    /**
     * @return string
     */
    public function getProcessDateStart()
    {
        return $this->process_date_start;
    }

    /**
     * @param string $process_date_start
     */
    public function setProcessDateStart($process_date_start)
    {
        $this->process_date_start = $process_date_start;
    }

    /**
     * @return string
     */
    public function getProcessDateEnd()
    {
        return $this->process_date_end;
    }

    /**
     * @param string $process_date_end
     */
    public function setProcessDateEnd($process_date_end)
    {
        $this->process_date_end = $process_date_end;
    }

    /**
     * @return string
     */
    public function getTransactionDateStart()
    {
        return $this->transaction_date_start;
    }

    /**
     * @param string $transaction_date_start
     */
    public function setTransactionDateStart($transaction_date_start)
    {
        $this->transaction_date_start = $transaction_date_start;
    }

    /**
     * @return string
     */
    public function getTransactionDateEnd()
    {
        return $this->transaction_date_end;
    }

    /**
     * @param string $transaction_date_end
     */
    public function setTransactionDateEnd($transaction_date_end)
    {
        $this->transaction_date_end = $transaction_date_end;
    }

    /**
     * @return int|string
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param int|string $limit
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    /**
     * @return int|string
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param int|string $page
     */
    public function setPage($page)
    {
        $this->page = $page;
    }



    public function __construct($config = [])
    {
        parent::__construct('events', $config);
    }

    /**
     * @param array $params
     * @return array
     */
    public function getTransactions()
    {
        $response = $this->get('transactions', [
            'query' => $this->createQueryString()
        ]);

        return $response->json();

    }

    private function createQueryString()
    {
        $q_string = '';
        $q_string .= isset($this->process_date_start) ? '&process_date_start=' . str_replace(' ', '%20', $this->process_date_start) : '';
        $q_string .= isset($this->process_date_end) ? '&process_date_end=' . str_replace(' ', '%20', $this->process_date_end) : '';
        $q_string .= isset($this->transaction_date_start) ? '&transaction_date_start=' . str_replace(' ', '%20', $this->transaction_date_start) : '';
        $q_string .= isset($this->transaction_date_end) ? '&transaction_date_end=' . str_replace(' ', '%20', $this->transaction_date_end) : '';
        $q_string .= isset($this->limit) ? '&limit=' . $this->limit : '';
        $q_string .= isset($this->page) ? '&page=' . $this->page : '';


        return Query::fromString($q_string, false);
    }

}