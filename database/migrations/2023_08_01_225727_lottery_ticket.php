<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Syndicate;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lottery_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'added_by');
            $table->foreignIdFor(Syndicate::class, 'syndicate_id');
            $table->boolean('drawn')->default(false);
            $table->boolean('won')->default(false);
            $table->integer('matched_balls')->default(0);
            $table->integer('matched_lucky_dip')->default(0);
            $table->date('draw_date');
            $table->integer('ball_one');
            $table->integer('ball_two');
            $table->integer('ball_three');
            $table->integer('ball_four');
            $table->integer('ball_five');
            $table->integer('ball_lp_one');
            $table->integer('ball_lp_two');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lottery_tickets');
    }
};
