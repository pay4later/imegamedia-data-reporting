<?php

namespace Imega\DataReporting\Repositories;

use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Imega\DataReporting\Models\Angus\LicenceStatusChange;

final class LeaversRepository
{
    /**
     * Get a list of non-test mode leavers by date filter.
     *
     * @param CarbonInterface $startDate       The startDate to filter on.
     * @param CarbonInterface $endDate         The endDate to filter on.
     * @param string|null     $financeProvider The financeProvider string coming from orders database.
     * @return Collection
     */
    public function getLeaversByFilter
    (
        CarbonInterface $startDate,
        CarbonInterface $endDate,
        ?string $financeProvider = null
    ): Collection
    {
        $qb = LicenceStatusChange::query()
            ->select('client_id')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('to', config('data-reporting.client-statuses.INACTIVE'))
            ->reasonNotImegaMigration();

        if ($financeProvider) {
            $qb->where('finance_provider_id', $financeProvider);
        }

        return $qb->groupBy('client_id')->pluck('client_id');
    }
}
