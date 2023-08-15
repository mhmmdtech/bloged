<?php

use App\Enums\CityStatus;
use App\Models\Province;
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
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('local_name');
            $table->string('latin_name')->nullable();
            $table->foreignIdFor(Province::class, 'province_id')->constrained('provinces')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(User::class, 'creator_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->tinyInteger('status')->default(CityStatus::Active->value)->comment('1 => active, 2 => disable');
            $table->timestamps();

            $table->fullText(['local_name', 'latin_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};