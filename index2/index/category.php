<?php

@include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
   header('location:login.php');
   exit();
}

if (isset($_POST['add_to_wishlist']) || isset($_POST['add_to_cart'])) {
   $pid = filter_var($_POST['pid'], FILTER_SANITIZE_STRING);
   $p_name = filter_var($_POST['p_name'], FILTER_SANITIZE_STRING);
   $p_price = filter_var($_POST['p_price'], FILTER_SANITIZE_STRING);
   $p_image = filter_var($_POST['p_image'], FILTER_SANITIZE_STRING);
   $p_qty = 1;

   if (isset($_POST['add_to_wishlist'])) {
      $check = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
      $check->execute([$p_name, $user_id]);
      $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
      $check_cart->execute([$p_name, $user_id]);

      if ($check->rowCount() > 0) {
         $message[] = 'Đã có trong danh sách yêu thích!';
      } elseif ($check_cart->rowCount() > 0) {
         $message[] = 'Đã có trong danh sách trao đổi!';
      } else {
         $insert = $conn->prepare("INSERT INTO `wishlist` (user_id, pid, name, price, image) VALUES (?, ?, ?, ?, ?)");
         $insert->execute([$user_id, $pid, $p_name, $p_price, $p_image]);
         $message[] = 'Đã thêm vào danh sách yêu thích!';
      }
   }

   if (isset($_POST['add_to_cart'])) {
      $check = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
      $check->execute([$p_name, $user_id]);

      if ($check->rowCount() > 0) {
         $message[] = 'Đã có trong danh sách trao đổi!';
      } else {
         $conn->prepare("DELETE FROM `wishlist` WHERE name = ? AND user_id = ?")->execute([$p_name, $user_id]);
         $insert = $conn->prepare("INSERT INTO `cart` (user_id, pid, name, price, quantity, image) VALUES (?, ?, ?, ?, ?, ?)");
         $insert->execute([$user_id, $pid, $p_name, $p_price, $p_qty, $p_image]);
         $message[] = 'Đã thêm vào danh sách trao đổi!';
      }
   }
}

?>

<!DOCTYPE html>
<html lang="vi">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Danh mục đồ dùng</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
   <style>
      .description {
         font-size: 14px;
         color: #555;
         margin: 10px 0;
         line-height: 1.5;
      }
      .category-links {
         display: flex;
         flex-wrap: wrap;
         gap: 10px;
         justify-content: flex-start;
         margin: 20px 0;
         padding: 0 10px;
      }

      .category-links a {
         background-color: #f0f0f0;
         padding: 8px 14px;
         border-radius: 6px;
         text-decoration: none;
         color: #333;
         border: 1px solid #ccc;
         font-size: 14px;
         transition: 0.2s ease;
      }

      .category-links a:hover,
      .category-links a.active {
         background-color: #333;
         color: #fff;
         border-color: #333;
      }
   </style>
</head>
<body>

<?php include 'header.php'; ?>

<section class="products">
   <h1 class="title">Danh mục đồ dùng học tập</h1>

   <?php
   $categoryNames = [
      'sach' => 'Sách',
      'but' => 'Bút',
      'maytinhcamtay' => 'Máy Tính Cầm Tay',
      'sach_giao_trinh' => 'Sách Giáo Trình',
      'dung_cu_hoc_tap' => 'Dụng Cụ Học Tập',
      'thiet_bi_dien_tu' => 'Thiết Bị Điện Tử',
      'balo_tui' => 'Ba Lô / Túi',
      'khac' => 'Khác'
   ];
   $category = $_GET['category'] ?? '';
   $displayCategory = $categoryNames[$category] ?? 'Không xác định';
   echo "<h2 class='subtitle'>Danh mục: $displayCategory</h2>";
   ?>

   <div class="category-links">
      <?php foreach ($categoryNames as $key => $name): ?>
         <a href="category.php?category=<?= $key; ?>" class="<?= ($key === $category) ? 'active' : ''; ?>"><?= $name; ?></a>
      <?php endforeach; ?>
   </div>

   <div class="box-container">
   <?php
      $select = $conn->prepare("SELECT * FROM `products` WHERE category = ?");
      $select->execute([$category]);

      if ($select->rowCount() > 0) {
         while ($product = $select->fetch(PDO::FETCH_ASSOC)) {
   ?>
   <form method="POST" class="box">
      <div class="price">Giá: <span><?= number_format($product['price'], 0, ',', '.'); ?> VND</span></div>
      <a href="view_page.php?pid=<?= $product['id']; ?>" class="fas fa-eye" title="Xem chi tiết"></a>
      <img src="uploaded_img/<?= $product['image']; ?>" alt="Ảnh sản phẩm">
      <div class="name"><?= $product['name']; ?></div>
      <div class="description"><?= htmlspecialchars($product['description']); ?></div>
      <input type="hidden" name="pid" value="<?= $product['id']; ?>">
      <input type="hidden" name="p_name" value="<?= $product['name']; ?>">
      <input type="hidden" name="p_price" value="<?= $product['price']; ?>">
      <input type="hidden" name="p_image" value="<?= $product['image']; ?>">
      <input type="hidden" name="p_qty" value="1">
      <input type="submit" name="add_to_wishlist" value="Thêm vào yêu thích" class="option-btn">
      <input type="submit" name="add_to_cart" value="Thêm vào trao đổi" class="btn">
   </form>
   <?php
         }
      } else {
         echo '<p class="empty">Hiện chưa có đồ dùng nào trong danh mục này!</p>';
      }
   ?>
   </div>
</section>

<?php include 'footer.php'; ?>
<script src="js/script.js"></script>
</body>
</html>
