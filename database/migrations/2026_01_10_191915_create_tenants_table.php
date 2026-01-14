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
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('subdomain')->unique()->index();
            $table->string('custom_domain')->nullable()->unique()->index();
            
            // Status & Lifecycle
            $table->enum('status', ['trial', 'active', 'suspended', 'cancelled'])->default('trial');
            $table->timestamp('trial_ends_at')->nullable();
            $table->boolean('is_active')->default(true);
            
            // Branding
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            $table->string('primary_color')->nullable();
            $table->string('secondary_color')->nullable();
            $table->json('branding_config')->nullable(); // Extra branding options
            
            // Configuration
            $table->string('locale')->default('en');
            $table->string('timezone')->default('UTC');
            $table->json('feature_flags')->nullable(); // Enable/Disable features
            
            // Limits & Usage
            $table->unsignedBigInteger('storage_limit')->default(5368709120); // 5GB Default
            $table->unsignedBigInteger('storage_usage')->default(0);
            $table->integer('user_limit')->default(5);
            
            // Audit & Meta
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
