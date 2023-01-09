<?php

namespace Imega\DataReporting\Console;

use Illuminate\Console\Command;
use Imega\DataReporting\Models\RollUp\LiveClient;
use Imega\DataReporting\Models\RollUp\LiveMerchant;
use Imega\DataReporting\Repositories\ClientRepository;
use Imega\DataReporting\Repositories\MerchantRepository;

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
    private MerchantRepository $merchantRepo;

    public function __construct(ClientRepository $clientRepository, MerchantRepository $merchantRepository)
    {
        parent::__construct();

        $this->clientRepo = $clientRepository;
        $this->merchantRepo = $merchantRepository;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->liveClientCounts();
        $this->liveMerchantCounts();
    }

    private function liveClientCounts(): void
    {
        LiveClient::upsert(
            $this->clientRepo->getLiveClientCounts()->toArray(),
            ['finance_provider_id', 'sampled_at'],
        );
    }

    private function liveMerchantCounts(): void
    {
        LiveMerchant::upsert(
            $this->merchantRepo->getLiveMerchantCounts()->toArray(),
            ['sampled_at'],
        );
    }
}
