<?php

namespace Imega\DataReporting\Models\Angus;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Imega\DataReporting\Models\Angus
 *
 * @property int $id
 * @property string $name
 * @property string $alias
 * @property string|null $image
 * @property string $checkout_url
 * @property string|null $test_checkout_url
 * @property string|null $dev_checkout_url
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
final class FinanceProvider extends AngusModel
{
    public const ALIAS_AFFORDITNOW = 'afforditnow';
    public const ALIAS_ALLIUM = 'allium';
    public const ALIAS_BARCLAYS = 'barclays';
    public const ALIAS_BNP = 'bnp';
    public const ALIAS_CBRF = 'cbrf';
    public const ALIAS_DEKO = 'deko';
    public const ALIAS_DIVIDEBUY = 'dividebuy';
    public const ALIAS_DIVIDO = 'divido';
    public const ALIAS_DUOLOGI = 'duologi';
    public const ALIAS_FUNDSZA = 'fundsza';
    public const ALIAS_HITACHI = 'hitachi';
    public const ALIAS_IDEAL4FINANCE = 'ideal4finance';
    public const ALIAS_IMEGAMEDIA = 'imegamedia';
    public const ALIAS_KLARNA = 'klarna';
    public const ALIAS_OMNICAPITAL = 'omnicapital';
    public const ALIAS_PAYL8R = 'payl8r';
    public const ALIAS_PAYPAL = 'paypal';
    public const ALIAS_PIM = 'pim';
    public const ALIAS_SNAP = 'snap';
    public const ALIAS_SNAPUK = 'snapuk';
    public const ALIAS_TEST = 'test';
    public const ALIAS_V12 = 'v12';

    public function clients(): HasMany
    {
        return $this->hasMany(Client::class, 'finance_provider');
    }
}
