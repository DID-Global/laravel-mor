<?php 

namespace MOR\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string checkDID($did, $client_id)
 * @method static string getServiceSetup($price1, $price2)
 * @method static string getDIDs($client_id)
 * @method static bool checkBlocked($client_id)
 * @method static string getID($client_id)
 * @method static string getDevice($client_id)
 * @method static string getBalance($client_id)
 * @method static string unblock($client_id)
 * @method static string addDIDRates($did, $rate)
 * @method static string getDevices($user_id)
 * @method static string assignDID($did, $device_id)
 * @method static string unassignDID($did)
 * @method static string userRegister($email, $id, $device_type, $username, $first_name, $last_name, $password, $password2, $country_id, $client_id)
 * @method static string paymentCreate($client_id, $amount, $transaction_id)
 * @method static string createDID($client_id, $did, $provider, $rate)
 * @method static string addDID($client_id, $did, $provider, $setup, $monthly, $rate)
 * @method static string createSubscription($client_id, $service_id, $memo)
 * @method static string createSubscriptionMonth($client_id, $service_id, $memo)
 * @method static string didTerminate($did)
 * @method static string morRequest($host, $data = [], $useHash = true, $hashKeys)
 * @method static string sendRequest($data, $host = false)
 * @method static string getUserDetails($user_id)
 * @method static string getUsers()
 * @method static string getListEmails()
 * @method static string getListWHMCSUsersIDs()
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
	protected static function getFacadeAccessor() { return 'mor'; }
}