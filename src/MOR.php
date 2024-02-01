<?php

namespace MOR;

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
    public function getUserDetails(int|string $user_id): mixed
    {
        $params = [];
        $params[is_numeric($user_id) ? 'user_id' : 'username'] = $user_id;

        $response = $this->submitRequest('user_details', $params, [is_numeric($user_id) ? 'user_id' : 'username']);
        return $response;
    }

    /**
     * @return mixed
     */
    public function getUsers(): mixed
    {
        $response = $this->submitRequest('users_get', [], ['u', 'p']);
        return $response;
    }

    /**
     * @return mixed
     */
    public function getDIDs(): mixed
    {
        $response = $this->submitRequest('dids_get');
        return $response;
    }
}
