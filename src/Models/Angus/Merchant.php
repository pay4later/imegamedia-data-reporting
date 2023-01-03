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
     * Scope a query to only include merchants that have active site(s).
     *
     * @param EloquentBuilder $query
     * @return EloquentBuilder
     */
    public function scopeActive(EloquentBuilder $query): EloquentBuilder
    {
        return $query->join('merchant_sites AS sites', fn (JoinClause $join) => $join
            ->on('sites.merchant_id', '=', 'merchants.id')
            ->where('sites.status', MerchantSiteStatus::PAID_ENABLED)
        );
    }

    /**
     * Scope a query to only include merchants of a given test_mode.
     *
     * @param EloquentBuilder $query
     * @param bool $type
     * @return EloquentBuilder
     */
    public function scopeTestMode(EloquentBuilder $query, bool $type): EloquentBuilder
    {
        return $query->join('merchant_sites AS sites', 'sites.merchant_id', '=', 'merchants.id')
            ->join('clients AS c', fn (JoinClause $join) => $join
                ->on('c.merchant_site_id', '=', 'sites.id')
                ->where('test_mode', $type)
            );
    }

    /**
     * @return EloquentBuilder
     */
    public static function totalActiveLive(): EloquentBuilder
    {
        return Merchant::selectRaw('COUNT(merchants.id)')
            ->active()
            ->testMode(false)
            ->whereNull('deleted_at');
    }

    /**
     * @return EloquentBuilder
     */
    public static function totalActiveTest(): EloquentBuilder
    {
        return Merchant::selectRaw('COUNT(merchants.id)')
            ->active() // merchant_site at least one paid-enabled
            ->testMode(true) // all clients.test_mode = true
            ->whereNull('deleted_at');
    }
}
