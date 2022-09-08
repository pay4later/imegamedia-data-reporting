<?php

namespace Imega\DataReporting\Repositories;

use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Imega\DataReporting\Enums\OrderJobType;
use Imega\DataReporting\Models\Orders\Order;

final class OrderRepository
{
    /**
     * Get a list of non-test orders by date filter.
     *
     * @param CarbonInterface $startDate The startDate to filter on.
     * @param CarbonInterface $endDate The endDate to filter on.
     * @param string|null $financeProvider The financeProvider string coming from orders database.
     * @param int|null $jobType The jobType int coming from orders database enum OrderJobType.
     * @return Collection
     */
    public function getOrdersByFilter
    (
        CarbonInterface $startDate,
        CarbonInterface $endDate,
        ?string         $financeProvider = null,
        ?int            $jobType = null
    ): Collection
    {
        $qb = Order::query()
            ->select(['orderid', 'invoiceid', 'company', 'finance', 'package', 'email', 'created', 'statusid'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->statusNotTest();

        if ($jobType) {
            $qb->jobType($jobType);
        }

        if ($financeProvider) {
            $qb->financeProvider($financeProvider);
        }

        return $qb->orderBy('orderid', 'DESC')->get();
    }

    /**
     * Get a list of new orders by date filter.
     *
     * @param CarbonInterface $startDate The startDate to filter on.
     * @param CarbonInterface $endDate The endDate to filter on.
     * @param string|null $financeProvider The financeProvider string coming from orders database.
     * @return Collection
     */
    public function getNewOrdersCount
    (
        CarbonInterface $startDate,
        CarbonInterface $endDate,
        ?string         $financeProvider = null,
    ): Collection
    {
        $qb = Order::query()
            ->jobType(OrderJobType::NewInstall->value)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->statusNotTest();

        if ($financeProvider) {
            $qb->financeProvider($financeProvider);
        }

        return $qb->count('orderid');
    }
}
