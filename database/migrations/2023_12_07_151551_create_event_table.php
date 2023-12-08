<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();

            $table->string('name');
            $table->text('thumbnail_url')->nullable();
            $table->text('description');
            $table->date('date');
            $table->timestamp('start_time');
            $table->timestamp('end_time');
            $table->float('lat');
            $table->float('lon');
            $table->unsignedInteger('max_participant');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
