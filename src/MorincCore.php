<?php

namespace MORINC;

use GuzzleHttp\Client;
use Carbon\Carbon;

class MorincCore {
        
    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $processor;

    /**
     * @var string
     */
    protected $timezone;

    /**
     * @var bool
     */
    protected $hashed_password;

    /**
     * @var int
     */
    protected $timeout;

    /**
     * @var string
     */
    protected $response_type;

    /**
     * Instantiate a new instance
     * 
     * @return void
     */
    public function __construct()
    {
        $this->processor        = config('morinc.processor');
        $this->timezone         = config('morinc.timezone');
        $this->response_type    = strtolower(config('morinc.response_type'));

        $this->client = new Client([
            'base_uri'  => config('morinc.url'),
            'timeout'   => config('morinc.timeout'),
            'headers'   => ['Accept' => 'application/json']
        ]);
    }

    /**
     * Respond to a WHMCS request
     * 
     * @param type 
     * @return array
     */
    public function submitRequest($data)
    {
        $response = $this->client->request('POST', '', [
            'query' => $data,
            'http_errors' => true,
            'verify' => false
        ]);

        return $this->handleResponse($response);
    }

    /**
     * Formats the response based on the set response_type
     *
     * @param array $response
     * @return array
     */
    protected function handleResponse($response)
    {
        if ($this->response_type === 'json')
            return json_decode($response->getBody(), true);

        return simplexml_load_string($response->getBody());
    }

    protected function getDate($format = 'YM')
    {
        //
    }

}