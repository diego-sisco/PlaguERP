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
use App\Http\Controllers\ProfileController;
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



Route::get('/', function () {
	return view('/auth/login');
});

Route::middleware('auth')->group(function () {
	Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
	Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
	Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::redirect('/', '/dashboard');

// DASHBOARD
Route::get('/dashboard', [PagesController::class, 'dashboard'])->name('dashboard');
Route::get('/dashboard/CRM/{section}', [PagesController::class, 'crm'])->name('dashboard.crm');
Route::get('/dashboard/RRHH/{section}', [PagesController::class, 'rrhh'])->name('dashboard.rrhh');

Route::prefix('planning')->group(function () {
    Route::get('/schedule', [PagesController::class, 'planning'])->name('planning.schedule');
    Route::post('/schedule/update', [PagesController::class, 'updateSchedule'])->name('planning.schedule.update');
    Route::get('/activities', [PagesController::class, 'planning'])->name('planning.activities');
    Route::post('/filter', [PagesController::class, 'filterPlanning'])->name('planning.filter');
});

Route::prefix('quality')->group(function () {
    Route::get('/control/{page}', [PagesController::class, 'qualityControl'])->name('quality.control');
    Route::post('/control/store', [PagesController::class, 'qualityControlStore'])->name('quality.control.store');
    Route::get('/control/destroy/{customerId}', [PagesController::class, 'qualityControlDestroy'])->name('quality.control.destroy');
    Route::get('/customers/{page}', [PagesController::class, 'qualityCustomers'])->name('quality.customers');
    Route::get('/orders/{status}/{page}', [PagesController::class, 'qualityOrders'])->name('quality.orders');
});


// CHARTS
//Inventories
Route::get('/warehouses/{is_active}', [WarehouseController::class, 'index'])->name('warehouse.index');
Route::get('/warehouse/create', [WarehouseController::class, 'create'])->name('warehouse.create');
Route::post('/warehouse/store', [WarehouseController::class, 'store'])->name('warehouse.store');
Route::get('/warehouse/edit/{id}', [WarehouseController::class, 'edit'])->name('warehouse.edit');
Route::put('/warehouse/update/{id}/', [WarehouseController::class, 'update'])->name('warehouse.update');
Route::get('/warehouse/show/{id}', [WarehouseController::class, 'show'])->name('warehouse.show');
Route::get('/warehouse/delete/{id}', [WarehouseController::class, 'delete'])->name('warehouse.delete');

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
Route::get('/lot/index',[LotController::class, 'index'])->name('lot.index');
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
Route::get('/clients', [ClientController::class, 'index'])->name('client.index');
Route::get('/clients/mip/{path}', [ClientController::class, 'mip'])->where('path', '.*')->name('client.mip.index');
Route::get('/clients/system/{path}', [ClientController::class, 'directories'])->where('path', '.*')->name('client.system.index');
Route::get('/clients/reports/{section}', [ClientController::class, 'reports'])->name('client.reports.index');
Route::post('/cquiero verte en escenalient/directory/store', [ClientController::class, 'storeDirectory'])->name('client.directory.store');
Route::post('/client/file/store', [ClientController::class, 'storeFile'])->name('client.file.store');
Route::post('/client/directory/update', [ClientController::class, 'updateDirectory'])->name('client.directory.update');
Route::post('/client/directory/permissions', [ClientController::class, 'permissions'])->name('client.directory.permissions');
Route::get('/client/directory/mip/{path}', [ClientController::class, 'createMip'])->where('path', '.*')->name('client.directory.mip');

Route::get('/clients/file/download/{path}', [ClientController::class, 'downloadFile'])->where('path', '.*')->name('client.file.download');

Route::get('/client/directory/destroy/{path}', [ClientController::class, 'destroyDirectory'])->where('path', '.*')->name('client.directory.destroy');
Route::get('/client/file/destroy/{path}', [ClientController::class, 'destroyFile'])->where('path', '.*')->name('client.file.destroy');

Route::get('/client/report/search', [ClientController::class, 'searchReport'])->name('client.report.search');
Route::get('/client/report/search/backup', [ClientController::class, 'searchBackupReport'])->name('client.report.search.backup');

Route::get('/report/customer/export/{va}', [ReportController::class, 'indexc'])->name('customersexport.index');
Route::get('/report/export/{va}', [ReportController::class, 'index'])->name('reportServs.index');
Route::post('/report/export/{va}', [ReportController::class, 'create'])->name('reportServs.create');
Route::post('/report/customer/export/{va}', [ReportController::class, 'create_customer_report'])->name('reportcustomer.create');

//Users.
Route::get('/users/{page}', [UserController::class, 'index'])->name('user.index');
Route::get('/user/create/{type}', [UserController::class, 'create'])->name('user.create');
Route::post('/user/store', [UserController::class, 'store'])->name('user.store');
Route::get('/user/show/{id}/{section}', [UserController::class, 'show'])->name('user.show');
Route::get('/user/edit/{id}/{section}', [UserController::class, 'edit'])->name('user.edit');
Route::post('/user/update/{id}', [UserController::class, 'update'])->name('user.update');
Route::get('/user/destroy/{id}', [UserController::class, 'destroy'])->name('user.destroy');
Route::get('users/restore/{id}', [UserController::class, 'active'])->name('user.restore');
Route::get('/users/search/{type}/{page}', [UserController::class, 'search'])->name('user.search');
Route::post('/users/file/upload/', [UserController::class, 'storeFile'])->name('user.file.upload');
Route::get('/users/file/download/{id}', [UserController::class, 'downloadFile'])->name('user.file.download');
Route::get('/user/export', [UserController::class, 'export'])->name('user.export');

//RUTAS DE CLIENTES
Route::get('/customers/{type}/{page}', [CustomerController::class, 'index'])->name('customer.index');
Route::get('/customer/create/{id}/{type}', [CustomerController::class, 'create'])->name('customer.create');
Route::post('/customer/store/{id}/{type}', [CustomerController::class, 'store'])->name('customer.store');
Route::get('/customer/edit/{id}/{type}/{section}', [CustomerController::class, 'edit'])->name('customer.edit');
Route::get('/customer/show/{id}/{type}/{section}', [CustomerController::class, 'show'])->name('customer.show');
Route::post('/customer/update/{id}/{type}', [CustomerController::class, 'update'])->name('customer.update');
Route::get('/customer/delete/{id}', [CustomerController::class, 'destroy'])->name('customer.destroy');
Route::get('/customer/search/{type}/{page}', [CustomerController::class, 'search'])->name('customer.search');
Route::post('/customer/file/upload/', [CustomerController::class, 'store_file'])->name('customer.file.upload');
Route::get('/customer/file/download/{id}', [CustomerController::class, 'download_file'])->name('customer.file.download');
Route::post('/customer/autocomplete', [CustomerController::class, 'search_lead'])->name('customer.autocomplete');
Route::get('/customer/convert/{id}', [CustomerController::class, 'convert'])->name('customer.convert');
Route::get('/customer/tracking/{id}', [CustomerController::class, 'tracking'])->name('customer.tracking');

Route::post('/saveIndex', [PagesController::class, 'setIndexEdit'])->name('page.index.set');

//RUTAS PARA LA REFERENCIAS DEL CLIENTE
Route::get('customer/reference/create/{id}/{type}', [CustomerController::class, 'createReference'])->name('reference.create');
Route::get('customer/reference/edit/{id}/{type}', [CustomerController::class, 'editReference'])->name('reference.edit');
Route::post('customer/reference/store/{id}/{type}', [CustomerController::class, 'storeReference'])->name('reference.store');
Route::post('customer/reference/{ref}/{type}', [CustomerController::class, 'updateReference'])->name('customer.Referenceupdate');
Route::get('customer/reference/show/{id}/{type}', [CustomerController::class, 'showReference'])->name('reference.show');

// RUTAS PARA LAS AREAS DEL CLIENTE
Route::post('/area/store/{customerId}', [CustomerController::class, 'storeArea'])->name('area.store');
Route::post('/area/update', [CustomerController::class, 'updateArea'])->name('area.update');
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

//Rutas para el control de servicios.
Route::get('/services/{page}', [ServiceController::class, 'index'])->name('service.index');
Route::get('/service/create', [ServiceController::class, 'create'])->name('service.create');
Route::post('/service/store', [ServiceController::class, 'store'])->name('service.store');
Route::get('/service/show/{id}/{section}', [ServiceController::class, 'show'])->name('service.show');
Route::get('/service/edit/{id}', [ServiceController::class, 'edit'])->name('service.edit');
Route::post('/service/update/{id}', [ServiceController::class, 'update'])->name('service.update');
Route::get('/service/destroy/{id}', [ServiceController::class, 'destroy'])->name('service.destroy');
Route::get('/service/search/{page}', [ServiceController::class, 'search'])->name('service.search');

//RUTAS DE PESTES
Route::get('/pests/{page}', [PestController::class, 'index'])->name('pest.index');
Route::get('/pest/create/{page}', [PestController::class, 'create'])->name('pest.create');
Route::post('/pest/store', [PestController::class, 'store'])->name('pest.store');
Route::get('/pest/show/{id}', [PestController::class, 'show'])->name('pest.show');
Route::get('/pest/edit/{id}', [PestController::class, 'edit'])->name('pest.edit');
Route::post('/pest/update/{id}', [PestController::class, 'update'])->name('pest.update');
Route::get('/pest/delete/{id}', [PestController::class, 'destroy'])->name('pest.destroy');
Route::get('/pest/search/{page}', [PestController::class, 'search'])->name('pest.search');

//RUTAS PARA CONTROLAR LAS ORDENES DE SERVICIO
Route::get('/orders/{page}', [OrderController::class, 'index'])->name('order.index');
Route::get('order/create', [OrderController::class, 'create'])->name('order.create');
Route::post('order/store', [OrderController::class, 'store'])->name('order.store');
Route::post('order/show-service', [OrderController::class, 'show_service'])->name('order.show_service');
Route::get('order/show/{id}/{section}', [OrderController::class, 'show'])->name('order.show');
Route::get('order/edit/{id}', [OrderController::class, 'edit'])->name('order.edit');
Route::post('order/update/{id}', [OrderController::class, 'update'])->name('order.update');
Route::get('order/destroy/{id}', [OrderController::class, 'destroy'])->name('order.destroy');
Route::get('order/search/{page}', [OrderController::class, 'search'])->name('order.search');
Route::post('order/search/customer', [OrderController::class, 'searchCustomer'])->name('order.search.customer');
Route::post('order/search/service/{type}', [OrderController::class, 'searchService'])->name('order.search.service');

//RUTAS PARA GENERAR UN REPORTE
Route::get('/report/review/{id}', [OrderController::class, 'checkReport'])->name('check.report');
Route::get('/report/autoreview/{orderId}/{floorplanId}', [ReportController::class, 'autoreview'])->name('report.autoreview');
Route::post('/report/store/{orderId}', [ReportController::class, 'store'])->name('report.store');
Route::post('/report/store/incidents/{orderId}', [ReportController::class, 'storeIncidents'])->name('report.store.incidents');
Route::post('/report/store/dom/{id}/{optionChooser}', [OrderController::class, 'createReports'])->name('report.create');
Route::post('/report/search/product', [ReportController::class,'searchProduct'])->name('report.search.product');
Route::post('/report/set/product/{orderId}', [ReportController::class,'setProduct'])->name('report.set.product');
Route::get('/report/destroy/product/{incidentId}', [ReportController::class,'destroyProduct'])->name('report.destroy.product');

// Daily Program
Route::get('/dailyprogram', [ScheduleController::class, 'index'])->name('dailyprogram.index');
Route::post('/dailyprogram/date', [ScheduleController::class, 'get_dailyprogram'])->name('dailyprogram.get');

Route::get('/branches', [BranchController::class, 'index'])->name('branch.index');
Route::get('/branch/create', [BranchController::class, 'create'])->name('branch.create');
Route::post('/branch/store', [BranchController::class, 'store'])->name('branch.store');
Route::get('/branch/show/{id}/{section}', [BranchController::class, 'show'])->name('branch.show');
Route::get('/branch/edit/{id}/{section}', [BranchController::class, 'edit'])->name('branch.edit');
Route::post('/branch/update/{id}', [BranchController::class, 'update'])->name('branch.update');
Route::get('/branch/destroy/{id}', [BranchController::class, 'destroy'])->name('branch.destroy');
Route::get('branches/restore/{id}', [BranchController::class, 'active'])->name('branch.restore');
Route::get('/branches/search', [BranchController::class, 'search'])->name('branch.search');

Route::get('/next-page', [PagesController::class, 'next_page'])->name('next-page');
Route::get('/prev-page', [PagesController::class, 'prev_page'])->name('prev-page');
Route::get('/nextpage', [PagesController::class, 'nextpage'])->name('nextpage');
Route::get('/prevpage', [PagesController::class, 'prevpage'])->name('prevpage');
Route::get('/nextpag', [PagesController::class, 'nextpag'])->name('nextpag');
Route::get('/prevpag', [PagesController::class, 'prevpag'])->name('prevpag');
Route::get('/nextpa', [PagesController::class, 'nextpa'])->name('nextpa');
Route::get('/prevpa', [PagesController::class, 'prevpa'])->name('prevpa');

//RUTAS DE PRODUCTO
Route::get('/products/{page}', [ProductController::class, 'index'])->name('product.index');
Route::post('/product/store', [ProductController::class, 'store'])->name('product.store');
Route::get('/product/create', [ProductController::class, 'create'])->name('product.create');
Route::get('/product/show/{id}/{section}', [ProductController::class, 'show'])->name('product.show');
Route::get('/product/edit/{id}/{section}', [ProductController::class, 'edit'])->name('product.edit');
Route::post('/product/update/{id}/{section}', [ProductController::class, 'update'])->name('product.update');
Route::get('/product/delete/{id}', [ProductController::class, 'destroy'])->name('product.destroy');
Route::get('/product/search/{page}', [ProductController::class, 'search'])->name('product.search');
Route::post('/product/input/{id}', [ProductController::class, 'input'])->name('product.input');
Route::get('/product/input/destroy/{id}', [ProductController::class, 'destroyInput'])->name('product.input.destroy');
Route::post('/product/file/upload/', [ProductController::class, 'store_file'])->name('product.file.upload');
Route::get('/product/file/download/{id}/{file}', [ProductController::class, 'file_download'])->name('product.file.download');
Route::get('/product/file/delete/{id}/{file}/{section}', [ProductController::class, 'file_delete'])->name('product.file.delete');

//PRODUCTO - PUNTO DE CONTROL
Route::get('/controlpoints/{page}', [ControlPointController::class, 'index'])->name('point.index');
Route::get('/controlpoint/create', [ControlPointController::class, 'create'])->name('point.create');
Route::get('/controlpoint/show/{id}/{section}', [ControlPointController::class, 'show'])->name('point.show');
Route::post('/controlpoint/store', [ControlPointController::class, 'store'])->name('point.store');
Route::get('/controlpoint/edit/{id}/{section}', [ControlPointController::class, 'edit'])->name('point.edit');
Route::post('/controlpoint/update/{product}', [ControlPointController::class, 'update'])->name('point.update');
Route::get('/controlpoint/delete/{id}', [ControlPointController::class, 'destroy'])->name('point.destroy');
Route::get('/controlpoint/search/{page}', [ControlPointController::class, 'search'])->name('point.search');


//PREGUNTAS - PUNTO DE CONTROL
Route::get('/question/create/{pointID}/{section}', [QuestionController::class, 'create'])->name('question.create');
Route::post('/question/store/{pointID}/{section}', [QuestionController::class, 'store'])->name('question.store');
Route::get('/question/edit/{question}', [QuestionController::class, 'edit'])->name('question.edit');
Route::put('/question/update/{question}', [QuestionController::class, 'update'])->name('question.update');
Route::get('/question/delete/{question}', [QuestionController::class, 'destroy'])->name('question.destroy');
Route::post('/question/set/{pointID}', [QuestionController::class, 'set'])->name('question.set');

//RUTAS PARA CALENDARIO
Route::get('/contracts/{page}', [ContractController::class, 'index'])->name('contract.index');
Route::get('/contract/create', [ContractController::class, 'create'])->name('contract.create');
Route::post('/contract/store', [ContractController::class, 'store'])->name('contract.store');
Route::get('/contract/show/{id}/{section}', [ContractController::class, 'show'])->name('contract.show');
Route::get('/contract/edit', [ContractController::class, 'edit'])->name('contract.edit');
Route::post('/contract/update', [ContractController::class, 'update'])->name('contract.update');
Route::get('/contract/destroy/{id}', [ContractController::class, 'destroy'])->name('contract.destroy');
Route::get('/contract/search/{page}', [ContractController::class, 'search'])->name('contract.search');
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

require __DIR__ . '/auth.php';