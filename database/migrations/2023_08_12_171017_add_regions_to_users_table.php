<?php

use App\Models\City;
use App\Models\Province;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignIdFor(Province::class, 'province_id')->nullable()->after('military_status')->constrained('provinces')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(City::class, 'city_id')->nullable()->after('city_id')->constrained('cities')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['province_id', 'city_id']);
        });
    }
};