<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_backups', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('manual'); // manual, scheduled, before_restore
            $table->string('status')->default('PENDING'); // PENDING, SUCCESS, FAILED
            $table->string('disk')->default('local');
            $table->string('path');
            $table->string('filename');
            $table->unsignedBigInteger('size_bytes')->default(0);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->unsignedBigInteger('duration_ms')->nullable();
            $table->foreignId('triggered_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });

        Schema::create('system_audits', function (Blueprint $table) {
            $table->id();
            $table->string('module', 100);
            $table->string('action', 100);
            $table->string('level', 20)->default('INFO');
            $table->text('message');
            $table->json('context')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('user_name')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->index(['module', 'action']);
            $table->index(['level']);
        });

        Schema::create('system_alerts', function (Blueprint $table) {
            $table->id();
            $table->string('code', 100)->unique();
            $table->string('title');
            $table->string('level', 20)->default('ERROR'); // INFO, WARNING, ERROR, CRITICAL
            $table->string('component', 100);
            $table->text('message');
            $table->string('status', 20)->default('OPEN'); // OPEN, RESOLVED
            $table->timestamp('first_seen_at')->nullable();
            $table->timestamp('last_seen_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->json('meta')->nullable();
            $table->foreignId('resolved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['component', 'status']);
        });

        Schema::create('system_incidents', function (Blueprint $table) {
            $table->id();
            $table->string('code', 100)->unique();
            $table->string('title');
            $table->string('level', 20)->default('MAJEUR'); // MINEUR, MAJEUR, CRITIQUE
            $table->string('source', 100)->nullable();
            $table->text('impact')->nullable();
            $table->string('status', 20)->default('OPEN'); // OPEN, IN_PROGRESS, RESOLVED, CLOSED
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->longText('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('resolved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['status', 'level']);
        });

        Schema::create('system_task_runs', function (Blueprint $table) {
            $table->id();
            $table->string('task_key', 100);
            $table->string('label');
            $table->string('command');
            $table->string('status', 20)->default('SUCCESS'); // SUCCESS, FAILED
            $table->longText('output')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->unsignedBigInteger('duration_ms')->nullable();
            $table->foreignId('triggered_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['task_key', 'started_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_task_runs');
        Schema::dropIfExists('system_incidents');
        Schema::dropIfExists('system_alerts');
        Schema::dropIfExists('system_audits');
        Schema::dropIfExists('system_backups');
    }
};