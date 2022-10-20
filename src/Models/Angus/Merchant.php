<?php

namespace Imega\DataReporting\Models\Angus;

use Carbon\Carbon;
use Database\Factories\Angus\MerchantFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Collection;

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
}
