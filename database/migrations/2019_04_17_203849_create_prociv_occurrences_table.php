<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcivOccurrencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('prociv_occurrences', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedTinyInteger('type_id');
            $table->unsignedTinyInteger('status_id');

            $table->string('remote_id')->unique();

            $table->unsignedSmallInteger('ground_assets');
            $table->unsignedSmallInteger('ground_operatives');

            $table->unsignedSmallInteger('aerial_assets');
            $table->unsignedSmallInteger('aerial_operatives');

            $table->timestamps();

            $table->foreign('status_id')
                ->references('id')
                ->on('prociv_occurrence_statuses')
                ->onUpdate('cascade');

            $table->foreign('type_id')
                ->references('id')
                ->on('prociv_occurrence_types')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::drop('prociv_occurrences');
    }
}
