<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/Product.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$idsParam = $_GET['ids'] ?? '';
if (!$idsParam) {
    echo json_encode(['success' => false, 'message' => 'No ids provided']);
    exit;
}

$ids = array_filter(array_map('intval', explode(',', $idsParam)), function($id) { return $id > 0; });
if (empty($ids)) {
    echo json_encode(['success' => false, 'message' => 'No valid ids']);
    exit;
}

$product = new Product();
$products = [];
foreach ($ids as $id) {
    $p = $product->getProductById($id);
    if ($p) {
        $p['image'] = Product::getImagePath($p);
        $products[] = [
            'id' => (int)$p['id'],
            'name' => $p['name'],
            'price' => (float)$p['price'],
            'image' => $p['image'],
            'slug' => $p['slug']
        ];
    }
}

echo json_encode(['success' => true, 'products' => $products]);
