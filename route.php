<?php

return [
    '' => ['path' => 'home.php'],
    'home' => ['path' => 'home.php'],
    'search' => ['path' => 'search.php'],
    'product' => ['path' => 'shop/product.php'],
    'product/{slug}' => ['path' => 'shop/product-detail.php', 'params' => ['slug']],
    'crawler/yonex-product-detail' => ['path' => 'shop/crawler/yonex-product-detail.php'],
    'import/yonex-product' => ['path' => 'shop/import/yonex-product.php'],
    'string' => ['path' => 'stringing/table.php'],
    'affilate' => ['path' => 'human/affilate.php'],
    'recruitment' => ['path' => 'human/recruitment.php'],
    
];