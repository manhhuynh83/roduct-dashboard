<?php
include_once("config.php");

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  die("Thiếu hoặc sai định dạng ID sản phẩm");
}

$id = intval($_GET['id']);

// Khi nhấn nút Lưu
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = trim($_POST['name']);
  $description = trim($_POST['description']);
  $price_raw = $_POST['price'];
  $price = floatval(str_replace('.', '', $price_raw));

  try {
    $sql = "UPDATE products SET name = :name, description = :description, price = :price, updated_at = NOW() WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
      ':name' => $name,
      ':description' => $description,
      ':price' => $price,
      ':id' => $id
    ]);

    header("Location: dashboard.php");
    exit();
  } catch (PDOException $e) {
    $error = "Lỗi khi cập nhật: " . $e->getMessage();
  }
} else {
  // Lấy dữ liệu sản phẩm
  try {
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
      die("Không tìm thấy sản phẩm");
    }
  } catch (PDOException $e) {
    die("Lỗi truy vấn: " . $e->getMessage());
  }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Sửa Sản Phẩm</title>
  <?php require_once('inc_headcss.php'); ?>
</head>
<body>
  <div class="container mt-4">
    <h2>Sửa Sản Phẩm</h2>

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="form-group">
        <label>Tên Sản Phẩm</label>
        <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($product['name']); ?>" required>
      </div>
      <div class="form-group">
        <label>Mô Tả</label>
        <textarea name="description" class="form-control" rows="4"><?php echo htmlspecialchars($product['description']); ?></textarea>
      </div>
      <div class="form-group">
        <label>Giá (vd: 2.000.000)</label>
        <input type="text" name="price" class="form-control" value="<?php echo number_format($product['price'], 0, '', '.'); ?>" required pattern="\d{1,3}(\.\d{3})*" title="Chỉ nhập số, ví dụ: 2.000.000">
      </div>
      <button type="submit" class="btn btn-success">Lưu Thay Đổi</button>
      <a href="dashboard.php" class="btn btn-secondary">Hủy</a>
    </form>
  </div>

  <?php require_once('inc_scripts.php'); ?>
</body>
</html>
