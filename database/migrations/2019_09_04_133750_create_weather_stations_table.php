<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeatherStationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('weather_stations', static function (Blueprint $table): void {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('county_id');

            $table->string('entity');
            $table->string('name');
            $table->string('serial');

            $table->timestamps();

            $table->unique([
                'entity',
                'name',
            ]);

            $table->unique([
                'entity',
                'serial',
            ]);

            $table->foreign('county_id')
                ->references('id')
                ->on('counties')
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
        Schema::drop('weather_stations');
    }
}
