<?php

namespace Imega\DataReporting\Console;

use Illuminate\Console\Command;
use Imega\DataReporting\Models\RollUp\LiveClient;
use Imega\DataReporting\Repositories\ClientRepository;

final class DailyReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data-reporting:daily-reports';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generated roll-up reports saved every day.';

    private ClientRepository $clientRepo;

    public function __construct(ClientRepository $clientRepository)
    {
        parent::__construct();

        $this->clientRepo = $clientRepository;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->liveClientCounts();
    }

    private function liveClientCounts(): void
    {
        LiveClient::upsert(
            $this->clientRepo->getLiveClientCounts()->toArray(),
            ['finance_provider_id', 'sampled_at'],
        );
    }
}
