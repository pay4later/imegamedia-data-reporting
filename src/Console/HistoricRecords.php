<?php

namespace Imega\DataReporting\Console;

use Carbon\CarbonInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Imega\DataReporting\Models\RollUp\AcceptanceRate;
use Imega\DataReporting\Models\RollUp\TotalApplication;
use Imega\DataReporting\Repositories\AuditRepository;
use Imega\DataReporting\Repositories\CsnAuditRepository;

final class HistoricRecords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data-reporting:historic {start} {end}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generated roll-up reports for historic data';

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

    public function handle(): void
    {
        $start = Carbon::createFromFormat('Y-m-d H:i:s', $this->argument('start') . ' 00:00:00');
        $end = Carbon::createFromFormat('Y-m-d H:i:s', $this->argument('end') . ' 23:59:59');

        for ($d = $start; $d <= $end; $d->addHour()) {
            $carbonStart = clone $d->setMinutes(0)->setSeconds(0);
            $carbonEnd = clone $d->setMinutes(59)->setSeconds(59);

            $this->info('Upserting ' . $carbonStart->format('Y-m-d H:i:s') . ' to ' . $carbonEnd->format('Y-m-d H:i:s'));

            $this->applicationCounts($carbonStart, $carbonEnd);
            $this->acceptanceRates($carbonStart, $carbonEnd);
        }
    }

    private function applicationCounts(CarbonInterface $start, CarbonInterface $end): void
    {
        $start_time = microtime(true);
        TotalApplication::upsert(
            $this->auditRepo->getDateBetweenApplicationCounts($start, $end)->toArray(),
            ['finance_provider_id', 'client_id', 'sampled_at'],
        );
        $end_time = microtime(true);

        $this->info(__FUNCTION__ .' execution time ' . round($end_time - $start_time, 2) . ' sec');
    }

    private function acceptanceRates(CarbonInterface $start, CarbonInterface $end): void
    {
        $start_time = microtime(true);
        AcceptanceRate::upsert(
            $this->csnAuditRepo->getDateBetweenAcceptanceRates($start, $end)->toArray(),
            ['finance_provider_id', 'client_id', 'sampled_at'],
        );
        $end_time = microtime(true);

        $this->info(__FUNCTION__ .' execution time ' . round($end_time - $start_time, 2) . ' sec');
    }
}
