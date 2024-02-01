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
    public function getUserDetails(int|string $userId): mixed
    {
        $params = [];
        $params[is_numeric($userId) ? 'user_id' : 'username'] = $userId;

        return $this->submitRequest(
            'user_details',
            $params,
            [is_numeric($userId) ? 'user_id' : 'username']
        );
    }

    /**
     * @return mixed
     */
    public function getUsers(): mixed
    {
        return $this->submitRequest('users_get', [], ['u', 'p']);
    }

    /**
     * Retrieve DIDs based on specified parameters.
     *
     * @param array $params {
     *     An associative array of parameters.
     *
     *     @var string|null $search_did_number            Search by DID number.
     *     @var string|null $search_did_owner             Search by DID owner.
     *     @var string|null $search_dialplan              Search by dialplan.
     *     @var string|null $search_user                  Search by user ID.
     *     @var string|null $search_device                Search by device.
     *     @var string|null $search_provider              Search by provider.
     *     @var string|null $search_language              Search by language.
     *     @var int|null    $search_hide_terminated_dids  Hide terminated DIDs if '1'.
     *     @var int|null    $max_results                  Maximum number of results to retrieve.
     *     @var string|null $from                         Specify a starting point for the search.
     * }
     *
     * @return mixed The result of the request.
     */
    public function getDIDs(array $params = []): mixed
    {
        return $this->submitRequest('dids_get');
    }
}
