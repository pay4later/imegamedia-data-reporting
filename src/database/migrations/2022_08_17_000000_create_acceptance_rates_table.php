<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('data-reporting-roll-up')->create('acceptance_rates', static function ($table): void {
            $table->bigIncrements('id');
            $table->foreign('finance_provider_id')->references('id')->on('data-reporting-angus.finance_providers');
            $table->integer('acceptance_rate');
            $table->integer('total_unique_csns');
            $table->integer('total_unique_accepted_csns');
            $table->timestamp('sampled_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        $schema = Schema::connection('data-reporting-roll-up');
        if ($schema->hasTable('acceptance_rates')) {
            $schema->table('acceptance_rates', static function (Blueprint $table): void {
                $table->dropForeign(['finance_provider_id']);
            });
            $schema->drop('acceptance_rates');
        }
    }
};
