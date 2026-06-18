<?php

// Tự động nạp cấu hình hệ thống nếu chưa có
if (!defined('PATH_RETAIL') || PATH_RETAIL === '') {
    define('PATH_RETAIL', dirname(__DIR__) . '/'); 
}

/**
 * Load JSON
 */
function load_json(string $path): array
{
    if (!file_exists($path)) {
        die("File not found: {$path}");
    }

    $data = json_decode(file_get_contents($path), true);

    if (!is_array($data)) {
        die("Invalid JSON format in file: {$path}");
    }

    return $data;
}

/**
 * CATEGORY NAME VI
 */
function category_name_vi(string $slug, string $name = ''): string
{
    return match ($slug) {
        'racquets'           => 'Vợt cầu lông',
        'strings'            => 'Cước cầu lông',
        'stringing-machines' => 'Máy đan vợt',
        'shuttlecocks'       => 'Quả cầu lông',
        'apparel'            => 'Quần áo cầu lông',
        'footwear'           => 'Giày cầu lông',
        'bags'               => 'Túi cầu lông',
        'accessories'        => 'Phụ kiện cầu lông',
        default => $name ?: ucfirst(str_replace('-', ' ', $slug))
    };
}

/**
 * PRODUCT PREFIX NAME VI (Tự động sinh tiền tố tên sản phẩm)
 */
function product_prefix_name_vi(string $slug): string
{
    return match ($slug) {
        'racquets'           => 'Vợt cầu lông ',
        'strings'            => 'Cước cầu lông ',
        'stringing-machines' => 'Máy đan vợt ',
        'shuttlecocks'       => 'Quả cầu lông ',
        'apparel'            => 'Quần áo cầu lông ',
        'footwear'           => 'Giày cầu lông ',
        'bags'               => 'Túi cầu lông ',
        'accessories'        => 'Phụ kiện cầu lông ',
        default              => ''
    };
}

/**
 * RESET DATA
 */
function reset_data(): void
{
    db_query("SET FOREIGN_KEY_CHECKS = 0");

    db_query("TRUNCATE TABLE retail_product_image");
    db_query("TRUNCATE TABLE retail_product_spec");
    db_query("TRUNCATE TABLE retail_product");
    db_query("TRUNCATE TABLE retail_category");

    db_query("SET FOREIGN_KEY_CHECKS = 1");
}

/**
 * CATEGORY MAP
 */
function get_category_map(): array
{
    $rows = db_all("SELECT id, slug FROM retail_category");

    $map = [];
    foreach ($rows as $row) {
        $map[$row['slug']] = (int)$row['id'];
    }

    return $map;
}

/**
 * BRAND ID
 */
function get_brand_id(): int
{
    $row = db_one("SELECT id FROM retail_brand WHERE id = 1 LIMIT 1");
    
    if (!$row) {
        db_insert('retail_brand', ['id' => 1, 'name' => 'Yonex', 'slug' => 'yonex']);
        return 1;
    }

    return (int)$row['id'];
}

/**
 * SPEC KEY VI
 */
function spec_key_vi(string $key): string
{
    $map = [
        'Flex'               => 'Độ dẻo',
        'Frame'              => 'Khung vợt',
        'Shaft'              => 'Thân vợt',
        'Joint'              => 'Khớp nối',
        'Length'             => 'Chiều dài',
        'Weight / Grip'      => 'Trọng lượng / Cán',
        'Stringing Advice'   => 'Lực căng',
        'Recommended String' => 'Dây khuyên dùng',
        'Color(s)'           => 'Màu sắc',
        'Made In'            => 'Xuất xứ',
        'Item Code'          => 'Mã sản phẩm',
        'Gauge'              => 'Đường kính cước',
        'Core'               => 'Lõi dây',
        'Outer'              => 'Vỏ dây',
        'Coating'            => 'Lớp phủ',
        'Size'               => 'Kích thước',
        'Weight'             => 'Trọng lượng',
        'Height Range'       => 'Phạm vi điều chỉnh độ cao',
        'Pull Speed'         => 'Tốc độ căng dây',
        'Tension Range'      => 'Phạm vi lực căng',
        'Pre Stretch'        => 'Độ giãn trước',
        'Speeds'             => 'Tốc độ',
        'Quantity'           => 'Số lượng',
        'Material'           => 'Chất liệu',
        'Material(s)'        => 'Chất liệu',
        'Upper'              => 'Thân giày (Phần trên)',
        'Midsole'            => 'Đế giữa',
        'Outsole'            => 'Đế ngoài',
        'Size (LxWxH)'       => 'Kích thước (Dài x Rộng x Cao)',
        'Width'              => 'Chiều rộng',
        'Thickness'          => 'Độ dày'
    ];

    return $map[$key] ?? $key;
}

/**
 * IMPORT SPECS
 */
