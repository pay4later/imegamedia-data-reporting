<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('data-reporting-roll-up')->table('acceptance_rates', static function (Blueprint $table): void {
            $table->decimal('cost_unique_accepted_csns', 12)->after('total_unique_accepted_csns');
            $table->decimal('cost_unique_declined_csns', 12)->after('total_unique_declined_csns');
            $table->decimal('cost_unique_completed_csns', 12)->after('total_unique_completed_csns');
        });
    }

    public function down(): void
    {
        Schema::connection('data-reporting-roll-up')->table('acceptance_rates', function (Blueprint $table) {
            $table->dropColumn([
                'cost_unique_accepted_csns',
                'cost_unique_declined_csns',
                'cost_unique_completed_csns',
            ]);
        });
    }
};
