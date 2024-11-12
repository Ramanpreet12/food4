<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('phone');
            $table->string('altNumber')->nullable();
            $table->text('address')->nullable();
            $table->text('image')->nullable();
            $table->text('gender')->nullable();
            $table->enum('status' , ['active' , 'inactive'])->default('active');
            $table->enum('role', ['admin', 'restaurant_owner', 'customer', 'delivery'])->default('customer');
            // $table->string('role')->default('customer');  // Default role:
            $table->string('otp')->nullable();
            $table->timestamp('otp_expire_at')->nullable()->after('otp');
            $table->boolean('is_otp_verified')->default(false)->after('otp_expire_at');

            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
