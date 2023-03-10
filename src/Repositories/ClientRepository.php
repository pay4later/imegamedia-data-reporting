<?php

namespace Imega\DataReporting\Repositories;

use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Imega\DataReporting\Models\Angus\Client;

final class ClientRepository
{
    /**
     * Fetches a list of live client counts from the Angus system, used for daily report
     *
     * @return Collection
     */
    public function getLiveClientCounts(): Collection
    {
        $qb = Client::query()
            ->from('clients', $tableAlias = 'c1')
            ->select($tableAlias . '.finance_provider AS finance_provider_id')
            ->selectRaw('"' . Carbon::now()->format('Y-m-d 00:00:00') . '" AS sampled_at')
            ->selectRaw('COUNT(' . $tableAlias . '.id) AS total_active')
            ->active($tableAlias)
            ->whereNull($tableAlias . '.deleted_at')
            ->groupBy($tableAlias . '.finance_provider');

        $whereSecondColumn = $tableAlias . '.finance_provider';

        $qb
            ->selectSub(Client::totalBillable($whereSecondColumn), 'total_billable')
            ->selectSub(Client::totalInactive($whereSecondColumn), 'total_inactive')
            ->selectSub(Client::totalActiveLive($whereSecondColumn), 'total_active_live')
            ->selectSub(Client::totalActiveTest($whereSecondColumn), 'total_active_test')
            ->selectSub(Client::totalActiveNoDemoLive($whereSecondColumn), 'total_active_nodemo_live')
            ->selectSub(Client::totalActiveNoDemoTest($whereSecondColumn), 'total_active_nodemo_test');

        return $qb->get();
    }

    /**
     * Fetches a list of live clients by provider
     *
     * @return Collection
     */
    public function getClientsByProviderCount(): Collection
    {
        return Client::query()
        ->select([
            'finance_providers.id AS finance_provider_id',
            'finance_providers.name AS finance_provider_name',
            'finance_providers.alias AS finance_provider_alias',
        ])
        ->join('finance_providers', 'clients.finance_provider', 'finance_providers.id')
        ->selectRaw('COUNT(clients.id) as client_count')
        ->active()
        ->live()
        ->whereNull('deleted_at')
        ->groupBy('finance_provider')
        ->get();
    }

    /**
     * Fetches a list of new clients within a date range.
     *
     * @param CarbonInterface $startDate
     * @param CarbonInterface $endDate
     * @return array
     */
    public function getNewLiveGroupCounts
    (
        CarbonInterface $startDate,
        CarbonInterface $endDate,
    ): array
    {
        $lenders = [];

        $clients = Client::query()
            ->select([
                'clients.name AS client_name',
                'finance_providers.name AS finance_provider_name',
                'finance_providers.alias AS finance_provider_alias',
            ])
            ->join('finance_providers', 'clients.finance_provider', 'finance_providers.id')
            ->whereBetween('clients.created_at', [$startDate, $endDate])
            ->live()
            ->get();

        foreach ($clients as $client) {
            if (!isset($lenders[$client->finance_provider_name])) {
                $lenders[$client->finance_provider_name] = [
                    'alias'   => $client->finance_provider_alias,
                    'clients' => [],
                ];
            }

            $lenders[$client->finance_provider_name]['clients'][] = $client->client_name;
            $lenders[$client->finance_provider_name]['count'] = count($lenders[$client->finance_provider_name]['clients']);
        }

        return ['lenders' => $lenders, 'count' => count($clients)];
    }

    /**
     * Fetches a list of merchants with merchant sites.
     *
     * @param array|null $financeProviderIds
     * @param int|null $ecommercePlatform
     * @param int|null $testMode
     * @param string|null $licenceStatus
     * @return array
     */
    public function getMerchantsAndMerchantSites
    (
        ?array  $financeProviderIds = null,
        ?int    $ecommercePlatform = null,
        ?int    $testMode = null,
        ?string $licenceStatus = null
    ): array
    {
        $response = [];
        $clients = Client::query()
            ->select([
                'merchant_sites.merchant_id AS merchant_id',
                'merchant_sites.name AS merchant_site_name',
                'merchants.name AS merchant_name',
                'merchants.created_at AS merchant_created',
                'clients.merchant_site_id AS merchant_site_id',
                'clients.id AS client_id',
                'clients.name AS  client_name',
                'clients.test_mode AS  client_test_mode',
                'clients.licence_status AS  client_licence_status',
                'finance_providers.name AS  finance_provider_name',
                'ecommerce_platforms.name AS  ecommerce_platform_name',
            ])
            ->join('merchant_sites', 'clients.merchant_site_id', 'merchant_sites.id')
            ->join('merchants', 'merchant_sites.merchant_id', 'merchants.id')
            ->join('finance_providers', 'clients.finance_provider', 'finance_providers.id')
            ->join('ecommerce_platforms', 'clients.ecommerce_platform_id', 'ecommerce_platforms.id')
            ->whereNotNull('merchant_sites.merchant_id')
            ->whereNotNull('merchant_site_id')
            ->live();


        if ($financeProviderIds) {
            $clients->whereIn('finance_provider', $financeProviderIds);
        }

        if ($ecommercePlatform) {
            $clients->where('clients.ecommerce_platform_id', $ecommercePlatform);
        }

        if (!is_null($testMode)) {
            $clients->where('clients.test_mode', $testMode);
        }

        if ($licenceStatus) {
            $clients->where('clients.licence_status', $licenceStatus);
        }

        $clients = $clients->orderBy('merchant_sites.merchant_id')->get();

        foreach ($clients as $client) {
            if (!isset($response[$client->merchant_id]['merchant_name'])) {
                $response[$client->merchant_id] = [
                    'merchant_name' => $client->merchant_name,
                    'merchant_created' => $client->merchant_created
                ];
            }
            if (!isset($response[$client->merchant_id]['merchant_sites'][$client->merchant_site_id])) {
                $response[$client->merchant_id]['merchant_sites'][$client->merchant_site_id] = [
                    'website_name' => $client->merchant_site_name,
                ];
            }

            $response[$client->merchant_id]['merchant_sites'][$client->merchant_site_id]['clients'][$client->client_id] = [
                'client_name'        => $client->client_name,
                'finance_provider'   => $client->finance_provider_name,
                'ecommerce_platform' => $client->ecommerce_platform_name,
                'test_mode'          => $client->client_test_mode,
                'licence_status'     => $client->client_licence_status,
            ];
        }

        return $response;
    }
}
