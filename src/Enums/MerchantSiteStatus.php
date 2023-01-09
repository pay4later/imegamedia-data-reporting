<?php

namespace Imega\DataReporting\Enums;

enum MerchantSiteStatus: string
{
    case PAID_ENABLED = 'paid-enabled';
    case PAID_DISABLED = 'paid-disabled';
    case UNPAID_ENABLED = 'unpaid-enabled';
    case UNPAID_DISABLED = 'unpaid-disabled';
    case CANCELLED_DISABLED = 'cancelled-disabled';
}
