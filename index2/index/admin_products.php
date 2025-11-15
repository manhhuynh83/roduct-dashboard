<?php

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
   exit;
}

if(isset($_POST['add_product'])){

   $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
   $price = filter_var($_POST['price'], FILTER_SANITIZE_STRING);
   $category = filter_var($_POST['category'], FILTER_SANITIZE_STRING);
   $details = filter_var($_POST['details'], FILTER_SANITIZE_STRING);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/'.$image;

   $select_products = $conn->prepare("SELECT * FROM `products` WHERE name = ?");
   $select_products->execute([$name]);

   if($select_products->rowCount() > 0){
      $message[] = 'Tên sản phẩm đã tồn tại!';
   } else {
      $insert_products = $conn->prepare("INSERT INTO `products`(name, category, details, price, image) VALUES(?,?,?,?,?)");
      $insert_products->execute([$name, $category, $details, $price, $image]);

      if($image_size > 2000000){
         $message[] = 'Kích thước ảnh quá lớn!';
      } else {
         move_uploaded_file($image_tmp_name, $image_folder);
         $message[] = 'Đã thêm sản phẩm mới!';
      }
   }
}

if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $select_delete_image = $conn->prepare("SELECT image FROM `products` WHERE id = ?");
   $select_delete_image->execute([$delete_id]);
   $fetch_delete_image = $select_delete_image->fetch(PDO::FETCH_ASSOC);
   if ($fetch_delete_image && file_exists('uploaded_img/'.$fetch_delete_image['image'])) {
      unlink('uploaded_img/'.$fetch_delete_image['image']);
   }

   $conn->prepare("DELETE FROM `products` WHERE id = ?")->execute([$delete_id]);
   $conn->prepare("DELETE FROM `wishlist` WHERE pid = ?")->execute([$delete_id]);
   $conn->prepare("DELETE FROM `cart` WHERE pid = ?")->execute([$delete_id]);
   header('location:admin_products.php');
   exit;
}

?>

<!DOCTYPE html>
<html lang="vi">
<head>
   <meta charset="UTF-8">
   <title>Quản lý sản phẩm</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>

<?php include 'admin_header.php'; ?>

<section class="add-products">
   <h1 class="title">Thêm sản phẩm mới</h1>
   <form action="" method="POST" enctype="multipart/form-data">
      <div class="flex">
         <div class="inputBox">
            <input type="text" name="name" class="box" required placeholder="Tên sản phẩm">
            <select name="category" class="box" required>
               <option value="" selected disabled>Chọn danh mục</option>
               <option value="sach">Sách</option>
               <option value="balo">Ba lô</option>
               <option value="dungcu">Dụng cụ học tập</option>
            </select>
         </div>
         <div class="inputBox">
            <input type="number" min="0" name="price" class="box" required placeholder="Giá sản phẩm">
            <input type="file" name="image" required class="box" accept="image/jpg, image/jpeg, image/png">
         </div>
      </div>
      <textarea name="details" class="box" required placeholder="Chi tiết sản phẩm" cols="30" rows="10"></textarea>
      <input type="submit" class="btn" value="Thêm sản phẩm" name="add_product">
   </form>
</section>

<section class="show-products">
   <h1 class="title">Danh sách sản phẩm</h1>
   <div class="box-container">
   <?php
      $show_products = $conn->prepare("SELECT * FROM `products`");
      $show_products->execute();
      if($show_products->rowCount() > 0){
         while($fetch = $show_products->fetch(PDO::FETCH_ASSOC)){  
   ?>
   <div class="box">
      <div class="price"><?= number_format($fetch['price'], 0, ',', '.'); ?> VNĐ</div>
      <img src="uploaded_img/<?= htmlspecialchars($fetch['image']); ?>" alt="Ảnh sản phẩm">
      <div class="name"><?= htmlspecialchars($fetch['name']); ?></div>
      <div class="cat"><?= htmlspecialchars($fetch['category']); ?></div>
      <div class="details"><?= htmlspecialchars($fetch['details']); ?></div>
      <div class="flex-btn">
         <a href="admin_update_product.php?update=<?= $fetch['id']; ?>" class="option-btn">Cập nhật</a>
         <a href="admin_products.php?delete=<?= $fetch['id']; ?>" class="delete-btn" onclick="return confirm('Xóa sản phẩm này?');">Xóa</a>
      </div>
   </div>
   <?php
         }
      } else {
         echo '<p class="empty">Chưa có sản phẩm nào!</p>';
      }
   ?>
   </div>
</section>

<script src="js/script.js"></script>

</body>
</html>
