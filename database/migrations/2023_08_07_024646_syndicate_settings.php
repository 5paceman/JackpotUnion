<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Syndicate;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('syndicate_settings', function(Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Syndicate::class);
            $table->longText('rules');
            $table->boolean('email_on_win')->default(true);
            $table->boolean('email_on_drawn')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('syndicate_settings');
    }
};
