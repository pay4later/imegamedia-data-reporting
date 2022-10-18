<?php

namespace Imega\DataReporting\Repositories;

use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Imega\DataReporting\Models\Angus\ApprovedApplication;

final class ApprovedApplicationRepository
{
    /**
     * Gets total approved applications between two dates
     *
     * @param CarbonInterface $startDate
     * @param CarbonInterface $endDate
     * @param int|null $clientId
     * @return Collection
     */
    public function getApprovedApplications
    (
        CarbonInterface $startDate,
        CarbonInterface $endDate,
        ?int $clientId = null,
    ): Collection
    {
        $qb =  ApprovedApplication::query()
            ->select([
                'finance_providers.id AS finance_providers_id',
                'finance_providers.alias AS finance_providers_alias',
            ])
            ->join('finance_providers', 'finance_provider_id', 'finance_providers.id')
            ->selectRaw('COUNT(*) as approved_application_count')
            ->whereBetween('approved_applications.created_at', [$startDate, $endDate]);

        if ($clientId) {
            $qb->where('client_id', $clientId);
        }

        return $qb->groupBy('finance_provider_id', 'finance_providers_id', 'finance_providers_alias')->get();
    }
}
