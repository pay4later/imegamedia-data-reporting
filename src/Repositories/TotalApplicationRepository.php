<?php

namespace Imega\DataReporting\Repositories;

use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Imega\DataReporting\Models\RollUp\TotalApplication;

final class TotalApplicationRepository
{
    /**
     * Gets the amount of applications between two dates
     *
     * @param CarbonInterface $startDate
     * @param CarbonInterface $endDate
     * @param int|null $clientId
     * @return Collection
     */
    public function getApplicationCountQuery
    (
        CarbonInterface $startDate,
        CarbonInterface $endDate,
        ?int $clientId = null,
    ): Collection
    {
        $qb =  TotalApplication::query()
            ->select([
                'finance_provider_id',
                'finance_providers.alias AS finance_providers_alias',
            ])
            ->join('finance_providers', 'finance_provider_id', 'finance_providers.id')
            ->selectRaw('SUM(count) as application_count')
            ->whereBetween('sampled_at', [$startDate, $endDate])
            ->groupBy('finance_provider_id', 'finance_providers.alias');

        if ($clientId) {
            $qb->whereIn('client_id', $clientId);
        }

        return $qb->get();
    }

    /**
     * Gets the value of applications between two dates
     *
     * @param CarbonInterface $startDate
     * @param CarbonInterface $endDate
     * @return Collection
     */
    public function getApplicationValueQuery
    (
        CarbonInterface $startDate,
        CarbonInterface $endDate,
    ): Collection
    {
        $qb =  TotalApplication::query()
            ->select([
                'finance_provider_id',
                'finance_providers.alias AS finance_providers_alias',
            ])
            ->join('finance_providers', 'finance_provider_id', 'finance_providers.id')
            ->selectRaw('SUM(value) as application_value')
            ->whereBetween('sampled_at', [$startDate, $endDate])
            ->groupBy('finance_provider_id', 'finance_providers.alias');

        return $qb->get();
    }
}
