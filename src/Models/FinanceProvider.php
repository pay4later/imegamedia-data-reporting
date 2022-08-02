<?php

namespace Imega\DataReporting\Models;

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

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'alias',
        'calculator_url',
        'checkout_url',
        'test_checkout_url',
        'dev_checkout_url',
        'image',
    ];
}
