<?php

namespace MOR\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string morRequest($host, $data = [], $useHash = true, $hashKeys = [], $username = null, $password = null)
 * @method static string sendRequest($data, $host = false)
 * @method static string getUserDetails($user_id)
 * @method static string getUsers()
 * @method static string getDIDs()
 *
 * @see \MOR\MOR
 * @see \MOR\MorCore
 * @see \MOR\Facades\MOR
 */
class MOR extends Facade
{
    /**
     * Get the registered name of the component
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'mor';
    }
}
