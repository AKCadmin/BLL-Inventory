<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use App\Models\Module;
use App\Models\AdvanceModuleMaster;
use App\Models\Page;
use App\Models\UserPagePermission;
use App\Models\PagePermission;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    // Function to display Role table
    public function permissionManager()
    {
        if (auth()->user()->cannot('view-permission-manager')) {
            abort(403); 
        }
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

            if (auth()->user()->cannot('add-permission-manager')) {
                abort(403); 
            }

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
                'message' => 'Permission Already Exist',
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
            if (auth()->user()->cannot('edit-permission-manager')) {
                abort(403); 
            }

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

    public function assignPermissions()
    {
        $users = User::where('id','!=',auth()->user()->id)->get();

        $pages = Page::all();

        return view('userPermissions', compact('users', 'pages'));
    }


    // public function savePermissions(Request $request)
    // {
    //     dd($request->all());
    //     // Get the selected user
    //     $user = User::find($request->user_id);

    //     // Loop through the selected permissions for each page
    //     foreach ($request->permissions as $pageId => $permissions) {
    //         // Find the page by ID
    //         $page = Page::find($pageId);

    //         // Loop through the permissions and store each one in the user_page_permissions table
    //         foreach ($permissions as $permissionSlug => $permissionValue) {
    //             $permission = PagePermission::where('name', $permissionSlug)->first();

    //             if ($permission) {
    //                 // Check if permission already exists for this user, page, and permission
    //                 $userPagePermission = UserPagePermission::where('user_id', $user->id)
    //                     ->where('page_id', $page->id)
    //                     ->where('permission_id', $permission->id)
    //                     ->first();

    //                 if ($userPagePermission) {
    //                     // If record exists, update it
    //                     $userPagePermission->update([
    //                         'permission_id' => $permission->id,
    //                     ]);
    //                 } else {
    //                     // If record doesn't exist, create a new one
    //                     UserPagePermission::create([
    //                         'user_id' => $user->id,
    //                         'page_id' => $page->id,
    //                         'permission_id' => $permission->id,
    //                         // 'company_id' => $request->company_id, // Assuming company_id is part of the form
    //                     ]);
    //                 }
    //             }
    //         }
    //     }

    //     // Redirect or return a success message
    //     return redirect()->route('permissions.index')->with('success', 'Permissions updated successfully.');
    // }

    public function savePermissions(Request $request)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'permissions' => 'required|array',
                'permissions.*' => 'array',
                'permissions.*.*' => 'in:1,2,3,4',
            ]);
    
            DB::beginTransaction();
    
            // Get all page IDs from the request
            $pageIds = array_keys($validated['permissions']);
    
            // Delete permissions for pages that are not in the request
            DB::table('user_page_permissions')
                ->where('user_id', $validated['user_id'])
                ->whereNotIn('page_id', $pageIds)
                ->delete();
    
            // Update or create remaining permissions
            foreach ($validated['permissions'] as $pageId => $permission) {
                // Filter out any invalid values that might have passed validation
                $cleanPermissions = array_filter($permission, fn($p) => in_array($p, ['1', '2', '3', '4']));
                
                DB::table('user_page_permissions')->updateOrInsert(
                    ['user_id' => $validated['user_id'], 'page_id' => $pageId],
                    ['page_permission' => json_encode(array_values($cleanPermissions))]
                );
            }
    
            DB::commit();
    
            return response()->json(['success' => 200, 'message' => 'Permissions saved successfully.']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Validation Error',
                'details' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'An unexpected error occurred',
                'details' => $e->getMessage()
            ], 500);
        }
    }
    
    public function getUserPermissions(Request $request)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
            ]);
    
            $userId = $validated['user_id'];
    
            $pages = Page::with(['permissions' => function ($query) use ($userId) {
                $query->where('user_id', $userId);
            }])->get();
    
            return response()->json($pages, 200);
        } catch (\ValidationException $e) {
            return response()->json([
                'error' => 'Validation Error',
                'details' => $e->errors()
            ], 422);
        } catch (\QueryException $e) {
            return response()->json([
                'error' => 'Database Error',
                'details' => $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An unexpected error occurred',
                'details' => $e->getMessage()
            ], 500);
        }
    }
    
}
