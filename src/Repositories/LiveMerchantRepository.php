<?php

namespace Imega\DataReporting\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Carbon\CarbonInterface;
use Imega\DataReporting\Models\RollUp\LiveMerchant;

final class LiveMerchantRepository
{
    /**
     * Get a list of all active merchants counts by date filter.
     *
     * @param CarbonInterface $date
     * @return Collection
     */
    public function getActiveMerchantsByDate
    (
        CarbonInterface $date,
    ): Collection
    {
        return $this->liveMerchantQueryBuilder($date)->addSelect('total_active_live')->get();
    }

    /**
     * Get a list of all test merchants counts by date filter.
     *
     * @param CarbonInterface $date
     * @return Collection
     */
    public function getTestMerchantsByDate
    (
        CarbonInterface $date,
    ): Collection
    {
        return $this->liveMerchantQueryBuilder($date)->addSelect('total_active_test')->get();
    }

    /**
     * Get a list of merchant counts by date filter.
     *
     * @param CarbonInterface $date
     * @return Builder
     */
    private function liveMerchantQueryBuilder
    (
        CarbonInterface $date,
    ): Builder
    {
        return LiveMerchant::query()
            ->select([
                'sampled_at',
            ])
            ->where('sampled_at', $date);
    }
}
