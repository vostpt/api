<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeatherObservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('weather_observations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedSmallInteger('station_id');

            $table->float('temperature')->nullable(); // Cº (degree centigrade)
            $table->unsignedTinyInteger('humidity')->nullable(); // % (percent)
            $table->float('wind_speed')->nullable(); // Km/h (kilometer/hour)
            $table->enum('wind_direction', [
                'N',
                'NE',
                'E',
                'SE',
                'S',
                'SW',
                'W',
                'NW',
            ])->nullable();
            $table->float('precipitation')->nullable(); // mm (millimeter)
            $table->float('atmospheric_pressure')->nullable(); // hPa (Hectopascal)
            $table->float('radiation')->nullable(); // R (Roentgen)

            $table->timestamp('timestamp');

            $table->timestamps();

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
        Schema::drop('weather_observations');
    }
}
