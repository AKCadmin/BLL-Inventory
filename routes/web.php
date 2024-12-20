<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\AccommodationController;
use App\Http\Controllers\AdvanceModuleController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\paymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SellController;
use App\Http\Controllers\SellCornerController;
use App\Http\Controllers\userManagement;
use App\Http\Middleware\PermissionMiddleware;
use App\Models\user;
use App\Models\Menu;
use Illuminate\Support\Facades\Artisan;
use Modules\Cricbuzz\Http\Controllers\CricbuzzController;
use App\Http\Controllers\StockController;




// Show the app timezone and current time
Route::get('/current-time', function () {
    $appTimezone = Config::get('app.timezone');
    $currentTime = Carbon::now($appTimezone);
    echo 'App Timezone:   ' . $appTimezone . '<br>';
    echo 'Current Time:   ' . $currentTime->format('d-m-y H:i:s');
});

// Route::get('/trigger-404', [FrontController::class,'error404'])->name('/trigger-404');
// Route::get('/trigger-500', [FrontController::class,'error500'])->name('/trigger-500');

Route::get('/optimize', function () {
    Artisan::call('optimize');
    return response()->json(['success' => true]);
})->name('optimize');

// Guest middleware for unauthenticated users
Route::group(['middleware' => 'guest'], function () {

    Route::get('/', [FrontController::class, 'index'])->name('index');// Root should use `/` instead of an empty string

    // Registration routes
    Route::get('/register', [RegistrationController::class, 'register_index'])->name('register');
    Route::post('/register', [RegistrationController::class, 'register'])->name('register.submit'); // Changed name to distinguish POST

    // Login routes
    Route::get('/login', [LoginController::class, 'login_index'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit'); // Changed name to distinguish POST

    // Forgot Password routes
    Route::get('/forget-password', [PasswordResetController::class, 'forgetPassword_index'])->name('forget-password');
    Route::post('/forget-password', [PasswordResetController::class, 'forgetPassword'])->name('forget-password.submit'); // Changed name to distinguish POST

    // Reset Password routes
    Route::get('/reset-password', [PasswordResetController::class, 'resetPassword_index'])->name('reset-password');
    Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])->name('reset-password.submit'); // Changed name to distinguish POST

    // Account activation route (uncomment the POST route if activation is required)
    Route::get('/activate-account', [RegistrationController::class, 'activateAccount_index'])->name('activate-account');
    // Route::post('/activate-account', [RegistrationController::class, 'activateAccount'])->name('activate-account.submit');
});


// Auth middleware for authenticated users only
Route::group(['middleware' => 'auth'], function () {

    Route::get('/home', [FrontController::class, 'home'])->name('home')->middleware('can:dashboard');

    // Logout routes
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout'); // POST is better practice for logout
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout.get'); // In case GET is needed, but not recommended

    // Role management routes
    Route::get('/role-manager', [RoleController::class, 'roleManager'])->name('role-manager');
    // Route::middleware([PermissionMiddleware::class . ':add_role'])->group(function () {
      
        Route::post('/add-new-role', [RoleController::class, 'addNewRole'])->name('roles.add');
    // });
    //Route::post('/add-new-role', [RoleController::class, 'addNewRole'])->name('roles.add');

    // Permission management routes
    Route::get('/permission-manager', [PermissionController::class, 'permissionManager'])->name('permission-manager');
    // URL should be kebab-case for consistency
    // Route::post('/add-new-permission', [PermissionController::class, 'addNewPermission'])->name('permissions.add');


    // Menu management routes
    Route::get('/menu', [MenuController::class, 'menu'])->name('menu');


    //user management routes
    Route::get('/management', [userManagement::class, 'show'])->name('user.management')->middleware('can:user_management');

    //stock
    Route::resource('company',CompanyController::class)->middleware('can:company');
    Route::get('company/edit/{company}', [CompanyController::class, 'edit'])->name('company.editCompany')->middleware('can:company');
    Route::Post('company/{company}', [CompanyController::class, 'destroy'])->middleware('can:company');

    Route::get('company/data', [CompanyController::class,'companyData'])->name('company.getData');
    Route::resource('product',ProductController::class)->middleware('can:product');
    Route::get('/product/data/get', [CompanyController::class,'productDataGet'])->name('product.getData');
    Route::resource('stock', StockController::class)->middleware('can:add_purchase');
    Route::resource('invoice',InvoiceController::class);
    Route::resource('report',ReportController::class);

    // Sell Management routes
    Route::resource('sell', SellController::class)->middleware('can:add_sell');
    Route::get('sellList',[SellController::class,'list'])->name('sell.list');
     Route::post('/sell/update/{sell}', [SellController::class, 'update'])->name('sell.updateSell');
    Route::get('/sell/batches/{sku}', [SellController::class, 'getSellBatchesBySku'])->name('batch.getSellBatchesBySku');
    Route::get('/batches/{sku}', [SellController::class, 'getBatchesBySku'])->name('batch.getBatchesBySku');

    // Stock Management routes
    Route::get('stockList',[StockController::class,'list'])->name('stock.list');

    Route::resource('sellCounter', SellCornerController::class)->middleware('can:sell_stock');
    Route::get('/sellcounter/batches/{sku}', [SellCornerController::class, 'getSellcounterBatchesBySku'])->name('batch.getSellCounterBatchesBySku');
    Route::get('/sellcounter/product/data/get', [SellCornerController::class,'sellProductDataGet'])->name('sell.product.getData');
    Route::get('/sellcounter/cartons/{batch}', [SellCornerController::class, 'getSellcounterCartonsByBatch'])->name('batch.getSellCounterCartonsByBatch');
    route::view('stockEdit','admin.edit')->name('stock.editStock');
    route::post('/stock/update/batch',[StockController::class,'update'])->name('stock.batch.update');
    Route::get('/sellcounter/orders', [SellCornerController::class, 'orderList'])->name('sell.orders.list');
    route::get('sellcounteredit/{id}',[SellCornerController::class,'editSellCounter']);

});
