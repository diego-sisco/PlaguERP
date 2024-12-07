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
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GraphicController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\LotController;

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
    Route::get('/control', [PagesController::class, 'qualityControl'])->name('control');
    Route::post('/control/store', [PagesController::class, 'qualityControlStore'])->name('control.store');
    Route::get('/control/destroy/{customerId}', [PagesController::class, 'qualityControlDestroy'])->name('control.destroy');
    Route::get('/customers', [PagesController::class, 'qualityCustomers'])->name('customers');
    Route::get('/orders/{status}', [PagesController::class, 'qualityOrders'])->name('orders');
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

Route::post('/warehouse/input', [WarehouseController::class, 'storeMovement'])->name('warehouse.input');
Route::post('/warehouse/output', [WarehouseController::class, 'storeMovement'])->name('warehouse.output');
Route::get('/warehouse/movements/show', [WarehouseController::class, 'allMovements'])->name('warehouse.movements.show');
Route::get('/warehouse/movements/{id}', [WarehouseController::class, 'movements'])->name('warehouse.movements');
Route::get('/warehouse/stock/{id}', [WarehouseController::class, 'stock'])->name('warehouse.stock');
Route::post('/warehouse/destroy/{id}', [WarehouseController::class, 'destroy'])->name('warehouse.destroy');

Route::post('/inventory/store_entry', [WarehouseController::class, 'store_entry'])->name('warehouse.store_entry');
Route::post('/inventory/search', [WarehouseController::class, 'search'])->name('inventory.search');
Route::get('/movements', [WarehouseController::class, 'showAllMovements'])->name('movements.all');
Route::get('/inventory/{id}/generate-pdf', [WarehouseController::class, 'movement_print'])->name('warehouse.pdf');


//lot
Route::get('/lot/index', [LotController::class, 'index'])->name('lot.index');
Route::get('/lot/create', [LotController::class, 'create'])->name('lot.create');
Route::post('/lot/store', [LotController::class, 'store'])->name('lot.store');
Route::get('/lot/{id}/edit', [LotController::class, 'edit'])->name('lot.edit');
Route::put('/lot/update/{id}', [LotController::class, 'update'])->name('lot.update');
Route::get('/lot/show/{id}', [LotController::class, 'show'])->name('lot.show');
Route::get('/lot/destroy/{id}', [LotController::class, 'destroy'])->name('lot.destroy');


// New Customers
Route::get('/CRM/chart/customers', [GraphicController::class, 'newCustomersDataset'])->name('crm.chart.customers');
Route::get('/CRM/chart/customers/update', [GraphicController::class, 'refreshNewCustomers'])->name('crm.chart.customers.refresh');

// Scheduled Orders
Route::get('/CRM/chart/orders', [GraphicController::class, 'ordersDataset'])->name('crm.chart.orders');
Route::get('/CRM/chart/orders/update', [GraphicController::class, 'refreshOrders'])->name('crm.chart.orders.refresh');

// Order Types
Route::get('/CRM/chart/ordertypes/{service_type}', [GraphicController::class, 'orderTypesDataset'])->name('crm.chart.ordertypes');
Route::get('/CRM/chart/ordertypes/{service_type}/update', [GraphicController::class, 'refreshOrderTypes'])->name('crm.chart.ordertypes.refresh');

// Client System
Route::prefix('clients')
    ->middleware('auth')
    ->name('client.')
    ->group(function () {
        Route::get('/', [ClientController::class, 'index'])->name('index');
        Route::get('/reports/{section}', [ClientController::class, 'reports'])->name('reports.index');

        Route::post('/reports/search', [ClientController::class, 'searchReport'])->name('report.search');
    });
Route::get('/clients/mip/{path}', [ClientController::class, 'mip'])->where('path', '.*')->name('client.mip.index');
Route::get('/clients/system/{path}', [ClientController::class, 'directories'])->where('path', '.*')->name('client.system.index');
Route::post('/client/directory/store', [ClientController::class, 'storeDirectory'])->name('client.directory.store');
Route::post('/client/file/store', [ClientController::class, 'storeFile'])->name('client.file.store');
Route::post('/client/directory/update', [ClientController::class, 'updateDirectory'])->name('client.directory.update');
Route::post('/client/directory/permissions', [ClientController::class, 'permissions'])->name('client.directory.permissions');
Route::get('/client/directory/mip/{path}', [ClientController::class, 'createMip'])->where('path', '.*')->name('client.directory.mip');

Route::get('/clients/file/download/{path}', [ClientController::class, 'downloadFile'])->where('path', '.*')->name('client.file.download');

Route::get('/client/directory/destroy/{path}', [ClientController::class, 'destroyDirectory'])->where('path', '.*')->name('client.directory.destroy');
Route::get('/client/file/destroy/{path}', [ClientController::class, 'destroyFile'])->where('path', '.*')->name('client.file.destroy');


Route::post('/client/reports/signature/store', [ClientController::class, 'storeSignature'])->name('client.report.signature.store');
Route::get('/client/report/search/backup', [ClientController::class, 'searchBackupReport'])->name('client.report.search.backup');

