<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Auth\AuthenticatedSessionController;

// ADMIN
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\StockReportController;
use App\Http\Controllers\Admin\UserActivityController;
use App\Http\Controllers\Admin\TransactionReportController;

// MANAGER
use App\Http\Controllers\Manager\ManagerDashboardController;
use App\Http\Controllers\Manager\ManagerStokController;
use App\Http\Controllers\Manager\ProductController as ManagerProductController;
use App\Http\Controllers\Manager\ManagerSupplierController;

// STAFF
use App\Http\Controllers\Staff\StaffDashboardController;
use App\Http\Controllers\Staff\StaffStockController;


Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->name('login');

Route::post('/login', [AuthenticatedSessionController::class, 'store']);

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');
})->name('logout');

Route::redirect('/', '/login');

Route::middleware('auth')
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/products/minimum-stock',
            [App\Http\Controllers\Admin\ProductController::class, 'minimumStock']
            )->name('products.minimumStock');

        Route::get('settings', [SettingController::class, 'index'])
            ->name('settings.index');

        Route::put('settings', [SettingController::class, 'update'])
            ->name('settings.update');

        Route::get('/reports/mutation',
            [StockReportController::class, 'mutation']
        )->name('reports.mutation');

        Route::get('/products/export', 
            [AdminProductController::class, 'export']
        )->name('products.export');

        Route::get('/products/import', [AdminProductController::class, 'showImport'])
            ->name('products.import.form');

        Route::post('/products/import', [AdminProductController::class, 'import'])
            ->name('products.import');

        Route::patch('/products/{product}/toggle-status', 
            [AdminProductController::class, 'toggleStatus']
        )->name('products.toggle-status');

        Route::post('/products/update-minimum/{product}',
                [App\Http\Controllers\Admin\ProductController::class, 'updateMinimumStock']
            )->name('products.updateMinimumStock');

        Route::resource('products', App\Http\Controllers\Admin\ProductController::class);
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // Stock
        Route::prefix('stocks')->name('stocks.')->group(function () {

            Route::get('/', [StockController::class, 'index'])
                ->name('index');

        });


        // Reports
        Route::prefix('reports')->name('reports.')->group(function () {

            Route::get('/stock', [StockReportController::class, 'index'])
                ->name('stock');

            Route::get('/transactions', [StockReportController::class, 'transactions'])
                ->name('transactions');

            Route::get('/activities', [UserActivityController::class, 'index'])
                ->name('activities');
        });


        // Resource
        Route::resource('products', AdminProductController::class)->except('show');
        Route::resource('categories', CategoryController::class)->except('show');
        Route::resource('suppliers', SupplierController::class);
        Route::resource('users', UserController::class);

        Route::patch('users/{user}/toggle-status',
            [UserController::class, 'toggleStatus']
        )->name('users.toggle-status');

        // Stock

        Route::view('/settings', 'admin.settings.index')
            ->name('settings.index');
    });

Route::prefix('manager')
    ->middleware(['auth', 'role:Manajer Gudang'])
    ->name('manager.')
    ->group(function () {

        Route::get('stock/incoming', [ManagerStokController::class, 'incoming'])
            ->name('stock.incoming');

        Route::post('stock/incoming', [ManagerStokController::class, 'storeIncoming'])
            ->name('stock.storeIncoming');

        Route::get('stock/outgoing', [ManagerStokController::class, 'outgoing'])
            ->name('stock.outgoing');

        Route::post('stock/outgoing', [ManagerStokController::class, 'storeOutgoing'])
            ->name('stock.storeOutgoing');

        Route::get('stock/history', [ManagerStokController::class, 'transactionHistory'])
            ->name('stock.history');
        Route::get('stock/opname', [ManagerStokController::class, 'opnameForm'])
            ->name('stock.opname.form');

        Route::post('stock/opname', [ManagerStokController::class, 'storeOpname'])
            ->name('stock.opname');
            

        Route::resource('suppliers', ManagerSupplierController::class)
            ->except(['show'])
            ->names('suppliers');

        Route::get('/products/search', 
            [AdminProductController::class, 'ajaxSearchProducts']
        )->name('products.search');
});

Route::middleware('auth')
    ->prefix('manager')
    ->name('manager.')
    ->group(function () {

        Route::get('/dashboard', [ManagerDashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('products', ManagerProductController::class);

        Route::prefix('stocks')->name('stocks.')->group(function () {

            Route::get('/incoming',
                [ManagerStokController::class, 'incoming']
            )->name('incoming');

            Route::post('/incoming',
                [ManagerStokController::class, 'storeIncoming']
            )->name('incoming.store');

            Route::get('/outgoing',
                [ManagerStokController::class, 'outgoing']
            )->name('outgoing');

            Route::post('/outgoing',
                [ManagerStokController::class, 'storeOutgoing']
            )->name('outgoing.store');

            Route::get('/history',
                [ManagerStokController::class, 'transactionHistory']
            )->name('history');
        });

        Route::get('/transactions/history',
            [ManagerStokController::class, 'transactionHistory']
        )->name('transactions.history');
    });

Route::prefix('staff')
    ->middleware(['auth', 'role:Staff Gudang'])
    ->name('staff.')
    ->group(function () {

        Route::get('/dashboard',
            [StaffDashboardController::class, 'index']
        )->name('dashboard');

        Route::get('/stock/incoming',
            [StaffStockController::class, 'incoming']
        )->name('stock.incoming');

        Route::get('/stock/outgoing',
            [StaffStockController::class, 'outgoing']
        )->name('stock.outgoing');

        Route::post('/stock/confirm/{id}',
            [StaffStockController::class, 'confirm']
        )->name('stock.confirm');

        Route::post('/stock/reject/{id}',
            [StaffStockController::class, 'reject']
        )->name('stock.reject');
        Route::get('/stock/history', 
            [StaffStockController::class, 'history']
        )->name('stock.history');

    });
