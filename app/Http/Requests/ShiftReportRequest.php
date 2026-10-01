<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShiftReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // route already requires auth:sanctum
    }

    public function rules(): array
    {
        return [
            'participant_name' => ['required', 'string', 'max:255'],
            'support_date' => ['required', 'date'],
            'shift_start' => ['required', 'date_format:H:i'],
            'shift_end' => ['required', 'date_format:H:i', 'after:shift_start'],
            'roster_hours' => ['nullable', 'numeric', 'min:0'],
            'progress_report' => ['required', 'string'],
            'reimbursement_amount' => ['nullable', 'numeric', 'min:0'],
            'evidence' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'kilometre' => ['nullable', 'numeric', 'min:0'],
            'kilometre_description' => ['nullable', 'string', 'max:255'],
            'staff_signature' => ['required', 'string', 'starts_with:data:image'],
            'client_signature' => ['nullable', 'string', 'starts_with:data:image'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ];
    }
}