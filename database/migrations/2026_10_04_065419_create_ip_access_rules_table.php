<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ip_access_rules', function (Blueprint $table) {
            $table->id();
            // Basic information
            $table->string('name')->nullable();
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            // Content
            $table->text('paragraph')->nullable();
            $table->longText('details')->nullable();
            // Public IP
            $table->string('ip_address', 45)->unique();
            // Assigned existing Category IDs
            $table->json('category_ids')->nullable();
            // Assigned existing Service IDs
            $table->json('service_ids')->nullable();
            // Extra remark
            $table->text('remark')->nullable();
            // Active / Inactive
            $table->boolean('status')->default(true);
            // Optional expiry
            $table->dateTime('expires_at')->nullable();
            $table->timestamps();
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ip_access_rules');
    }
};