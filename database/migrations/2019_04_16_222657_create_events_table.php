<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedTinyInteger('type_id');
            $table->unsignedSmallInteger('parish_id');

            $table->string('name');
            $table->text('description');

            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);

            $table->timestamp('started_at');
            $table->timestamp('ended_at')->nullable();

            $table->timestamps();

            $table->foreign('type_id')
                ->references('id')
                ->on('event_types')
                ->onUpdate('cascade');

            $table->foreign('parish_id')
                ->references('id')
                ->on('parishes')
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
        Schema::drop('events');
    }
}
