<?php

namespace Imega\DataReporting\Repositories;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Imega\DataReporting\Models\Angus\CsnAudit;

final class CsnAuditRepository
{
    /**
     * Gets the acceptance rates for all finance providers within the last hour
     *
     * @return Collection
     */
    public function getLastHourAcceptanceRates(): Collection
    {
        $qb = CsnAudit::query()
            ->from('csn_audits', $tableAlias = 'ca1')
            ->select([$tableAlias . '.finance_provider_id', $tableAlias . '.client_id'])
            ->selectRaw('"' . Carbon::now()->format('Y-m-d H:00:00') . '" AS sampled_at')
            ->selectRaw('0 AS acceptance_rate')
            ->groupBy([$tableAlias . '.finance_provider_id', $tableAlias . '.client_id']);

        $qb
            ->selectSub(
                CsnAudit::selectRaw('COUNT(id)')
                    ->whereColumn('finance_provider_id', $tableAlias . '.finance_provider_id')
                    ->whereColumn('client_id', $tableAlias . '.client_id')
                    ->createdLastHour()
                    ->where(static fn($query) => $query
                        ->whereIn('id', CsnAudit::selectRaw('MAX(id)')->createdLastHour()->groupBy('order_id'))
                    ),
                'total_unique_csns'
            )
            ->selectSub(
                CsnAudit::selectRaw('COUNT(id)')
                    ->whereColumn('finance_provider_id', $tableAlias . '.finance_provider_id')
                    ->whereColumn('client_id', $tableAlias . '.client_id')
                    ->createdLastHour()
                    ->statusOptions([config('data-reporting.csn-statuses.APPROVED')]),
                'total_unique_accepted_csns'
            );

        $qb
            ->having('total_unique_csns', '>', 0)
            ->orHaving('total_unique_accepted_csns', '>', 0);

        return $qb->get()->map(static function (CsnAudit $row) {
            $row->acceptance_rate = $row->calculateAcceptedRatePercentage($row->total_unique_accepted_csns, $row->total_unique_csns);
            return $row;
        });
    }
}