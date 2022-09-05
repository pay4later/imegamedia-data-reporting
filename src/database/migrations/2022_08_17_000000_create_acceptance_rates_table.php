<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('data-reporting-roll-up')->create('acceptance_rates', static function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->unsignedInteger('finance_provider_id');
            $table->unsignedInteger('client_id');
            $table->integer('acceptance_rate');
            $table->integer('total_unique_csns');
            $table->integer('total_unique_accepted_csns');
            $table->timestamp('sampled_at');
            $table->timestamps();

            $table->unique(['finance_provider_id', 'client_id', 'sampled_at']);
        });
    }

    public function down(): void
    {
        Schema::connection('data-reporting-roll-up')->dropIfExists('acceptance_rates');
    }
};
