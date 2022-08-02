<?php

namespace Imega\DataReporting\Console;

use Illuminate\Console\Command;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Imega\DataReporting\Models\Client;
use Imega\DataReporting\Models\LiveClientReport;

final class LiveClients extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data-reporting:live-clients';

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
        LiveClientReport::insert($this->reportData())
            ? $this->output->success('Live client report data created')
            : $this->output->error('Unable to write live client report data');
    }

    private function reportData(): array
    {
        $qb = Client::query()->from('clients', 'c1') //DB::connection('data-reporting-angus')->table('clients', 'c1')
            ->select('c1.finance_provider')
            ->selectRaw("DATE_FORMAT(NOW(),'%Y-%m-%d 00:00:00') as sampled_at")
            ->selectRaw('COUNT(c1.id) as total_active')

            // Total Billable
            ->selectSub(
                static fn (Builder $query) => $query
                ->selectRaw('COUNT(c2.id)')
                ->from('clients', 'c2')
                ->whereColumn('c2.finance_provider', 'c1.finance_provider')
                ->where('c2.licence_status', config('data-reporting.client-statuses.ACTIVE'))
                ->where('c2.name', 'NOT LIKE', '\_%')
                ->whereNull('c1.deleted_at'),
                'total_billable'
            )

            // Total Inactive
            ->selectSub(
                static fn (Builder $query) => $query
                ->selectRaw('COUNT(id)')
                ->from('clients', 'c2')
                ->whereColumn('c2.finance_provider', 'c1.finance_provider')
                ->where('c2.licence_status', config('data-reporting.client-statuses.INACTIVE'))
                ->whereNull('c2.deleted_at'),
                'total_inactive'
            )

            // Total Active Live
            ->selectSub(
                fn (Builder $query) => $query
                ->selectRaw('COUNT(id)')
                ->from('clients', 'c2')
                ->whereColumn('c2.finance_provider', 'c1.finance_provider')
                ->where('c2.licence_status', config('data-reporting.client-statuses.ACTIVE'))
                ->where('c2.test_mode', false)
                ->whereNull('c2.deleted_at'),
                'total_active_live'
            )

            // Total Active Test
            ->selectSub(
                fn (Builder $query) => $query
                ->selectRaw('COUNT(id)')
                ->from('clients', 'c2')
                ->whereColumn('c2.finance_provider', 'c1.finance_provider')
                ->where('c2.licence_status', config('data-reporting.client-statuses.ACTIVE'))
                ->where('c2.test_mode', true)
                ->whereNull('c2.deleted_at'),
                'total_active_test'
            )

            // Total Active (No Demo) Live
            ->selectSub(
                static fn (Builder $query) => $query
                ->selectRaw('COUNT(id)')
                ->from('clients', 'c2')
                ->whereColumn('c2.finance_provider', 'c1.finance_provider')
                ->where('c2.licence_status', config('data-reporting.client-statuses.ACTIVE'))
                ->where('c2.test_mode', false)
                ->where('c2.name', 'NOT LIKE', '\_%')
                ->whereNull('c2.deleted_at'),
                'total_active_nodemo_live'
            )

            // Total Active (No Demo) Test
            ->selectSub(
                static fn (Builder $query) => $query
                ->selectRaw('COUNT(id)')
                ->from('clients', 'c2')
                ->whereColumn('c2.finance_provider', 'c1.finance_provider')
                ->where('c2.licence_status', config('data-reporting.client-statuses.ACTIVE'))
                ->where('c2.test_mode', true)
                ->where('c2.name', 'NOT LIKE', '\_%')
                ->whereNull('c2.deleted_at'),
                'total_active_nodemo_test'
            )
            ->where('c1.licence_status', config('data-reporting.client-statuses.ACTIVE'))
            ->whereNull('c1.deleted_at')
            ->groupBy('c1.finance_provider');


        $x = $qb->get()->toArray();

        dd($x);

        return array_map(fn ($row) => (array)$row, $qb->get()->toArray());
    }
}
