<?php

Router::get(
    '/admin/products',
    'ProductController@index',
    ['auth', 'admin']
);

Router::get(
    '/admin/products/{id}',
    'ProductController@show',
    ['auth', 'admin']
);