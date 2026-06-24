<?php

// =========================
// HOME
// =========================

Router::get(
    '/',
    'HomeController@index'
);

// =========================
// SHOP
// =========================

Router::get(
    '/product',
    'ShopController@index'
);

Router::get(
    '/product/{slug}',
    'ShopController@show'
);

Router::get(
    '/category/{slug}',
    'ShopController@category'
);

Router::get(
    '/search',
    'ShopController@search'
);



// =========================
// CART
// =========================

Router::get(
    '/cart',
    'CartController@index'
);

Router::get(
    '/checkout',
    'CartController@checkout'
);

Router::get(
    '/cart/success',
    'CartController@success'
);

// =========================
// AUTH
// =========================

Router::get(
    '/login',
    'AuthController@login'
);

Router::post(
    '/login',
    'AuthController@authenticate'
);

Router::get(
    '/register',
    'AuthController@register'
);

Router::post(
    '/register',
    'AuthController@store'
);

Router::get(
    '/forgot-password',
    'AuthController@forgotPassword'
);

Router::post(
    '/forgot-password',
    'AuthController@sendResetLink'
);

Router::post(
    '/logout',
    'AuthController@logout',
    ['auth']
);

// =========================
// PAGE
// =========================

Router::get(
    '/string',
    'PageController@string'
);

Router::get(
    '/affiliate',
    'PageController@affiliate'
);

Router::get(
    '/career',
    'PageController@career'
);

Router::get(
    '/contact',
    'PageController@contact'
);

Router::get(
    '/warranty-policy',
    'PageController@warrantyPolicy'
);

Router::get(
    '/shipping-policy',
    'PageController@shippingPolicy'
);

Router::get(
    '/return-policy',
    'PageController@returnPolicy'
);