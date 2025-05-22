<?php




use App\Application\Product\UseCases\GetProductByIdUseCase;
use Controllers\Pdf\ShowBillController;
use Controllers\Category\GetListCategoryCompanyController;
use Controllers\Client\GetClientFindByIdController;
use Controllers\Employee\DeleteEmployeeRestaurantController;
use Controllers\Employee\GetListEmployeeRestaurantsController;
use Controllers\Employee\GetListEmployeesCompanyController;
use Controllers\Employee\PostEmployeeRestaurantController;
use Controllers\Employee\PostEmployeesRestaurantController;
use Controllers\Location\GetLocationFindByIdController;
use Controllers\Payment\DeletePaymentController;
use Controllers\Payment\GetAllPaymentsController;
use Controllers\Payment\PostPaymentController;
use Controllers\Pdf\GenerateBillController;
use Controllers\Pdf\GenerateTicketController;
use Controllers\Pdf\UpdateBillController;
use Controllers\Product\DeleteProductController;
use Controllers\Product\GetAllProductsRestaurantController;
use Controllers\Product\GetListProductsRestaurantController;
use Controllers\Product\GetProductByIdController;
use Controllers\Product\GetProductRestaurantController;
use Controllers\Product\PostRestaurantProductController;
use Controllers\Product\PutDeactivateProductController;
use Controllers\Product\PutProductController;
use Controllers\Product\PutProductRestaurantController;
use Controllers\Product\UpdateImageController;
use Controllers\Restaurant\GetRestaurantController;
use Controllers\Restaurant\PostRestaurantController;
use Controllers\Category\DeleteCategoryController;
use Controllers\Category\GetCategoryCompanyController;
use Controllers\Category\GetListCategoryRestaurantController;
use Controllers\Category\PostCategoryController;
use Controllers\Category\PutActiveCategoryController;
use Controllers\Category\PutCategoryController;
use Controllers\Client\DeleteClientCompanyController;
use Controllers\Client\GetClientCifController;
use Controllers\Client\GetListClientCompanyController;
use Controllers\Client\GetPaymentsClientController;
use Controllers\Client\PostClientCompanyController;
use Controllers\Client\PutClientCompanyController;
use Controllers\Company\GetCompanyOrdersController;
use Controllers\Company\GetListProductsCompanyController;
use Controllers\Company\GetListRestaurantsCompanyController;
use Controllers\Company\GetListRolesCompanyController;
use Controllers\Company\GetLoginCompanyController;
use Controllers\Company\PostProductCompanyController;
use Controllers\Employee\DeleteEmployeesController;
use Controllers\Employee\GetEmployeeController;
use Controllers\Employee\GetListEmployeesRestaurantController;
use Controllers\Employee\GetLoginEmployeesController;
use Controllers\Employee\PostEmployeesController;
use Controllers\Employee\PutEmployeeRoleController;
use Controllers\Employee\PutEmployeesController;
use Controllers\Location\DeleteLocationController;
use Controllers\Location\GetListLocationsRestaurantController;
use Controllers\Location\PostLocationController;
use Controllers\Location\PutActiveLocationController;
use Controllers\Location\PutLocationController;
use Controllers\Order\DeleteOrderController;
use Controllers\Order\DeleteOrderLineController;
use Controllers\Order\GetListOrderLineTableController;
use Controllers\Order\GetOngoingOrdersController;
use Controllers\Order\GetOrderController;
use Controllers\Order\GetPaymentsOrderController;
use Controllers\Order\GetRestaurantOrdersController;
use Controllers\Order\PostOrderController;
use Controllers\Order\PostOrderLineController;
use Controllers\Order\PutOrderDinersController;
use Controllers\Order\PutOrderLineController;
use Controllers\Order\PutOrderStatusController;
use Controllers\Payment\UpdatePaymentController;
use Controllers\Restaurant\DeleteRestaurantController;
use Controllers\Restaurant\GetListTablesRestaurantController;
use Controllers\Restaurant\PostLoginRestaurantController;
use Controllers\Restaurant\PutRestaurantController;
use Controllers\Role\DeleteRoleController;
use Controllers\Role\GetRoleController;
use Controllers\Role\PostRoleController;
use Controllers\Role\PutRoleController;
use Controllers\Table\DeleteTableController;
use Controllers\Table\GetTableFindByIdController;
use Controllers\Table\PostTableController;
use Controllers\Table\PutActiveTableController;
use Controllers\Table\PutTableController;
use Illuminate\Support\Facades\Route;




// Categoria
Route::get(uri: '/category', action: GetCategoryCompanyController::class);
Route::get(uri: '/categories', action: GetListCategoryRestaurantController::class);
Route::get(uri: '/company/categories', action: GetListCategoryCompanyController::class);
Route::post('/categories', action: PostCategoryController::class);
Route::put('/categories', action: PutCategoryController::class);
Route::put('/active-categories', action: PutActiveCategoryController::class);
Route::delete('/category', action: DeleteCategoryController::class);

