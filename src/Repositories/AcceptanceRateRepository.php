<?php

namespace Imega\DataReporting\Repositories;

use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Imega\DataReporting\Models\RollUp\AcceptanceRate;

final class AcceptanceRateRepository
{
    /**
     * Gets the average acceptance rates between two dates
     *
     * @param CarbonInterface $startDate
     * @param CarbonInterface $endDate
     * @return Collection
     */
    public function getAcceptanceRateByDate
    (
        CarbonInterface $startDate,
        CarbonInterface $endDate,
    ): Collection
    {
        return AcceptanceRate::query()
            ->select([
                'finance_providers.id AS finance_providers_id',
                'finance_providers.alias AS finance_providers_alias',
            ])
            ->join('finance_providers', 'finance_provider_id', 'finance_providers.id')
            ->selectRaw('AVG(acceptance_rate) as average_acceptance_rate')
            ->whereBetween('sampled_at', [$startDate, $endDate])
            ->groupBy('finance_provider_id', 'finance_providers_id', 'finance_providers_alias')
            ->get();
    }
}
