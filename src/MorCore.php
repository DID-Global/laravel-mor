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
        $this->timeout          = config('mor.timeout');
        $this->hash_checking    = config('mor.hash_checking');

        $this->client = new Client([
            'base_uri'  => sprintf('%s/billing/api/', rtrim($this->api_url, '/')),
            'timeout'   => $this->timeout
        ]);
    }

    /**
     * @param string $path
     * @param array $data
     * @param array $hashParamKeys
     * @param string|null $username
     * @param string|null $password
     * @return mixed
     */
    public function submitRequest(
        string $path,
        array $data = [],
        array $hashParamKeys = []
    ): mixed {
        $params = $this->buildRequestParams($data, $hashParamKeys);
        $headers = $this->getRequestHeaders();

        $response = $this->client->post(ltrim($path, '/'), [
            'query' => $params,
            'headers' => $headers,
            'http_errors' => true,
            'verify' => false
        ]);

        $responseContent = $response->getBody()->getContents();

        return $this->parseResponse($responseContent);
    }

    /**
     * @param array $data
     * @param array $hashParamKeys
     * @return array
     */
    protected function buildRequestParams(
        array $data,
        array $hashParamKeys
    ): array {
        $params = array_merge([
            'u' => $this->username,
            'p' => $this->password
        ], $data);

        if ($this->hash_checking) {
            $params['hash'] = $this->constructHashParam($params, $hashParamKeys);
        }

        return $params;
    }

    /**
     * @param array $params
     * @param array $hashParamKeys
     * @return string sha1 hash
     */
    protected function constructHashParam(
        array $params,
        array $hashParamKeys
    ): string {
        $hashParams = [];

        foreach ($hashParamKeys as $paramKey) {
            if (in_array($paramKey, array_keys($params))) {
                array_push($hashParams, $params[$paramKey]);
            }
        }

        array_push($hashParams, $this->api_secret_key);

        return sha1(implode($hashParams));
    }

    /**
     * @param string $response
     * @return array
     */
    protected function parseResponse(string $response)
    {
        return XmlToArray::convert($response);
    }

    /**
     * @return array
     */
    protected function getRequestHeaders(): array
    {
        return [];
    }
}
