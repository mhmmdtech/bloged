<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'actioner_id')->comment('who performed the action')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('action', 100);
            $table->string('model_type', 100);
            $table->bigInteger('model_id');
            $table->json('old_model')->comment('the old structure of model');
            $table->json('new_model')->comment('the new structure of model');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};