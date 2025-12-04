<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\ProjectStage;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create Projects Table
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // The Client
            $table->string('tracking_code')->unique(); // Random 8-char string for public access
            $table->string('title'); // e.g., "Lekki Phase 1 - Duplex Lift"
            $table->string('location')->nullable();
            $table->string('current_status')->default(ProjectStage::SCHEDULED_VISIT->value);
            $table->date('installation_date')->nullable();
            $table->text('notes')->nullable(); // Internal Admin notes
            $table->timestamps();
        });

        // 2. Create Project Updates (History) Table
        Schema::create('project_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->string('status_key'); // Stores the enum value at that time
            $table->text('description')->nullable(); // e.g. "Container arrived at Apapa Port"
            $table->boolean('notify_client')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_updates');
        Schema::dropIfExists('projects');
    }
};