// Cliente
Route::get('/clients', GetListClientCompanyController::class);
Route::post('/clients', action: PostClientCompanyController::class);
Route::put('/clients', action: PutClientCompanyController::class);
Route::get(uri: '/clients/find-by-cif', action: GetClientCifController::class);
Route::get(uri: '/clients/find-by-id', action: GetClientFindByIdController::class);
Route::delete(uri: '/clients', action: DeleteClientCompanyController::class);



//Empleado¡
Route::get('/employees', GetListEmployeesRestaurantController::class);
Route::get('/employees-company', GetListEmployeesCompanyController::class);
Route::post('/employees/login', GetLoginEmployeesController::class);
Route::get('/employee', action: GetEmployeeController::class);
Route::post('/employees', PostEmployeesController::class);
Route::put('/employees', PutEmployeesController::class);
Route::delete('/employees', action: DeleteEmployeesController::class);
Route::put('/employees-role', PutEmployeeRoleController::class);



// EmpleadoRestaurante
Route::get('/employee-restaurants', GetListEmployeeRestaurantsController::class);
Route::post('/employee-restaurant', PostEmployeeRestaurantController::class);
Route::delete('/employee-restaurant', DeleteEmployeeRestaurantController::class);

// Empresa
Route::post('/company/login', GetLoginCompanyController::class);


// LineaPedido
Route::get('/orders', action: GetListOrderLineTableController::class);
Route::post('/order-line', action: PostOrderLineController::class);
Route::put('/order-line', action: PutOrderLineController::class);
Route::delete('/order-line', action: DeleteOrderLineController::class);


// Mesa
Route::get('/tables', GetListTablesRestaurantController::class);
Route::get('/table/find-by-id', GetTableFindByIdController::class);
Route::post('/tables', action: PostTableController::class);
Route::put('/tables', action: PutTableController::class);
Route::put('/active-tables', action: PutActiveTableController::class);
Route::delete('/tables', action: DeleteTableController::class);

// Pago
Route::get('/payments', GetAllPaymentsController::class);
Route::get('/order/payments', GetPaymentsOrderController::class);
Route::get('/client/payments', GetPaymentsClientController::class);
Route::post('/payment', PostPaymentController::class);
Route::delete('/payment', action: DeletePaymentController::class);
Route::put('/payment', UpdatePaymentController::class);


// Pedido
Route::post(uri: '/order', action: PostOrderController::class);
Route::get('/order/get-order', [GetOrderController::class, 'getOrder']);
Route::get('/ongoing-orders', GetOngoingOrdersController::class);
Route::get('/restaurant/orders', GetRestaurantOrdersController::class);
Route::get('/company/orders', GetCompanyOrdersController::class);
Route::put(uri: '/order', action: PutOrderDinersController::class);
Route::put(uri: '/order-status', action: PutOrderStatusController::class);
Route::delete('/order', DeleteOrderController::class);

// Producto
Route::get(uri: '/products', action: GetListProductsRestaurantController::class);
Route::get(uri: '/restaurant/products', action: GetAllProductsRestaurantController::class);
Route::get(uri: '/product', action: GetProductByIdController::class);
Route::get(uri: '/company/products', action: GetListProductsCompanyController::class);
Route::post(uri: '/products', action: PostProductCompanyController::class);
Route::put(uri: '/product', action: PutProductController::class);
Route::put(uri: '/product/active', action: PutDeactivateProductController::class);
Route::delete('/product', DeleteProductController::class);
Route::post('/upload', UpdateImageController::class);



// Restaurante
Route::get(uri: '/restaurants', action: GetListRestaurantsCompanyController::class);
Route::get(uri: '/restaurant', action: GetRestaurantController::class);
Route::post('/restaurant/login', PostLoginRestaurantController::class);
Route::post('/restaurant', PostRestaurantController::class);
Route::put('/restaurant', PutRestaurantController::class);
// RestauranteProducto
Route::get(uri: '/restaurant/product', action: GetProductRestaurantController::class);
Route::put('/restaurant/product', PutProductRestaurantController::class);
Route::post('/restaurant-product', PostRestaurantProductController::class);
Route::delete(uri: '/restaurant', action: DeleteRestaurantController::class);


// Rol

Route::get('/roles', GetListRolesCompanyController::class);
Route::get('/role', GetRoleController::class);
Route::post('/role', PostRoleController::class);
Route::put('/role', PutRoleController::class);
Route::delete('/role', action: DeleteRoleController::class);


// Ubicación
Route::get('/locations', GetListLocationsRestaurantController::class);
Route::get('/location/find-by-id', GetLocationFindByIdController::class);
Route::post('/location', PostLocationController::class);
Route::put('/location', PutLocationController::class);
Route::put('/active-location', PutActiveLocationController::class);
Route::delete('/location', DeleteLocationController::class);


// PDF
Route::post('/generate-ticket', GenerateTicketController::class);
Route::post('/generate-bill', GenerateBillController::class);
Route::put('/update-bill', UpdateBillController::class);
Route::get('/show-bill', action: ShowBillController::class);