Route::get('/report/customer/export/{va}', [ReportController::class, 'indexc'])->name('customersexport.index');
Route::get('/report/export/{va}', [ReportController::class, 'index'])->name('reportServs.index');
Route::post('/report/export/{va}', [ReportController::class, 'create'])->name('reportServs.create');
Route::post('/report/customer/export/{va}', [ReportController::class, 'create_customer_report'])->name('reportcustomer.create');

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
        Route::get('/delete/{id}', [CustomerController::class, 'destroy'])->name('destroy');
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
        Route::get('/show/{id}/{section}', [OrderController::class, 'show'])->name('show');
        Route::get('/edit/{id}', [OrderController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [OrderController::class, 'update'])->name('update');
        Route::get('/destroy/{id}', [OrderController::class, 'destroy'])->name('destroy');
        Route::get('/search', [OrderController::class, 'search'])->name('search');
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



Route::post('/saveIndex', [PagesController::class, 'setIndexEdit'])->name('page.index.set');

//RUTAS PARA LA REFERENCIAS DEL CLIENTE
Route::get('customer/reference/create/{id}/{type}', [CustomerController::class, 'createReference'])->name('reference.create');
Route::get('customer/reference/edit/{id}/{type}', [CustomerController::class, 'editReference'])->name('reference.edit');
Route::post('customer/reference/{ref}/{type}', [CustomerController::class, 'updateReference'])->name('customer.Referenceupdate');
Route::get('customer/reference/show/{id}/{type}', [CustomerController::class, 'showReference'])->name('reference.show');

// RUTAS PARA LAS AREAS DEL CLIENTE
Route::post('/area/store/{customerId}', [CustomerController::class, 'storeArea'])->name('area.store');
Route::post('/area/update/{id}', [CustomerController::class, 'updateArea'])->name('area.update');
Route::get('/area/delete/{id}', [CustomerController::class, 'destroyArea'])->name('area.destroy');

//RUTAS PARA PLANOS
Route::get('/floorplans/{id}', [FloorplansController::class, 'index'])->name('floorplans.index');
Route::get('/floorplans/create/{id}', [FloorplansController::class, 'create'])->name('floorplans.create');
Route::post('/floorplan/store/{customerID}/{type}', [FloorplansController::class, 'store'])->name('floorplans.store');
Route::get('/floorplan/edit/{id}/{customerID}/{type}/{section}', [FloorplansController::class, 'edit'])->name('floorplans.edit');
Route::get('/floorplans/print/{id}/{type}', [FloorplansController::class, 'print'])->name('floorplans.print');
Route::post('floorplans/update/{id}/{section}', [FloorplansController::class, 'update'])->name('floorplans.update');
Route::get('/floorplans/delete/{id}/{customerID}/{type}', [FloorplansController::class, 'delete'])->name('floorplans.delete');
Route::get('/floorplans/show/{filename}', [FloorPlansController::class, 'getImage'])->where('filename', '.*')->name('image.show');
Route::get('/floorplan/QR/{id}', [FloorPlansController::class, 'getQR'])->name('floorplans.qr');
Route::post('/floorplans/QR/print/{id}', [FloorplansController::class, 'printQR'])->name('floorplan.qr.print');

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
Route::get('/dailyprogram', [ScheduleController::class, 'index'])->name('dailyprogram.index');
Route::post('/dailyprogram/date', [ScheduleController::class, 'get_dailyprogram'])->name('dailyprogram.get');



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
Route::get('/question/create/{pointId}', [QuestionController::class, 'create'])->name('question.create');
Route::post('/question/store/{pointId}', [QuestionController::class, 'store'])->name('question.store');
Route::get('/question/edit/{question}', [QuestionController::class, 'edit'])->name('question.edit');
Route::put('/question/update/{question}', [QuestionController::class, 'update'])->name('question.update');
Route::get('/question/delete/{question}', [QuestionController::class, 'destroy'])->name('question.destroy');
Route::post('/question/set/{pointId}', [QuestionController::class, 'set'])->name('question.set');

//RUTAS PARA CALENDARIO
Route::get('/contracts', [ContractController::class, 'index'])->name('contract.index');
Route::get('/contract/create', [ContractController::class, 'create'])->name('contract.create');
Route::post('/contract/store', [ContractController::class, 'store'])->name('contract.store');
Route::get('/contract/show/{id}/{section}', [ContractController::class, 'show'])->name('contract.show');
Route::get('/contract/edit/{id}', [ContractController::class, 'edit'])->name('contract.edit');
Route::post('/contract/update/{id}', [ContractController::class, 'update'])->name('contract.update');
Route::get('/contract/destroy/{id}', [ContractController::class, 'destroy'])->name('contract.destroy');
Route::get('/contract/search', [ContractController::class, 'search'])->name('contract.search');
Route::get('/contract/upload', [ContractController::class, ''])->name('contract.upload');
Route::get('/contract/download/{id}/{file}', [ContractController::class, ''])->name('contract.download');
Route::get('/contract/getSelectedTechnicians', [ContractController::class, 'getSelectedTechnicians'])->name('contract.getTechnicans');
Route::post('/contract/update/technicians/{id}', [ContractController::class, 'updateTechnicians'])->name('contract.update.technicians');
Route::post('/contract/file/{contractID}/{type}', [ContractController::class, 'store_file'])->name('contract.file');
Route::get('/contract/index/{contractID}/', [ContractController::class, 'index_contract'])->name('contract.indexS');
Route::get('/contract/file/download/{id}', [ContractController::class, 'contract_downolad'])->name('contract.file.download');

//Rutas de busqueda para AJAX
Route::post('/ajax/control-points', [OrderController::class, 'getControlPoints'])->name('ajax.devices.points');
Route::post('/ajax/devices/{id}', [FloorPlansController::class, 'getDevicesVersion'])->name('ajax.devices');
Route::post('/ajax/quality/search/orders/customer', [PagesController::class, 'getOrdersByCustomer'])->name('ajax.quality.search.customer');
Route::get('/ajax/contract/service', [ContractController::class, 'getSelectData'])->name('ajax.contract.service');
Route::post('/ajax/search/devices/{floorplan_id}', [FloorPlansController::class, 'searchDevices'])->name('ajax.search.devices');

require __DIR__ . '/auth.php';