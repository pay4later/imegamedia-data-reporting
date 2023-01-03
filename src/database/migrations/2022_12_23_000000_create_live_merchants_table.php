<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('data-reporting-roll-up')->create('live_merchants', static function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->integer('total_active_live');
            $table->integer('total_active_test');
            $table->timestamp('sampled_at');
            $table->timestamps();

            $table->unique(['sampled_at'], 'live_merchants_sampled_at_unique');
        });
    }

    public function down(): void
    {
        Schema::connection('data-reporting-roll-up')->dropIfExists('live_merchants');
    }
};
