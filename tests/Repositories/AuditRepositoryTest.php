<?php

namespace Imega\DataReporting\Tests\Repositories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Collection;
use Imega\DataReporting\Models\Angus\Audit;
use Imega\DataReporting\Models\Angus\FinanceProvider;
use Imega\DataReporting\Repositories\AuditRepository;
use Imega\DataReporting\Tests\TestCase;
use Imega\DataReporting\Tests\TestData\Angus\FinanceProviderData;

/**
 * Class AuditRepositoryTest
 *
 * @package Imega\DataReporting\Tests\Repositories
 */
final class AuditRepositoryTest extends TestCase
{
    private AuditRepository $auditRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->auditRepository = new AuditRepository;

        FinanceProvider::insert((new FinanceProviderData)->data);
    }

    /**
     * @test
     * @return void
     */
    public function canCountLastHourApplications(): void
    {
        /** @var Collection $providers */
        $providers = FinanceProvider::all()->random(2);
        $count = 5;
        $orderAmount = rand(1, 100);

        // Counted in stats due to test_mode (false)
        Audit::factory()
            ->count($count)
            ->forClient([
                'test_mode'        => false,
                'finance_provider' => $providers->get(0),
            ])
            ->state(new Sequence([
                'retailer'    => $providers->get(0),
                'orderamount' => $orderAmount,
            ]))
            ->create();

        // Audits not counted due to test_mode (true)
        Audit::factory()
            ->count($count)
            ->forClient([
                'test_mode'        => true,
                'finance_provider' => $providers->get(1),
            ])
            ->state(new Sequence([
                'retailer'    => $providers->get(1),
                'orderamount' => 5,
            ]))
            ->create();

        $result = $this->auditRepository->getLastHourApplicationCounts();
        $this->assertTrue($result->containsOneItem());
        $this->assertEquals($result->first()->toArray(), [
            'finance_provider_id' => $providers->get(0)->id,
            'sampled_at' => Carbon::now()->format('Y-m-d H:00:00'),
            'total_applications' => $count,
            'total_application_value' => $orderAmount * $count,
        ]);
    }
}
