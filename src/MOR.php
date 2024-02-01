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
     * Retrieve user information based on specified parameters.
     *
     * @param array $params {
     *     An associative array of parameters.
     *
     *     @var int|null    $user_id            Search by user ID. Required if username is not used
     *     @var string|null $username           Search by username. Required if user_id is not used.
     * }
     *
     * @return mixed
     */
    public function getUserDetails(array $params): mixed
    {
        return $this->submitRequest(
            'user_details_get',
            $params,
            ['user_id', 'username']
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
     *     @var int|null    $search_did_number            Search by DID number.
     *     @var int|null    $search_dialplan              Search by dialplan ID.
     *     @var int|null    $search_user                  Search by user ID.
     *     @var int|null    $search_device                Search by device ID.
     *     @var int|null    $search_provider              Search by provider ID.
     *     @var int|null    $search_hide_terminated_dids  Hide terminated DIDs if '1'.
     *     @var string|null $search_did_owner             Search by DID owner.
     *     @var string|null $search_language              Word which is used in DIDs configuration as language.
     *     @var int|null    $max_results                  Maximum number of results to retrieve.
     *     @var int|null    $from                         Specify a starting point for the search.
     * }
     *
     * @return mixed
     */
    public function getDIDs(array $params = []): mixed
    {
        return $this->submitRequest('dids_get', $params);
    }
}
