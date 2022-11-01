<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('data-reporting-roll-up')->table('acceptance_rates', static function (Blueprint $table): void {
            $table->dropColumn(['total_unique_csns', 'acceptance_rate']);
            $table->integer('total_unique_declined_csns');
        });
    }

    public function down(): void
    {
        Schema::connection('data-reporting-roll-up')->table('acceptance_rates', function (Blueprint $table) {
            $table->dropColumn(['total_unique_declined_csns']);
            $table->integer('total_unique_csns');
            $table->integer('acceptance_rate');
        });
    }
};
