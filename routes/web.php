<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\MarketingController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\LeadController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::view('/contact', 'contact')->name('contact');
Route::get('marketing/webhook', function (Request $request) {
    $request->validate([
        'device' => 'required|string',
        'id' => 'required|integer',
        'stateid' => 'nullable|integer',
        'status' => 'required|string',
        'state' => 'required|string',
    ]);
    dd($request);
    $device = $request->input('device');
    $id = $request->input('id');
    $stateid = $request->input('stateid');
    $status = $request->input('status');
    $state = $request->input('state');

    try {
        if (isset($id) && isset($stateid)) {
            MarketingDetail::where('send_id', $id)
                ->update([
                    'status' => $status,
                    'state' => $state,
                    'state_id' => $stateid,
                ]);
        } else if (isset($id) && !isset($stateid)) {
            MarketingDetail::where('send_id', $id)
                ->update(['status' => $status]);
        } else {
            MarketingDetail::where('state_id', $stateid)
                ->update(['state' => $state]);
        }
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }

    return response()->json(['message' => 'Data updated successfully']);
});
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {


    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Users Management
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::get('/profile', [UserController::class, 'profile'])->name('users.profile');
        Route::get('/access', [UserController::class, 'access'])->name('users.access');
    });

    // Customers
    Route::prefix('customers')->group(function () {
        Route::get('/', [CustomerController::class, 'index'])->name('customers.index');
        Route::get('/interaction', [CustomerController::class, 'interaction'])->name('customers.interaction');
        Route::get('/segmentation', [CustomerController::class, 'segmentation'])->name('customers.segmentation');
    });

    Route::get('/vendors', function () {
        return view('vendors.index');
    })->name('vendors.index');

    // Sales
    Route::prefix('sales')->name('sales.')->group(function () {
        Route::get('/quotation', function () {
            return view('sales.quotation');
        })->name('quotation');

        Route::get('/orders', function () {
            return view('sales.orders');
        })->name('orders');

        Route::get('/shipping', function () {
            return view('sales.shipping');
        })->name('shipping');
    });

    // Marketing
    Route::prefix('marketing')->group(function () {
        Route::get('/whatsapp', [MarketingController::class, 'whatsapp'])->name('marketing.whatsapp');
        Route::get('whatsapp/send/{campaignId}', [MarketingController::class, 'sendCustomer'])->name('marketing.send');//go to target customer choice page
        Route::post('/send', [MarketingController::class, 'send'])->name('send.whatsapp');
        Route::get('/analysis', [MarketingController::class, 'analysis'])->name('marketing.analysis');
        Route::post('/save-selected-customers', [MarketingController::class, 'saveSelectedCustomers'])->name('saveSelectedCustomers');
        Route::get('/detail/{campaignId}', [MarketingController::class, 'detailShow'])->name('marketing.detail');
        Route::get('/history', [MarketingController::class, 'historyShow'])->name('message.history');
    });
    Route::prefix('leads')->group(function () {
        Route::get('/', [LeadController::class, 'index'])->name('marketing.leads');
        Route::get('/update', [LeadController::class, 'update'])->name('leads.update');
        Route::get('/prices', [ProductController::class, 'prices'])->name('products.prices');
    });
    // Products
    Route::prefix('products')->group(function () {
        Route::get('/catalog', [ProductController::class, 'catalog'])->name('products.catalog');
        Route::get('/categories', [ProductController::class, 'categories'])->name('products.categories');
        Route::get('/prices', [ProductController::class, 'prices'])->name('products.prices');
    });

    // Projects 
    Route::prefix('projects')->group(function () {
        Route::get('/', [ProjectController::class, 'index'])->name('projects.index');
        Route::get('/timeline', [ProjectController::class, 'timeline'])->name('projects.timeline');
        Route::get('/status', [ProjectController::class, 'status'])->name('projects.status');
    });

    // Reports
    Route::prefix('reports')->group(function () {
        Route::get('/sales', [ReportController::class, 'sales'])->name('reports.sales');
        Route::get('/customers', [ReportController::class, 'customers'])->name('reports.customers');
        Route::get('/marketing', [ReportController::class, 'marketing'])->name('reports.marketing');
    });

    // Settings
    Route::prefix('settings')->group(function () {
        Route::get('/system', [SettingController::class, 'system'])->name('settings.system');
        Route::get('/notifications', [SettingController::class, 'notifications'])->name('settings.notifications');
        Route::get('/backup', [SettingController::class, 'backup'])->name('settings.backup');
    });
});