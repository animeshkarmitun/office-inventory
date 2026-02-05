<?php

use App\Http\Controllers\BorrowerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SignoutController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\AssetMovementController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\FloorController;
use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [LoginController::class, 'index'])->name('index');
Route::post('/', [LoginController::class, 'login'])->name('index.login');

Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.store');

Route::middleware('auth')->group(function () {

    Route::get('/signout', [SignoutController::class, 'signOut'])->name('logout');

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'showEdit'])->name('profile.showEdit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // User Management (Super Admin Only)
    Route::middleware('role:super_admin')->group(function () {
        Route::resource('user-management', UserManagementController::class);
        Route::post('user-management/store-ajax', [UserManagementController::class, 'storeAjax'])->name('user-management.storeAjax');
        
        // Designation Management
        Route::post('designation/store-ajax', [DesignationController::class, 'storeAjax'])->name('designation.storeAjax');
    });

    // Floor Management (Super Admin & Admin)
    Route::resource('floor', FloorController::class);
    Route::post('floor/store-ajax', [FloorController::class, 'storeAjax'])->name('floor.storeAjax');
    
    // Room Management (Super Admin & Admin)
    Route::resource('room', RoomController::class);
    Route::post('room/store-ajax', [RoomController::class, 'storeAjax'])->name('room.storeAjax');
    Route::get('room/by-floor/{floorId}', [RoomController::class, 'getByFloor'])->name('room.by-floor');

    // Company Management
    Route::post('company/store-ajax', [\App\Http\Controllers\CompanyController::class, 'storeAjax'])->name('company.storeAjax');

    // Item
    Route::controller(ItemController::class)->prefix('item')->name('item')->group(function () {
        Route::get('/', 'index');
        Route::get('/add', 'showAdd')->name('.showAdd');
        Route::post('/add', 'store')->name('.store');
        Route::get('/{id}/delete', 'destroy')->name('.destroy');
        Route::get('/{id}/edit', 'showEdit')->name('.showEdit');
        Route::post('/{id}/edit', 'update')->name('.update');
        Route::post('/{id}/approve', 'approve')->name('.approve');
        Route::get('/export', 'export')->name('.export');
        Route::get('/import', 'showImport')->name('.showImport');
        Route::post('/import', 'import')->name('.import');
        Route::get('/template', 'downloadTemplate')->name('.template');
        Route::delete('/{id}/remove-legacy-image', 'removeLegacyImage')->name('.removeLegacyImage');
        Route::delete('/item-image/{id}', 'removeImage')->name('image.remove');
        Route::get('/assign-from-purchase/{purchaseId}/{itemId}', 'assignFromPurchase')->name('.assignFromPurchase');
    });

    // Supplier
    Route::controller(SupplierController::class)->prefix('supplier')->name('supplier')->group(function () {
        Route::get('/', 'index');
        Route::get('/add', 'showAdd')->name('.showAdd');
        Route::post('/add', 'store')->name('.store');
        Route::post('/store-ajax', 'storeAjax')->name('.storeAjax');
        Route::get('/{id}/delete', 'destroy')->name('.destroy');
        Route::get('/{id}/edit', 'showEdit')->name('.showEdit');
        Route::post('/{id}/edit', 'update')->name('.update');
    });

    // Category
    Route::controller(CategoryController::class)->prefix('category')->name('category')->group(function () {
        Route::get('/', 'index');
        Route::get('/add', 'showAdd')->name('.showAdd');
        Route::post('/add', 'store')->name('.store');
        Route::get('/{id}/delete', 'destroy')->name('.destroy');
        Route::get('/{id}/edit', 'showEdit')->name('.showEdit');
        Route::post('/{id}/edit', 'update')->name('.update');
    });

    // Department
    Route::controller(DepartmentController::class)->prefix('department')->name('department')->group(function () {
        Route::get('/', 'index');
        Route::get('/add', 'showAdd')->name('.showAdd');
        Route::post('/add', 'store')->name('.store');
        Route::get('/{id}/delete', 'destroy')->name('.destroy');
        Route::get('/{id}/edit', 'showEdit')->name('.showEdit');
        Route::post('/{id}/edit', 'update')->name('.update');
        
        // Designation management under department
        Route::get('/designations', 'designations')->name('.designations');
        Route::get('/designations/create', 'createDesignation')->name('.create-designation');
        Route::post('/designations', 'storeDesignation')->name('.store-designation');
        Route::get('/designations/{designation}', 'showDesignation')->name('.show-designation');
        Route::get('/designations/{designation}/edit', 'editDesignation')->name('.edit-designation');
        Route::put('/designations/{designation}', 'updateDesignation')->name('.update-designation');
        Route::delete('/designations/{designation}', 'destroyDesignation')->name('.destroy-designation');
    });

    // Borrower
    Route::controller(BorrowerController::class)->prefix('borrower')->name('borrower')->group(function () {
        Route::get('/', 'index');
        Route::get('/add', 'showAdd')->name('.showAdd');
        Route::post('/add', 'store')->name('.store');
        Route::get('/{id}/delete', 'destroy')->name('.destroy');
        Route::get('/{id}/edit', 'showEdit')->name('.showEdit');
        Route::post('/{id}/edit', 'update')->name('.update');
    });

    // Asset Movement Routes
    Route::get('/asset/{id}/movement-history', [AssetMovementController::class, 'index'])->name('asset.movement.history');
    Route::get('/asset/{id}/move', [AssetMovementController::class, 'create'])->name('asset.movement.create');
    Route::post('/asset/{id}/move', [AssetMovementController::class, 'store'])->name('asset.movement.store');
    Route::get('/asset/movement/{id}', [AssetMovementController::class, 'show'])->name('asset.movement.show');

    // Purchase
    Route::resource('purchase', PurchaseController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
    Route::delete('purchase/{purchase}/image/{image}', [App\Http\Controllers\PurchaseController::class, 'deleteImage'])->name('purchase.deleteImage');
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
    
    // Company Management (Admin Only)
    Route::controller(\App\Http\Controllers\CompanyController::class)->prefix('company')->name('company')->group(function () {
        Route::get('/', 'index');
        // Route::get('/add', 'showAdd')->name('.showAdd'); // Using modal for add, but if we need page later
        // Route::post('/add', 'store')->name('.store');
        Route::get('/{id}/edit', 'showEdit')->name('.showEdit');
        Route::post('/{id}/edit', 'update')->name('.update');
        Route::get('/{id}/delete', 'destroy')->name('.destroy');
    });
});

