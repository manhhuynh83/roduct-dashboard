<?php
include_once("session.php");
include_once("config.php");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <title>Quản Trị Viên</title>
  <?php require_once('inc_headcss.php'); ?>
</head>
<body>
  <div class="container-scroller">
    <!-- Navbar -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <?php require_once('inc_searchbar.php'); ?>
    </nav>

    <div class="container-fluid page-body-wrapper">
      <!-- Sidebar -->
      <?php require_once('inc_sidebarnav.php'); ?>

      <div class="main-panel">
        <div class="content-wrapper">
          <!-- Breadcrumb -->
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="d-flex justify-content-between flex-wrap">
                <div class="d-flex align-items-end flex-wrap">
                  <div class="d-flex">
                    <i class="mdi mdi-home text-muted hover-cursor"></i>
                    <p class="text-muted mb-0 hover-cursor">&nbsp;/&nbsp;Quản Trị Viên&nbsp;/&nbsp;</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Tabs -->
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body dashboard-tabs p-0">
                  <ul class="nav nav-tabs px-4" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" id="overview-tab" data-toggle="tab" href="#overview" role="tab">Tổng Quan</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="sales-tab" data-toggle="tab" href="#sales" role="tab">Doanh Số</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="purchases-tab" data-toggle="tab" href="#purchases" role="tab">Mua Hàng</a>
                    </li>
                  </ul>
                  <div class="tab-content py-0 px-0">
                    <div class="tab-pane fade show active" id="overview" role="tabpanel">
                      <div class="d-flex flex-wrap justify-content-xl-between">
                        <!-- Nội dung tổng quan -->
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Danh Sách Sản Phẩm -->
          <div class="row">
            <div class="col-md-12 stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Danh Sách Sản Phẩm</h4>
                  <a href="add_product.php" class="btn btn-success mb-3">+ Thêm Sản Phẩm</a>

                  <?php
                  try {
                    $stmt = $conn->query("SELECT * FROM products ORDER BY id DESC");
                    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
                  } catch (PDOException $e) {
                    echo "<p class='text-danger'>Lỗi truy vấn: " . $e->getMessage() . "</p>";
                  }
                  ?>

                  <div class="table-responsive">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>Mã SP</th>
                          <th>Tên Sản Phẩm</th>
                          <th>Mô Tả</th>
                          <th>Giá</th>
                          <th>Ngày Tạo</th>
                          <th>Ngày Cập Nhật</th>
                          <th>Chỉnh Sửa / Xóa</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if (!empty($products)): ?>
                          <?php foreach ($products as $row): ?>
                            <tr>
                              <td><?php echo htmlspecialchars($row['id']); ?></td>
                              <td><?php echo htmlspecialchars($row['name']); ?></td>
                              <td><?php echo htmlspecialchars($row['description']); ?></td>
                              <td><?php echo number_format($row['price'], 0, ',', '.') . ' đ'; ?></td>
                              <td><?php echo date('d/m/Y H:i', strtotime($row['created_at'])); ?></td>
                              <td><?php echo date('d/m/Y H:i', strtotime($row['updated_at'])); ?></td>
                              <td>
                                <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Sửa</a>
                                <a href="delete_product.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?');">Xóa</a>
                              </td>
                            </tr>
                          <?php endforeach; ?>
                        <?php else: ?>
                          <tr>
                            <td colspan="7" class="text-center text-muted">Không có sản phẩm nào.</td>
                          </tr>
                        <?php endif; ?>
                      </tbody>
                    </table>
                  </div>

                </div>
              </div>
            </div>
          </div>

        </div>
        <!-- content-wrapper ends -->

        <!-- Footer -->
        <?php require_once('inc_footer.php'); ?>
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->

  <?php require_once('inc_scripts.php'); ?>
</body>
</html>
