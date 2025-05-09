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
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\paymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CustomerController;
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

Route::get('/optimize-clear', function () {
    Artisan::call('optimize:clear');
    return response()->json(['success' => true]);
})->name('optimize-clear');

// Guest middleware for unauthenticated users
Route::group(['middleware' => 'guest'], function () {

    Route::get('/', [FrontController::class, 'index'])->name('index'); // Root should use `/` instead of an empty string

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

    Route::get('/home', [FrontController::class, 'home'])->name('home')->middleware('can:view-dashboard');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout'); // POST is better practice for logout
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout.get'); // In case GET is needed, but not recommended
    Route::get('/role-manager', [RoleController::class, 'roleManager'])->name('role-manager');
    Route::post('/add-new-role', [RoleController::class, 'addNewRole'])->name('roles.add');
    Route::get('/permission-manager', [PermissionController::class, 'permissionManager'])->name('permission-manager');
    // Route::post('/add-new-permission', [PermissionController::class, 'addNewPermission'])->name('permissions.add');
    Route::get('/menu', [MenuController::class, 'menu'])->name('menu');


    //user management routes
    Route::get('/user-management', [userManagement::class, 'show'])->name('user.management');
    Route::get('/user-list', [userManagement::class, 'list'])->name('users.list');
    Route::get('/purchase/history', [HistoryController::class, 'history'])->name('purchase.history');
    Route::get('/purchase/get/history', [HistoryController::class, 'getHistory'])->name('purchase.getHistory');
    Route::get('/purchase/details/{id}/{companyName}/{noOfCartoon}', [HistoryController::class, 'detailHistory'])->name('purchase.detailHistory')->can('view-purchase-history');
    Route::get('history/products/options', [HistoryController::class, 'historyProducts'])->name('purchase.historyProducts');
    Route::get('/purchaseHistory/show/{productId}/{createdAt}/{noOfCartoon}', [HistoryController::class, 'purchaseHistoryShow'])->name('purchaseHistoryShow');
    //organizations routes

    //sell history routes

    Route::get('/sell/history', [HistoryController::class, 'sellHistory'])->name('sell.history');
    Route::get('/sell/get/history', [HistoryController::class, 'getSellHistory'])->name('sell.getHistory');
    Route::get('/sellHistory/show/{productId}/{createdAt}', [HistoryController::class, 'sellHistoryShow'])->name('sellHistoryShow');
    Route::get('/sell/details/{id}/{companyName}/{orderId}', [HistoryController::class, 'saleDetailHistory'])->name('sell.detailHistory');



    Route::resource('organization', OrganizationController::class);
    Route::get('organization/edit/{organization}', [OrganizationController::class, 'edit'])->name('organization.editorganization');
    Route::Post('organization/{organization}', [OrganizationController::class, 'destroy']);
    Route::get('organization/data', [OrganizationController::class, 'organizationData'])->name('organization.getData');

    Route::post('/switch-organization', [OrganizationController::class, 'switchOrganization'])->name('switch.organization');

    //brands routes
    Route::resource('supplier', BrandController::class)->names('supplier');
    Route::get('brand/edit/{company}', [BrandController::class, 'edit'])->name('brand.editbrand');
    Route::Post('brand/{brand}', [BrandController::class, 'destroy']);
    Route::get('brand/data', [BrandController::class, 'brandData'])->name('brand.getData');
    Route::get('brand/ledger/{id}', [BrandController::class, 'showLedger'])->name('brand.ledger');

    //company routes
    Route::resource('company', CompanyController::class);
    Route::get('company/edit/{company}', [CompanyController::class, 'edit'])->name('company.editCompany')->middleware('can:edit-company');
    Route::Post('company/{company}', [CompanyController::class, 'destroy']);
    Route::get('company/data', [CompanyController::class, 'companyData'])->name('company.getData');

    //products routes
    Route::resource('product', ProductController::class);
    Route::post('/product/toggle-status', [ProductController::class, 'toggleStatus'])->name('product.toggleStatus');
    // Route::get('/product/data/get', [CompanyController::class, 'productDataGet'])->name('product.getData');
    Route::get('/product/data/get', [OrganizationController::class, 'productDataGet'])->name('product.getData');
    Route::get('/product/data/byid', [ProductController::class, 'productDataGetById'])->name('product.getDataById');

    //stock routes
    Route::resource('stock', StockController::class);
    Route::get('purchaseList', [StockController::class, 'list'])->name('purchase.list');
    Route::get('stockList', [StockController::class, 'stockList'])->name('stock.list');
    Route::get('stockList/bycompany', [StockController::class, 'listByCompany'])->name('stocks.bycompany');
    Route::get('stockList/byproduct', [StockController::class, 'listByProduct'])->name('stocks.byproduct');
    route::view('stockEdit', 'admin.edit')->name('stock.editStock');
    route::post('/stock/update/batch', [StockController::class, 'update'])->name('stock.batch.update');
    route::get('/stock/details/{id}/{date}/{totalUnit}/{invoice}', [StockController::class, 'stockDetails'])->name('stockDetails');
   

    // Sell Management routes
    Route::resource('sell', SellController::class);
    Route::get('sellList', [SellController::class, 'list'])->name('sell.list');
    Route::post('/sell/update/{sell}', [SellController::class, 'update'])->name('sell.updateSell');
    Route::get('/sell/batches/{sku}', [SellController::class, 'getSellBatchesBySku'])->name('batch.getSellBatchesBySku');
    Route::get('/batches/{sku}', [SellController::class, 'getBatchesBySku'])->name('batch.getBatchesBySku');
    Route::post('/sell/approve', [SellController::class, 'sellApprove'])->name('sell.approveStock');


    //  Customer routes
    Route::resource('customer', CustomerController::class);
    Route::get('customers/list', [CustomerController::class,'customerList'])->name('customers.list');
    Route::get('retail/customers/list', [CustomerController::class,'retailCustomerList'])->name('retail.customers.list');
    Route::get('/customers/{customer}/detail', [CustomerController::class, 'detail'])->name('customers.detail');

    // sell counter Management routes
    Route::resource('sellCounter', SellCornerController::class);
    Route::get('/sellcounter/orders', [SellCornerController::class, 'orderList'])->name('sell.orders.list');
    route::get('sellcounteredit/{id}', [SellCornerController::class, 'editSellCounter']);
    Route::get('/sellcounter/batches/{sku}', [SellCornerController::class, 'getSellcounterBatchesBySku'])->name('batch.getSellCounterBatchesBySku');
    Route::get('/sellcounter/product/data/get', [SellCornerController::class, 'sellProductDataGet'])->name('sell.product.getData');
    Route::get('/sellcounter/cartons/{batch}', [SellCornerController::class, 'getSellcounterCartonsByBatch'])->name('batch.getSellCounterCartonsByBatch');
    Route::get('/sellcorner/batche/data/{batch}', [SellCornerController::class, 'getBatchData'])->name('getBatchData');


    //for user assign permission
    Route::get('/assign-permissions', [PermissionController::class, 'assignPermissions'])->name('assign.permissions');
    Route::post('/assign-permissions/save', [PermissionController::class, 'savePermissions'])->name('assign.permissions.save');
    Route::post('/assign-permissions/get', [PermissionController::class, 'getUserPermissions'])->name('assign.permissions.get');

    //invoice routes
    Route::resource('invoice', InvoiceController::class);
    Route::get('/invoice/{orderId}/download', [InvoiceController::class, 'download'])->name('invoice.download');

    //report routes
    Route::resource('report', ReportController::class);
});
