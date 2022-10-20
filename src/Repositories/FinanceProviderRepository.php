<?php

namespace Imega\DataReporting\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Imega\DataReporting\Models\Angus\FinanceProvider;

final class FinanceProviderRepository
{
    /**
     * Get a list of client counts for providers.
     *
     * @return Collection
     */
    public function getClientCount(): Collection
    {
        return FinanceProvider::query()
            ->select('alias')
            ->withCount(['clients' => fn(Builder $query) => $query
                ->live()
                ->where('licence_status', [
                    config('data-reporting.client-statuses.ACTIVE'),
                    config('data-reporting.client-statuses.LICENCE_STATUS_QUOTA_EXCEEDED'),
                ]),
            ])
            ->get();
    }

    /**
     * Returns the id of the finance provider filtered by alias
     *
     * @param string $alias
     * @return int
     */
    public function getFinanceProviderIdByAlias(string $alias): int
    {
        return FinanceProvider::query()
            ->where('alias', $alias)
            ->value('id');
    }
}
