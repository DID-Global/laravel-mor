<?php

namespace MOR;

use Mtownsend\XmlToArray\XmlToArray;

class MOR extends MorCore
{
    /**
     * Instantiate a new instance
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param int|string $user_id
     * @return mixed
     */
    public function getUserDetails($user_id)
    {
        $params = [];
        $params[is_numeric($user_id) ? 'user_id' : 'username'] = $user_id;

        $rez = $this->morRequest('/api/user_details', $params, true, [is_numeric($user_id) ? 'user_id' : 'username']);
        return $rez;
    }

    /**
     * @return mixed
     */
    public function getUsers()
    {
        $rez = $this->morRequest('/api/users_get', [], true);
        return $rez;
    }

    /**
     * @return mixed
     */
    public function getDIDs()
    {
        $rez = $this->morRequest('/api/dids_get', [], true, ['u']);
        return $rez;
    }

    /**
     * @param string $host
     * @param array $data
     * @param bool $useHash
     * @param array $hashKeys
     * @param string|null $username
     * @param string|null $password
     * @return mixed
     */
    public function morRequest($host, $data = [], $useHash = true, $hashKeys = [], $username = null, $password = null)
    {
        $req = $this->buildRequestData($data, $useHash, $hashKeys, $username, $password);
        $reqHost = sprintf('%s/billing%s', $this->api_url, $host);
        $response = $this->sendRequest($req, $reqHost);

        return $this->parseMorResponse($response);
    }

    /**
     * @param array $data
     * @param string|bool $host
     * @return mixed
     */
    public function sendRequest($data, $host = false)
    {
        $c = curl_init($host);

        $opts = [
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_FRESH_CONNECT => true,
        ];

        $headers = $this->getRequestHeaders();

        if (!empty($headers)) {
            $opts[CURLOPT_HTTPHEADER] = $headers;
        }

        $opts[CURLOPT_POSTFIELDS] = $data;
        $opts[CURLOPT_ENCODING] = 'gzip';

        curl_setopt_array($c, $opts);

        $ret = @curl_exec($c);
        if ($ret === false) {
            throw new \RuntimeException('cURL request failed: ' . curl_error($c));
        }

        curl_close($c);

        return $ret;
    }

    /**
     * @param array $data
     * @param bool $useHash
     * @param array $hashKeys
     * @param string|null $username
     * @param string|null $password
     * @return array
     */
    protected function buildRequestData($data, $useHash, $hashKeys, $username, $password)
    {
        $req = $data;

        if ($useHash) {
            $hash_string = $this->generateHashString($data, $hashKeys, $username, $password);
            $hash = sha1($hash_string);
            $req['hash'] = $hash;
        }

        $req['u'] = $username ?? $this->username;
        $req['p'] = $password ?? $this->password;

        return $req;
    }

    /**
     * @param array $data
     * @param array $hashKeys
     * @param string|null $username
     * @param string|null $password
     * @return string
     */
    protected function generateHashString($data, $hashKeys, $username, $password)
    {
        $hash_string = '';

        if (!empty($data)) {
            foreach ($hashKeys as $val) {
                if (in_array($val, array_keys($data))) {
                    $hash_string .= $data[$val];
                }
            }
        } else {
            $hash_string .= $username ?? $this->username;
            $hash_string .= $password ?? $this->password;
        }

        $hash_string .= $this->api_secret_key;

        return $hash_string;
    }

    /**
     * @param string $response
     * @return array
     */
    protected function parseMorResponse(string $response)
    {
        // $result = $this->convertXmlToJson($response);
        // $this->removeValueKey($result);

        return XmlToArray::convert($response);
    }

    /**
     * @param array $array
     */
    protected function removeValueKey(array &$array)
    {
        foreach ($array as $key => &$value) {
            if (is_array($value)) {
                $this->removeValueKey($value);
            }

            if (is_array($value) && array_key_exists('_value', $value)) {
                $value = $value['_value'];
            }
        }
    }

    /**
     * @return array
     */
    protected function getRequestHeaders()
    {
        return [];
    }
}
