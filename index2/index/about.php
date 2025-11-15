<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="vi">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Giới thiệu</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="about">

   <div class="row">

      <div class="box">
         <img src="images/about-img_1.png" alt="">
         <h3>Tại sao chọn chúng tôi?</h3>
         <p>Chúng tôi mang đến một nền tảng giúp sinh viên và học sinh dễ dàng **trao đổi, tặng hoặc nhận** đồ dùng học tập không còn sử dụng. Vừa tiết kiệm chi phí, vừa thân thiện với môi trường!</p>
         <a href="contact.php" class="btn">Liên hệ ngay</a>
      </div>

      <div class="box">
         <img src="images/about-img_2.png" alt="">
         <h3>Chúng tôi cung cấp gì?</h3>
         <p>Nơi kết nối cộng đồng học sinh, sinh viên cùng chia sẻ sách giáo trình, bút viết, máy tính, balo và nhiều vật dụng học tập khác. Mọi thứ đều có thể trao đổi miễn phí hoặc với chi phí thấp.</p>
         <a href="shop.php" class="btn">Xem đồ dùng</a>
      </div>

   </div>

</section>

<section class="reviews">

   <h1 class="title">Đánh giá từ người dùng</h1>

   <div class="box-container">

      <div class="box">
         <img src="images/pic-3.png" alt="">
         <p>Mình đã tìm được bộ sách giáo trình cũ đúng môn đang học mà không mất phí. Rất hữu ích và tiện lợi!</p>
         <div class="stars">
            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
         </div>
         <h3>Vũ Mạnh Huynh</h3>
      </div>

      <div class="box">
         <img src="images/pic-1.png" alt="">
         <p>Một ý tưởng tuyệt vời! Giúp mình trao đổi lại chiếc máy tính bỏ không và nhận được đồ cần thiết.</p>
         <div class="stars">
            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
         </div>
         <h3>Nguyễn Minh Trí</h3>
      </div>

      <div class="box">
      <img src="images/pic-5.png" alt="">
         <p>Nhờ trang web mà mình đã đổi được bộ sách cũ lấy bút và vở – vừa tiết kiệm vừa giúp được người khác!</p>
         <div class="stars">
            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
         </div>
         <h3>Phạm Trần Gia Hùng</h3>
      </div>

      <div class="box">
         <img src="images/face21.jpg" alt="">
         <p>Mình tặng lại chiếc balo cũ và nhận được một máy tính bỏ túi đang rất cần. Rất tiện lợi và ý nghĩa!</p>
         <div class="stars">
            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
         </div>
         <h3>Nguyễn Văn Hà</h3>
      </div>

      <div class="box">
         <img src="images/face9.jpg" alt="">
         <p>Trang web thật sự hữu ích! Chỉ sau vài phút đăng đồ dùng không cần, đã có bạn liên hệ nhận – cảm thấy rất vui.</p>
         <div class="stars">
            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
         </div>
         <h3>Trần Lê Mạnh Hùng</h3>
      </div>