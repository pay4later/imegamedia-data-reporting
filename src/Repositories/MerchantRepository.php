<?php

namespace Imega\DataReporting\Repositories;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Imega\DataReporting\Models\Angus\FinanceProvider;
use Imega\DataReporting\Models\Angus\Merchant;
use Imega\DataReporting\Models\Angus\MerchantSite;

final class MerchantRepository
{
    /**
     * Fetches a list of live merchant counts from the Angus system, used for daily report
     *
     * @return Collection
     */
    public function getLiveMerchantCounts(): Collection
    {
        $qb = Merchant::query()
            ->selectRaw('"' . Carbon::now()->format('Y-m-d 00:00:00') . '" AS sampled_at');

        $qb
            ->selectSub(Merchant::totalActiveLive(), 'total_active_live')
            ->selectSub('SELECT COUNT(*) FROM (' . Merchant::totalActiveTest()->toSql() . ') as count', 'total_active_test');

        return $qb->groupBy('sampled_at')->get();
    }

    /**
     * A list of merchants by date with active integrations
     *
     * @param CarbonInterface $startDate
     * @param CarbonInterface $endDate
     * @param array|null $financeProviderIds
     * @return Collection
     */
    public function getMerchantsByDate
    (
        CarbonInterface $startDate,
        CarbonInterface $endDate,
        ?array          $financeProviderIds = null
    ): Collection
    {
        return Merchant::query()
            ->select(['merchants.id', 'merchants.name'])
            ->with(['financeIntegrations' => function ($query) {
                $query->select(['clients.name', 'finance_provider']);
            }])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereHas('financeIntegrations', function (Builder $query) use ($financeProviderIds) {
                $query->testMode(false);

                if ($financeProviderIds) {
                    $query->whereIn('finance_provider', $financeProviderIds);
                }
            })
            ->get();
    }

    /**
     * Listing of merchants with total counts of integrations (incl and excl Deko Provider)
     *
     * @param CarbonInterface $startDate
     * @param CarbonInterface $endDate
     * @param array|null $merchantSiteStatuses
     * @return Collection
     */
    public function getUniqueMerchantHierarchyCounts
    (
        CarbonInterface $startDate,
        CarbonInterface $endDate,
        ?array          $merchantSiteStatuses = null
    ): Collection
    {
        $dekoProvider = (new FinanceProviderRepository)->getFinanceProviderIdByAlias(FinanceProvider::ALIAS_DEKO);

        $qb = MerchantSite::query()
            ->select(['merchants.id as merchant_id', 'merchants.name as merchant_name', 'merchant_sites.status'])
            ->selectRaw('COUNT(DISTINCT merchant_sites.id) as merchant_site_count')
            ->selectRaw('CAST(SUM(CASE WHEN clients.finance_provider = ? THEN 1 ELSE 0 END) as UNSIGNED) as deko_integrations', [$dekoProvider])
            ->selectRaw('CAST(SUM(CASE WHEN clients.finance_provider = ? THEN 0 ELSE 1 END) as UNSIGNED) as non_deko_integrations', [$dekoProvider])
            ->selectRaw('COUNT(clients.id) as total_integrations')
            ->join('merchants', 'merchant_sites.merchant_id', '=', 'merchants.id')
            ->join('clients', 'merchant_sites.id', '=', 'clients.merchant_site_id')
            ->whereBetween('merchants.created_at', [$startDate, $endDate])
            ->groupBy('merchants.id');

        if ($merchantSiteStatuses) {
            $qb->whereIn('merchant_sites.status', $merchantSiteStatuses);
        }

        return $qb->get();
    }
}
