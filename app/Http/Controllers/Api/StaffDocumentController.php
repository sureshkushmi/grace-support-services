<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StaffDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StaffDocumentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'document_type' => ['required', 'string', 'max:100'],
            'file' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);

        $path = $request->file('file')->store('staff-documents', 'public');

        $document = $request->user()->staffDocuments()->create([
            'document_type' => $request->document_type,
            'file_name' => $request->file('file')->getClientOriginalName(),
            'file_path' => $path,
            'uploaded_at' => now(),
        ]);

        return response()->json($document, 201);
    }

    public function destroy(Request $request, StaffDocument $staffDocument)
    {
        $isManager = in_array($request->user()->role->name, ['Admin', 'Manager']);

        if (! $isManager && $staffDocument->user_id !== $request->user()->id) {
            abort(403, 'You can only delete your own documents.');
        }

        Storage::disk('public')->delete($staffDocument->file_path);
        $staffDocument->delete();

        return response()->json(['message' => 'Document deleted.']);
    }
}
