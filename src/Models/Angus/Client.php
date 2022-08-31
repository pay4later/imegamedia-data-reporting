<?php

namespace Imega\DataReporting\Models\Angus;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Imega\DataReporting\Traits\QueryDateTrait;

/**
 * Imega\DataReporting\Models\Angus\Client
 *
 * @property int $id
 * @property int|null $merchant_id
 * @property int|null $merchant_site_id
 * @property int|null $invoice_client_id
 * @property string $name
 * @property string $alias
 * @property int $finance_provider
 * @property int|null $integration_type_id
 * @property string $api_key
 * @property string $api_secret
 * @property int $version
 * @property int $ecommerce_platform_id
 * @property string|null $deposit_processor
 * @property string|null $website_url
 * @property string $contact_email
 * @property string|null $finance_api_key
 * @property string|null $test_finance_api_key
 * @property string $enc_key
 * @property string|null $finance_store_id
 * @property string|null $test_finance_store_id
 * @property string|null $finance_store_uuid
 * @property string|null $test_finance_store_uuid
 * @property string $max_order_amount
 * @property string $min_order_amount
 * @property int $deposit_increments
 * @property bool $vkey_enabled
 * @property int $calculator_style
 * @property int|null $calculator_style_cart
 * @property int $checkout_style
 * @property string $licence_status
 * @property bool $test_mode
 * @property string|null $notification_email
 * @property string $notification_url
 * @property string|null $accepted_url
 * @property string|null $referred_url
 * @property string|null $declined_url
 * @property string|null $to_store_url
 * @property string|null $error_url
 * @property int|null $secondary_finance_integration
 * @property string|null $csn_api_url
 * @property string|null $csn_api_username
 * @property string|null $csn_api_password
 * @property int|null $csn_api_version_id
 * @property bool $process_csn_requests
 * @property bool $enable_payment_requests
 * @property string|null $payment_request_fulfilled_status
 * @property int|null $delivery_time
 * @property bool $enable_merchant_applications
 * @property bool $manual_application_comment
 * @property bool $notify_on_application_error
 * @property bool $email_csn
 * @property int|null $checkout_intent_validity_time
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
final class Client extends AngusModel
{
    use QueryDateTrait;

    public function audits(): HasMany
    {
        return $this->hasMany(Audit::class, 'imegaid');
    }

    public function financeProvider(): BelongsTo
    {
        return $this->belongsTo(FinanceProvider::class, 'finance_provider');
    }

    public function licenceStatusChanges(): HasMany
    {
        return $this->hasMany(LicenceStatusChange::class);
    }

    /**
     * Scope a query to only include active users.
     *
     * @param EloquentBuilder $query
     * @return EloquentBuilder
     */
    public function scopeActive(EloquentBuilder $query): EloquentBuilder
    {
        return $query->where('licence_status', config('data-reporting.client-statuses.ACTIVE'));
    }

    /**
     * Scope a query to only include inactive users.
     *
     * @param EloquentBuilder $query
     * @return EloquentBuilder
     */
    public function scopeInactive(EloquentBuilder $query): EloquentBuilder
    {
        return $query->where('licence_status', config('data-reporting.client-statuses.INACTIVE'));
    }

    /**
     * Scope a query to only include users of a given test_mode.
     *
     * @param EloquentBuilder $query
     * @param  bool  $type
     * @return EloquentBuilder
     */
    public function scopeTestMode(EloquentBuilder $query, bool $type): EloquentBuilder
    {
        return $query->where('test_mode', $type);
    }

    /**
     * Scope a query to only include live users.
     *
     * @param EloquentBuilder $query
     * @param string $tableAlias
     * @return EloquentBuilder
     */
    public function scopeLive(EloquentBuilder $query, string $tableAlias = 'clients'): EloquentBuilder
    {
        return $query->where(sprintf('%s.name', $tableAlias), 'NOT LIKE', '\_%');
    }

    public static function totalBillable(string $whereSecondColumn): EloquentBuilder
    {
        return Client::selectRaw('COUNT(id)')
            ->whereColumn('finance_provider', $whereSecondColumn)
            ->active()
            ->live()
            ->whereNull('deleted_at');
    }

    /**
     * @param string $whereSecondColumn
     * @return EloquentBuilder
     */
    public static function totalInactive(string $whereSecondColumn): EloquentBuilder
    {
        return Client::selectRaw('COUNT(id)')
            ->whereColumn('finance_provider', $whereSecondColumn)
            ->inactive()
            ->whereNull('deleted_at');
    }

    /**
     * @param string $whereSecondColumn
     * @return EloquentBuilder
     */
    public static function totalActiveLive(string $whereSecondColumn): EloquentBuilder
    {
        return Client::selectRaw('COUNT(id)')
            ->whereColumn('finance_provider', $whereSecondColumn)
            ->active()
            ->testMode(false)
            ->whereNull('deleted_at');
    }

    /**
     * @param string $whereSecondColumn
     * @return EloquentBuilder
     */
    public static function totalActiveTest(string $whereSecondColumn): EloquentBuilder
    {
        return Client::selectRaw('COUNT(id)')
            ->whereColumn('finance_provider', $whereSecondColumn)
            ->active()
            ->testMode(true)
            ->whereNull('deleted_at');
    }

    /**
     * @param string $whereSecondColumn
     * @return EloquentBuilder
     */
    public static function totalActiveNoDemoLive(string $whereSecondColumn): EloquentBuilder
    {
        return Client::selectRaw('COUNT(id)')
            ->whereColumn('finance_provider', $whereSecondColumn)
            ->active()
            ->testMode(false)
            ->live()
            ->whereNull('deleted_at');
    }

    /**
     * @param string $whereSecondColumn
     * @return EloquentBuilder
     */
    public static function totalActiveNoDemoTest(string $whereSecondColumn): EloquentBuilder
    {
        return Client::selectRaw('COUNT(id)')
            ->whereColumn('finance_provider', $whereSecondColumn)
            ->active()
            ->testMode(true)
            ->live()
            ->whereNull('deleted_at');
    }
}
