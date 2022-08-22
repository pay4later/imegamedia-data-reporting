<?php

namespace Imega\DataReporting\Repositories;

use Illuminate\Database\Eloquent\Builder;
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
            ->select('retailer as finance_provider_id')
            ->whereHas('clients', fn (Builder $query) => $query->testMode(false))
            ->selectRaw("DATE_FORMAT(NOW(),'%Y-%m-%d %H:00:00') as sampled_at")
            ->selectRaw('COUNT(*) as total_applications')
            ->selectRaw('SUM(orderamount) as total_application_value')
            ->createdLastHour('audits.created_at')
            ->groupBy('finance_provider_id')
            ->get();
    }
}
