<?php

namespace Imega\DataReporting\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Carbon\CarbonInterface;
use Imega\DataReporting\Models\RollUp\LiveClient;

final class LiveClientRepository
{
    /**
     * Get a list of all live clients counts by date filter.
     *
     * @param CarbonInterface $date
     * @param string|null $field
     * @return Collection
     */
    public function getLiveClientsByDate
    (
        CarbonInterface $date,
        string          $field = null
    ): Collection
    {
        $columns = match ($field) {
            'total_active' => ['total_active'],
            'total_active_live' => ['total_active_live'],
            'total_active_test' => ['total_active_test'],
            default => ['total_active', 'total_active_live', 'total_active_test'],
        };

        return $this->liveClientQueryBuilder($date)->addSelect($columns)->get();
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
            ])
            ->with(['financeProvider' => function ($query) {
                $query->select('id', 'alias');
            }])
            ->where('sampled_at', $date)
            ->orderBy('finance_provider_id', 'DESC');
    }
}
