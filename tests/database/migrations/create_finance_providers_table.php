<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinanceProvidersTable extends Migration
{
    public function up(): void
    {
        Schema::connection('data-reporting-angus')->create('finance_providers', static function (Blueprint $table): void {
            $table->increments('id');
            $table->string('name', 100);
            $table->string('alias', 100);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('data-reporting-angus')->dropIfExists('finance_providers');
    }
}
