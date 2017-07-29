<?php

namespace MORINC;

class MORINC extends MorincCore {
    
    /**
     * Instantiate a new instance
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function validateLogin($email, $password)
    {
        $data = [
            'action'    => 'ValidateLogin',
            'email'     => $email,
            'password2' => $password,
        ];

        return $this->submitRequest($data);
    }

    /**
     * Return a list of all clients
     * 
     * @param int $start
     * @param int $limit
     * @param string $search
     * @return array
     */
    public function getClients($start = 0, $limit = 25, $search = null)
    {
        $data = [
            'action'        => 'GetClients',
            'limitstart'    => $start,
            'limitnum'      => $limit,
        ];

        if ($search) {
            $data['search'] = $search;
        }
        
        return $this->submitRequest($data);
    }

    /**
     * Returns the specified client's data
     * 
     * @param string|int $client_id
     * @param bool $stats
     * @return array
     */
    public function getClientDetails($client_id, $stats = false)
    {
        $data = [
            'action'    =>  'GetClientsDetails',
            'clientid'  =>  $client_id,
            'stats'     =>  $stats
        ];

        return $this->submitRequest($data);
    }

    /**
     * Returns the specified client's domains
     * 
     * @param string|int $client_id
     * @param int $start
     * @param int $limit
     * @return array
     */
    public function getClientDomains($client_id, $start = 0, $limit = 25)
    {
        $data = [
            'action'        =>  'GetClientsDomains',
            'clientid'      =>  $client_id,
            'limitstart'    =>  $start,
            'limitnum'      =>  $limit
        ];

        return $this->submitRequest($data);
    }

    /**
     * Return a list of a client's products
     * 
     * @param int $client_id
     * @param int $start
     * @param int $limit
     * @return array
     */
    public function getClientProducts($client_id, $start = 0, $limit = 25)
    {
        $data = [
            'action'        => 'GetClientsProducts',
            'clientid'      => $client_id,
            'limitstart'    =>  $start,
            'limitnum'      =>  $limit
        ];

        return $this->submitRequest($data);
    }

    /**
     * Creates a new client
     * 
     * @param array $data
     * @return array
     */
    public function createClient($data)
    {
        $data['action'] = 'AddClient';

        return $this->submitRequest($data);
    }

    /**
     * Execute command
     *
     * @param string $command Command name
     * @param array $data Parameters
     * @return array
     */
    public function executeCommand($command, $data)
    {
        $data['action'] = $command;

        return $this->submitRequest($data);
    }
}