<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('counties', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedTinyInteger('district_id');

            $table->string('code')->unique();
            $table->string('name');

            $table->timestamps();

            $table->unique([
                'district_id',
                'name',
            ]);

            $table->foreign('district_id')
                ->references('id')
                ->on('districts')
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
        Schema::drop('counties');
    }
}
