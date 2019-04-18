<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParishesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('parishes', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('county_id');

            $table->string('code')->unique();
            $table->string('name');

            $table->timestamps();

            $table->unique([
                'county_id',
                'name',
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
        Schema::drop('parishes');
    }
}
