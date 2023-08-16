<?php

use App\Enums\ProvinceStatus;
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
        Schema::create('provinces', function (Blueprint $table) {
            $table->id();
            $table->string('local_name');
            $table->string('latin_name')->nullable();
            $table->foreignIdFor(User::class, 'creator_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->tinyInteger('status')->default(ProvinceStatus::Active->value)->comment('1 => active, 2 => disable');
            $table->timestamps();
            $table->softDeletes();

            $table->fullText(['local_name', 'latin_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provinces');
    }
};