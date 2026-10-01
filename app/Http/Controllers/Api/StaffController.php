<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStaffRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeManager($request);

        $staff = User::with('role')->orderBy('first_name')->get();

        return response()->json($staff);
    }

    public function store(StoreStaffRequest $request)
    {
        $data = $request->validated();

        $role = Role::where('name', $data['role'])->firstOrFail();

        $user = User::create([
            'role_id' => $role->id,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'dob' => $data['dob'] ?? null,
            'address' => $data['address'] ?? null,
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'status' => true,
        ]);

        return response()->json($user->load('role'), 201);
    }

    public function show(Request $request, User $staff)
    {
        $this->authorizeManager($request);

        return response()->json($staff->load(['role', 'staffDocuments']));
    }

    public function update(Request $request, User $staff)
    {
        $this->authorizeAdmin($request);

        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'dob' => ['nullable', 'date'],
            'address' => ['nullable', 'string'],
            'email' => ['required', 'email', 'unique:users,email,'.$staff->id],
            'phone' => ['nullable', 'string', 'max:30'],
            'role' => ['required', Rule::in(['Worker', 'Manager'])],
        ]);

        $role = Role::where('name', $data['role'])->firstOrFail();
        unset($data['role']);
        $data['role_id'] = $role->id;

        $staff->update($data);

        return response()->json($staff->load('role'));
    }

    // Deactivate rather than delete - shift reports link back to staff_id,
    // and wiping a worker's account shouldn't wipe their report history.
    public function updateStatus(Request $request, User $staff)
    {
        $this->authorizeAdmin($request);

        $request->validate(['status' => ['required', 'boolean']]);

        $staff->update(['status' => $request->status]);

        return response()->json($staff->load('role'));
    }

    public function resetPassword(Request $request, User $staff)
    {
        $this->authorizeAdmin($request);

        $request->validate([
            'password' => ['required', 'string', 'min:8'],
        ]);

        $staff->update([
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'Password updated.']);
    }

    // Admin and Manager can both view staff
    private function authorizeManager(Request $request): void
    {
        if (! in_array($request->user()->role->name, ['Admin', 'Manager'])) {
            abort(403, 'Only Admin or Manager can view staff.');
        }
    }

    // Only Admin can create, edit, deactivate, or reset passwords
    private function authorizeAdmin(Request $request): void
    {
        if ($request->user()->role->name !== 'Admin') {
            abort(403, 'Only Admin can manage staff accounts.');
        }
    }
}