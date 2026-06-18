<?php

require_once PATH_RETAIL . 'crawler/base.php';

/**
 * =========================
 * CONFIG
 * =========================
 */

$categoryFile = PATH_RETAIL . 'json/yonex_category.json';

$jsonFile = PATH_RETAIL . 'json/yonex_product.json';
$imgDir   = PATH_RETAIL . 'image/yonex_product';

/**
 * =========================
 * FUNCTIONS
 * =========================
 */

function slugify(string $text): string
{
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    $text = preg_replace('/-+/', '-', $text);
    return trim($text, '-');
}

function normalize_url(string $href, string $baseUrl): string
{
    if (str_starts_with($href, 'http')) {
        return $href;
    }

    return rtrim($baseUrl, '/') . '/' . ltrim($href, '/');
}

function get_page_urls(string $html, string $baseUrl): array
{
    libxml_use_internal_errors(true);

    $dom = new DOMDocument();
    $dom->loadHTML($html);

    $xpath = new DOMXPath($dom);

    $nodes = $xpath->query(
        '//div[contains(@class,"pages")]//a[contains(@class,"page")]'
    );

    $urls = [];

    foreach ($nodes as $node) {
        $href = trim($node->getAttribute('href'));

        if ($href) {
            $urls[] = normalize_url($href, $baseUrl);
        }
    }

    // luôn thêm page 1
    $urls[] = $baseUrl;

    return array_values(array_unique($urls));
}

function parse_products(string $html, array $category, string $imgDir): array
{
    libxml_use_internal_errors(true);

    $dom = new DOMDocument();
    $dom->loadHTML($html);

    $xpath = new DOMXPath($dom);

    $nodes = $xpath->query('//li[contains(@class,"product-item")]');

    $products = [];

    // folder theo category
    $categoryImgDir = $imgDir . '/' . $category['slug'];

    if (!is_dir($categoryImgDir)) {
        mkdir($categoryImgDir, 0777, true);
    }

    foreach ($nodes as $node) {

        $linkNode = $xpath->query(
            './/a[contains(@class,"product-item-link")]',
            $node
        )->item(0);

        if (!$linkNode) continue;

        $name = trim(preg_replace('/\s+/', ' ', $linkNode->textContent));
        $url  = trim($linkNode->getAttribute('href'));

        if (!$name || !$url) continue;

        $imgNode = $xpath->query('.//img', $node)->item(0);

        $image = null;

        if ($imgNode) {
            $image = $imgNode->getAttribute('src')
                ?: $imgNode->getAttribute('data-src')
                ?: $imgNode->getAttribute('data-original');
        }

        $slug = slugify($name);

        $product = [
            'name' => $name,
            'slug' => $slug,
            'url'  => $url,
            'category' => $category['slug'],
            'image' => $image,
            'image_file' => null,
        ];

        /**
         * DOWNLOAD IMAGE
         */
        if ($image) {

            $ext = pathinfo(parse_url($image, PHP_URL_PATH), PATHINFO_EXTENSION);
            if (!$ext) $ext = 'jpg';

            $fileName = $slug . '.' . $ext;
            $savePath = $categoryImgDir . '/' . $fileName;

            crawler_log("Downloading: $name");

            if (crawler_download_image($image, $savePath)) {
                $product['image_file'] =
                    'image/yonex_product/' .
                    $category['slug'] . '/' .
                    $fileName;
            }
        }

        $products[$url] = $product;
    }

    return $products;
}

function crawl_category(array $category, string $imgDir): array
{
    $firstHtml = crawler_get_html($category['url']);

    if (!$firstHtml) return [];

    $pageUrls = get_page_urls($firstHtml, $category['url']);

    crawler_log("Pages found: " . count($pageUrls));

    $products = [];

    foreach ($pageUrls as $url) {

        crawler_log("Crawling: $url");

        $html = crawler_get_html($url);

        if (!$html) continue;

        $items = parse_products($html, $category, $imgDir);

        foreach ($items as $key => $item) {
            $products[$key] = $item;
        }
    }

    return $products;
}

/**
 * =========================
 * RUN
 * =========================
 */

set_time_limit(0);

crawler_delete_directory($imgDir);

if (!is_dir($imgDir)) {
    mkdir($imgDir, 0777, true);
}

crawler_log("Loading categories...");

$categories = json_decode(file_get_contents($categoryFile), true);

if (!is_array($categories)) {
    throw new RuntimeException("Invalid category file");
}

$products = [];

foreach ($categories as $category) {

    crawler_log("");
    crawler_log("====================");
    crawler_log("Category: " . $category['name']);

    $items = crawl_category($category, $imgDir);

    foreach ($items as $key => $item) {
        $products[$key] = $item;
    }

    crawler_log("Found: " . count($items));
}

/**
 * SAVE JSON
 */
file_put_contents(
    $jsonFile,
    json_encode(
        array_values($products),
        JSON_PRETTY_PRINT |
        JSON_UNESCAPED_UNICODE |
        JSON_UNESCAPED_SLASHES
    )
);

crawler_log("");
crawler_log("====================");
crawler_log("DONE");
crawler_log("TOTAL PRODUCTS: " . count($products));
crawler_log("====================");