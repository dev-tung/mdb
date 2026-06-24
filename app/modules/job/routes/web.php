<?php



// =========================
// JOB
// =========================

Router::get('/job/yonex-category-crawl', 'YonexCategoryCrawler@run');
Router::get('/job/yonex-product-crawl', 'YonexProductCrawler@run');
Router::get('/job/yonex-product-detail-crawl', 'YonexProductDetailCrawler@run');
Router::get('/job/yonex-product-import', 'YonexProductImporter@run');
