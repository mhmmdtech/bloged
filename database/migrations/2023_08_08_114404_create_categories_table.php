<?php

use App\Enums\CategoryStatus;
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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->text('thumbnail');
            $table->string('title', 100);
            $table->string('seo_title', 100);
            $table->string('description', 255);
            $table->string('seo_description', 255);
            $table->char('unique_id', 11)->unique();
            $table->string('slug', 150)->nullable();
            $table->foreignIdFor(User::class, 'creator_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->tinyInteger('status')->default(CategoryStatus::Disable->value)->comment('1 => active, 2 => disable');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};