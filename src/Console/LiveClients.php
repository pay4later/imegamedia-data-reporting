<?php

namespace Imega\DataReporting\Console;

use Illuminate\Console\Command;
use Imega\DataReporting\Models\Client;
use Imega\DataReporting\Models\ReportLiveClient;

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
        ReportLiveClient::insert($this->reportData())
            ? $this->output->success('Live client report data created')
            : $this->output->error('Unable to write live client report data');
    }

    private function reportData(): array
    {
        $clientAlias = 'c1';
        $qb = Client::query()
            ->from('clients', $clientAlias)
            ->select($clientAlias . '.finance_provider')
            ->selectRaw("DATE_FORMAT(NOW(),'%Y-%m-%d 00:00:00') as sampled_at")
            ->selectRaw('COUNT(' . $clientAlias . '.id) as total_active')
            ->selectSub(Client::totalBillableQuery(), 'total_billable')
            ->selectSub(Client::totalInactiveQuery(), 'total_inactive')
            ->selectSub(Client::totalActiveLiveQuery(), 'total_active_live')
            ->selectSub(Client::totalActiveTestQuery(), 'total_active_test')
            ->selectSub(Client::totalActiveNoDemoLiveQuery(), 'total_active_nodemo_live')
            ->selectSub(Client::totalActiveNoDemoTestQuery(), 'total_active_nodemo_test')
            ->active($clientAlias)
            ->whereNull($clientAlias . '.deleted_at')
            ->groupBy($clientAlias . '.finance_provider');

        return array_map(fn($row) => (array)$row, $qb->get()->toArray());
    }
}
