<?php

namespace Imega\DataReporting\Console;

use Illuminate\Console\Command;
use Imega\DataReporting\Models\RollUp\AcceptanceRate;
use Imega\DataReporting\Models\RollUp\TotalApplication;
use Imega\DataReporting\Repositories\AuditRepository;
use Imega\DataReporting\Repositories\CsnAuditRepository;

final class HourlyReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data-reporting:hourly-reports';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generated roll-up reports saved every hour';

    private AuditRepository $auditRepo;
    private CsnAuditRepository $csnAuditRepo;

    public function __construct
    (
        AuditRepository $auditRepository,
        CsnAuditRepository $csnAuditRepository
    )
    {
        parent::__construct();

        $this->auditRepo = $auditRepository;
        $this->csnAuditRepo = $csnAuditRepository;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->applicationCounts();
        $this->acceptanceRates();
    }

    private function applicationCounts(): void
    {
        TotalApplication::upsert(
            $this->auditRepo->getLastHourApplicationCounts()->toArray(),
            ['finance_provider_id', 'sampled_at'],
        );
    }

    private function acceptanceRates(): void
    {
        AcceptanceRate::upsert(
            $this->csnAuditRepo->getLastHourAcceptanceRates()->toArray(),
            ['finance_provider_id', 'sampled_at'],
        );
    }
}
