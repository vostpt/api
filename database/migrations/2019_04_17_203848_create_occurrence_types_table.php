<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOccurrenceTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('occurrence_types', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->unsignedTinyInteger('species_id');

            $table->unsignedSmallInteger('code')->unique();
            $table->string('name');

            $table->timestamps();

            $table->unique([
                'species_id',
                'name',
            ]);

            $table->foreign('species_id')
                ->references('id')
                ->on('occurrence_species')
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
        Schema::drop('occurrence_types');
    }
}
