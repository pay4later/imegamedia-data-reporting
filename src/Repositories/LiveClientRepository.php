<?php

namespace Imega\DataReporting\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Carbon\CarbonInterface;
use Imega\DataReporting\Models\RollUp\LiveClient;

final class LiveClientRepository
{
    /**
     * Get a list of all active clients counts by date filter.
     *
     * @param CarbonInterface $date
     * @return Collection
     */
    public function getActiveClientsByDate
    (
        CarbonInterface $date,
    ): Collection
    {
        return $this->liveClientQueryBuilder($date)->addSelect('total_active')->get();
    }

    /**
     * Get a list of active live clients counts by date filter.
     *
     * @param CarbonInterface $date
     * @return Collection
     */
    public function getActiveLiveClientsByDate
    (
        CarbonInterface $date,
    ): Collection
    {
        return $this->liveClientQueryBuilder($date)->addSelect('total_active_live')->get();
    }

    /**
     * Get a list of active test clients counts by date filter.
     *
     * @param CarbonInterface $date
     * @return Collection
     */
    public function getActiveTestClientsByDate
    (
        CarbonInterface $date,
    ): Collection
    {
        return $this->liveClientQueryBuilder($date)->addSelect('total_active_test')->get();
    }

    /**
     * Get a list of active test clients counts by date filter.
     *
     * @param CarbonInterface $date
     * @return Builder
     */
    private function liveClientQueryBuilder
    (
        CarbonInterface $date,
    ): Builder
    {
        return LiveClient::query()
            ->select([
            'finance_provider_id',
            'sampled_at',
            'finance_providers.alias AS finance_provider_alias'
            ])
            ->join('finance_providers', 'finance_provider_id', 'finance_providers.id')
            ->where('sampled_at', $date)
            ->orderBy('finance_provider_id','DESC');
    }
}
