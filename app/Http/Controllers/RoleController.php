<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class RoleController extends Controller
{

    // Function to display Role table
    public function roleManager()
    {
        $roles = Role::all();
        $users = User::orderBy('id', 'asc')->get();;
        return view('roleManager', compact('roles', 'users'));
    }

    // Toggle status Active or Inactive when button toggled
    public function toggleStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:roles,id',
            'status' => 'required|boolean',
        ]);

        try {

            $role = Role::findOrFail($request->id);
            $role->status = $request->status;
            $role->save();

            return response()->json([
                'success' => true,
                'message' => 'Role status updated successfully.',
                'data' => $role,
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function addNewRole(Request $request)
    {
        try {

            $request->validate([
                'role_name' => 'required|unique:roles,role_name',
                'current_status' => 'required|in:0,1',
            ]);

            $role = new Role();
            $role->role_name = $request->input('role_name');
            $role->status = $request->input('current_status');
            $role->user_count = 1;
            $role->save();

            return response()->json([
                "status" => 200,
                "message" => "New role has been added successfully."
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                "status" => 422,
                "message" => "Validation error.",
                "errors" => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                "status" => 500,
                "message" => "An error occurred while adding the role.",
                "error" => $e->getMessage(),
            ], 500);
        }
    }
}
