<?php

namespace Imega\DataReporting\Repositories;

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
        return Audit::query()
            ->selectRaw('CAST(retailer AS UNSIGNED) as finance_provider_id')
            ->selectRaw('"' . Carbon::now()->format('Y-m-d H:00:00') . '" AS sampled_at')
            ->selectRaw('COUNT(*) as total_applications')
            ->selectRaw('SUM(orderamount) as total_application_value')
            ->whereHas('client', fn(Builder $query) => $query->testMode(false))
            ->createdLastHour('audits.created_at')
            ->groupBy('finance_provider_id')
            ->get();
    }
}
