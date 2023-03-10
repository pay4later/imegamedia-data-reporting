<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('data-reporting-roll-up')->table('acceptance_rates', static function (Blueprint $table): void {
            $table->integer('total_unique_completed_csns')->after('total_unique_declined_csns');
        });
    }

    public function down(): void
    {
        Schema::connection('data-reporting-roll-up')->table('acceptance_rates', function (Blueprint $table) {
            $table->dropColumn(['total_unique_completed_csns']);
        });
    }
};
