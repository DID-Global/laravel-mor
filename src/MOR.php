<?php

namespace MOR;

use ExtPHP\XmlToJson\XmlToJsonConverter;

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
     * @param $did
     * @param $client_id
     * @return string
     */
    public function checkDID($did, $client_id)
    {
        $data = [
            'cmd'       => 'check_did',
            'auth'      => $this->getDatum(),
            'did'       => $did,
            'client_id' => $client_id,
        ];

        return $this->submitRequest($data);
    }

    /**
     * @param $price1
     * @param $price2
     * @return string
     */
    public function getServiceSetup($price1, $price2)
    {
        $data = [
            'cmd'       => 'get_service_setup',
            'auth'      => $this->getDatum(),
            'price1'    => $price1,
            'price2'    => $price2,
        ];

        return $this->submitRequest($data);
    }

    /**
     * @param $client_id
     * @return string
     */
    public function getDIDs($client_id)
    {
        $data = [
            'cmd'       => 'get_dids',
            'auth'      => $this->getDatum(),
            'client_id' => $client_id,
        ];

        return $this->submitRequest($data);
    }

    /**
     * True if blocked
     *
     * @param $client_id
     * @return bool
     */
    public function checkBlocked($client_id)
    {
        $data = [
            'cmd'       => 'check_blocked',
            'auth'      => $this->getDatum(),
            'client_id' => $client_id,
        ];

        return (int) $this->submitRequest($data) === 1;
    }

    /**
     * @param $client_id
     * @return string
     */
    public function getID($client_id)
    {
        $data = [
            'cmd'       => 'get_id',
            'auth'      => $this->getDatum(),
            'client_id' => $client_id,
        ];

        return $this->submitRequest($data);
    }

    /**
     * @param $client_id
     * @return string
     */
    public function getDevice($client_id)
    {
        $data = [
            'cmd'       => 'get_device',
            'auth'      => $this->getDatum(),
            'client_id' => $client_id,
        ];

        return $this->submitRequest($data);
    }

    /**
     * @param $client_id
     * @return string
     */
    public function getBalance($client_id)
    {
        $data = [
            'cmd'       => 'get_balance',
            'auth'      => $this->getDatum(),
            'client_id' => $client_id,
        ];

        return $this->submitRequest($data);
    }

    /**
     * @param $client_id
     * @return string
     */
    public function unblock($client_id)
    {
        $data = [
            'cmd'       => 'unblock',
            'auth'      => $this->getDatum(),
            'client_id' => $client_id,
        ];

        return $this->submitRequest($data);
    }

    /**
     * @param $did
     * @param $rate
     * @return string
     */
    public function addDIDRates($did, $rate)
    {
        $data = [
            'cmd'       => 'add_did_rates',
            'auth'      => $this->getDatum(),
            'did'       => $did,
            'rate'      => $rate,
        ];

        return $this->submitRequest($data);
    }

    /**
     * @param $user_id
     * @return mixed
     */
    public function getDevices($user_id)
    {
        $params = ['user_id' => $user_id];
        $rez = $this->morRequest('/api/devices_get', $params, true, ['user_id']);
        return $rez;
    }

    /**
     * @param $did
     * @param $device_id
     * @return mixed
     */
    public function assignDID($did, $device_id)
    {
        $params = ['device_id' => $device_id, 'did' => $did];
        $rez = $this->morRequest('/api/did_device_assign', $params, true, ['device_id','did']);
        return $rez;
    }

    /**
     * @param $did
     * @return mixed
     */
    public function unassignDID($did)
    {
        $params = ['did' => $did];
        $rez = $this->morRequest('/api/did_device_unassign', $params, true, ['did']);
        return $rez;
    }

    /**
     * @param $email
     * @param $id
     * @param $device_type
     * @param $username
     * @param $first_name
     * @param $last_name
     * @param $password
     * @param $password2
     * @param $country_id
     * @param $client_id
     * @return mixed
     */
    public function userRegister($email, $id, $device_type, $username, $first_name, $last_name, $password, $password2, $country_id, $client_id)
    {
        $params = [
            'email' => $email, 'id' => $id, 'device_type' => $device_type, 'username' => $username,
            'first_name' => $first_name, 'last_name' => $last_name, 'password' => $password,
            'password2' => $password2, 'country_id' => $country_id, 'accounting_number' => $client_id
        ];
        $rez = $this->morRequest('/api/user_register', $params, true, ['email', 'id', 'device_type', 'first_name', 'last_name', 'password', 'password2', 'country_id']);
        return $rez;
    }

    /**
     * @param $client_id
     * @param $amount
     * @param $transaction_id
     * @return mixed|string
     */
    public function paymentCreate($client_id, $amount, $transaction_id)
    {
        $user_id = $this->getID($client_id);
        if ((int) $user_id === 0) {
            return 'error';
        }
        $params = ['user_id' => $user_id, 'p_currency' => 'USD', 'amount' => $amount, 'transaction' => $transaction_id];
        $rez = $this->morRequest('/api/payment_create', $params, true, ['user_id', 'p_currency', 'amount', 'transaction']);
        return $rez;
    }

    /**
     * @param $client_id
     * @param $did
     * @param $provider
     * @param $rate
     * @return mixed
     */
    public function createDID($client_id, $did, $provider, $rate)
    {
        $user_id = $this->getID($client_id);
        $params = ['u' => 'admin', 'did' => $did, 'provider_id' => $provider];
        $rez = $this->morRequest('/api/did_create', $params, true, ['u', 'did', 'provider_id']);

        $data = [
            'cmd'       => 'assign_did',
            'auth'      => $this->getDatum(),
            'did'       => $did,
            'rate'      => $rate,
            'user_id'   => $user_id,
        ];

        $this->submitRequest($data);

        return $rez;
    }

    /**
     * @param $client_id
     * @param $did
     * @param $provider
     * @param $setup
     * @param $monthly
     * @param $rate
     */
    public function addDID($client_id, $did, $provider, $setup, $monthly, $rate)
    {
        $this->createDID($client_id, $did, $provider, $rate);
        $device_id = $this->getDevice($client_id);
        $this->unassignDID($did);
        $this->assignDID($did, $device_id);
    }

    /**
     * @param $client_id
     * @param $service_id
     * @param $memo
     * @return mixed
     */
    public function createSubscription($client_id, $service_id, $memo)
    {
        $user_id = $this->getID($client_id);
        $params = ['service_id' => $service_id, 'user_id' => $user_id, 'subscription_memo' => $memo, 'subscription_until_canceled' => 1];
        $rez = $this->morRequest('/api/subscription_create', $params, true, ['service_id', 'user_id']);
        return $rez;
    }

    /**
     * @param $client_id
     * @param $service_id
     * @param $memo
     * @return mixed
     */
    public function createSubscriptionMonth($client_id, $service_id, $memo)
    {
        $user_id = $this->getID($client_id);
        $activation_start = strtotime('first day of next month');
        $params = [
            'service_id' => $service_id, 'user_id' => $user_id, 'subscription_activation_start' => $activation_start,
            'subscription_memo' => $memo, 'subscription_until_canceled' => 1
        ];
        $rez = $this->morRequest('/api/subscription_create', $params, true, ['service_id', 'user_id']);
        return $rez;
    }

    /**
     * @param $did
     * @return array
     */
    public function didTerminate($did)
    {
        $response = $this->unassignDID($did);

        $data = [
            'cmd'       => 'did_terminate',
            'auth'      => $this->getDatum(),
            'did'       => $did,
        ];

        $responseT = $this->submitRequest($data);

        return [
            'unassign' => $response,
            'terminate' => $responseT,
        ];
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
    public function getListEmails()
    {
        $data = [
            'cmd'       => 'get_list_emails',
            'auth'      => $this->getDatum(),
        ];

        $response = $this->submitRequest($data);

        return json_decode($response, true);
    }

    /**
     * @return mixed
     */
    public function getListWHMCSUsersIDs()
    {
        $data = [
            'cmd'       => 'get_list_users_whmcs_ids',
            'auth'      => $this->getDatum(),
        ];

        $response = $this->submitRequest($data);

        return json_decode($response, true);
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
     * @param string $response
     * @return array
     */
    protected function parseMorResponse(string $response)
    {
        $result = $this->convertXmlToJson($response);
        $this->removeValueKey($result);

        return $result;
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
     * @param string $xml
     * @return array
     */
    protected function convertXmlToJson(string $xml)
    {
        $converter = new XmlToJsonConverter(simplexml_load_string($xml));
        return $converter->toArray();
    }

    /**
     * @return array
     */
    protected function getRequestHeaders()
    {
        return [];
    }
}
