<?php

namespace Imega\DataReporting\Models\Angus;

use Carbon\Carbon;
use Database\Factories\Angus\MerchantSiteFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * App\Models\MerchantSite
 *
 * @property int $id
 * @property int $merchant_id
 * @property string $domain
 * @property string $name
 * @property string $api_key
 * @property string $api_secret
 * @property string $encryption_key
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Client[] $financeIntegrations
 * @property-read int|null $finance_integrations_count
 * @property-read Merchant $merchant
 */
final class MerchantSite extends AngusModel
{
    use HasFactory;

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return MerchantSiteFactory::new();
    }

    public function merchant(): BelongsTo
    {
        return $this->belongsTo(Merchant::class);
    }

    public function financeIntegrations(): HasMany
    {
        return $this->hasMany(Client::class);
    }
}
