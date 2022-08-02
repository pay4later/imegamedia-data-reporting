<?php

namespace Imega\DataReporting\Models;

final class Client extends AngusModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'merchant_id',
        'merchant_site_id',
        'name',
        'alias',
        'finance_provider',
        'website_url',
        'finance_api_key',
        'finance_store_id',
        'finance_store_uuid',
        'test_finance_store_id',
        'test_finance_store_uuid',
        'calculator_style',
        'calculator_style_cart',
        'checkout_style',
        'licence_status',
        'notification_email',
        'notification_url',
        'accepted_url',
        'referred_url',
        'declined_url',
        'to_store_url',
        'error_url',
        'ecommerce_platform_id',
        'test_mode',
        'test_finance_api_key',
        'api_key',
        'api_secret',
        'enc_key',
        'min_order_amount',
        'max_order_amount',
        'contact_email',
        'deposit_increments',
        'vkey_enabled',
        'invoice_client_id',
        'version',
        'csn_api_url',
        'csn_api_username',
        'csn_api_password',
        'csn_api_version_id',
        'enable_payment_requests',
        'payment_request_fulfilled_status',
        'integration_type_id',
        'delivery_time',
        'manual_application_comment',
        'notify_on_application_error',
        'email_csn',
        'enable_merchant_applications',
        'secondary_finance_integration',
        'deposit_processor',
        'checkout_intent_validity_time',
        'process_csn_requests',
    ];
}
