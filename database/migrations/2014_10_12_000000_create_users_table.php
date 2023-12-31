<?php

use App\Enums\MilitaryStatus;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('national_code', 100)->unique();
            $table->string('mobile_number', 100);
            $table->tinyInteger('gender')->comment('1 => male, 2 => female');
            $table->string('email', 255)->unique();
            $table->string('username', 100)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->foreignIdFor(User::class, 'creator_id')->nullable()->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('avatar')->nullable();
            $table->date('birthday')->nullable();
            $table->tinyInteger('military_status')->nullable()->comment('1 => temporary exemption, 2 => permanent exemption, 3 => done');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};