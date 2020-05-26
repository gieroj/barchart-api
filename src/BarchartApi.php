<?php

namespace Gieroj\BarchartApi;

use Carbon\Carbon;
use GuzzleHttp\Client;

class BarchartApi
{
    /**
     * The Guzzle instance used for all requests to the BarchartApi
     *
     * @var \GuzzleHttp\Client
     */
    private $client;

    /**
     * Key ID
     *
     * @var string
     */
    private $key;

    /**
     * BarchartApi constructor
     *
     * @param string $key       The Barchart account key
     * @param string $secret    The Barchart account secret key
     * @param boolean $paper    Use the paper trading endpoint (true) or the production endpoint (false)
     *
     * @return void
     */
    public function __construct($key = "", $base_url = null)
    {
        $this->setKey($key);

        if ($base_url) {
            $this->client = new Client([
                'base_uri' => $base_url
            ]);
        } else {
            $this->client = new Client();
        }
    }

    /**
     * Set the account key.
     *
     * @param string $key
     *
     * @return void
     */
    public function setKey($key = "")
    {
        $this->key = $key;
    }

    private function _request($path, $queryStrings = [], $type = "GET")
    {
        try {
            $request = [
                "headers" => [
                    "Content-Type" => "application/json",
                    "Accept" => "application/json",
                ],
            ];

            $queryString = '';
            foreach ($queryStrings as $key => $value) {
                if (is_array($value)) {
                    continue;
                }
                $queryString .= "&{$key}={$value}";
            }

            $url = $path . '?apikey=' . $this->key . $queryString;

            $response = $this->client->request($type, $url);

            return new Response($response);
        } catch (\GuzzleHttp\Exception\TransferException $e) {
            if ($e->hasResponse()) {
                return new Response($e->getResponse());
            } else {
                throw $e;
            }
        }
    }

    /**
     * Get last Quote.
     *
     * @link https://www.barchart.com/ondemand/api/getQuote
     *
     * @param string $symbols 'AAPL','GOOG'
     * @param string $fields fiftyTwoWkHigh,fiftyTwoWkHighDate,fiftyTwoWkLow,fiftyTwoWkLowDate
     * @param string $only symbol,name
     *
     * @return Response
     */
    public function getQuote($symbols, $fields = null, $only = null ){

        $qs = [];

        if (is_array($symbols)) {
            $qs["symbols"] = implode(",", $symbols);
        } else {
            $qs["symbols"] = $symbols;
        }

        if (!is_null($fields)) {
            if (is_array($fields)) {
                $qs["fields"] = implode(",", $fields);
            } else {
                $qs["fields"] = $fields;
            }
        }

        if (!is_null($only)) {
            if (is_array($only)) {
                $qs["only"] = implode(",", $only);
            } else {
                $qs["only"] = $only;
            }
        }

        return $this->_request("getQuote.json", $qs);
    }

    /**
     * Get Custom query data.
     *
     * @link https://www.barchart.com/ondemand/api
     *
     * @param string $query 'getCorporateActions'
     * @param array $options ['symbols' => 'AMZN,AAPL', 'startDate' => '2013-01-01', 'maxRecords' => 5 ]
     *
     * @return Response
     */
    public function getCustom($query, $options){

        $qs = [];

        if (is_array($options)) {
            foreach ($options as $key => $value) {
                $qs[$key] = $value;
            }
        }

        return $this->_request( $query . ".json", $qs);
    }
}