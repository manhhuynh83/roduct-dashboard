<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_POST['order'])){

   $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
   $number = filter_var($_POST['number'], FILTER_SANITIZE_STRING);
   $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
   $method = filter_var($_POST['method'], FILTER_SANITIZE_STRING);
   $address = 'Số nhà '. $_POST['flat'] .', Đường '. $_POST['street'] .', TP. '. $_POST['city'] .', '. $_POST['state'] .', '. $_POST['country'] .' - Mã bưu điện: '. $_POST['pin_code'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);
   $placed_on = date('d-m-Y');

   $cart_total = 0;
   $cart_products[] = '';

   $cart_query = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $cart_query->execute([$user_id]);
   if($cart_query->rowCount() > 0){
      while($cart_item = $cart_query->fetch(PDO::FETCH_ASSOC)){
         $cart_products[] = $cart_item['name'].' ( '.$cart_item['quantity'].' )';
         $sub_total = ($cart_item['price'] * $cart_item['quantity']);
         $cart_total += $sub_total;
      };
   };

   $total_products = implode(', ', $cart_products);

   $order_query = $conn->prepare("SELECT * FROM `orders` WHERE name = ? AND number = ? AND email = ? AND method = ? AND address = ? AND total_products = ? AND total_price = ?");
   $order_query->execute([$name, $number, $email, $method, $address, $total_products, $cart_total]);

   if($cart_total == 0){
      $message[] = 'Giỏ hàng của bạn đang trống!';
   }elseif($order_query->rowCount() > 0){
      $message[] = 'Bạn đã đặt đơn hàng này rồi!';
   }else{
      $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price, placed_on) VALUES(?,?,?,?,?,?,?,?,?)");
      $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $cart_total, $placed_on]);
      $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
      $delete_cart->execute([$user_id]);
      $message[] = 'Đặt hàng thành công!';
   }
}

?>

<!DOCTYPE html>
<html lang="vi">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Đặt hàng</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'header.php'; ?>

<section class="display-orders">
   <?php
      $cart_grand_total = 0;
      $select_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
      $select_cart_items->execute([$user_id]);
      if($select_cart_items->rowCount() > 0){
         while($fetch_cart_items = $select_cart_items->fetch(PDO::FETCH_ASSOC)){
            $cart_total_price = ($fetch_cart_items['price'] * $fetch_cart_items['quantity']);
            $cart_grand_total += $cart_total_price;
   ?>
   <p> <?= $fetch_cart_items['name']; ?> <span>(<?= number_format($fetch_cart_items['price'], 0, ',', '.') . ' VND x '. $fetch_cart_items['quantity']; ?>)</span> </p>
   <?php
         }
      }else{
         echo '<p class="empty">Giỏ hàng trống!</p>';
      }
   ?>
   <div class="grand-total">Tổng cộng: <span><?= number_format($cart_grand_total, 0, ',', '.') . ' VND'; ?></span></div>
</section>

<section class="checkout-orders">
   <form action="" method="POST">
      <h3>Tiến hành đặt hàng</h3>

      <div class="flex">
         <div class="inputBox">
            <span>Họ và tên:</span>
            <input type="text" name="name" placeholder="Nhập họ tên" class="box" required>
         </div>
         <div class="inputBox">
            <span>Số điện thoại:</span>
            <input type="number" name="number" placeholder="Nhập số điện thoại" class="box" required>
         </div>
         <div class="inputBox">
            <span>Email:</span>
            <input type="email" name="email" placeholder="Nhập email" class="box" required>
         </div>
         <div class="inputBox">
            <span>Phương thức thanh toán:</span>
            <select name="method" class="box" required>
               <option value="cash on delivery">Thanh toán khi nhận hàng</option>
               <option value="credit card">Thẻ tín dụng</option>
               <option value="momo">Momo</option>
               <option value="zalopay">ZaloPay</option>
            </select>
         </div>
         <div class="inputBox">
            <span>Số nhà:</span>
            <input type="text" name="flat" placeholder="VD: 123" class="box" required>
         </div>
         <div class="inputBox">
            <span>Đường:</span>
            <input type="text" name="street" placeholder="VD: Lê Lợi" class="box" required>
         </div>
         <div class="inputBox">
            <span>Thành phố:</span>
            <input type="text" name="city" placeholder="VD: Hà Nội" class="box" required>
         </div>
         <div class="inputBox">
            <span>Tỉnh:</span>
            <input type="text" name="state" placeholder="VD: Hà Nội" class="box" required>
         </div>
         <div class="inputBox">
            <span>Quốc gia:</span>
            <input type="text" name="country" placeholder="VD: Việt Nam" class="box" required>
         </div>
         <div class="inputBox">
            <span>Mã bưu điện:</span>
            <input type="number" min="0" name="pin_code" placeholder="VD: 100000" class="box" required>
         </div>
      </div>

      <input type="submit" name="order" class="btn <?= ($cart_grand_total > 1)?'':'disabled'; ?>" value="Đặt hàng">
   </form>
</section>

<?php include 'footer.php'; ?>
<script src="js/script.js"></script>
</body>
</html>
