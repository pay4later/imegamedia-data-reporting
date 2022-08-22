<?php

namespace Imega\DataReporting\Repositories;

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
            ->select($tableAlias.'.finance_provider_id')
            ->selectRaw("DATE_FORMAT(NOW(),'%Y-%m-%d %H:00:00') as sampled_at")
            ->selectRaw('0 AS acceptance_rate')
            ->groupBy([$tableAlias.'.finance_provider_id']);

        $whereSecondColumn = $tableAlias . '.finance_provider_id';
        $qb
            ->selectSub(CsnAudit::totalUniqueCsnsInLastHour($whereSecondColumn), 'total_unique_csns')
            ->selectSub(CsnAudit::totalUniqueAcceptedCsnsInLastHour($whereSecondColumn), 'total_unique_accepted_csns');

        return $qb->get()->map(static function (CsnAudit $row) {
            $row->acceptance_rate = $row->calculateAcceptedRatePercentage($row->total_unique_accepted_csns, $row->total_unique_csns);
            return $row;
        });
    }
}
