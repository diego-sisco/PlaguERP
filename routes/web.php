<?php
use App\Http\Controllers\BranchController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\ControlPointController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\FloorPlansController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\PestController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CRMController;
use App\Http\Controllers\QualityController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GraphicController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\LotController;
use App\Http\Controllers\RotationPlanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
 */



/*Route::get('/', function () {
    return view('/auth/login');
});*/

/*Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});*/



Route::middleware('auth')->group(function () {
    Route::redirect('/', '/dashboard');
    Route::get('/dashboard', [PagesController::class, 'dashboard'])->name('dashboard');
    Route::get('/RRHH/{section}', [PagesController::class, 'rrhh'])->name('rrhh');
});
// DASHBOARD

// CRM
/*Route::prefix('crm')->middleware('auth')->group(function () {
    Route::get('/schedule', [PagesController::class, 'planning'])->name('planning.schedule');
    Route::post('/schedule/update', [PagesController::class, 'updateSchedule'])->name('planning.schedule.update');
    Route::get('/activities', [PagesController::class, 'planning'])->name('planning.activities');
    Route::post('/filter', [PagesController::class, 'filterPlanning'])->name('planning.filter');
});*/

// PLANEACION
Route::prefix('planning')->middleware('auth')->group(function () {
    Route::get('/schedule', [PagesController::class, 'planning'])->name('planning.schedule');
    Route::post('/schedule/update', [PagesController::class, 'updateSchedule'])->name('planning.schedule.update');
    Route::get('/activities', [PagesController::class, 'planning'])->name('planning.activities');
    Route::post('/filter', [PagesController::class, 'filterPlanning'])->name('planning.filter');
});

// CALIDAD
Route::prefix('quality')->name('quality.')->middleware('auth')->group(function () {
    Route::get('/index', [QualityController::class, 'index'])->name('index');
    Route::get('/control', [QualityController::class, 'control'])->name('control');
    Route::post('/control/store', [QualityController::class, 'storeControl'])->name('control.store');
    Route::get('/control/destroy/{id}', [PagesController::class, 'destroyControl'])->name('control.destroy');
    Route::get('/search', [QualityController::class, 'search'])->name('search');
    Route::get('/customer/{id}', [QualityController::class, 'customer'])->name('customer');

    Route::get('/customer/{id}/orders', [QualityController::class, 'orders'])->name('orders');
    Route::get('/customer/{id}/orders/search', [QualityController::class, 'searchOrders'])->name('orders.search');
    Route::get('/customer/{id}/contracts', [QualityController::class, 'contracts'])->name('contracts');
    Route::get('/customer/{id}/floorplans', [QualityController::class, 'floorplans'])->name('floorplans');
    Route::get('/customer/{id}/zones', [QualityController::class, 'zones'])->name('zones');
    Route::get('/customer/{id}/devices', [QualityController::class, 'devices'])->name('devices');
    Route::post('/customer/{id}/replace/technicians', [QualityController::class, 'replaceTechnicians'])->name('technicians.replace');
    //Route::get('/control', [PagesController::class, 'qualityControl'])->name('control');
    //Route::get('/control/destroy/{customerId}', [PagesController::class, 'qualityControlDestroy'])->name('control.destroy');
    //Route::get('/orders/{status}', [PagesController::class, 'qualityOrders'])->name('orders');
    //Route::get('/customer/{customerId}/{section}/{status}', [PagesController::class, 'qualityGeneralByCustomer'])->name('customer.details.general');
});

// CRM
Route::prefix('crm')->name('crm.')->middleware('auth')->group(function () {
    Route::get('/dashboard', [PagesController::class, 'crm'])->name('dashboard');
    Route::get('/tracking/{type}', [CRMController::class, 'index'])->name('tracking');
    Route::post('/tracking/store/{type}', [CRMController::class, 'tracking'])->name('tracking.store');
    Route::get('/orders/{status}', [CRMController::class, 'orders'])->name('orders');
});

// CHARTS

