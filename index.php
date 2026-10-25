<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: dangnhap.php");
    exit();
}

// Lấy tất cả tin tuyển dụng mới nhất từ cơ sở dữ liệu
$sql = "SELECT * FROM TinTuyenDung ORDER BY ID DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Trang Chủ - Cổng Tuyển Dụng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Thanh menu -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="index.php">Tuyển Dụng CT428</a>
        <div class="d-flex align-items-center">
            <span class="navbar-text me-3 text-white">
                Xin chào, <strong><?= htmlspecialchars($_SESSION['ho_ten']) ?></strong>!
            </span>
            <!-- Thêm nút Lịch sử nộp CV cho ứng viên -->
            <?php if($_SESSION['vai_tro'] == 'ung_vien'): ?>
                <a href="lichsu_ungtuyen.php" class="btn btn-info btn-sm me-2 text-white">Lịch sử nộp CV</a>
            <?php endif; ?>
            <a href="dangxuat.php" class="btn btn-outline-danger btn-sm">Đăng xuất</a>
        </div>
    </div>
</nav>

<div class="container">
    <h2 class="text-center mb-4 text-primary">Việc Làm Mới Nhất</h2>
    
    <div class="row">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm h-100 border-0">
                        <div class="card-body">
                            <h5 class="card-title text-success fw-bold"><?= htmlspecialchars($row['Tieu_de']) ?></h5>
                            <p class="card-text text-muted small mb-2">
                                📍 <strong>Địa điểm:</strong> <?= htmlspecialchars($row['Dia_diem']) ?> <br>
                                ⏳ <strong>Hạn nộp:</strong> <?= date('d/m/Y', strtotime($row['Han_nop'])) ?>
                            </p>
                            <p class="card-text">
                                <!-- Hiển thị một đoạn ngắn mô tả (cắt bớt nếu dài quá) -->
                                <?= mb_substr(htmlspecialchars($row['Mo_ta']), 0, 100) ?>...
                            </p>
                        </div>
                        <div class="card-footer bg-white border-0 pb-3 text-center">
                            <!-- Nút xem chi tiết và nộp CV -->
                            <a href="nop_cv.php?id_tin=<?= $row['ID'] ?>" class="btn btn-primary w-100">Xem & Nộp CV</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-warning text-center shadow-sm">
                    Hiện tại chưa có công việc nào được đăng.
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>