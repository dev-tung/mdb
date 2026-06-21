<?php

// =========================
// PRODUCTS
// =========================

Router::get(
    '/admin/products',
    'ProductController@index'
);

Router::get(
    '/admin/products/create',
    'ProductController@create'
);

Router::get(
    '/admin/products/{id}/edit',
    'ProductController@edit'
);


// =========================
// CATEGORIES
// =========================

Router::get(
    '/admin/categories',
    'CategoryController@index'
);

Router::get(
    '/admin/categories/create',
    'CategoryController@create'
);

Router::get(
    '/admin/categories/{id}/edit',
    'CategoryController@edit'
);


// =========================
// BRANDS
// =========================

Router::get(
    '/admin/brands',
    'BrandController@index'
);

Router::get(
    '/admin/brands/create',
    'BrandController@create'
);

Router::get(
    '/admin/brands/{id}/edit',
    'BrandController@edit'
);


// =========================
// INVENTORY
// =========================

Router::get(
    '/admin/inventory',
    'InventoryController@index'
);

Router::get(
    '/admin/inventory/create',
    'InventoryController@create'
);

Router::get(
    '/admin/inventory/{id}/edit',
    'InventoryController@edit'
);


// =========================
// SUPPLIERS
// =========================

Router::get(
    '/admin/suppliers',
    'SupplierController@index'
);

Router::get(
    '/admin/suppliers/create',
    'SupplierController@create'
);

Router::get(
    '/admin/suppliers/{id}/edit',
    'SupplierController@edit'
);


// =========================
// PURCHASES
// =========================

Router::get(
    '/admin/purchases',
    'PurchaseController@index'
);

Router::get(
    '/admin/purchases/create',
    'PurchaseController@create'
);

Router::get(
    '/admin/purchases/{id}/edit',
    'PurchaseController@edit'
);


// =========================
// ORDERS
// =========================

Router::get(
    '/admin/orders',
    'OrderController@index'
);

Router::get(
    '/admin/orders/create',
    'OrderController@create'
);

Router::get(
    '/admin/orders/{id}/edit',
    'OrderController@edit'
);