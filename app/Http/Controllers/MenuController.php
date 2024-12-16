<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Menu;
use App\Models\Permission;
use App\Models\Module;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;


class MenuController extends Controller
{

    public function menu()
    {
        $roles = Role::all();
        $menus = Menu::orderBy('id','asc')->get();

        return view('menu', compact('menus', 'roles',));
    }

    public function toggleStatus(Request $request)
    {
        if($request->db_name){
            $dbName = $request->db_name;
            config(['database.connections.pgsql.database' => $dbName]);
            DB::purge('pgsql');
            DB::connection('pgsql')->getPdo();
        }
        $menu = Menu::find($request->id);
        if ($menu) {
            $menu->status = $request->status;
            $menu->save();
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function addNewMenu(Request $request)
    {
        if($request->db_name){
            $dbName = $request->db_name;
            config(['database.connections.pgsql.database' => $dbName]);
            DB::purge('pgsql');
            DB::connection('pgsql')->getPdo();
        }
    
        $request->validate([
            'menu_name' => 'required|string|unique:menus,menu_name|regex:/^[A-Za-z0-9_]+$/',
            'current_status' => 'required|in:0,1',
            'role_ids' => 'required|array|min:1', 
            'role_ids.*' => 'exists:roles,id', 
        ]);

        $roleIds = $request->input('role_ids');

        $roles = Role::whereIn('id', $roleIds)->pluck('role_name', 'id')->toArray();

        $roleNames = [];
        foreach ($roleIds as $roleId) {
            if (isset($roles[$roleId])) {
                $roleNames[] = $roles[$roleId];
            }
        }
        
        $menuName = $request->input('menu_name');
        $word = "Controller";
        $controllerName = ucfirst($menuName) . "" . $word;

        $menuName_lowercase = strtolower($menuName);


        $menu = new Menu();
        $menu->menu_name = $request->input('menu_name');
        $menu->roles = json_encode($roleNames); // Store role IDs as JSON
        $menu->status = $request->input('current_status');
        // $menu->route = $menuName_lowercase;

        $menu->save();



        try {
            // Command to create a new controller
            // Artisan::call('make:controller ' . $controllerName);

            // $output = Artisan::output();
            // //return redirect('menu')->with('success', 'New menu has been added successfully.', $output);

            // $controller_filepath =  __DIR__ . '/' . $controllerName . '.php'; // The nre controller file 


            // //Clear all contents of controller file to write new function

            // // Open the file in write mode, which clears all content
            // $file = fopen($controller_filepath, 'w');

            // if ($file) {
            //     fclose($file);
            //     echo "File content removed successfully!";
            // } else {
            //     echo "Failed to open the file!";
            // }


            // // Add new function in controller
            // $newContent = "<?php

            // namespace App\Http\Controllers;

            // use Illuminate\Http\Request;

            // class " . $controllerName . " extends Controller
            // {
            //         public function " . $menuName . "()
            //     {

            //         echo 'this is " . $menuName . " controller';
            //         //return view('menu', compact('menus', 'roles',));
            //     }
            // }";

            // //open file
            // $controller_file = fopen($controller_filepath, 'a');

            // if ($controller_file) {
            //     fwrite($controller_file, $newContent); // Write the new content
            //     fclose($controller_file); // Close the file to save changes
            // } else {
            //     echo "something went wrong";
            // }



            // # Add Routing 
            // $route_filepath = __DIR__ . '/../../../routes/web.php';

            // $newRoute = "
            // use App\Http\Controllers\\" . $controllerName . ";
            
            // Route::group(['middleware' => 'auth'], function () {
            //     Route::get('/" . $menuName_lowercase . "', [" . $controllerName . "::class, '" . $menuName . "'])->name('" . $menuName_lowercase . "');
            // });";

            // $router_file = fopen($route_filepath, 'a');

            // if ($router_file) {
            //     fwrite($router_file, $newRoute); // Write the new content
            //     fclose($router_file); // Close the file to save changes
            // } else {
            //     echo "something went wrong";
            // }


            //return redirect($menuName_lowercase);
            return response()->json(['status'=>200,'success' => true, 'message' => "menu added successfully"]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    /**
 * @OA\Delete(
 *     path="/menus/{id}",
 *     summary="Delete a menu item",
 *     description="Deletes a specific menu item, its associated route, and controller if it is marked as deletable.",
 *     operationId="deleteMenu",
 *     tags={"Menus"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer"),
 *         description="ID of the menu item"
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Menu item deleted successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true)
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Menu item is not deletable",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="error", type="string", example="Error: File is not deletable")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Menu item not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false)
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error",
 *     )
 * )
 */

    // Function to Delete Controller and routing after deleting menu
    // public function destroy($id)
    // {
        
    //     $menu = Menu::find($id);
    //     $menuName = $menu->menu_name;
    //     $word = "Controller";
    //     $controllerName = ucfirst($menuName) . "" . $word;
    //     $menuName_lowercase = strtolower($menuName);

    //     //echo $menu->is_deletable; exit();
    //     if ($menu->is_deletable == 1) {
    //         try {

    //             // Delete Routing
    //             $web_filepath = __DIR__ . '/../../../routes/web.php'; // Path to web.php

    //             // Read the entire content of the file
    //             $fileContent = file_get_contents($web_filepath);

    //             // Define the code block to remove
    //             $codeToRemove = "
    //         use App\Http\Controllers\\" . $controllerName . ";
            
    //         Route::group(['middleware' => 'auth'], function () {
    //             Route::get('/" . $menuName_lowercase . "', [" . $controllerName . "::class, '" . $menuName . "'])->name('" . $menuName_lowercase . "');
    //         });";

    //             // Remove the code block from the file content
    //             $fileContent = str_replace($codeToRemove, '', $fileContent);

    //             // Write the updated content back to the file
    //             if (file_put_contents($web_filepath, $fileContent)) {
    //                 echo "Route was removed successfully!";
    //             } else {
    //                 echo "Failed to update the file.";
    //             }


    //             //Delete Controller
    //             $filePath = __DIR__ . '/' . $controllerName . '.php';

    //             // Check if the file exists
    //             if (file_exists($filePath)) {
    //                 // Attempt to delete the file
    //                 if (unlink($filePath)) {
    //                     echo "Controller was deleted successfully.";
    //                 } else {
    //                     echo "Error: Unable to delete the controller.";
    //                 }
    //             } else {
    //                 echo "Error: File does not exist.";
    //             }


    //             $menu = Menu::findOrFail($id);
    //             $menu->delete();
    //         } catch (\Exception $e) {
    //             Log::error($e->getMessage());
    //             return response()->json(['success' => false, 'error' => $e->getMessage()]);
    //         }


    //         // Return a simple success message
    //         return response()->json(['Success' => true]);
    //     }
    //     else{
    //         echo "Error: File is not deletable";
    //     }
    // }

    public function destroy(Request $request, $id)
    {
        if($request->db_name){
            $dbName = $request->db_name;
            config(['database.connections.pgsql.database' => $dbName]);
            DB::purge('pgsql');
            DB::connection('pgsql')->getPdo();
        }  
        $menu = Menu::find($id);
        if ($menu->is_deletable == 1) {
            try {
                $menu = Menu::findOrFail($id);
                $menu->delete();
                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                return response()->json(['success' => false, 'error' => $e->getMessage()]);
            }
            return response()->json(['Success' => true]);
        }
        else{
            echo "Error: File is not deletable";
        }
    }
}
