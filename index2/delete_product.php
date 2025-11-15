<?php
include_once("config.php");

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    try {
        $stmt = $conn->prepare("DELETE FROM products WHERE id = :id");
        $stmt->execute([':id' => $id]);

        // Nếu có dòng bị ảnh hưởng thì redirect, ngược lại thông báo không tìm thấy
        if ($stmt->rowCount() > 0) {
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Không tìm thấy sản phẩm để xóa.";
        }
    } catch (PDOException $e) {
        echo "Lỗi khi xóa sản phẩm: " . $e->getMessage();
    }
} else {
    echo "Không có ID sản phẩm hợp lệ để xóa.";
}
?>
