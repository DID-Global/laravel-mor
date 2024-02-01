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
    protected $apiUrl;

    /**
     * @var string
     */
    protected $apiSecretKey;

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
    protected $hashChecking;

    /**
     * Instantiate a new instance
     */
    public function __construct()
    {
        $this->apiUrl          = config('mor.url');
        $this->apiSecretKey    = config('mor.secret_key');
        $this->username        = config('mor.username');
        $this->password        = config('mor.password');
        $this->timezone        = config('mor.timezone');
        $this->timeout         = config('mor.timeout');
        $this->hashChecking    = config('mor.hash_checking');

        $this->client = new Client([
            'base_uri'  => sprintf('%s/billing/api/', rtrim($this->apiUrl, '/')),
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
        array $hashParamKeys = [],
        string $username = null,
        string $password = null
    ): mixed {
        $params = $this->buildRequestParams($data, $hashParamKeys);

        $response = $this->client->post($path, [
            'query' => $params,
            'headers' => $this->getRequestHeaders(),
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
    public function buildRequestParams(array $data, array $hashParamKeys): array
    {
        $params = array_merge([
            'u' => $this->username,
            'p' => $this->password
        ], $data);

        if ($this->hashChecking) {
            $params['hash'] = $this->constructRequestHash($params, $hashParamKeys);
        }

        return $params;
    }

    /**
     * @param array $params
     * @param array $hashParamKeys
     * @return string sha1 hash
     */
    protected function constructRequestHash(array $params, array $hashParamKeys): string
    {
        $hashParams = [];

        foreach ($hashParamKeys as $paramKey) {
            if (in_array($paramKey, array_keys($params))) {
                array_push($hashParams, $params[$paramKey]);
            }
        }

        array_push($hashParams, $this->apiSecretKey);

        return sha1(implode($hashParams));
    }

    /**
     * @param string $response
     * @return array
     */
    protected function parseResponse(string $response): array
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
