<?php

namespace MOR;

use GuzzleHttp\Client;
use Carbon\Carbon;

class MorCore {
        
    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $api_url;

    /**
     * @var string
     */
    protected $processor;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var string
     */
    protected $timezone;

    /**
     * @var int
     */
    protected $timeout;

    /**
     * Instantiate a new instance
     */
    public function __construct()
    {
        $this->api_url          = config('mor.url');
        $this->processor        = config('mor.processor');
        $this->timezone         = config('mor.timezone');
        $this->username         = config('mor.username');
        $this->password         = config('mor.password');
        $this->timezone         = config('mor.timezone');

        $this->client = new Client([
            'base_uri'  => sprintf('%s/%s', rtrim($this->api_url, '/'), ltrim($this->processor, '/')),
            'timeout'   => config('mor.timeout')
        ]);
    }

    /**
     * Respond to a MOR request
     * 
     * @param type 
     * @return string
     */
    public function submitRequest($data)
    {
        $response = $this->client->get('?', [
            'query' => $data,
            'http_errors' => true,
            'verify' => false
        ]);

        return (string)$response->getBody();
    }

    public function getDate($format = 'YM')
    {
        return (string)Carbon::now($this->timezone)->format($format);
    }

    /**
     * @return string sha1 hash
     */
    public function getDatum()
    {
        return sha1($this->getDate('YM') . 'm0nk3ys');
    }

}