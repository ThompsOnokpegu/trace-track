<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email'); // Critical for WhatsApp
            $table->string('address')->nullable()->after('phone');
            $table->string('company_name')->nullable()->after('name'); // Optional, if B2B
            $table->boolean('is_admin')->default(false)->after('id'); // Simple Role check
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'address', 'company_name', 'is_admin']);
        });
    }
};