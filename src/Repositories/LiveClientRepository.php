<?php

namespace Imega\DataReporting\Repositories;

use Illuminate\Support\Collection;
use Carbon\CarbonInterface;
use Imega\DataReporting\Models\RollUp\LiveClient;

final class LiveClientRepository
{
    /**
     * Get a list of live clients counts by date filter.
     *
     * @param CarbonInterface $date
     * @return Collection
     */
    public function getLiveClientsByDate(
        CarbonInterface $date,
    ): Collection
    {
        return LiveClient::query()
            ->select(['finance_provider_id', 'total_active', 'total_billable', 'total_inactive', 'total_active_live', 'total_active_nodemo_live', 'total_active_nodemo_test', 'sampled_at'])
            ->where('sampled_at', $date)
            ->orderBy('sampled_at','DESC')->get();
    }
}