// Asset Manager routes
Route::middleware(['auth', 'role:asset_manager'])->group(function () {
    Route::get('/asset-manager/dashboard', [DashboardController::class, 'assetManagerDashboard'])->name('asset-manager.dashboard');
    // Add more asset manager-only routes here
});

// Employee routes
Route::middleware(['auth', 'role:employee'])->group(function () {
    Route::get('/employee/dashboard', [DashboardController::class, 'employeeDashboard'])->name('employee.dashboard');
    // Add more employee-only routes here
});

Route::get('/depreciation-report', [ItemController::class, 'depreciationReport'])->name('depreciation.report');
Route::get('item/{item}/history', [App\Http\Controllers\ItemController::class, 'history'])->name('item.history');
Route::get('item/{item}/history/pdf', [App\Http\Controllers\ItemController::class, 'exportHistoryPdf'])->name('item.history.pdf');
Route::get('borrower/{borrower}/history', [App\Http\Controllers\BorrowerController::class, 'history'])->name('borrower.history');
Route::get('supplier/{supplier}/purchases', [App\Http\Controllers\SupplierController::class, 'purchases'])->name('supplier.purchases');


Route::get('/public-clear-cache', [\App\Http\Controllers\AdminController::class, 'clearCache'])->name('public.clear-cache');

// Superadmin cache clear route
Route::middleware(['auth', 'role:super_admin'])->group(function () {
    Route::post('/superadmin/clear-cache', function () {
        \Artisan::call('cache:clear');
        \Artisan::call('config:clear');
        \Artisan::call('view:clear');
        \Artisan::call('route:clear');
        return back()->with(['message' => 'Cache cleared successfully!', 'alert' => 'alert-success']);
    })->name('superadmin.clear-cache');
});


// Public deployment utility (no auth): run essential post-deploy commands
Route::get('/deploy/run', function () {
	$results = [];
	$run = function (string $command, array $params = []) use (&$results) {
		try {
			\Artisan::call($command, $params);
			$results[] = [
				'command' => $command,
				'params' => $params,
				'status' => 'ok',
				'output' => \Artisan::output(),
			];
		} catch (\Throwable $e) {
			$results[] = [
				'command' => $command,
				'params' => $params,
				'status' => 'error',
				'error' => $e->getMessage(),
			];
		}
	};

	$run('migrate', ['--force' => true]);
	$run('config:cache');
	$run('route:cache');
	$run('view:cache');
	$run('storage:link');

	return response()->json([
		'ok' => true,
		'results' => $results,
		'timestamp' => now()->toDateTimeString(),
	]);
})->name('deploy.run');