function import_product_specs(int $productId, array $specs): void
{
    foreach ($specs as $key => $value) {

        $key = trim((string)$key);
        $value = trim((string)$value);

        if ($key === '' || $value === '') {
            continue;
        }

        db_insert('retail_product_spec', [
            'product_id' => $productId,
            'spec_key'   => spec_key_vi($key),
            'spec_value' => $value
        ]);
    }
}

/**
 * IMPORT IMAGES
 */
function import_product_images(int $productId, array $images): void
{
    foreach ($images as $index => $image) {
        
        if (is_array($image)) {
            $image = $image[0] ?? '';
        }

        $image = trim((string)$image);

        if ($image === '') {
            continue;
        }

        db_insert('retail_product_image', [
            'product_id' => $productId,
            'image'      => $image,
            'sort_order' => $index + 1
        ]);
    }
}

/**
 * IMPORT CATEGORIES
 */
function import_categories(array $categories, array $products): void
{
    $firstImageByCategory = [];

    foreach ($products as $p) {

        $cat = $p['category'] ?? '';

        if ($cat === '' || isset($firstImageByCategory[$cat])) {
            continue;
        }

        $imgs = $p['local_images'] ?? null;
        $img = null;

        if (is_array($imgs)) {
            $firstElement = $imgs[0] ?? null;
            if (is_array($firstElement)) {
                $img = $firstElement[0] ?? null;
            } else {
                $img = $firstElement;
            }
        } elseif (is_string($imgs)) {
            $img = $imgs;
        }

        if (!empty($img) && is_string($img)) {
            $firstImageByCategory[$cat] = trim($img); 
        }
    }

    foreach ($categories as $item) {

        $slug = $item['slug'] ?? '';

        if ($slug === '') {
            continue;
        }

        db_insert('retail_category', [
            'name'        => category_name_vi($slug, $item['name'] ?? ''),
            'slug'        => $slug,
            'thumbnail'   => $firstImageByCategory[$slug] ?? null
        ]);
    }
}

/**
 * IMPORT PRODUCTS (Đã thêm cơ chế tự nối chuỗi Tiền tố tiếng Việt cho tên sản phẩm)
 */
function import_products(array $products): void
{
    $brandId = get_brand_id();
    $categoryMap = get_category_map();

    $inserted = [];

    foreach ($products as $item) {

        $slug = $item['slug'] ?? '';
        $name = $item['name'] ?? $item['title'] ?? '';

        if ($slug === '' || $name === '') {
            continue;
        }

        if (isset($inserted[$slug])) {
            continue;
        }

        $inserted[$slug] = true;

        $categorySlug = $item['category'] ?? '';
        
        // TIẾN TRÌNH: Tự động gộp tiền tố tiếng Việt (Ví dụ: "Vợt cầu lông " + "ASTROX 99 GAME")
        $prefix = product_prefix_name_vi($categorySlug);
        
        // Kiểm tra tránh gộp lặp nếu chuỗi gốc lỡ chứa sẵn chữ tiếng Việt đó rồi
        if ($prefix !== '' && mb_stripos($name, trim($prefix)) === false) {
            $name = $prefix . $name;
        }

        db_insert('retail_product', [
            'category_id' => $categoryMap[$categorySlug] ?? null,
            'brand_id'    => $brandId,
            'name'        => $name, // Lưu tên mới đã có tiền tố chuẩn
            'slug'        => $slug,
            'thumbnail'   => $item['image_file'] ?? null,
            'description' => $item['description'] ?? null,
            'price'       => 0,
            'status'      => 1
        ]);

        // Lấy chính xác ID vừa tạo
        $res = db_one("SELECT id FROM retail_product WHERE slug = :slug LIMIT 1", ['slug' => $slug]);
        $productId = $res ? (int)$res['id'] : 0;

        if (!$productId) {
            die("Insert failed hoặc không tìm thấy ID vừa tạo cho sản phẩm: {$slug}");
        }

        import_product_specs($productId, $item['specs'] ?? []);
        import_product_images($productId, $item['local_images'] ?? []);
    }
}

/**
 * =========================
 * RUN PROCESSOR
 * =========================
 */
$categoryFile = PATH_RETAIL . 'json/yonex_category.json';
$productFile  = PATH_RETAIL . 'json/yonex_product_detail.json';

$categories = load_json($categoryFile);
$products   = load_json($productFile);

// 1. Dọn dẹp sạch dữ liệu cũ
reset_data();

// 2. Sử dụng Database Transaction để tối ưu tốc độ ghi dữ liệu
if (function_exists('db_query')) {
    @db_query("START TRANSACTION"); 
}

// 3. Tiến hành Import dữ liệu tuần tự
import_categories($categories, $products);
import_products($products);

// 4. Xác nhận lưu dữ liệu thành công vào ổ đĩa
if (function_exists('db_query')) {
    @db_query("COMMIT"); 
}

echo 'IMPORT DONE!';
