<?php



// =========================
// JOB
// =========================

Router::get('/job/crawl-yonex-category', 'CrawYonexCategoryController@run');
Router::get('/job/crawl-yonex-product', 'CrawYonexProductController@run');
Router::get('/job/crawl-yonex-product-detail', 'CrawYonexProductDetailController@run');


Router::get('/job/import-yonex-product', 'ImportYonexProductController@run');
