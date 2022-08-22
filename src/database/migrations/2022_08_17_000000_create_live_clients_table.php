<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('data-reporting-roll-up')->create('live_clients', static function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->unsignedInteger('finance_provider_id');
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
        Schema::connection('data-reporting-roll-up')->dropIfExists('live_clients');
    }
};
