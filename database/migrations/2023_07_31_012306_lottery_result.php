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
        Schema::create('lottery_results', function (Blueprint $table) {
            $table->id();
            $table->date('draw_date');
            $table->integer('ball_one');
            $table->integer('ball_two');
            $table->integer('ball_three');
            $table->integer('ball_four');
            $table->integer('ball_five');
            $table->integer('ball_lp_one');
            $table->integer('ball_lp_two');
            $table->integer('draw_number');
            $table->string('millionaire_maker');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lottery_results');
    }
};
