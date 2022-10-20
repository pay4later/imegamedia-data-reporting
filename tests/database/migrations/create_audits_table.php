<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuditsTable extends Migration
{
    public function up(): void
    {
        Schema::connection('data-reporting-angus')->create('audits', static function (Blueprint $table): void {
            $table->increments('id');
            $table->integer('imegaid')->nullable();
            $table->string('retailer', 30)->nullable();
            $table->string('orderamount', 20)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('data-reporting-angus')->dropIfExists('audits');
    }
}
