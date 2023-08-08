<?php

use App\Enums\PostStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->text('thumbnail');
            $table->string('title');
            $table->string('seo_title');
            $table->string('description');
            $table->string('seo_description');
            $table->text('body');
            $table->boolean('is_featured')->default(0);
            $table->tinyInteger('status')->default(PostStatus::Draft->value)->comment('1 => draft, 2 => published, 3 => archived');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};