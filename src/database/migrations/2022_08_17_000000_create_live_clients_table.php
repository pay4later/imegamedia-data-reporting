<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('data-reporting-roll-up')->create('live_clients', static function ($table): void {
            $table->bigIncrements('id');
            $table->foreign('finance_provider_id')->references('id')->on('data-reporting-angus.finance_providers');
            $table->integer('total_active');
            $table->integer('total_billable');
            $table->integer('total_inactive');
            $table->integer('total_active_live');
            $table->integer('total_active_test');
            $table->integer('total_active_nodemo_live');
            $table->integer('total_active_nodemo_test');
            $table->timestamp('sampled_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        $schema = Schema::connection('data-reporting-roll-up');
        if ($schema->hasTable('live_clients')) {
            $schema->table('live_clients', static function (Blueprint $table): void {
                $table->dropForeign(['finance_provider_id']);
            });
            $schema->drop('live_clients');
        }
    }
};
