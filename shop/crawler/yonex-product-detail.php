<?php

require_once PATH_SHOP . 'crawler/base.php';

/**
 * =========================
 * CONFIG
 * =========================
 */

$inputFile = PATH_SHOP . 'json/yonex_product.json';

$page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 20;
$offset = ($page - 1) * $limit;

$outputFile = PATH_SHOP . 'json/yonex_product_detail_page_' . $page . '.json';

$imageDir = PATH_SHOP . 'image/yonex_product_detail';

/**
 * =========================
 * FUNCTIONS
 * =========================
 */

function normalize_image_url(string $url): string
{
    $url = trim($url);
    $url = strtok($url, '?');
    return str_replace('http://', 'https://', $url);
}

/**
 * FETCH HTML
 */
function fetch_html(string $url): array
{
    $ch = curl_init($url);

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 20,
        CURLOPT_USERAGENT => 'Mozilla/5.0'
    ]);

    $html = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    return [
        'html' => $html ?: '',
        'code' => $code
    ];
}

/**
 * PARSE DETAIL (GIỮ NGUYÊN LOGIC)
 */
function parse_detail(string $html): array
{
    libxml_use_internal_errors(true);

    $dom = new DOMDocument();
    $dom->loadHTML($html);

    $xpath = new DOMXPath($dom);

    $data = [
        'title' => null,
        'description' => null,
        'specs' => [],
        'images' => []
    ];

    $h1 = $xpath->query('//h1')->item(0);
    if ($h1) {
        $data['title'] = trim($h1->textContent);
    }

    $desc = $xpath->query('//div[contains(@class,"description")]')->item(0);
    if ($desc) {
        $data['description'] = trim($desc->textContent);
    }

    foreach ($xpath->query('//table//tr') as $row) {

        $cols = $xpath->query('.//td|.//th', $row);

        if ($cols->length < 2) continue;

        $k = trim($cols->item(0)->textContent);
        $v = trim($cols->item(1)->textContent);

        if ($k && $v) {
            $data['specs'][$k] = $v;
        }
    }

    /**
     * IMAGES
     */
    $images = [];

    if (preg_match('#"data"\s*:\s*(\[[\s\S]*?\])#', $html, $m)) {

        $json = json_decode($m[1], true);

        if (is_array($json)) {
            foreach ($json as $item) {

                if (!empty($item['img'])) {
                    $images[] = normalize_image_url($item['img']);
                }

                if (!empty($item['full'])) {
                    $images[] = normalize_image_url($item['full']);
                }
            }
        }
    }

    if (empty($images)) {

        $dom2 = new DOMDocument();
        $dom2->loadHTML($html);
        $xpath2 = new DOMXPath($dom2);

        $frames = $xpath2->query('//div[contains(@class,"fotorama__stage__frame")]');

        foreach ($frames as $frame) {

            $href = $frame->getAttribute('href');
            if ($href) {
                $images[] = normalize_image_url($href);
            }

            $img = $xpath2->query('.//img', $frame)->item(0);
            if ($img) {
                $src = $img->getAttribute('src');
                if ($src) {
                    $images[] = normalize_image_url($src);
                }
            }
        }
    }

    if (empty($images)) {

        $dom3 = new DOMDocument();
        $dom3->loadHTML($html);
        $xpath3 = new DOMXPath($dom3);

        $imgNodes = $xpath3->query('//img[contains(@src,"/media/catalog/product/")]');

        foreach ($imgNodes as $img) {
            $src = $img->getAttribute('src');
            if ($src) {
                $images[] = normalize_image_url($src);
            }
        }
    }

    // dedupe
    $unique = [];
    foreach ($images as $img) {
        if ($img) $unique[md5($img)] = $img;
    }

    $data['images'] = array_values($unique);

    return $data;
}

/**
 * DOWNLOAD IMAGES
 */
function download_images(string $slug, array $images): array
{
    global $imageDir;

    $dir = $imageDir . '/' . $slug;

    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }

    $saved = [];

    foreach ($images as $i => $url) {

        if (!$url) continue;

        $ext = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);
        if (!$ext) $ext = 'jpg';

        $fileName = ($i + 1) . '.' . $ext;
        $savePath = $dir . '/' . $fileName;

        crawler_log("Downloading: $url");

        if (crawler_download_image($url, $savePath)) {
            $saved[] =
                'image/yonex_product_detail/' .
                $slug . '/' .
                $fileName;
        }
    }

    return $saved;
}

/**
 * =========================
 * RUN
 * =========================
 */

set_time_limit(0);

crawler_log("PAGE: $page | LIMIT: $limit");

/**
 * LOAD PRODUCTS
 */
$products = json_decode(file_get_contents($inputFile), true);

if (!is_array($products)) {
    throw new RuntimeException("Invalid product file");
}

/**
 * PAGINATION
 */
$products = array_slice($products, $offset, $limit);

/**
 * OUTPUT FILE
 */
$outputDir = dirname($outputFile);

/**
 * =========================
 * CLEAR IMAGE ONLY PAGE 1
 * =========================
 */
if ($page === 1) {
    if (is_dir($imageDir)) {
        crawler_delete_directory($imageDir);
    }
}

if (!is_dir($imageDir)) {
    mkdir($imageDir, 0777, true);
}

$results = [];

foreach ($products as $i => $product) {

    $url = $product['url'] ?? '';

    if (!$url) {
        crawler_log("[$i] SKIP NO URL");
        continue;
    }

    $slug = $product['slug'];

    /**
     * =========================
     * RESUME CHECK
     * =========================
     */
    $slugFile = $imageDir . '/' . $slug . '/data.json';

    if (file_exists($slugFile)) {
        crawler_log("[$i] SKIP EXISTING: $slug");
        continue;
    }

    crawler_log("[$i] Crawling: $url");

    $res = fetch_html($url);

    crawler_log("HTTP CODE: " . $res['code']);

    if ($res['code'] !== 200 || !$res['html']) {
        crawler_log("SKIP INVALID PAGE");
        continue;
    }

    $detail = parse_detail($res['html']);

    crawler_log("IMAGES FOUND: " . count($detail['images']));

    $localImages = download_images($slug, $detail['images']);

    $item = array_merge(
        $product,
        $detail,
        [
            'detail_url' => $url,
            'local_images' => $localImages
        ]
    );

    /**
     * SAVE PER PRODUCT
     */
    $dir = $imageDir . '/' . $slug;

    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }

    file_put_contents(
        $dir . '/data.json',
        json_encode(
            $item,
            JSON_PRETTY_PRINT |
            JSON_UNESCAPED_UNICODE |
            JSON_UNESCAPED_SLASHES
        )
    );

    $results[$slug] = $item;

    crawler_log("DONE: $slug");
}

/**
 * SAVE PAGE JSON
 */
file_put_contents(
    $outputFile,
    json_encode(
        array_values($results),
        JSON_PRETTY_PRINT |
        JSON_UNESCAPED_UNICODE |
        JSON_UNESCAPED_SLASHES
    )
);

crawler_log("================================");
crawler_log("DONE PAGE: $page");
crawler_log("TOTAL: " . count($results));
crawler_log("OUTPUT: " . $outputFile);
crawler_log("================================");