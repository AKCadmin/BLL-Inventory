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
        $permissions = Permission::with('roles')->orderBy('id', 'asc')->get();
        return view('permissionManager', compact('roles', 'permissions'));
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
                'permission_name' => 'required|string|max:255|unique:permissions,permission_name',
                'role_id' => 'required|integer|exists:roles,id',
                'current_status' => 'required|in:0,1',
            ]);
             
            $permission = new Permission();
            $permission->permission_name = $request->input('permission_name');
            $permission->role_id = $request->input('role_id');
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
                'message' => 'An unexpected error occurred while adding the permission.',
            ]);
        }
    }
    
}
