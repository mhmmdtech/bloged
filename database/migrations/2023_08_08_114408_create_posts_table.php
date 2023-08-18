<?php

use App\Enums\PostStatus;
use App\Models\Category;
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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->text('thumbnail');
            $table->string('title', 100);
            $table->string('seo_title', 100);
            $table->string('description', 255);
            $table->string('seo_description', 255);
            $table->char('unique_id', 11)->unique();
            $table->string('slug', 150)->nullable();
            $table->text('body');
            $table->text('html_content');
            $table->boolean('is_featured')->default(0);
            $table->tinyInteger('reading_time')->nullable();
            $table->foreignIdFor(User::class, 'author_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(Category::class, 'category_id')->constrained('categories')->cascadeOnDelete()->cascadeOnUpdate();
            $table->tinyInteger('status')->default(PostStatus::Draft->value)->comment('1 => draft, 2 => published, 3 => archived');
            $table->timestamps();
            $table->softDeletes();

            $table->fullText(['title', 'seo_title', 'description', 'seo_description', 'body']);
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