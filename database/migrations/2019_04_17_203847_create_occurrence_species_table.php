<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOccurrenceSpeciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('occurrence_species', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->unsignedTinyInteger('family_id');

            $table->unsignedSmallInteger('code')->unique();
            $table->string('name');

            $table->timestamps();

            $table->unique([
                'family_id',
                'name',
            ]);

            $table->foreign('family_id')
                ->references('id')
                ->on('occurrence_families')
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
        Schema::drop('occurrence_species');
    }
}
