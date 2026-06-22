<?php

// =========================
// PRODUCT
// =========================
Router::get(
    '/api/products',
    'ProductEndpoint@apiList'
);

Router::post(
    '/api/products',
    'ProductEndpoint@apiCreate'
);

Router::get(
    '/api/products/show',
    'ProductEndpoint@apiShow'
);

Router::post(
    '/api/products/update',
    'ProductEndpoint@apiUpdate'
);

Router::post(
    '/api/products/delete',
    'ProductEndpoint@apiDelete'
);


// =========================
// CATEGORY
// =========================
Router::get(
    '/api/categories',
    'CategoryEndpoint@apiList'
);

Router::post(
    '/api/categories',
    'CategoryEndpoint@apiCreate'
);

Router::post(
    '/api/categories/update',
    'CategoryEndpoint@apiUpdate'
);

Router::post(
    '/api/categories/delete',
    'CategoryEndpoint@apiDelete'
);


// =========================
// BRAND
// =========================
Router::get(
    '/api/brands',
    'BrandEndpoint@apiList'
);

Router::post(
    '/api/brands',
    'BrandEndpoint@apiCreate'
);

Router::post(
    '/api/brands/update',
    'BrandEndpoint@apiUpdate'
);

Router::post(
    '/api/brands/delete',
    'BrandEndpoint@apiDelete'
);


// =========================
// INVENTORY
// =========================
Router::get(
    '/api/inventories',
    'InventoryEndpoint@apiList'
);

Router::post(
    '/api/inventories',
    'InventoryEndpoint@apiCreate'
);

Router::post(
    '/api/inventories/update',
    'InventoryEndpoint@apiUpdate'
);

Router::post(
    '/api/inventories/delete',
    'InventoryEndpoint@apiDelete'
);


// =========================
// SUPPLIER
// =========================
Router::get(
    '/api/suppliers',
    'SupplierEndpoint@apiList'
);

Router::post(
    '/api/suppliers',
    'SupplierEndpoint@apiCreate'
);

Router::post(
    '/api/suppliers/update',
    'SupplierEndpoint@apiUpdate'
);

Router::post(
    '/api/suppliers/delete',
    'SupplierEndpoint@apiDelete'
);


// =========================
// PURCHASE
// =========================
Router::get(
    '/api/purchases',
    'PurchaseEndpoint@apiList'
);

Router::post(
    '/api/purchases',
    'PurchaseEndpoint@apiCreate'
);

Router::post(
    '/api/purchases/update',
    'PurchaseEndpoint@apiUpdate'
);

Router::post(
    '/api/purchases/delete',
    'PurchaseEndpoint@apiDelete'
);


// =========================
// ORDER
// =========================
Router::get(
    '/api/orders',
    'OrderEndpoint@apiList'
);

Router::post(
    '/api/orders',
    'OrderEndpoint@apiCreate'
);

Router::post(
    '/api/orders/update',
    'OrderEndpoint@apiUpdate'
);

Router::post(
    '/api/orders/delete',
    'OrderEndpoint@apiDelete'
);