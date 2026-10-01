<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShiftReport extends Model
{
    protected $fillable = [
        'staff_id',
        'participant_name',
        'status',
        'reviewed_by',
        'reviewed_at',
        'support_date',
        'shift_start',
        'shift_end',
        'roster_hours',
        'progress_report',
        'reimbursement_amount',
        'evidence_path',
        'kilometre',
        'kilometre_description',
        'staff_signature_path',
        'staff_signed_at',
        'client_signature_path',
        'client_signed_at',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'support_date' => 'date',
        'staff_signed_at' => 'datetime',
        'client_signed_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    public function staff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}