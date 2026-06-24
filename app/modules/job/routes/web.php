<?php



// =========================
// JOB
// =========================

Router::get('/job/crawl-yonex-category', 'YonexCategoryCrawler@run');
Router::get('/job/crawl-yonex-product', 'YonexProductCrawler@run');
Router::get('/job/crawl-yonex-product-detail', 'YonexProductDetailCrawler@run');


Router::get('/job/import-yonex-product', 'YonexProductImporter@run');
