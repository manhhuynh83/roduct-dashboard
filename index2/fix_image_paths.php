<?php
include_once("config.php");

try {
    // Lấy toàn bộ sản phẩm
    $sql = "SELECT id, image FROM products";
    $stmt = $conn->query($sql);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $fixedCount = 0;

    foreach ($products as $product) {
        $id = $product['id'];
        $image = $product['image'];

        // Kiểm tra xem có chứa 'index/uploaded_img/' không
        if (strpos($image, 'index/uploaded_img/') === 0) {
            $newImage = basename($image); // Chỉ lấy tên file

            // Cập nhật vào DB
            $updateSql = "UPDATE products SET image = ? WHERE id = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->execute([$newImage, $id]);

            echo "✅ Đã sửa ID $id: $image → $newImage<br>";
            $fixedCount++;
        }
    }

    echo "<br>🔧 Đã sửa $fixedCount dòng thành công.";
} catch (PDOException $e) {
    echo "Lỗi khi sửa dữ liệu: " . $e->getMessage();
}
?>
