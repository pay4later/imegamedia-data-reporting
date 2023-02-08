<?php

namespace Imega\DataReporting\Repositories;

use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Imega\DataReporting\Models\Angus\CsnAudit;

final class CsnAuditRepository
{
    private const COLUMN_TOTAL_UNIQUE_ACCEPTED_CSNS  = 'total_unique_accepted_csns';
    private const COLUMN_TOTAL_UNIQUE_DECLINED_CSNS  = 'total_unique_declined_csns';
    private const COLUMN_TOTAL_UNIQUE_COMPLETED_CSNS = 'total_unique_completed_csns';

    /**
     * Gets the acceptance rates for all finance providers within the last hour
     *
     * @return Collection
     */
    public function getLastHourAcceptanceRates(): Collection
    {
        return $this->getDateBetweenAcceptanceRates(
            Carbon::now()->subHours(),
            Carbon::now()
        );
    }

    /**
     * Gets the acceptance rates for all finance providers within the date range
     *
     * @param CarbonInterface $start
     * @param CarbonInterface $end
     * @return Collection
     */
    public function getDateBetweenAcceptanceRates(CarbonInterface $start, CarbonInterface $end): Collection
    {
        return CsnAudit::query()
            ->select(['csn_audits.finance_provider_id', 'csn_audits.client_id'])
            ->selectRaw('"' . $end->format('Y-m-d H:00:00') . '" AS sampled_at')
            ->selectRaw('SUM(CASE WHEN csn_audits.imega_status & ' . CsnAudit::getImegaFlagStatus(config('data-reporting.csn-statuses.DECLINED')) . ' THEN 1 ELSE 0 END) AS ' . self::COLUMN_TOTAL_UNIQUE_DECLINED_CSNS)
            ->selectRaw('SUM(CASE WHEN csn_audits.imega_status & ' . CsnAudit::getImegaFlagStatus(config('data-reporting.csn-statuses.APPROVED')) . ' THEN 1 ELSE 0 END) AS ' . self::COLUMN_TOTAL_UNIQUE_ACCEPTED_CSNS)
            ->selectRaw('SUM(CASE WHEN csn_audits.imega_status & ' . CsnAudit::getImegaFlagStatus(config('data-reporting.csn-statuses.COMPLETED')) . ' THEN 1 ELSE 0 END) AS ' . self::COLUMN_TOTAL_UNIQUE_COMPLETED_CSNS)
            ->selectRaw('SUM(CASE WHEN csn_audits.imega_status & ' . CsnAudit::getImegaFlagStatus(config('data-reporting.csn-statuses.DECLINED')) . ' THEN audits.orderamount ELSE 0 END) AS cost_unique_declined_csns')
            ->selectRaw('SUM(CASE WHEN csn_audits.imega_status & ' . CsnAudit::getImegaFlagStatus(config('data-reporting.csn-statuses.APPROVED')) . ' THEN audits.orderamount ELSE 0 END) AS cost_unique_approved_csns')
            ->selectRaw('SUM(CASE WHEN csn_audits.imega_status & ' . CsnAudit::getImegaFlagStatus(config('data-reporting.csn-statuses.COMPLETED')) . ' THEN audits.orderamount ELSE 0 END) AS cost_unique_completed_csns')
            ->join('audits', 'csn_audits.audit_id', '=', 'audits.id')
            ->whereNotNull('csn_audits.audit_id')
            ->whereBetween('csn_audits.created_at', [$start, $end])
            ->groupBy(['csn_audits.finance_provider_id', 'csn_audits.client_id'])
            ->having(self::COLUMN_TOTAL_UNIQUE_ACCEPTED_CSNS, '>', 0)
            ->orHaving(self::COLUMN_TOTAL_UNIQUE_DECLINED_CSNS, '>', 0)
            ->orHaving(self::COLUMN_TOTAL_UNIQUE_COMPLETED_CSNS, '>', 0)
            ->get();
    }
}
