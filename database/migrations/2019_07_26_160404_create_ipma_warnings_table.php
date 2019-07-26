<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIpmaWarningsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('ipma_warnings', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->string('text')->nullable();
            $table->string('awareness_type_name');
            $table->string('awareness_level_id');
            $table->string('id_area_warning');
            $table->string('area_name');
            $table->dateTime('started_at');
            $table->dateTime('ended_at');
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::drop('ipma_warnings');
    }
}
