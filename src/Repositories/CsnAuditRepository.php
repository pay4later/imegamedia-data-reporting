<?php

namespace Imega\DataReporting\Repositories;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Imega\DataReporting\Models\Angus\CsnAudit;

final class CsnAuditRepository
{
    private string $tableAlias;

    private const COLUMN_TOTAL_UNIQUE_CSNS = 'total_unique_csns';
    private const COLUMN_TOTAL_UNIQUE_ACCEPTED_CSNS = 'total_unique_accepted_csns';

    /**
     * Gets the acceptance rates for all finance providers within the last hour
     *
     * @return Collection
     */
    public function getLastHourAcceptanceRates(): Collection
    {
        return $this->acceptanceRates(
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
        return $this->acceptanceRates($start, $end);
    }

    private function acceptanceRates(CarbonInterface $start, CarbonInterface $end): Collection
    {
        $qb = CsnAudit::query()
            ->from('csn_audits', $this->tableAlias = 'ca1')
            ->select([$this->tableAlias . '.finance_provider_id', $this->tableAlias . '.client_id'])
            ->selectRaw('"' . $end->format('Y-m-d H:00:00') . '" AS sampled_at')
            ->selectRaw('0 AS acceptance_rate')
            ->groupBy([$this->tableAlias . '.finance_provider_id', $this->tableAlias . '.client_id'])

            ->selectSub(
                $this->totalUniqueCsnsQueryBuilder($start, $end),
                self::COLUMN_TOTAL_UNIQUE_CSNS
            )

            ->selectSub(
                $this->totalUniqueCsnsQueryBuilder($start, $end)->statusOptions([config('data-reporting.csn-statuses.APPROVED')]),
                self::COLUMN_TOTAL_UNIQUE_ACCEPTED_CSNS
            )

            ->having(self::COLUMN_TOTAL_UNIQUE_CSNS, '>', 0)
            ->orHaving(self::COLUMN_TOTAL_UNIQUE_ACCEPTED_CSNS, '>', 0);

        return $qb->get()->map(static function (CsnAudit $row) {
            $row->acceptance_rate = $row->calculateAcceptedRatePercentage($row->total_unique_accepted_csns, $row->total_unique_csns);
            return $row;
        });
    }

    private function totalUniqueCsnsQueryBuilder(CarbonInterface $start, CarbonInterface $end): Builder
    {
        return CsnAudit::selectRaw('COUNT(id)')
            ->whereColumn('finance_provider_id', $this->tableAlias . '.finance_provider_id')
            ->whereColumn('client_id', $this->tableAlias . '.client_id')
            ->createdBetween($start, $end)
            ->where(fn(Builder $query) => $this->groupedOrdersQueryBuilder($query, $start, $end));
    }

    private function groupedOrdersQueryBuilder(Builder $query, CarbonInterface $start, CarbonInterface $end): Builder
    {
        return $query
            ->whereIn('id', CsnAudit::selectRaw('MAX(id)')
                ->createdBetween($start, $end)
                ->groupBy('order_id'));
    }
}
