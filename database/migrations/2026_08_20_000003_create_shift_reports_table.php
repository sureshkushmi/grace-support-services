<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shift_reports', function (Blueprint $table) {
            $table->id();

            // Who / when — staff_id links to the logged-in user rather than free text,
            // since we already know who's submitting. Participant stays free text for
            // Stage 1 to match the paper form; promote to a participants_id FK once the
            // participants table exists (Stage 2).
            $table->foreignId('staff_id')->constrained('users')->cascadeOnDelete();
            $table->string('participant_name'); // stored upper-cased to match "ALL CAPS" convention

            $table->date('support_date');
            $table->time('shift_start');
            $table->time('shift_end');
            $table->decimal('roster_hours', 5, 2)->nullable(); // entered, not auto-derived —
            // rostered hours can legitimately differ from clock time for pay purposes

            $table->text('progress_report');

            // Reimbursement / travel
            $table->decimal('reimbursement_amount', 8, 2)->nullable();
            $table->string('evidence_path')->nullable(); // uploaded receipt/photo for reimbursement
            $table->decimal('kilometre', 6, 2)->nullable();
            $table->string('kilometre_description')->nullable();

            // Signatures — stored as image files with who/when, per NDIS audit expectations
            $table->string('staff_signature_path');
            $table->timestamp('staff_signed_at');
            $table->string('client_signature_path')->nullable(); // client sign-off is optional
            $table->timestamp('client_signed_at')->nullable();

            // GPS capture on submit, per the original brief
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->timestamps();

            $table->index(['staff_id', 'support_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shift_reports');
    }
};
