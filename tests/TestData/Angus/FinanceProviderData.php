<?php

namespace Imega\DataReporting\Tests\TestData\Angus;

use Imega\DataReporting\Models\Angus\FinanceProvider;

/**
 * @property array[] data
 */
final class FinanceProviderData
{
    public function __construct()
    {
        $this->data = [
            [
                'name'  => ucfirst(FinanceProvider::ALIAS_AFFORDITNOW),
                'alias' => FinanceProvider::ALIAS_AFFORDITNOW,
            ],
            [
                'name'  => ucfirst(FinanceProvider::ALIAS_ALLIUM),
                'alias' => FinanceProvider::ALIAS_ALLIUM,
            ],
            [
                'name'  => ucfirst(FinanceProvider::ALIAS_BARCLAYS),
                'alias' => FinanceProvider::ALIAS_BARCLAYS,
            ],
            [
                'name'  => ucfirst(FinanceProvider::ALIAS_BNP),
                'alias' => FinanceProvider::ALIAS_BNP,
            ],
            [
                'name'  => ucfirst(FinanceProvider::ALIAS_CBRF),
                'alias' => FinanceProvider::ALIAS_CBRF,
            ],
            [
                'name'  => ucfirst(FinanceProvider::ALIAS_DEKO),
                'alias' => FinanceProvider::ALIAS_DEKO,
            ],
            [
                'name'  => ucfirst(FinanceProvider::ALIAS_DIVIDEBUY),
                'alias' => FinanceProvider::ALIAS_DIVIDEBUY,
            ],
            [
                'name'  => ucfirst(FinanceProvider::ALIAS_DIVIDO),
                'alias' => FinanceProvider::ALIAS_DIVIDO,
            ],
            [
                'name'  => ucfirst(FinanceProvider::ALIAS_FUNDSZA),
                'alias' => FinanceProvider::ALIAS_FUNDSZA,
            ],
            [
                'name'  => ucfirst(FinanceProvider::ALIAS_HITACHI),
                'alias' => FinanceProvider::ALIAS_HITACHI,
            ],
            [
                'name'  => ucfirst(FinanceProvider::ALIAS_IDEAL4FINANCE),
                'alias' => FinanceProvider::ALIAS_IDEAL4FINANCE,
            ],
            [
                'name'  => ucfirst(FinanceProvider::ALIAS_IMEGAMEDIA),
                'alias' => FinanceProvider::ALIAS_IMEGAMEDIA,
            ],
            [
                'name'  => ucfirst(FinanceProvider::ALIAS_DUOLOGI),
                'alias' => FinanceProvider::ALIAS_DUOLOGI,
            ],
            [
                'name'  => ucfirst(FinanceProvider::ALIAS_KLARNA),
                'alias' => FinanceProvider::ALIAS_KLARNA,
            ],
            [
                'name'  => ucfirst(FinanceProvider::ALIAS_OMNICAPITAL),
                'alias' => FinanceProvider::ALIAS_OMNICAPITAL,
            ],
            [
                'name'  => ucfirst(FinanceProvider::ALIAS_PAYL8R),
                'alias' => FinanceProvider::ALIAS_PAYL8R,
            ],
            [
                'name'  => ucfirst(FinanceProvider::ALIAS_PAYPAL),
                'alias' => FinanceProvider::ALIAS_PAYPAL,
            ],
            [
                'name'  => ucfirst(FinanceProvider::ALIAS_PIM),
                'alias' => FinanceProvider::ALIAS_PIM,
            ],
            [
                'name'  => ucfirst(FinanceProvider::ALIAS_SNAP),
                'alias' => FinanceProvider::ALIAS_SNAP,
            ],
            [
                'name'  => ucfirst(FinanceProvider::ALIAS_SNAPUK),
                'alias' => FinanceProvider::ALIAS_SNAPUK,
            ],
            [
                'name'  => ucfirst(FinanceProvider::ALIAS_TEST),
                'alias' => FinanceProvider::ALIAS_TEST,
            ],
            [
                'name'  => ucfirst(FinanceProvider::ALIAS_V12),
                'alias' => FinanceProvider::ALIAS_V12,
            ],
        ];
    }
}
