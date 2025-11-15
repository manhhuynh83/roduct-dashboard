<?php

@include 'config.php';

if(isset($_POST['submit'])){

   $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
   $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
   $pass = filter_var(md5($_POST['pass']), FILTER_SANITIZE_STRING);
   $cpass = filter_var(md5($_POST['cpass']), FILTER_SANITIZE_STRING);
   $user_type = filter_var($_POST['user_type'], FILTER_SANITIZE_STRING);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/'.$image;

   $select = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
   $select->execute([$email]);

   if($select->rowCount() > 0){
      $message[] = 'Email đã được sử dụng!';
   }else{
      if($pass != $cpass){
         $message[] = 'Xác nhận mật khẩu không khớp!';
      }else{
         $insert = $conn->prepare("INSERT INTO `users` (name, email, password, user_type, image) VALUES (?,?,?,?,?)");
         $insert->execute([$name, $email, $pass, $user_type, $image]);

         if($insert){
            if($image_size > 2000000){
               $message[] = 'Ảnh quá lớn, vui lòng chọn ảnh dưới 2MB!';
            }else{
               move_uploaded_file($image_tmp_name, $image_folder);
               $message[] = 'Đăng ký thành công! Bạn có thể đăng nhập ngay.';
               header('location:login.php');
               exit;
            }
         } else {
            $message[] = 'Đăng ký thất bại, vui lòng thử lại.';
         }
      }
   }

}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
   <meta charset="UTF-8">
   <title>Đăng ký thành viên</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/components.css">
</head>
<body>

<?php
if(isset($message)){
   foreach($message as $msg){
      echo '
      <div class="message">
         <span>'.$msg.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>';
   }
}
?>
   
<section class="form-container">

   <form action="" enctype="multipart/form-data" method="POST">
      <h3>Đăng ký tài khoản</h3>
      <input type="text" name="name" class="box" placeholder="Nhập họ tên của bạn" required>
      <input type="email" name="email" class="box" placeholder="Nhập email của bạn" required>
      <input type="password" name="pass" class="box" placeholder="Nhập mật khẩu" required>
      <input type="password" name="cpass" class="box" placeholder="Xác nhận mật khẩu" required>

      <select name="user_type" class="box" required>
         <option value="user">Người dùng</option>
         <option value="admin">Quản trị viên</option>
      </select>

      <input type="file" name="image" class="box" required accept="image/jpg, image/jpeg, image/png">
      <input type="submit" value="Đăng ký ngay" class="btn" name="submit">
      <p>Đã có tài khoản? <a href="login.php">Đăng nhập</a></p>
   </form>

</section>

</body>
</html>
