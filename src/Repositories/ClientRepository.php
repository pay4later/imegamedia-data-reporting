<?php

namespace Imega\DataReporting\Repositories;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Imega\DataReporting\Models\Angus\Client;

final class ClientRepository
{
    /**
     * Fetches a list of live client counts from the Angus system
     *
     * @return Collection
     */
    public function getLiveClientCounts(): Collection
    {
        $qb = Client::query()
            ->from('clients', $tableAlias = 'c1')
            ->select($tableAlias . '.finance_provider AS finance_provider_id')
            ->selectRaw("DATE_FORMAT(NOW(),'%Y-%m-%d 00:00:00') AS sampled_at")
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
     * Fetches a list of new clients within a date range.
     *
     * @param CarbonInterface|null $startDate
     * @param CarbonInterface|null $endDate
     * @return array
     */
    public function getNewLiveGroupCounts
    (
        ?CarbonInterface $startDate = null,
        ?CarbonInterface $endDate = null,
    ): array
    {
        $startDate ??= Carbon::now()->subMonths()->setTime(0, 0);
        $endDate ??= Carbon::now()->setTime(23, 59, 59);
        $response = [];

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
            if (!isset($response[$client->finance_provider_name])) {
                $response[$client->finance_provider_name] = [
                    'alias' => $client->finance_provider_alias,
                    'clients' => [],
                ];
            }

            $response[$client->finance_provider_name]['clients'][] = $client->client_name;
        }
        $response['count'] = count($clients);

        return $response;
    }
}
