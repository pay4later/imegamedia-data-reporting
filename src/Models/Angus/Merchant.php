<?php

namespace Imega\DataReporting\Models\Angus;

use Carbon\Carbon;
use Database\Factories\Angus\MerchantFactory;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Imega\DataReporting\Enums\MerchantSiteStatus;

/**
 * App\Models\Merchant
 *
 * @property int $id
 * @property string $name
 * @property string $alias
 * @property string $api_key
 * @property string $api_secret
 * @property string $encryption_key
 * @property string $primary_email
 * @property int $invoice_client_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Client[] $financeIntegrations
 * @property-read int|null $finance_integrations_count
 * @property-read Collection|MerchantSite[] $sites
 * @property-read int|null $sites_count
 */
final class Merchant extends AngusModel
{
    use HasFactory;

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return MerchantFactory::new();
    }

    public function financeIntegrations(): HasManyThrough
    {
        return $this->hasManyThrough(Client::class, MerchantSite::class);
    }

    public function sites(): HasMany
    {
        return $this->hasMany(MerchantSite::class);
    }

    /**
     * @return EloquentBuilder
     */
    public static function totalActiveLive(): EloquentBuilder
    {
        return Merchant::selectRaw('COUNT(DISTINCT merchants.id)')
            ->join('merchant_sites AS ' . $merchantSiteAlias = Str::random(4), fn(JoinClause $join) => $join
                ->on($merchantSiteAlias . '.merchant_id', '=', 'merchants.id')
                ->where($merchantSiteAlias . '.status', MerchantSiteStatus::PAID_ENABLED)
            )
            ->join('clients AS ' . $clientAlias = Str::random(4), fn(JoinClause $join) => $join
                ->on($clientAlias . '.merchant_site_id', '=', $merchantSiteAlias . '.id')
                ->where($clientAlias . '.test_mode', false)
            )
            ->whereNull('merchants.deleted_at');
    }

    /**
     * @return EloquentBuilder
     */
    public static function totalActiveTest(): EloquentBuilder
    {
        return Merchant::selectRaw('DISTINCT merchants.id')
            ->leftJoin('merchant_sites', 'merchants.id', '=', 'merchant_sites.merchant_id')
            ->join('clients', 'merchant_sites.id', '=', 'clients.merchant_site_id')
            ->whereNull('merchants.deleted_at')
            ->groupBy('merchants.id')
            ->havingRaw('SUM(clients.test_mode) = COUNT(clients.id)');
    }
}
