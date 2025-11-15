<?php
@include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
   header('location:login.php');
   exit();
}

// Xử lý thêm vào wishlist
if (isset($_POST['add_to_wishlist'])) {
   $pid = filter_var($_POST['pid'], FILTER_SANITIZE_STRING);
   $p_name = filter_var($_POST['p_name'], FILTER_SANITIZE_STRING);
   $p_price = filter_var($_POST['p_price'], FILTER_SANITIZE_STRING);
   $p_image = filter_var($_POST['p_image'], FILTER_SANITIZE_STRING);

   $check_wishlist = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
   $check_wishlist->execute([$p_name, $user_id]);

   $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
   $check_cart->execute([$p_name, $user_id]);

   if ($check_wishlist->rowCount() > 0) {
      $message[] = 'Đã có trong danh sách yêu thích!';
   } elseif ($check_cart->rowCount() > 0) {
      $message[] = 'Đã có trong danh sách trao đổi!';
   } else {
      $insert = $conn->prepare("INSERT INTO `wishlist` (user_id, pid, name, price, image) VALUES (?, ?, ?, ?, ?)");
      $insert->execute([$user_id, $pid, $p_name, $p_price, $p_image]);
      $message[] = 'Đã thêm vào danh sách yêu thích!';
   }
}

// Xử lý thêm vào cart
if (isset($_POST['add_to_cart'])) {
   $pid = filter_var($_POST['pid'], FILTER_SANITIZE_STRING);
   $p_name = filter_var($_POST['p_name'], FILTER_SANITIZE_STRING);
   $p_price = filter_var($_POST['p_price'], FILTER_SANITIZE_STRING);
   $p_image = filter_var($_POST['p_image'], FILTER_SANITIZE_STRING);
   $p_qty = filter_var($_POST['p_qty'], FILTER_SANITIZE_STRING);

   $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
   $check_cart->execute([$p_name, $user_id]);

   if ($check_cart->rowCount() > 0) {
      $message[] = 'Đã có trong danh sách trao đổi!';
   } else {
      $conn->prepare("DELETE FROM `wishlist` WHERE name = ? AND user_id = ?")->execute([$p_name, $user_id]);
      $insert = $conn->prepare("INSERT INTO `cart` (user_id, pid, name, price, quantity, image) VALUES (?, ?, ?, ?, ?, ?)");
      $insert->execute([$user_id, $pid, $p_name, $p_price, $p_qty, $p_image]);
      $message[] = 'Đã thêm vào danh sách trao đổi!';
   }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Xem chi tiết</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
   <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" rel="stylesheet" />
   <style>
      .quick-view {
         padding: 40px 20px;
         max-width: 900px;
         margin: auto;
      }

      .quick-view .box {
         display: flex;
         flex-direction: column;
         align-items: center;
         border: 1px solid #ccc;
         padding: 30px;
         border-radius: 10px;
         background: #fff;
      }

      .quick-view img {
         max-width: 100%;
         max-height: 400px;
         object-fit: contain;
         border-radius: 10px;
         margin-bottom: 20px;
         transition: 0.3s ease;
      }

      .quick-view .name {
         font-size: 24px;
         font-weight: bold;
         margin-bottom: 15px;
         text-align: center;
      }

      .quick-view .details {
         font-size: 16px;
         color: #555;
         margin-bottom: 15px;
         text-align: center;
      }

      .quick-view .price {
         font-size: 18px;
         color: #000;
         margin-bottom: 20px;
         text-align: center;
      }

      .quick-view .qty {
         display: none;
      }

      .quick-view input[type="submit"] {
         margin: 10px 5px;
         padding: 10px 20px;
         font-size: 16px;
      }

      .title {
         text-align: center;
         margin-top: 20px;
         font-size: 32px;
      }
   </style>
</head>
<body>

<?php include 'header.php'; ?>

<section class="quick-view">
   <h1 class="title">Chi Tiết Sản Phẩm</h1>

   <?php
      $pid = $_GET['pid'] ?? '';
      $select = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
      $select->execute([$pid]);

      if ($select->rowCount() > 0) {
         $product = $select->fetch(PDO::FETCH_ASSOC);
         $formatted_price = number_format($product['price'], 0, ',', '.') . ' VND';
   ?>
   <form method="POST" class="box">
      <a href="uploaded_img/<?= htmlspecialchars($product['image']); ?>" data-lightbox="product-image" data-title="<?= htmlspecialchars($product['name']); ?>">
         <img src="uploaded_img/<?= htmlspecialchars($product['image']); ?>" alt="Sản phẩm">
      </a>
      <div class="name"><?= htmlspecialchars($product['name']); ?></div>
      <div class="details"><?= htmlspecialchars($product['details']); ?></div>
      <div class="price">Giá: <span><?= $formatted_price; ?></span></div>
      <input type="hidden" name="pid" value="<?= $product['id']; ?>">
      <input type="hidden" name="p_name" value="<?= htmlspecialchars($product['name']); ?>">
      <input type="hidden" name="p_price" value="<?= $product['price']; ?>">
      <input type="hidden" name="p_image" value="<?= htmlspecialchars($product['image']); ?>">
      <input type="number" name="p_qty" value="1" class="qty">
      <input type="submit" name="add_to_wishlist" value="Thêm vào yêu thích" class="option-btn">
      <input type="submit" name="add_to_cart" value="Thêm vào trao đổi" class="btn">
   </form>
   <?php
      } else {
         echo '<p class="empty">Không tìm thấy sản phẩm!</p>';
      }
   ?>
</section>

<?php include 'footer.php'; ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>
<script src="js/script.js"></script>

</body>
</html>
