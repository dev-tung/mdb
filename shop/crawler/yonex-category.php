<?php

require_once PATH_SHOP . 'crawler/base.php';

$baseUrl = 'https://www.yonex.com/badminton';

/**
 * OUTPUT PATH
 */
$jsonFile = PATH_SHOP . 'json/yonex_category.json';
$imgDir   = PATH_SHOP . 'image/yonex_category';

/**
 * PARSE CATEGORIES
 */
function extract_categories(string $html): array
{
    libxml_use_internal_errors(true);

    $dom = new DOMDocument();
    $dom->loadHTML($html);

    $xpath = new DOMXPath($dom);

    $categories = [];

    $nodes = $xpath->query('//a[@href]');

    foreach ($nodes as $node) {

        $href = trim($node->getAttribute('href'));

        if (!str_contains($href, '/badminton/')) {
            continue;
        }

        if (
            !preg_match(
                '#/badminton/(racquets|strings|stringing-machines|shuttlecocks|apparel|shoes|footwear|bags|accessories)#i',
                $href
            )
        ) {
            continue;
        }

        $url = str_starts_with($href, 'http')
            ? $href
            : 'https://www.yonex.com' . $href;

        $path = parse_url($url, PHP_URL_PATH);
        $slug = basename(trim($path, '/'));

        if (isset($categories[$slug])) continue;

        $name = trim($node->textContent);
        if (!$name) {
            $name = ucwords(str_replace('-', ' ', $slug));
        }

        $categories[$slug] = [
            'name' => $name,
            'slug' => $slug,
            'url'  => $url,
            'image' => null,
            'image_file' => null,
        ];
    }

    return $categories;
}

/**
 * RUN
 */
set_time_limit(0);

crawler_delete_directory($imgDir);

if (!is_dir($imgDir)) {
    mkdir($imgDir, 0777, true);
}

crawler_log("Loading homepage...");

$html = crawler_get_html($baseUrl);

if (!$html) {
    throw new RuntimeException("Cannot load Yonex website");
}

$categories = extract_categories($html);

crawler_log("Found " . count($categories) . " categories");

/**
 * GET MENU IMAGES
 */
preg_match_all(
    '#https://www\.yonex\.com/media/wysiwyg/submenu-icons/[^"\']+#i',
    $html,
    $matches
);

$images = array_values(array_unique($matches[0] ?? []));

crawler_log("Found " . count($images) . " menu images");

/**
 * MAP CATEGORY → KEYWORD
 */
$map = [
    'racquets' => 'racket',
    'strings' => 'string',
    'stringing-machines' => 'machine',
    'shuttlecocks' => 'shuttle',
    'shoes' => 'shoe',
    'footwear' => 'shoe',
    'bags' => 'bag',
    'apparel' => 'apparel',
    'accessories' => 'accessory',
];

/**
 * ATTACH IMAGES
 */
foreach ($categories as $key => $category) {

    $keyword = $map[$category['slug']] ?? null;
    if (!$keyword) continue;

    $matchedImage = null;

    foreach ($images as $imageUrl) {

        if (stripos($imageUrl, $keyword) !== false) {
            $matchedImage = $imageUrl;
            break;
        }
    }

    if (!$matchedImage) continue;

    $fileName = $category['slug'] . '.png';
    $savePath = $imgDir . '/' . $fileName;

    crawler_log("Downloading: $fileName");

    if (crawler_download_image($matchedImage, $savePath)) {

        $categories[$key]['image'] = $matchedImage;
        $categories[$key]['image_file'] = 'image/yonex_category/' . $fileName;
    }
}

/**
 * SAVE JSON
 */
file_put_contents(
    $jsonFile,
    json_encode(
        array_values($categories),
        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    )
);

/**
 * LOG
 */
crawler_log("====================");
crawler_log("DONE");
crawler_log("Categories: " . count($categories));
crawler_log("JSON: " . $jsonFile);
crawler_log("Images: " . $imgDir);
crawler_log("====================");