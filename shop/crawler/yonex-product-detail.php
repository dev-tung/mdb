<?php

// Tự động sửa lỗi nếu hằng số PATH_SHOP bị bỏ trống hoặc chưa định nghĩa
if (!defined('PATH_SHOP') || PATH_SHOP === '') {
    define('PATH_SHOP', dirname(__DIR__) . '/'); 
}

require_once PATH_SHOP . 'crawler/base.php';

/**
 * =========================
 * CONFIG
 * =========================
 */

$inputFile  = PATH_SHOP . 'json/yonex_product.json';

// Thay đổi: Lưu toàn bộ dữ liệu cào được vào một file duy nhất tại json/yonex_product_detail.json
$outputFile = PATH_SHOP . 'json/yonex_product_detail.json';

// Sử dụng đường dẫn tuyệt đối chính xác cho thư mục lưu ảnh
$imageDir   = rtrim(PATH_SHOP, '/') . '/image/yonex_product_detail';

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
 * PARSE DETAIL
 */
function parse_detail(string $html): array
{
    libxml_use_internal_errors(true);

    $dom = new DOMDocument();
    @$dom->loadHTML($html); // Ẩn các cảnh báo HTML5 không chuẩn từ mã nguồn trang gốc

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

        $cols = $xpath->query('.//td|./th', $row);

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
        @$dom2->loadHTML($html);
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
        @$dom3->loadHTML($html);
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
 * DOWNLOAD IMAGES (Giải pháp dứt điểm lỗi biến $imageDir bị null)
 */
function download_images(string $slug, array $images): array
{
    // Đảm bảo lấy đường dẫn tuyệt đối chính xác từ file, không dùng từ khóa global
    $localPathShop = (defined('PATH_SHOP') && PATH_SHOP !== '') ? PATH_SHOP : dirname(__DIR__) . '/';
    $localImageDir = rtrim($localPathShop, '/') . '/image/yonex_product_detail';

    // 1. Kiểm tra và tự động tạo thư mục ảnh tổng nếu chưa có
    if (!is_dir($localImageDir)) {
        @mkdir($localImageDir, 0777, true);
        @chmod($localImageDir, 0777);
    }

    $dir = $localImageDir . '/' . $slug;

    // 2. Tạo thư mục con riêng biệt cho từng sản phẩm
    if (!is_dir($dir)) {
        if (!@mkdir($dir, 0777, true)) {
            crawler_log("WARNING: Không thể tạo thư mục lưu trữ bằng code: $dir");
            return []; 
        }
        @chmod($dir, 0777);
    }

    // 3. Kiểm tra quyền ghi trước khi tiến hành ghi file ảnh vào ổ cứng
    if (!is_writable($dir)) {
        crawler_log("CRITICAL ERROR: Thư mục $dir không có quyền ghi dữ liệu!");
        return [];
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

// Đặt thời gian chạy vô hạn để không bị timeout khi cào lượng lớn dữ liệu
set_time_limit(0);

crawler_log("TIẾN TRÌNH: CÀO TOÀN BỘ SẢN PHẨM VÀ LƯU FILE JSON TỔNG DUY NHẤT");

/**
 * LOAD PRODUCTS
 */
if (!file_exists($inputFile)) {
    throw new RuntimeException("Lỗi nghiêm trọng: File danh sách sản phẩm gốc không tồn tại tại: $inputFile");
}

$products = json_decode(file_get_contents($inputFile), true);

if (!is_array($products)) {
    throw new RuntimeException("Dữ liệu file json đầu vào sai cấu trúc.");
}

/**
 * OUTPUT FILE SYSTEM DIRECTORY
 */
$outputDir = dirname($outputFile);
if (!is_dir($outputDir)) {
    @mkdir($outputDir, 0777, true);
    @chmod($outputDir, 0777);
}

/**
 * =========================
 * CLEAR IMAGE ON START
 * =========================
 */
// Tự động dọn dẹp thư mục ảnh cũ để tải mới từ đầu
if (is_dir($imageDir)) {
    crawler_delete_directory($imageDir);
}

if (!is_dir($imageDir)) {
    if (!@mkdir($imageDir, 0777, true)) {
        throw new RuntimeException("CRITICAL: Không thể khởi tạo thư mục lưu ảnh tổng: $imageDir");
    }
    @chmod($imageDir, 0777);
}

// ĐỌC TIẾP DỮ LIỆU CŨ TỪ FILE TỔNG ĐỂ HỖ TRỢ CƠ CHẾ RESUME (NẾU FILE ĐÃ TỒN TẠI)
$results = [];
if (file_exists($outputFile)) {
    $oldData = json_decode(file_get_contents($outputFile), true);
    if (is_array($oldData)) {
        foreach ($oldData as $oldItem) {
            if (!empty($oldItem['slug'])) {
                $results[$oldItem['slug']] = $oldItem;
            }
        }
    }
}

foreach ($products as $i => $product) {

    $url  = $product['url'] ?? '';
    $slug = $product['slug'] ?? '';

    if (!$url || !$slug) {
        crawler_log("[$i] SKIP LỖI: Sản phẩm thiếu thông tin URL hoặc Slug định danh");
        continue;
    }

    /**
     * =========================
     * RESUME CHECK (KIỂM TRA TRONG MẢNG FILE TỔNG)
     * =========================
     */
    // Đã sửa đổi: Không kiểm tra file cục bộ data.json nữa, kiểm tra sự tồn tại thẳng trong file tổng
    if (isset($results[$slug])) {
        crawler_log("[$i] SKIP EXISTING IN TOTAL JSON: $slug");
        continue;
    }

    crawler_log("[$i] Crawling: $url");

    $res = fetch_html($url);

    if ($res['code'] !== 200 || !$res['html']) {
        crawler_log("SKIP INVALID PAGE - HTTP CODE: " . $res['code']);
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

    // THÀNH CÔNG: Đã loại bỏ hoàn toàn khối mã tạo thư mục cục bộ và lưu file data.json tại đây!

    // Đẩy sản phẩm vừa cào thành công vào mảng tổng kết quả
    $results[$slug] = $item;

    /**
     * GHI CUỐN CHIẾU VÀO FILE TỔNG SAU MỖI VÒNG LẶP (Bảo vệ dữ liệu không bị mất nếu sập mạng)
     */
    if (is_dir($outputDir) && is_writable($outputDir)) {
        file_put_contents(
            $outputFile,
            json_encode(
                array_values($results),
                JSON_PRETTY_PRINT |
                JSON_UNESCAPED_UNICODE |
                JSON_UNESCAPED_SLASHES
            )
        );
        @chmod($outputFile, 0666);
    }

    crawler_log("DONE: $slug");

    // Giãn cách nhẹ 1 giây để tránh bị hệ thống tường lửa của Yonex chặn IP truy cập
    sleep(1);
}

crawler_log("================================");
crawler_log("HOÀN THÀNH TIẾN TRÌNH CÀO TỔNG");
crawler_log("TỔNG SỐ SẢN PHẨM ĐÃ LƯU: " . count($results));
crawler_log("ĐƯỜNG DẪN FILE JSON TỔNG: " . $outputFile);
crawler_log("================================");
