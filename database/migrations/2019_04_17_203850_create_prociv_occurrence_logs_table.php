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
        Schema::create('prociv_occurrence_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('occurrence_id');

            $table->string('rescue_operations_commander');
            $table->string('entities_at_the_theatre_of_operations');
            $table->text('notes');

            $table->string('operational_command_post');

            $table->unsignedTinyInteger('medium_aircrafts_involved');
            $table->unsignedTinyInteger('heavy_aircrafts_involved');
            $table->unsignedTinyInteger('other_aircrafts_involved');

            $table->unsignedTinyInteger('medium_helicopters_involved');
            $table->unsignedTinyInteger('heavy_helicopters_involved');
            $table->unsignedTinyInteger('other_helicopters_involved');

            $table->unsignedSmallInteger('fire_fighter_assets_involved');
            $table->unsignedSmallInteger('fire_fighter_operatives_involved');

            $table->unsignedSmallInteger('special_fire_fighter_force_assets_involved');
            $table->unsignedSmallInteger('special_fire_fighter_force_operatives_involved');

            $table->unsignedSmallInteger('forest_sapper_assets_involved');
            $table->unsignedSmallInteger('forest_sapper_operatives_involved');

            $table->unsignedSmallInteger('armed_force_assets_involved');
            $table->unsignedSmallInteger('armed_force_operatives_involved');

            $table->unsignedSmallInteger('gips_assets_involved');
            $table->unsignedSmallInteger('gips_operatives_involved');

            $table->unsignedSmallInteger('gnr_assets_involved');
            $table->unsignedSmallInteger('gnr_operatives_involved');

            $table->unsignedSmallInteger('psp_assets_involved');
            $table->unsignedSmallInteger('psp_operatives_involved');

            $table->unsignedSmallInteger('other_operatives_involved');

            $table->string('reinforcement_groups_involved');

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
