<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use App\Models\Module;
use App\Models\AdvanceModuleMaster;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    // Function to display Role table
    public function permissionManager()
    {
        $roles = Role::orderBy('id', 'asc')->get();
        $assignedRoleIds = Permission::pluck('role_id')->toArray();
        $availableRoles = $roles->filter(function ($role) use ($assignedRoleIds) {
            return !in_array($role->id, $assignedRoleIds);
        });
        $permissions = Permission::with('roles')->orderBy('id', 'asc')->get();


        return view('permissionManager', compact('availableRoles', 'permissions'));
    }

    public function toggleStatus(Request $request)
    {
        try {

            $request->validate([
                'id' => 'required|integer|exists:permissions,id',
                'status' => 'required|boolean',
            ]);

            $permission = Permission::findOrFail($request->id);


            $permission->status = $request->status;
            $permission->save();

            return response()->json([
                'success' => true,
                'message' => 'Permission status updated successfully.',
                'data' => $permission,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Permission not found.',
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error in toggleStatus: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the permission status.',
            ], 500);
        }
    }

    public function addNewPermission(Request $request)
    {

        try {

            $request->validate([
                'role_id' => 'required|integer|exists:roles,id',
                'current_status' => 'required|in:0,1',
                'menu_options' => 'array|nullable',
            ]);

            $permission = new Permission();
            $permission->role_id = $request->input('role_id');
            if ($request->has('menu_options')) {
                $permission->menus = json_encode($request->input('menu_options'));
            }
            $permission->status = $request->input('current_status');
            $permission->save();

            return response()->json([
                'status' => 200,
                'message' => 'New permission has been added successfully.',
                'data' => $permission,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 422,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ]);
        } catch (\Exception $e) {
            \Log::error('Error adding permission: ' . $e->getMessage());

            return response()->json([
                'status' => 500,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function updatePermission(Request $request, $menuId)
    {
        try {
         
            $request->validate([
                'role_id' => 'required|integer|exists:roles,id',
                'current_status' => 'required|in:0,1',
                'menu_options' => 'array|nullable',
            ]);

            $permission = Permission::findOrFail($menuId); 

            $permission->role_id = $request->input('role_id');
            if ($request->has('menu_options')) {
                $permission->menus = json_encode($request->input('menu_options'));
            }
            $permission->status = $request->input('current_status');

            $permission->save();

            return response()->json([
                'status' => 200,
                'message' => 'Permission has been updated successfully.',
                'data' => $permission,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Return validation errors
            return response()->json([
                'status' => 422,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ]);
        } catch (\Exception $e) {
            // Log and return generic error
            \Log::error('Error updating permission: ' . $e->getMessage());

            return response()->json([
                'status' => 500,
                'message' => $e->getMessage(),
            ]);
        }
    }


    public function getAvailableRoles($currentRole = null)
    {
        try {
          
            $roles = Role::orderBy('id', 'asc')->get();
            $assignedRoleIds = Permission::pluck('role_id')->toArray();
            if ($currentRole) {
                $assignedRoleIds = array_diff($assignedRoleIds, [$currentRole]);
            }
            $availableRoles = $roles->filter(function ($role) use ($assignedRoleIds, $currentRole) {
                return !in_array($role->id, $assignedRoleIds) || $role->id == $currentRole;
            });
            return response()->json($availableRoles);
        } catch (\Exception $e) {
            // Log the error and return an error response
            \Log::error('Error fetching available roles: ' . $e->getMessage());
    
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred while fetching available roles.',
            ], 500);
        }
    }
    
}
