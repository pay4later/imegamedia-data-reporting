<?php

namespace Imega\DataReporting\Repositories;

use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Imega\DataReporting\Models\RollUp\AcceptanceRate;
use Imega\DataReporting\Models\Angus\CsnAudit;

final class AcceptanceRateRepository
{
    /**
     * Gets the average acceptance rates between two dates
     *
     * @param CarbonInterface $startDate
     * @param CarbonInterface $endDate
     * @param int|null $clientId
     * @return Collection
     */
    public function getAcceptanceRateByDate
    (
        CarbonInterface $startDate,
        CarbonInterface $endDate,
        ?int $clientId = null,
    ): Collection
    {
        $qb =  AcceptanceRate::query()
            ->select([
                'finance_provider_id'
            ])
            ->with(['financeProvider' => function($query) {
                $query->select('id', 'alias');
            }])
            ->selectRaw('SUM(total_unique_accepted_csns) as total_accepted_csns')
            ->selectRaw('SUM(total_unique_declined_csns) as total_declined_csns')
            ->whereBetween('sampled_at', [$startDate, $endDate]);

        if ($clientId) {
            $qb->where('client_id', $clientId);
        }

        $qb->groupBy('finance_provider_id');

        return $qb->get()->map(static function (AcceptanceRate $row) {
            $row->acceptance_rate = $row->calculateAcceptedRatePercentage($row->total_accepted_csns, $row->total_declined_csns);
            return $row;
        });
    }
}
