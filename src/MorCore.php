<?php

namespace MOR;

use GuzzleHttp\Client;
use Mtownsend\XmlToArray\XmlToArray;

class MorCore
{
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
    protected $api_secret_key;

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
     * @var bool
     */
    protected $hash_checking;

    /**
     * Instantiate a new instance
     */
    public function __construct()
    {
        $this->api_url          = config('mor.url');
        $this->api_secret_key   = config('mor.secret_key');
        $this->username         = config('mor.username');
        $this->password         = config('mor.password');
        $this->timezone         = config('mor.timezone');
        $this->timeout         = config('mor.timeout');
        $this->hash_checking    = config('mor.hash_checking');

        $this->client = new Client([
            'base_uri'  => sprintf('%s/billing/api', rtrim($this->api_url, '/')),
            'timeout'   => $this->timeout
        ]);
    }

    /**
     * @param string $path
     * @param array $data
     * @param array $hashParamsKeys
     * @param string|null $username
     * @param string|null $password
     * @return mixed
     */
    public function submitRequest(
        string $path,
        array $data = [],
        array $hashParamsKeys = [],
        string $username = null,
        string $password = null
    ): mixed {
        $params = $this->buildRequestParams($data, $hashParamsKeys);

        $response = $this->client->post($path, [
            'query' => $params,
            'headers' => $this->getRequestHeaders(),
            'http_errors' => true,
            'verify' => false
        ]);

        return $response->getBody();
    }

    /**
     * @param array $data
     * @param array $hashParamsKeys
     * @return array
     */
    public function buildRequestParams(array $data, array $hashParamsKeys): array
    {
        $params = array_merge($data, [
            'u' => $this->username,
            'p' => $this->password
        ]);

        if ($this->hash_checking) {
            $params['hash'] = $this->constructRequestHash($params, $hashParamsKeys);
        }

        return $params;
    }

    /**
     * @param array $params
     * @param array $hashParamsKeys
     * @return string sha1 hash
     */
    protected function constructRequestHash(array $params, array $hashParamsKeys): string
    {
        $hashStringValues = array_filter(
            $hashParamsKeys,
            fn ($paramKey) => in_array($paramKey, array_keys($params))
        );

        array_push($hashStringValues, $this->api_secret_key);

        return sha1(implode($hashStringValues));
    }

    /**
     * @param string $response
     * @return array
     */
    protected function parseMorResponse(string $response)
    {
        return XmlToArray::convert($response);
    }

    /**
     * @return array
     */
    protected function getRequestHeaders()
    {
        return [];
    }
}
