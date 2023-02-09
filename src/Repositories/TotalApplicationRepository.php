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
    public function getTotalApplicationQuery
    (
        CarbonInterface $startDate,
        CarbonInterface $endDate,
        ?int $clientId = null,
    ): Collection
    {
        $qb = TotalApplication::query()
            ->select([
                'finance_provider_id',
            ])
            ->with(['financeProvider' => function($query) {
                $query->select('id', 'alias');
            }])
            ->selectRaw('SUM(count) as application_count')
            ->selectRaw('SUM(value) as application_value')
            ->whereBetween('sampled_at', [$startDate, $endDate])
            ->groupBy('finance_provider_id');

        if ($clientId) {
            $qb->where('client_id', $clientId);
        }

        return $qb->get();
    }
}
