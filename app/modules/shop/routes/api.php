<?php

// =========================
// PRODUCT
// =========================
Router::get('/api/products', 'ProductEndpoint@apiList');
Router::get('/api/products/stock', 'ProductEndpoint@apiStock');
Router::post('/api/products', 'ProductEndpoint@apiCreate');
Router::get('/api/products/show/{id}', 'ProductEndpoint@apiShow');
Router::post('/api/products/update', 'ProductEndpoint@apiUpdate');
Router::post('/api/products/delete', 'ProductEndpoint@apiDelete');

// =========================
// CATEGORY
// =========================
Router::get('/api/categories', 'CategoryEndpoint@apiList');
Router::post('/api/categories', 'CategoryEndpoint@apiCreate');
Router::get('/api/categories/show/{id}', 'CategoryEndpoint@apiShow');
Router::post('/api/categories/update', 'CategoryEndpoint@apiUpdate');
Router::post('/api/categories/delete', 'CategoryEndpoint@apiDelete');

// =========================
// BRAND
// =========================
Router::get('/api/brands', 'BrandEndpoint@apiList');
Router::post('/api/brands', 'BrandEndpoint@apiCreate');
Router::get('/api/brands/show/{id}', 'BrandEndpoint@apiShow');
Router::post('/api/brands/update', 'BrandEndpoint@apiUpdate');
Router::post('/api/brands/delete', 'BrandEndpoint@apiDelete');

// =========================
// INVENTORY
// =========================
Router::get('/api/inventories', 'InventoryEndpoint@apiList');
Router::post('/api/inventories', 'InventoryEndpoint@apiCreate');
Router::get('/api/inventories/show/{id}', 'InventoryEndpoint@apiShow');
Router::post('/api/inventories/update', 'InventoryEndpoint@apiUpdate');
Router::post('/api/inventories/delete', 'InventoryEndpoint@apiDelete');

// =========================
// SUPPLIER
// =========================
Router::get('/api/suppliers', 'SupplierEndpoint@apiList');
Router::post('/api/suppliers', 'SupplierEndpoint@apiCreate');
Router::get('/api/suppliers/show/{id}', 'SupplierEndpoint@apiShow');
Router::post('/api/suppliers/update', 'SupplierEndpoint@apiUpdate');
Router::post('/api/suppliers/delete', 'SupplierEndpoint@apiDelete');

// =========================
// WAREHOUSE
// =========================
Router::get('/api/warehouses', 'WarehouseEndpoint@apiList');
Router::post('/api/warehouses', 'WarehouseEndpoint@apiCreate');
Router::get('/api/warehouses/show/{id}', 'WarehouseEndpoint@apiShow');
Router::post('/api/warehouses/update', 'WarehouseEndpoint@apiUpdate');
Router::post('/api/warehouses/delete', 'WarehouseEndpoint@apiDelete');

// =========================
// PURCHASE
// =========================
Router::get('/api/purchases', 'PurchaseEndpoint@apiList');
Router::post('/api/purchases', 'PurchaseEndpoint@apiCreate');
Router::get('/api/purchases/show/{id}', 'PurchaseEndpoint@apiShow');
Router::post('/api/purchases/update', 'PurchaseEndpoint@apiUpdate');
Router::post('/api/purchases/delete', 'PurchaseEndpoint@apiDelete');
Router::post('/api/purchases/status', 'PurchaseEndpoint@apiStatus');
Router::post('/api/purchases/payment', 'PurchaseEndpoint@apiPayment');

// =========================
// ORDER
// =========================
Router::get('/api/orders', 'OrderEndpoint@apiList');
Router::post('/api/orders', 'OrderEndpoint@apiCreate');
Router::get('/api/orders/show/{id}', 'OrderEndpoint@apiShow');
Router::post('/api/orders/update', 'OrderEndpoint@apiUpdate');
Router::post('/api/orders/delete', 'OrderEndpoint@apiDelete');
Router::post('/api/orders/status', 'OrderEndpoint@apiStatus');
Router::post('/api/orders/payment', 'OrderEndpoint@apiPayment');
