<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_POST['add_to_wishlist'])){

   $pid = $_POST['pid'];
   $pid = filter_var($pid, FILTER_SANITIZE_STRING);
   $p_name = $_POST['p_name'];
   $p_name = filter_var($p_name, FILTER_SANITIZE_STRING);
   $p_price = $_POST['p_price'];
   $p_price = filter_var($p_price, FILTER_SANITIZE_STRING);
   $p_image = $_POST['p_image'];
   $p_image = filter_var($p_image, FILTER_SANITIZE_STRING);

   $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
   $check_wishlist_numbers->execute([$p_name, $user_id]);

   $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
   $check_cart_numbers->execute([$p_name, $user_id]);

   if($check_wishlist_numbers->rowCount() > 0){
      $message[] = 'Sản phẩm đã có trong danh sách mong muốn!';
   }elseif($check_cart_numbers->rowCount() > 0){
      $message[] = 'Sản phẩm đã có trong giỏ hàng!';
   }else{
      $insert_wishlist = $conn->prepare("INSERT INTO `wishlist`(user_id, pid, name, price, image) VALUES(?,?,?,?,?)");
      $insert_wishlist->execute([$user_id, $pid, $p_name, $p_price, $p_image]);
      $message[] = 'Đã thêm vào danh sách mong muốn!';
   }

}

if(isset($_POST['add_to_cart'])){

   $pid = $_POST['pid'];
   $pid = filter_var($pid, FILTER_SANITIZE_STRING);
   $p_name = $_POST['p_name'];
   $p_name = filter_var($p_name, FILTER_SANITIZE_STRING);
   $p_price = $_POST['p_price'];
   $p_price = filter_var($p_price, FILTER_SANITIZE_STRING);
   $p_image = $_POST['p_image'];
   $p_image = filter_var($p_image, FILTER_SANITIZE_STRING);
   $p_qty = $_POST['p_qty'];
   $p_qty = filter_var($p_qty, FILTER_SANITIZE_STRING);

   $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
   $check_cart_numbers->execute([$p_name, $user_id]);

   if($check_cart_numbers->rowCount() > 0){
      $message[] = 'Sản phẩm đã có trong giỏ hàng!';
   }else{

      $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
      $check_wishlist_numbers->execute([$p_name, $user_id]);

      if($check_wishlist_numbers->rowCount() > 0){
         $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE name = ? AND user_id = ?");
         $delete_wishlist->execute([$p_name, $user_id]);
      }

      $insert_cart = $conn->prepare("INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES(?,?,?,?,?,?)");
      $insert_cart->execute([$user_id, $pid, $p_name, $p_price, $p_qty, $p_image]);
      $message[] = 'Đã thêm vào giỏ hàng!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Trang Chủ</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<div class="home-bg">

   <section class="home">
      <div class="content">
         <span>Chia sẻ - Kết nối - Học tập</span>
         <h3>Kết nối học sinh-sinh viên thông qua trao đổi đồ dùng học tập</h3>
         <p>Website hỗ trợ bạn chia sẻ, trao đổi hoặc tặng lại các dụng cụ học tập không còn sử dụng.</p>
         <a href="about.php" class="btn">Giới thiệu</a>
      </div>
   </section>

</div>

<section class="home-category">
   <h1 class="title">Danh mục đồ dùng</h1>

   <div class="box-container">

      <div class="box">
         <img src="images/books.png" alt="">
         <h3>Sách giáo trình</h3>
         <p>Trao đổi các loại sách học tập, sách tham khảo các ngành.</p>
         <a href="category.php?category=sach_giao_trinh" class="btn">Xem sách</a>
      </div>

      <div class="box">
         <img src="images/stationery.png" alt="">
         <h3>Dụng cụ học tập</h3>
         <p>Bút, thước, máy tính, dụng cụ vẽ kỹ thuật, v.v...</p>
         <a href="category.php?category=dung_cu_hoc_tap" class="btn">Xem dụng cụ</a>
      </div>

      <div class="box">
         <img src="images/electronics.png" alt="">
         <h3>Thiết bị điện tử</h3>
         <p>Máy tính cũ, máy tính bảng, phụ kiện học online.</p>
         <a href="category.php?category=thiet_bi_dien_tu" class="btn">Xem thiết bị</a>
      </div>

      <div class="box">
         <img src="images/others.png" alt="">
         <h3>Khác</h3>
         <p>Các vật dụng học tập khác muốn chia sẻ hoặc trao đổi.</p>
         <a href="category.php?category=khac" class="btn">Xem thêm</a>
      </div>

   </div>
</section>

<section class="home-category">

   <h1 class="title">Trao Đổi Theo Danh Mục</h1>

   <div class="box-container">

      <div class="box">
         <img src="images/cat_1.png" alt="">
         <h3>Sách</h3>
         <p>Giáo trình, sách tham khảo, sách cũ còn sử dụng được để học tập.</p>
         <a href="category.php?category=sach" class="btn">Xem sách</a>
      </div>

      <div class="box">
         <img src="images/cat_2.png" alt="">
         <h3>Bút</h3>
         <p>Các loại bút, thước, compa, tẩy... còn sử dụng tốt có thể trao đổi.</p>
         <a href="category.php?category=but" class="btn">Xem bút</a>
      </div>

      <div class="box">
         <img src="images/maytinhcamtay.png" alt="">
         <h3>Máy Tính Cầm Tay</h3>
         <p>Máy tính khoa học, máy tính đồ họa... dành cho học sinh - sinh viên.</p>
         <a href="category.php?category=maytinhcamtay" class="btn">Xem máy tính</a>
      </div>

      <div class="box">
         <img src="images/balo.png" alt="">
         <h3>Ba Lô / Túi Sách</h3>
         <p>Ba lô, túi xách, túi đựng sách vở còn mới hoặc đã qua sử dụng nhẹ.</p>
         <a href="category.php?category=balo_tui" class="btn">Xem túi</a>
      </div>

   </div>

</section>

<section class="products">

   <h1 class="title">Đồ dùng mới nhất</h1>

   <div class="box-container">
   <?php
      $select_products = $conn->prepare("SELECT * FROM `products` LIMIT 6");
      $select_products->execute();
      if($select_products->rowCount() > 0){
         while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){ 
   ?>
<form action="" class="box" method="POST">
   <div class="price">
      <span><?= number_format($fetch_products['price'], 0, ',', '.'); ?></span> VND
   </div>
   <a href="view_page.php?pid=<?= $fetch_products['id']; ?>" class="fas fa-eye"></a>
   <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="">
   <div class="name"><?= $fetch_products['name']; ?></div>
   <div class="description"><?= htmlspecialchars($fetch_products['description']); ?></div> <!-- Mô tả -->

   <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
   <input type="hidden" name="p_name" value="<?= $fetch_products['name']; ?>">
   <input type="hidden" name="p_price" value="<?= $fetch_products['price']; ?>">
   <input type="hidden" name="p_image" value="<?= $fetch_products['image']; ?>">
   <input type="hidden" name="p_qty" value="1"> <!-- Ẩn số lượng -->

   <input type="submit" value="Thêm vào danh sách" class="option-btn" name="add_to_wishlist">
   <input type="submit" value="Yêu cầu trao đổi" class="btn" name="add_to_cart">
</form>
   <?php
      }
   }else{
      echo '<p class="empty">Chưa có đồ dùng nào được đăng!</p>';
   }
   ?>
   </div>

</section>








<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>