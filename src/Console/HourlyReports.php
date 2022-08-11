<?php

namespace Imega\DataReporting\Console;

use Illuminate\Console\Command;
use Imega\DataReporting\Models\Audit;
use Imega\DataReporting\Models\CsnAudit;

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
    protected $description = '';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {

        //TODO Currently no where for these reports to go, need to make table(s) for this data
        dd($this->csnAcceptanceRate());
    }

    private function applicationAmountAndValue(): array
    {
        $auditAlias = 'a1';
        $qb = Audit::query()
            ->from('audits', $auditAlias)
            ->select($auditAlias . '.retailer')
            ->joinClients()
            ->selectRaw("DATE_FORMAT(NOW(),'%Y-%m-%d 00:00:00') as sampled_at")
            ->selectRaw('COUNT(*) as total_applications')
            ->selectRaw('ROUND(sum(orderamount),2)/1000000 as total_application_value')
            ->clientsTestMode(false)
            ->createdToday()
            ->groupBy($auditAlias.'.retailer');

        return array_map(fn($row) => (array)$row, $qb->get()->toArray());
    }

    private function csnAcceptanceRate(): array
    {
        $csnAlias = 'ca1';
        $qb = CsnAudit::query()
            ->from('csn_audits', $csnAlias)
            ->select($csnAlias.'.finance_provider_id')
            ->selectRaw('0 AS acceptance_rate')
            ->selectSub(CsnAudit::totalUniqueCsns(), 'total_unique_csns')
            ->selectSub(CsnAudit::totalUniqueAcceptedCsns(), 'total_unique_accepted_csns')
            ->groupBy([$csnAlias.'.finance_provider_id', ]);

        return $qb->get()->map(static function (CsnAudit $row) {
            $row->acceptance_rate = $row->calculateAcceptedRatePercentage($row->total_unique_accepted_csns, $row->total_unique_csns);
            return $row;
        })->toArray();
    }
}
