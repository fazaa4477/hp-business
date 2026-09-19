<?php
return [
    'GET /' => [DashboardController::class, 'index'],
    'GET /login' => [AuthController::class, 'loginForm'],
    'GET /register' => [AuthController::class, 'registerForm'],
    'GET /signup' => [AuthController::class, 'registerForm'],
    'POST /login' => [AuthController::class, 'login'],
    'POST /register' => [AuthController::class, 'register'],
    'POST /setup' => [AuthController::class, 'register'],
    'GET /logout' => [AuthController::class, 'logout'],
    'POST /logout' => [AuthController::class, 'logout'],
    
    // Main Pages
    'GET /products' => [BusinessController::class, 'products'],
    'GET /suppliers' => [BusinessController::class, 'suppliers'],
    'GET /customers' => [BusinessController::class, 'customers'],
    'GET /inventory' => [BusinessController::class, 'inventory'],
    'GET /purchases' => [BusinessController::class, 'purchases'],
    'POST /purchases' => [BusinessController::class, 'purchase'],
    'GET /sales' => [BusinessController::class, 'sales'],
    'POST /sales' => [BusinessController::class, 'sale'],
    'GET /reports' => [BusinessController::class, 'reports'],
    'GET /finance' => [BusinessController::class, 'financePage'],
    'POST /finance' => [BusinessController::class, 'finance'],
    'GET /services' => [BusinessController::class, 'servicesPage'],
    'POST /services' => [BusinessController::class, 'service'],
    'POST /master' => [BusinessController::class, 'saveMaster'],

    // CRUD: Products
    'GET /products/edit' => [BusinessController::class, 'editProduct'],
    'POST /products/update' => [BusinessController::class, 'updateProduct'],
    'POST /products/delete' => [BusinessController::class, 'deleteProduct'],

    // CRUD: Suppliers
    'GET /suppliers/edit' => [BusinessController::class, 'editSupplier'],
    'POST /suppliers/update' => [BusinessController::class, 'updateSupplier'],
    'POST /suppliers/delete' => [BusinessController::class, 'deleteSupplier'],

    // CRUD: Customers
    'GET /customers/edit' => [BusinessController::class, 'editCustomer'],
    'POST /customers/update' => [BusinessController::class, 'updateCustomer'],
    'POST /customers/delete' => [BusinessController::class, 'deleteCustomer'],

    // CRUD: Purchases
    'GET /purchases/edit' => [BusinessController::class, 'editPurchase'],
    'POST /purchases/update' => [BusinessController::class, 'updatePurchase'],
    'POST /purchases/delete' => [BusinessController::class, 'deletePurchase'],

    // CRUD: Sales
    'GET /sales/edit' => [BusinessController::class, 'editSale'],
    'POST /sales/update' => [BusinessController::class, 'updateSale'],
    'POST /sales/delete' => [BusinessController::class, 'deleteSale'],

    // CRUD: Services
    'GET /services/edit' => [BusinessController::class, 'editService'],
    'POST /services/update' => [BusinessController::class, 'updateService'],
    'POST /services/delete' => [BusinessController::class, 'deleteService'],

    // EXPORT: Reports
    'GET /reports/export/excel' => [BusinessController::class, 'exportExcel'],
    'GET /reports/export/pdf' => [BusinessController::class, 'exportPdf'],
    'GET /reports/print' => [BusinessController::class, 'exportPdf'],
];
