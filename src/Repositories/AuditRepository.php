<?php

namespace Imega\DataReporting\Repositories;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Imega\DataReporting\Models\Angus\Audit;

final class AuditRepository
{
    /**
     * Get a list of application counts and values for the last hour.
     *
     * @return Collection
     */
    public function getLastHourApplicationCounts(): Collection
    {
        return $this->applicationCounts(
            Carbon::now()->subHours(),
            Carbon::now()
        );
    }

    /**
     * Get a list of application counts and values within the date range.
     *
     * @param CarbonInterface $start
     * @param CarbonInterface $end
     * @return Collection
     */
    public function getDateBetweenApplicationCounts(CarbonInterface $start, CarbonInterface $end): Collection
    {
        return $this->applicationCounts(
            clone $start->setTime(0,0),
            clone $end->setTime(23,59,59)
        );
    }

    private function applicationCounts(CarbonInterface $start, CarbonInterface $end): Collection
    {
        return Audit::query()
            ->selectRaw('CAST(retailer AS UNSIGNED) as finance_provider_id')
            ->selectRaw('CAST(imegaid AS UNSIGNED) as client_id')
            ->selectRaw('"' . $end->format('Y-m-d H:00:00') . '" AS sampled_at')
            ->selectRaw('COUNT(*) as count')
            ->selectRaw('SUM(orderamount) as value')
            ->whereHas('client', fn(Builder $query) => $query->testMode(false))
            ->createdBetween($start, $end, 'audits.created_at')
            ->groupBy('finance_provider_id', 'client_id')
            ->get();
    }
}
