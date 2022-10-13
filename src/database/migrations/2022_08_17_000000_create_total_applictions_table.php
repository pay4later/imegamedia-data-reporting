<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('data-reporting-roll-up')->create('total_applications', static function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->unsignedInteger('finance_provider_id');
            $table->unsignedInteger('client_id');
            $table->integer('count');
            $table->decimal('value', 12);
            $table->timestamp('sampled_at');
            $table->timestamps();

            $table->unique(['finance_provider_id', 'client_id', 'sampled_at'], 'unique_finance_client_sampled');
        });
    }

    public function down(): void
    {
        Schema::connection('data-reporting-roll-up')->dropIfExists('total_applications');
    }
};
