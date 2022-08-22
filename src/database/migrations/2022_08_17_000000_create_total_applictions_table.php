<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('data-reporting-roll-up')->create('total_applications', static function ($table): void {
            $table->bigIncrements('id');
            $table->foreign('finance_provider_id')->references('id')->on('data-reporting-angus.finance_providers');
            $table->integer('count');
            $table->decimal('value', 12);
            $table->timestamp('sampled_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        $schema = Schema::connection('data-reporting-roll-up');
        if ($schema->hasTable('total_applications')) {
            $schema->table('total_applications', static function (Blueprint $table): void {
                $table->dropForeign(['finance_provider_id']);
            });
            $schema->drop('total_applications');
        }
        Schema::connection('data-reporting-roll-up')->dropIfExists('total_applications');
    }
};
