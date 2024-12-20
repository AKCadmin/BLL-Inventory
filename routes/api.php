<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccommodationController;
use App\Http\Controllers\AdvanceModuleController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\paymentController;
use App\Http\Controllers\TokenController;
use App\Http\Middleware\BearerTokenMiddleware;
use OpenApi\Annotations as OA;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\userManagement;
use App\Http\Middleware\PermissionMiddleware;

/**
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="API Server"
 * )
 */

// Route::get('/api/documentation', '\L5Swagger\Http\Controllers\SwaggerController@api');


Route::post('/generate-token', [TokenController::class, 'generateToken']);



/**
 * @OA\Post(
 *     path="/api/add-properties",
 *     summary="Add properties with optional attributes",
 *     tags={"Properties"},
 *     security={{"bearer": {}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="property_name", type="string", example="Hotel XYZ"),
 *             @OA\Property(property="property_location", type="string", example="New York"),
 *             @OA\Property(property="type_name", type="string", example="Hotel"),
 *             @OA\Property(property="status", type="integer", example="1"),
 *             @OA\Property(property="category_name", type="string", example="Luxury")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Property added successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="success"),
 *             @OA\Property(property="data", type="object")
 *         )
 *     ),
 *     @OA\Response(response=400, description="Bad Request"),
 * )
 */

/**
 * @OA\Post(
 *     path="/api/add-properties-type",
 *     summary="Add property types",
 *     tags={"Properties"},
 *     security={{"bearer": {}}},
 *     // Add your parameters and responses for this endpoint
 * )
 */

/**
 * @OA\Post(
 *     path="/api/add-properties-category",
 *     summary="Add property categories",
 *     tags={"Properties"},
 *     security={{"bearer": {}}},
 *     // Add your parameters and responses for this endpoint
 * )
 */



Route::middleware([BearerTokenMiddleware::class])->group(function () {
    //role routes
    // Route::middleware([PermissionMiddleware::class.':create_user'])->group(function () {
    // Route::post('/add-new-role', [RoleController::class, 'addNewRole'])->name('roles.add');
    // });
    Route::post('/roles/toggle-status', [RoleController::class, 'toggleStatus'])->name('roles.toggleStatus');

    //permission routes
    Route::post('/add-new-permission', [PermissionController::class, 'addNewPermission'])->name('permissions.add');
    Route::put('/update-permission/{id}', [PermissionController::class, 'updatePermission'])->name('permissions.update');
    Route::get('/available-roles/{id}', [PermissionController::class, 'getAvailableRoles'])->name('availableRoles');
    Route::post('/permissions/toggle-status', [PermissionController::class, 'toggleStatus'])->name('permissions.toggleStatus'); 

    //user management
    Route::post('/user/create', [userManagement::class, 'create'])->name('user.create');
    Route::post('/user/status', [userManagement::class, 'status'])->name('user.toggleStatus');
    Route::post('/user/migration', [userManagement::class, 'userMigration'])->name('user.migration');
    Route::get('/user/{id}/edit', [userManagement::class, 'edit'])->name('user.edit');
    Route::post('/user/update', [userManagement::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [userManagement::class, 'delete'])->name('users.delete');
    //menu management routes
    Route::post('/add-new-menu', [MenuController::class, 'addNewMenu'])->name('menus.add');
    Route::post('/menus/toggle-status', [MenuController::class, 'toggleStatus'])->name('menus.toggleStatus'); // URL should be kebab-case for consistency
    Route::delete('/menus/{id}', [MenuController::class, 'destroy'])->name('menus.destroy');
});