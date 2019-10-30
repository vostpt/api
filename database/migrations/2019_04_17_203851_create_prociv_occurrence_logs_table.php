<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcivOccurrenceLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('prociv_occurrence_logs', static function (Blueprint $table): void {
            $table->increments('id');
            $table->unsignedInteger('occurrence_id');

            $table->string('rescue_operations_commander');
            $table->string('entities_at_the_theatre_of_operations');
            $table->text('notes');

            $table->string('operational_command_post');

            $table->unsignedTinyInteger('medium_aircrafts');
            $table->unsignedTinyInteger('heavy_aircrafts');
            $table->unsignedTinyInteger('other_aircrafts');

            $table->unsignedTinyInteger('medium_helicopters');
            $table->unsignedTinyInteger('heavy_helicopters');
            $table->unsignedTinyInteger('other_helicopters');

            $table->unsignedSmallInteger('fire_fighter_assets');
            $table->unsignedSmallInteger('fire_fighter_operatives');

            $table->unsignedSmallInteger('special_fire_fighter_force_assets');
            $table->unsignedSmallInteger('special_fire_fighter_force_operatives');

            $table->unsignedSmallInteger('forest_sapper_assets');
            $table->unsignedSmallInteger('forest_sapper_operatives');

            $table->unsignedSmallInteger('armed_force_assets');
            $table->unsignedSmallInteger('armed_force_operatives');

            $table->unsignedSmallInteger('gips_assets');
            $table->unsignedSmallInteger('gips_operatives');

            $table->unsignedSmallInteger('gnr_assets');
            $table->unsignedSmallInteger('gnr_operatives');

            $table->unsignedSmallInteger('psp_assets');
            $table->unsignedSmallInteger('psp_operatives');

            $table->unsignedSmallInteger('other_operatives');

            $table->string('reinforcement_groups');

            $table->string('state_of_affairs');
            $table->text('state_of_affairs_description');

            $table->string('active_previous_intervention_plan');

            $table->timestamps();

            $table->foreign('occurrence_id')
                ->references('id')
                ->on('prociv_occurrences')
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
        Schema::drop('prociv_occurrence_logs');
    }
}
