<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    public function up(): void
    {
        Schema::connection('data-reporting-angus')->create('clients', static function (Blueprint $table): void {
            $table->increments('id');
            $table->string('name', 100);
            $table->string('alias', 100);
            $table->unsignedInteger('finance_provider');
            $table->string('api_key', 32)->unique();
            $table->string('api_secret', 32);
            $table->string('licence_status');
            $table->decimal('min_order_amount', 10);
            $table->decimal('max_order_amount', 10);
            $table->boolean('test_mode');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('data-reporting-angus')->dropIfExists('clients');
    }
}
