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
     *     @var int|null    $user_id            Search by user ID. Required if username is not used.
     *     @var string|null $username           Search by username. Required if user_id is not used.
     * }
     *
     * @return mixed
     */
    public function getUserDetails(array $params = []): mixed
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

    /**
     * Retrieve user calls based on specified parameters.
     *
     * @param array $params {
     *     An associative array of parameters.
     *
     *     @var int|null    $period_start               Unix timestamp of calls period starting date. (Default: Today at 00:00).
     *     @var int|null    $period_end                 Unix timestamp of calls period end date. (Default: Today at 23:59).
     *     @var int|null    $s_reseller                 Reseller type User ID in MOR database. (Default: all).
     *     @var int|null    $s_user                     User's ID in MOR database. Required if s_reseller is not used.
     *     @var string|null $s_call_type                Call type. Possible values [all, answered, no answer, failed, busy]. (Default: all)
     *     @var int|null    $s_device                   Device ID in MOR database. Possible values [all, numeric value of device_id]. (Default: all).
     *     @var int|null    $s_provider                 Provider ID in MOR database. Possible values [all, numeric value of provider_id]. (Default: all). Only for Admin and Reseller PRO.
     *     @var int|null    $s_hgc                      Hangup cause code ID in MOR database. Possible values [all, numeric value of hangup_cause_code_id]. (Default: all). Only for Admin and Reseller if Show HGC for Resellers is ON.
     *     @var string|null $s_did                      Show calls made through a specific DID. Possible values [all, calls.did_id]. (Default: all). Only for Admin.
     *     @var string|null $s_destination              Prefix.
     *     @var string|null $order_by                   Possible values [time, src, dst, prefix, nice_billsec, hgc, server, p_name, p_rate, p_price, reseller, r_rate, r_price, user, u_rate, u_price, number, d_provider, d_inc, d_owner]. (Default: time).
     *     @var int|null    $order_desc                 Possible values [0,1]. (Default: 0).
     *     @var int|null    $only_did                   Show calls that only went through a DID. Possible values [0,1]. (Default: 0).
     *     @var int|null    $s_uniqueid                 Return a specific Call by uniqueid. Date parameters are ignored in this case.
     *     @var string|null $s_callback_uniqueid        Return Call(s) by callback uniqueid. Date parameters are ignored in this case. Only works when use_callback_uniqueid setting is enabled in mor.conf.
     *     @var string|null $originator_codec_name      Originator codec.
     *     @var string|null $terminator_codec_name      Terminator codec.
     * }
     *
     * @return mixed
     */
    public function getUserCalls(array $params): mixed
    {
        $hashParamKeys = [
            'period_start', 'period_end', 's_user',
            's_call_type', 's_device', 's_provider',
            's_hgc', 's_did', 's_destination',
            'order_by', 'order_desc', 'only_did',
            's_uniqueid', 'originator_codec_name', 'terminator_codec_name',
        ];

        return $this->submitRequest('user_calls_get', $params, $hashParamKeys);
    }
}
