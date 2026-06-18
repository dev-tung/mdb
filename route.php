<?php

return [
    '' => ['path' => 'home.php'],
    'home' => ['path' => 'home.php'],
    'product' => ['path' => 'shop/product.php'],
    'product/{slug}' => ['path' => 'shop/product-detail.php', 'params' => ['slug']],
    'import/yonex-product' => ['path' => 'shop/import/yonex-product.php'],

    'string' => ['path' => 'stringing/table.php'],
    'affilate' => ['path' => 'human/affilate.php'],
    'recruitment' => ['path' => 'human/recruitment.php'],
    
];