// Inventario/Almance
Route::prefix('warehouses')
    ->middleware('auth')
    ->name('warehouse.')
    ->group(function () {
        Route::get('/', [WarehouseController::class, 'index'])->name('index');
        Route::post('/store', [WarehouseController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [WarehouseController::class, 'edit'])->name('edit');
        Route::put('/update/{id}/', [WarehouseController::class, 'update'])->name('update');
        Route::get('/show/{id}', [WarehouseController::class, 'show'])->name('show');
        Route::get('/delete/{id}', [WarehouseController::class, 'delete'])->name('delete');
    });

Route::prefix('warehouse')
    ->middleware('auth')
    ->name('warehouse.')
    ->group(function () {
        Route::post('/input', [WarehouseController::class, 'storeMovement'])->name('input');
        Route::post('/output', [WarehouseController::class, 'storeMovement'])->name('output');
        Route::get('/movements/show', [WarehouseController::class, 'allMovements'])->name('movements.show');
        Route::get('/movements/{id}', [WarehouseController::class, 'movements'])->name('movements');
        Route::get('/stock/{id}', [WarehouseController::class, 'stock'])->name('stock');
        Route::post('/destroy/{id}', [WarehouseController::class, 'destroy'])->name('destroy');
    });

Route::prefix('inventory')
    ->middleware('auth')
    ->group(function () {
        Route::post('/store_entry', [WarehouseController::class, 'store_entry'])->name('warehouse.store_entry');
        Route::post('/search', [WarehouseController::class, 'search'])->name('inventory.search');
        Route::get('/{id}/generate-pdf', [WarehouseController::class, 'movement_print'])->name('warehouse.pdf');
    });

Route::get('/movements', [WarehouseController::class, 'showAllMovements'])->name('movements.all');

//lot
Route::prefix('lot')
    ->middleware('auth')
    ->name('lot.')
    ->group(function () {
        Route::get('/index', [LotController::class, 'index'])->name('index');
        Route::get('/create', [LotController::class, 'create'])->name('create');
        Route::post('/store', [LotController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [LotController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [LotController::class, 'update'])->name('update');
        Route::get('/show/{id}', [LotController::class, 'show'])->name('show');
        Route::get('/destroy/{id}', [LotController::class, 'destroy'])->name('destroy');
    });

Route::prefix('CRM/chart')
    ->middleware('auth')
    ->name('crm.chart.')
    ->group(function () {
        // New Customers
        Route::get('/customers', [GraphicController::class, 'newCustomersDataset'])->name('customers');
        Route::get('/customers/update', [GraphicController::class, 'refreshNewCustomers'])->name('customers.refresh');

        // Scheduled Orders
        Route::get('/orders', [GraphicController::class, 'ordersDataset'])->name('orders');
        Route::get('/orders/update', [GraphicController::class, 'refreshOrders'])->name('orders.refresh');

        // Order Types
        Route::get('/ordertypes/{service_type}', [GraphicController::class, 'orderTypesDataset'])->name('ordertypes');
        Route::get('/ordertypes/{service_type}/update', [GraphicController::class, 'refreshOrderTypes'])->name('ordertypes.refresh');

    });

// Client System
Route::prefix('clients')
    ->middleware('auth')
    ->name('client.')
    ->group(function () {
        Route::get('/', [ClientController::class, 'index'])->name('index');
        Route::get('/reports/{section}', [ClientController::class, 'reports'])->name('reports.index');

        Route::post('/reports/search', [ClientController::class, 'searchReport'])->name('report.search');

        Route::get('/mip/{path}', [ClientController::class, 'mip'])->where('path', '.*')->name('mip.index');
        Route::get('/system/{path}', [ClientController::class, 'directories'])->where('path', '.*')->name('system.index');

        Route::get('/file/download/{path}', [ClientController::class, 'downloadFile'])->where('path', '.*')->name('file.download');
    });

Route::prefix('client')
    ->middleware('auth')
    ->name('client.')
    ->group(function () {
        Route::post('/directory/store', [ClientController::class, 'storeDirectory'])->name('directory.store');
        Route::post('/file/store', [ClientController::class, 'storeFile'])->name('file.store');
        Route::post('/directory/update', [ClientController::class, 'updateDirectory'])->name('directory.update');
        Route::post('/directory/permissions', [ClientController::class, 'permissions'])->name('directory.permissions');
        Route::get('/directory/mip/{path}', [ClientController::class, 'createMip'])->where('path', '.*')->name('directory.mip');

        Route::get('/directory/destroy/{path}', [ClientController::class, 'destroyDirectory'])->where('path', '.*')->name('directory.destroy');
        Route::get('/file/destroy/{path}', [ClientController::class, 'destroyFile'])->where('path', '.*')->name('file.destroy');

        Route::post('/reports/signature/store', [ClientController::class, 'storeSignature'])->name('report.signature.store');
        Route::get('/report/search/backup', [ClientController::class, 'searchBackupReport'])->name('report.search.backup');
    });


Route::prefix('report')
    ->middleware('auth')
    ->group(function () {
        Route::get('/customer/export/{va}', [ReportController::class, 'indexc'])->name('customersexport.index');
        Route::get('/export/{va}', [ReportController::class, 'index'])->name('reportServs.index');
        Route::post('/export/{va}', [ReportController::class, 'create'])->name('reportServs.create');
        Route::post('/customer/export/{va}', [ReportController::class, 'create_customer_report'])->name('reportcustomer.create');
    });

// USUARIOS
Route::prefix('users')
    ->middleware('auth')
    ->name('user.')
    ->group(function () {
        Route::get('/{type}', [UserController::class, 'index'])->name('index');
        Route::get('/create/{type}', [UserController::class, 'create'])->name('create');
        Route::post('/store', [UserController::class, 'store'])->name('store');
        Route::get('/show/{id}/{section}', [UserController::class, 'show'])->name('show');
        Route::get('/edit/{id}/{section}', [UserController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [UserController::class, 'update'])->name('update');
        Route::get('/destroy/{id}', [UserController::class, 'destroy'])->name('destroy');
        Route::get('/restore/{id}', [UserController::class, 'active'])->name('restore');
        Route::get('/search/{type}', [UserController::class, 'search'])->name('search');
        Route::post('/file/upload', [UserController::class, 'storeFile'])->name('file.upload');
        Route::get('/file/download/{id}', [UserController::class, 'downloadFile'])->name('file.download');
        Route::get('/export', [UserController::class, 'export'])->name('export');
    });

// CLIENTES
Route::prefix('customers')
    ->name('customer.')
    ->middleware('auth')
    ->group(function () {
        Route::get('/{type}', [CustomerController::class, 'index'])->name('index');
        Route::get('/create/{id}/{type}', [CustomerController::class, 'create'])->name('create');
        Route::post('/store/{id}/{type}', [CustomerController::class, 'store'])->name('store');
        Route::get('/edit/{id}/{type}/{section}', [CustomerController::class, 'edit'])->name('edit');
        Route::get('/show/{id}/{type}/{section}', [CustomerController::class, 'show'])->name('show');
        Route::post('/update/{id}/{type}', [CustomerController::class, 'update'])->name('update');
        Route::get('/destroy/{id}', [CustomerController::class, 'destroy'])->name('destroy');
        Route::get('/search/{type}', [CustomerController::class, 'search'])->name('search');
        Route::post('/file/upload', [CustomerController::class, 'store_file'])->name('file.upload');
        Route::get('/file/download/{id}', [CustomerController::class, 'download_file'])->name('file.download');
        Route::post('/autocomplete', [CustomerController::class, 'search_lead'])->name('autocomplete');
        Route::get('/convert/{id}', [CustomerController::class, 'convert'])->name('convert');
        Route::get('/tracking/{id}', [CustomerController::class, 'tracking'])->name('tracking');

        Route::post('reference/store/{customerId}', [CustomerController::class, 'storeReference'])->name('reference.store');
        Route::post('reference/update/{id}', [CustomerController::class, 'updateReference'])->name('reference.update');
        Route::get('reference/destroy/{id}', [CustomerController::class, 'destroyReference'])->name('reference.destroy');
    });

// SERVICIOS
Route::prefix('services')
    ->name('service.')
    ->middleware('auth') // Aplica el middleware de autenticación
    ->group(function () {
        Route::get('/', [ServiceController::class, 'index'])->name('index');
        Route::get('/create', [ServiceController::class, 'create'])->name('create');
        Route::post('/store', [ServiceController::class, 'store'])->name('store');
        Route::get('/show/{id}/{section}', [ServiceController::class, 'show'])->name('show');
        Route::get('/edit/{id}', [ServiceController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [ServiceController::class, 'update'])->name('update');
        Route::get('/destroy/{id}', [ServiceController::class, 'destroy'])->name('destroy');
        Route::get('/search/{page}', [ServiceController::class, 'search'])->name('search');
    });

// ORDENES DE SERVICIO
Route::prefix('orders')
    ->middleware('auth')
    ->name('order.')
    ->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/create', [OrderController::class, 'create'])->name('create');
        Route::post('/store', [OrderController::class, 'store'])->name('store');
        Route::get('/search', [OrderController::class, 'search'])->name('search');

        Route::post('/store/signature', [OrderController::class, 'storeSignature'])->name('signature.store');
        // Route::get('/search')
        Route::get('/show/{id}/{section}', [OrderController::class, 'show'])->name('show');
        Route::get('/edit/{id}', [OrderController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [OrderController::class, 'update'])->name('update');
        Route::get('/destroy/{id}', [OrderController::class, 'destroy'])->name('destroy');

        Route::post('/search/customer', [OrderController::class, 'searchCustomer'])->name('search.customer');
        Route::post('/search/service/{type}', [OrderController::class, 'searchService'])->name('search.service');

    });

// PRODUCTOS
Route::prefix('products')
    ->name('product.')
    ->middleware('auth') // Aplica el middleware de autenticación
    ->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::post('/store', [ProductController::class, 'store'])->name('store');
        Route::get('/create', [ProductController::class, 'create'])->name('create');
        Route::get('/show/{id}/{section}', [ProductController::class, 'show'])->name('show');
        Route::get('/edit/{id}/{section}', [ProductController::class, 'edit'])->name('edit');
        Route::post('/update/{id}/{section}', [ProductController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [ProductController::class, 'destroy'])->name('destroy');
        Route::get('/search', [ProductController::class, 'search'])->name('search');
        Route::post('/input/{id}', [ProductController::class, 'input'])->name('input');
        Route::get('/input/destroy/{id}', [ProductController::class, 'destroyInput'])->name('input.destroy');
        Route::post('/file/upload/{id}', [ProductController::class, 'storeFile'])->name('file.upload');
        Route::get('/file/download/{id}/{file}', [ProductController::class, 'downloadFile'])->name('file.download');
        Route::get('/file/destroy/{id}/{file}', [ProductController::class, 'destroyFile'])->name('file.destroy');
    });



Route::middleware('auth')
    ->group(function () {
        Route::post('/saveIndex', [PagesController::class, 'setIndexEdit'])->name('page.index.set');
    });

//RUTAS PARA LA REFERENCIAS DEL CLIENTE
Route::prefix('customer/reference')
    ->middleware('auth')
    ->group(function () {
        Route::get('create/{id}/{type}', [CustomerController::class, 'createReference'])->name('reference.create');
        Route::get('/edit/{id}/{type}', [CustomerController::class, 'editReference'])->name('reference.edit');
        Route::post('/{ref}/{type}', [CustomerController::class, 'updateReference'])->name('customer.Referenceupdate');
        Route::get('/show/{id}/{type}', [CustomerController::class, 'showReference'])->name('reference.show');
    });

// RUTAS PARA LAS AREAS DEL CLIENTE
Route::prefix('area')
    ->name('area.')
    ->middleware('auth')
    ->group(function () {
        Route::post('/store/{customerId}', [CustomerController::class, 'storeArea'])->name('store');
        Route::post('/update/{id}', [CustomerController::class, 'updateArea'])->name('update');
        Route::get('/delete/{id}', [CustomerController::class, 'destroyArea'])->name('destroy');
    });

//RUTAS PARA PLANOS
Route::prefix('floorplans')
    ->name('floorplans.')
    ->middleware('auth')
    ->group(function () {
        Route::get('/{id}', [FloorplansController::class, 'index'])->name('index');
        Route::get('/create/{id}', [FloorplansController::class, 'create'])->name('create');
        Route::post('/search/devices/{id}', [FloorplansController::class, 'searchDevicesbyVersion'])->name('search.devices');
        Route::get('/print/{id}/{type}', [FloorplansController::class, 'print'])->name('print');
        Route::post('/update/{id}/{section}', [FloorplansController::class, 'update'])->name('update');
        Route::get('/delete/{id}/{customerID}/{type}', [FloorplansController::class, 'delete'])->name('delete');

    });

Route::middleware('auth')
    ->group(function () {
        Route::post('/floorplan/store/{customerID}/{type}', [FloorplansController::class, 'store'])->name('floorplans.store');
        Route::get('/floorplan/edit/{id}/{customerID}/{type}/{section}', [FloorplansController::class, 'edit'])->name('floorplans.edit');

        Route::get('/floorplans/show/{filename}', [FloorPlansController::class, 'getImage'])->where('filename', '.*')->name('image.show');
        Route::get('/floorplan/QR/{id}', [FloorPlansController::class, 'getQR'])->name('floorplans.qr');

        Route::post('/QR/print/{id}', [FloorplansController::class, 'printQR'])->name('floorplan.qr.print');
    });

//PLAGAS
Route::prefix('pests')
    ->name('pest.')
    ->middleware('auth') // Aplica el middleware de autenticación
    ->group(function () {
        Route::get('/', [PestController::class, 'index'])->name('index');
        Route::get('/create', [PestController::class, 'create'])->name('create');
        Route::post('/store', [PestController::class, 'store'])->name('store');
        Route::get('/show/{id}', [PestController::class, 'show'])->name('show');
        Route::get('/edit/{id}', [PestController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [PestController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [PestController::class, 'destroy'])->name('destroy');
        Route::get('/search', [PestController::class, 'search'])->name('search');
    });

// PUNTOS DE CONTROL
Route::prefix('controlpoints')
    ->name('point.')
    ->middleware('auth') // Aplica el middleware de autenticación
    ->group(function () {
        Route::get('/', [ControlPointController::class, 'index'])->name('index');
        Route::get('/create', [ControlPointController::class, 'create'])->name('create');
        Route::get('/show/{id}/{section}', [ControlPointController::class, 'show'])->name('show');
        Route::post('/store', [ControlPointController::class, 'store'])->name('store');
        Route::get('/edit/{id}/{section}', [ControlPointController::class, 'edit'])->name('edit');
        Route::post('/update/{product}', [ControlPointController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [ControlPointController::class, 'destroy'])->name('destroy');
        Route::get('/search', [ControlPointController::class, 'search'])->name('search');
    });

// SUCURSALES
Route::prefix('branches')
    ->name('branch.')
    ->middleware('auth') // Aplica el middleware de autenticación
    ->group(function () {
        Route::get('/', [BranchController::class, 'index'])->name('index');
        Route::get('/create', [BranchController::class, 'create'])->name('create');
        Route::post('/store', [BranchController::class, 'store'])->name('store');
        Route::get('/show/{id}/{section}', [BranchController::class, 'show'])->name('show');
        Route::get('/edit/{id}/{section}', [BranchController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [BranchController::class, 'update'])->name('update');
        Route::get('/destroy/{id}', [BranchController::class, 'destroy'])->name('destroy');
        Route::get('restore/{id}', [BranchController::class, 'active'])->name('restore');
        Route::get('/search', [BranchController::class, 'search'])->name('search');
    });


//RUTAS PARA GENERAR UN REPORTE
Route::prefix('report')
    ->name('report.')
    ->middleware('auth')
    ->group(function () {
        Route::get('/review/{id}', [OrderController::class, 'checkReport'])->name('review');
        Route::get('/autoreview/{orderId}/{floorplanId}', [ReportController::class, 'autoreview'])->name('autoreview');
        Route::post('/store/{orderId}', [ReportController::class, 'store'])->name('store');
        Route::get('/print/{orderId}', [ReportController::class, 'print'])->name('print');
        Route::post('/store/incidents/{orderId}', [ReportController::class, 'storeIncidents'])->name('store.incidents');
        Route::post('/store/dom/{id}/{optionChooser}', [OrderController::class, 'createReports'])->name('create');
        Route::post('/search/product', [ReportController::class, 'searchProduct'])->name('search.product');
        Route::post('/set/product/{orderId}', [ReportController::class, 'setProduct'])->name('set.product');
        Route::get('/destroy/product/{incidentId}', [ReportController::class, 'destroyProduct'])->name('destroy.product');
    });

// Daily Program
Route::name('dailyprogram.')
    ->middleware('auth')
    ->group(function () {
        Route::get('/dailyprogram', [ScheduleController::class, 'index'])->name('index');
        Route::post('/dailyprogram/date', [ScheduleController::class, 'get_dailyprogram'])->name('get');
    });



Route::get('/next-page', [PagesController::class, 'next_page'])->name('next-page');
Route::get('/prev-page', [PagesController::class, 'prev_page'])->name('prev-page');
Route::get('/nextpage', [PagesController::class, 'nextpage'])->name('nextpage');
Route::get('/prevpage', [PagesController::class, 'prevpage'])->name('prevpage');
Route::get('/nextpag', [PagesController::class, 'nextpag'])->name('nextpag');
Route::get('/prevpag', [PagesController::class, 'prevpag'])->name('prevpag');
Route::get('/nextpa', [PagesController::class, 'nextpa'])->name('nextpa');
Route::get('/prevpa', [PagesController::class, 'prevpa'])->name('prevpa');

//RUTAS DE PRODUCTO


//PREGUNTAS - PUNTO DE CONTROL
Route::prefix('question')
    ->name('question.')
    ->middleware('auth')
    ->group(function () {
        Route::get('/create/{pointId}', [QuestionController::class, 'create'])->name('create');
        Route::post('/store/{pointId}', [QuestionController::class, 'store'])->name('store');
        Route::get('/edit/{question}', [QuestionController::class, 'edit'])->name('edit');
        Route::put('/update/{question}', [QuestionController::class, 'update'])->name('update');
        Route::get('/delete/{question}', [QuestionController::class, 'destroy'])->name('destroy');
        Route::post('/set/{pointId}', [QuestionController::class, 'set'])->name('set');
    });

//RUTAS PARA CALENDARIO
Route::prefix('contracts')
    ->name('contract.')
    ->middleware('auth')
    ->group(function () {
        Route::get('/', [ContractController::class, 'index'])->name('index');
        Route::get('/create', [ContractController::class, 'create'])->name('create');
        Route::post('/store', [ContractController::class, 'store'])->name('store');
        Route::get('/show/{id}/{section}', [ContractController::class, 'show'])->name('show');
        Route::get('/edit/{id}', [ContractController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [ContractController::class, 'update'])->name('update');
        Route::get('/destroy/{id}', [ContractController::class, 'destroy'])->name('destroy');
        Route::get('/search', [ContractController::class, 'search'])->name('search');
        Route::get('/search/orders/{id}/{customerId}', [ContractController::class, 'searchOrders'])->name('search.orders');
        Route::get('/upload', [ContractController::class, ''])->name('upload');
        Route::get('/download/{id}/{file}', [ContractController::class, ''])->name('download');
        Route::get('/getSelectedTechnicians', [ContractController::class, 'getSelectedTechnicians'])->name('getTechnicans');
        Route::post('/update/technicians/{id}', [ContractController::class, 'updateTechnicians'])->name('update.technicians');
        Route::post('/file/{contractID}/{type}', [ContractController::class, 'store_file'])->name('file');
        Route::get('/index/{contractID}/', [ContractController::class, 'index_contract'])->name('indexS');
        Route::get('/file/download/{id}', [ContractController::class, 'contract_downolad'])->name('file.download');
    });

// RUTAS PARA EL PLAN DE ROTACION

Route::prefix('rotationplan')
    ->name('rotation.')
    ->middleware('auth')
    ->group(function () {
        Route::get('/{contractId}', [RotationPlanController::class, 'index'])->name('index');
        Route::get('/create/{contractId}', [RotationPlanController::class, 'create'])->name('create');
        Route::post('/store', [RotationPlanController::class, 'store'])->name('store');
        Route::get('/show/{id}', [RotationPlanController::class, 'show'])->name('show');
        Route::get('/edit/{id}', [RotationPlanController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [RotationPlanController::class, 'update'])->name('update');
        Route::get('/destroy/{id}', [RotationPlanController::class, 'destroy'])->name('destroy');

        Route::post('/ajax/search/product', [RotationPlanController::class, 'searchProduct'])->name('search.product');
    });


//Rutas de busqueda para AJAX
Route::prefix('ajax')
    ->name('ajax.')
    ->middleware('auth')
    ->group(function () {
        Route::post('/control-points', [OrderController::class, 'getControlPoints'])->name('devices.points');
        Route::post('/devices/{id}', [FloorPlansController::class, 'getDevicesVersion'])->name('devices');
        Route::post('/quality/search/orders/customer', [PagesController::class, 'getOrdersByCustomer'])->name('quality.search.customer');
        Route::post('/quality/search/orders/date', [QualityController::class, 'getOrdersByDate'])->name('quality.search.date');
        Route::post('/quality/search/orders/time', [QualityController::class, 'getOrdersByTime'])->name('quality.search.time');
        Route::post('/quality/search/orders/service', [QualityController::class, 'getOrdersByService'])->name('quality.search.service');
        Route::post('/quality/search/orders/status', [QualityController::class, 'getOrdersByStatus'])->name('quality.search.status');
        Route::post('/quality/search/orders/technician/{id}', [QualityController::class, 'searchOrdersTechnician'])->name('quality.search.date.technician');
        

        Route::get('/contract/service', [ContractController::class, 'getSelectData'])->name('contract.service');
        Route::post('/search/devices/{floorplan_id}', [FloorPlansController::class, 'searchDevices'])->name('search.devices');
    });

require __DIR__ . '/auth.php';