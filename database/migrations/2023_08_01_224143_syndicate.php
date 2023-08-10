<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('syndicates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignIdFor(User::class, 'owner_id');
            $table->timestamps();
        });

        Schema::create('syndicate_user', function (Blueprint $table) {
            $table->foreignId('user_id');
            $table->foreignId('syndicate_id');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('syndicates');
        Schema::dropIfExists('syndicate_user');
    }
};
