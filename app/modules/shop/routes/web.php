<?php

// =========================
// PRODUCTS
// =========================

Router::get('/admin/products', 'ProductController@index');

Router::get('/admin/products/create', 'ProductController@create');

Router::get('/admin/products/edit/{id}', 'ProductController@edit');


// =========================
// CATEGORIES
// =========================

Router::get('/admin/categories', 'CategoryController@index');

Router::get('/admin/categories/create', 'CategoryController@create');

Router::get('/admin/categories/edit/{id}', 'CategoryController@edit');


// =========================
// BRANDS
// =========================

Router::get('/admin/brands', 'BrandController@index');

Router::get('/admin/brands/create', 'BrandController@create');

Router::get('/admin/brands/edit/{id}', 'BrandController@edit');


// =========================
// INVENTORY
// =========================

Router::get('/admin/inventory', 'InventoryController@index');

Router::get('/admin/inventory/create', 'InventoryController@create');

Router::get('/admin/inventory/edit/{id}', 'InventoryController@edit');


// =========================
// SUPPLIERS
// =========================

Router::get('/admin/suppliers', 'SupplierController@index');

Router::get('/admin/suppliers/create', 'SupplierController@create');

Router::get('/admin/suppliers/edit/{id}', 'SupplierController@edit');


// =========================
// PURCHASES
// =========================

Router::get('/admin/purchases', 'PurchaseController@index');

Router::get('/admin/purchases/create', 'PurchaseController@create');

Router::get('/admin/purchases/edit/{id}', 'PurchaseController@edit');


// =========================
// ORDERS
// =========================

Router::get('/admin/orders', 'OrderController@index');

Router::get('/admin/orders/create', 'OrderController@create');

Router::get('/admin/orders/edit/{id}', 'OrderController@edit');