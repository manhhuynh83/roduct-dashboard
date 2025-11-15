<?php
include_once("config.php");

// Xử lý khi gửi form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $price_raw = $_POST['price'] ?? '0';
    $category = $_POST['category'] ?? '';
    $price = floatval(str_replace('.', '', $price_raw));

    // Xử lý hình ảnh
    $imagePath = "";
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "index/uploaded_img/"; // Thư mục lưu ảnh
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0755, true); // Tạo thư mục nếu chưa tồn tại
        }
        $filename = basename($_FILES["image"]["name"]);
        $imagePath = $targetDir . time() . "_" . $filename; // Đường dẫn lưu ảnh
        move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath);
    }

    // Thêm vào CSDL
    $sql = "INSERT INTO products (name, description, price, category, image, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, NOW(), NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(1, $name);
    $stmt->bindParam(2, $description);
    $stmt->bindParam(3, $price);
    $stmt->bindParam(4, $category);
    $stmt->bindParam(5, $imagePath);            

    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Lỗi: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Sản Phẩm</title>
    <?php require_once('inc_headcss.php'); ?>
</head>
<body>
<div class="container mt-4">
    <h2>Thêm Sản Phẩm Mới</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Tên Sản Phẩm</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Mô Tả</label>
            <textarea name="description" class="form-control" rows="4"></textarea>
        </div>
        <div class="form-group">
            <label>Giá (vd: 2.000.000)</label>
            <input type="text" name="price" class="form-control" required pattern="\d{1,3}(\.\d{3})*" title="Chỉ nhập số, ví dụ: 2.000.000">
        </div>
        <div class="form-group">
            <label>Danh Mục</label>
            <select name="category" class="form-control" required>
                <option value="">-- Chọn Danh Mục --</option>
                <option value="sach">Sách</option>
                <option value="but">Bút</option>
                <option value="maytinhcamtay">Máy Tính Cầm Tay</option>
                <option value="tui">Túi</option>
                <option value="sach_giao_trinh">Sách Giáo Trình</option>
                <option value="dung_cu_hoc_tap">Dụng Cụ Học Tập</option>
                <option value="thiet_bi_dien_tu">Thiết Bị Điện Tử</option>
                <option value="balo_tui">Ba Lô / Túi</option>
                <option value="khac">Khác</option>
            </select>
        </div>
        <div class="form-group">
            <label>Hình Ảnh</label>
            <input type="file" name="image" class="form-control" accept="image/*" required>
        </div>
        <button type="submit" class="btn btn-primary">Thêm Sản Phẩm</button>
        <a href="dashboard.php" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
<?php require_once('inc_scripts.php'); ?>
</body>
</html>
