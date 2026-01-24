<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Assets\AssetIndex;
use App\Livewire\Assets\AssetCreate;
use App\Livewire\Assets\AssetEdit;
use App\Livewire\Assets\AssetShow;
use App\Livewire\Categories\CategoryIndex;
use App\Livewire\Locations\LocationIndex;
use App\Livewire\Vendors\VendorIndex;
use App\Livewire\Movements\MovementIndex;
use App\Livewire\Movements\MovementCreate;
use App\Livewire\Maintenances\MaintenanceIndex;
use App\Livewire\Maintenances\MaintenanceCreate;
use App\Livewire\StockOpnames\StockOpnameIndex;
use App\Livewire\StockOpnames\StockOpnameCreate;
use App\Livewire\StockOpnames\StockOpnameScan;
use App\Http\Controllers\ReportController;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    
    // Assets Routes
    Route::prefix('assets')->name('assets.')->group(function () {
        Route::get('/', AssetIndex::class)->name('index')->middleware('can:view-assets');
        Route::get('/create', AssetCreate::class)->name('create')->middleware('can:create-assets');
        Route::get('/{asset}', AssetShow::class)->name('show')->middleware('can:view-assets');
        Route::get('/{asset}/edit', AssetEdit::class)->name('edit')->middleware('can:edit-assets');
    });
    
    // Categories Routes
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', CategoryIndex::class)->name('index')->middleware('can:view-categories');
    });
    
    // Locations Routes
    Route::prefix('locations')->name('locations.')->group(function () {
        Route::get('/', LocationIndex::class)->name('index')->middleware('can:view-locations');
    });
    
    // Vendors Routes
    Route::prefix('vendors')->name('vendors.')->group(function () {
        Route::get('/', VendorIndex::class)->name('index')->middleware('can:view-vendors');
    });
    
    // Movements Routes
    Route::prefix('movements')->name('movements.')->group(function () {
        Route::get('/', MovementIndex::class)->name('index')->middleware('can:view-movements');
        Route::get('/create', MovementCreate::class)->name('create')->middleware('can:create-movements');
    });
    
    // Maintenances Routes
    Route::prefix('maintenances')->name('maintenances.')->group(function () {
        Route::get('/', MaintenanceIndex::class)->name('index')->middleware('can:view-maintenances');
        Route::get('/create', MaintenanceCreate::class)->name('create')->middleware('can:create-maintenances');
    });
    
    // Stock Opnames Routes
    Route::prefix('stock-opnames')->name('stock-opnames.')->group(function () {
        Route::get('/', StockOpnameIndex::class)->name('index')->middleware('can:view-stock-opnames');
        Route::get('/create', StockOpnameCreate::class)->name('create')->middleware('can:create-stock-opnames');
        Route::get('/{stockOpname}/scan', StockOpnameScan::class)->name('scan')->middleware('can:scan-stock-opnames');
    });
    
    // Reports Routes
    Route::prefix('reports')->name('reports.')->middleware('can:view-reports')->group(function () {
        Route::get('/assets/pdf', [ReportController::class, 'assetsPdf'])->name('assets.pdf');
        Route::get('/assets/excel', [ReportController::class, 'assetsExcel'])->name('assets.excel');
        Route::get('/movements/pdf', [ReportController::class, 'movementsPdf'])->name('movements.pdf');
        Route::get('/maintenances/pdf', [ReportController::class, 'maintenancesPdf'])->name('maintenances.pdf');
        Route::get('/stock-opname/{stockOpname}/pdf', [ReportController::class, 'stockOpnamePdf'])->name('stock-opname.pdf');
        Route::get('/asset/{asset}/qr', [ReportController::class, 'assetQrPdf'])->name('asset.qr');
    });
});

require __DIR__.'/auth.php';
