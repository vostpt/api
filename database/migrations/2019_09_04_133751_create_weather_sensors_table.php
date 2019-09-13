<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeatherSensorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('weather_sensors', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('station_id');

            $table->string('type');

            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 10, 8);

            $table->unsignedSmallInteger('altitude');

            $table->date('started_at')->nullable();

            $table->timestamps();

            $table->unique([
                'station_id',
                'type',
            ]);

            $table->foreign('station_id')
                ->references('id')
                ->on('weather_stations')
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
        Schema::drop('weather_sensors');
    }
}
