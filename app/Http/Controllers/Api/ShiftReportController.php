<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShiftReportRequest;
use App\Http\Requests\UpdateShiftReportRequest;
use App\Models\ShiftReport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ShiftReportController extends Controller
{
    public function store(ShiftReportRequest $request)
    {
        $data = $request->validated();

        $data['staff_id'] = $request->user()->id;
        $data['participant_name'] = strtoupper($data['participant_name']);

        $data['staff_signature_path'] = $this->saveBase64Image($data['staff_signature'], 'signatures');
        $data['staff_signed_at'] = now();
        unset($data['staff_signature']);

        if (! empty($data['client_signature'])) {
            $data['client_signature_path'] = $this->saveBase64Image($data['client_signature'], 'signatures');
            $data['client_signed_at'] = now();
        }
        unset($data['client_signature']);

        if ($request->hasFile('evidence')) {
            $data['evidence_path'] = $request->file('evidence')->store('evidence', 'public');
        }
        unset($data['evidence']);

        $report = ShiftReport::create($data);

        return response()->json($report->load('staff'), 201);
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $query = ShiftReport::with('staff')->latest('support_date');

        $isManager = in_array($user->role->name, ['Admin', 'Manager']);

        if ($isManager) {
            if ($request->filled('staff_id')) {
                $query->where('staff_id', $request->staff_id);
            }
        } else {
            $query->where('staff_id', $user->id);
        }

        return response()->json($query->paginate(20));
    }

    public function show(Request $request, ShiftReport $shiftReport)
    {
        $this->authorizeAccess($request, $shiftReport);

        return response()->json($shiftReport->load('staff'));
    }

    public function update(UpdateShiftReportRequest $request, ShiftReport $shiftReport)
    {
        $user = $request->user();
        $isManager = in_array($user->role->name, ['Admin', 'Manager']);

        if (! $isManager) {
            if ($shiftReport->staff_id !== $user->id) {
                abort(403, 'You can only edit your own reports.');
            }
            if ($shiftReport->status !== 'pending') {
                abort(403, 'This report has already been reviewed and can no longer be edited.');
            }
        }

        $data = $request->validated();
        $data['participant_name'] = strtoupper($data['participant_name']);

        // Signatures are optional on edit - only replace if a new one was
        // actually drawn, so correcting a typo doesn't force re-signing
        if (! empty($data['staff_signature'])) {
            $data['staff_signature_path'] = $this->saveBase64Image($data['staff_signature'], 'signatures');
            $data['staff_signed_at'] = now();
        }
        unset($data['staff_signature']);

        if (! empty($data['client_signature'])) {
            $data['client_signature_path'] = $this->saveBase64Image($data['client_signature'], 'signatures');
            $data['client_signed_at'] = now();
        }
        unset($data['client_signature']);

        if ($request->hasFile('evidence')) {
            $data['evidence_path'] = $request->file('evidence')->store('evidence', 'public');
        }
        unset($data['evidence']);

        $shiftReport->update($data);

        return response()->json($shiftReport->load('staff'));
    }

    // Admin only - deleting a report removes history permanently
    public function destroy(Request $request, ShiftReport $shiftReport)
    {
        if ($request->user()->role->name !== 'Admin') {
            abort(403, 'Only Admin can delete reports.');
        }

        $shiftReport->delete();

        return response()->json(['message' => 'Report deleted.']);
    }

    public function accept(Request $request, ShiftReport $shiftReport)
    {
        $this->authorizeManager($request);

        $shiftReport->update([
            'status' => 'accepted',
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => now(),
        ]);

        return response()->json($shiftReport->load('staff'));
    }

    public function reject(Request $request, ShiftReport $shiftReport)
    {
        $this->authorizeManager($request);

        $shiftReport->update([
            'status' => 'rejected',
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => now(),
        ]);

        return response()->json($shiftReport->load('staff'));
    }

    public function downloadPdf(Request $request, ShiftReport $shiftReport)
    {
        $this->authorizeAccess($request, $shiftReport);

        $pdf = Pdf::loadView('pdf.shift-report', [
            'shiftReport' => $shiftReport->load('staff'),
        ]);

        return $pdf->download('shift-report-'.$shiftReport->id.'.pdf');
    }

    private function authorizeAccess(Request $request, ShiftReport $shiftReport): void
    {
        $user = $request->user();
        $isManager = in_array($user->role->name, ['Admin', 'Manager']);

        if (! $isManager && $shiftReport->staff_id !== $user->id) {
            abort(403, 'You can only access your own reports.');
        }
    }

    private function authorizeManager(Request $request): void
    {
        if (! in_array($request->user()->role->name, ['Admin', 'Manager'])) {
            abort(403, 'Only Admin or Manager can review reports.');
        }
    }

    private function saveBase64Image(string $base64, string $folder): string
    {
        [$type, $data] = explode(';base64,', $base64);
        $extension = str_replace('data:image/', '', $type);
        $fileName = $folder.'/'.Str::uuid().'.'.$extension;

        Storage::disk('public')->put($fileName, base64_decode($data));

        return $fileName;
    }
}