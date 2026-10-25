<?php
session_start();
require_once 'config.php';

// Kiểm tra: Nếu chưa đăng nhập hoặc KHÔNG PHẢI nhà tuyển dụng thì đuổi về trang đăng nhập
if (!isset($_SESSION['user_id']) || $_SESSION['vai_tro'] !== 'nha_tuyen_dung') {
    header("Location: dangnhap.php");
    exit();
}

$id_nha_tuyen_dung = $_SESSION['user_id'];

// Lấy danh sách tin tuyển dụng do người này đăng
$sql = "SELECT * FROM TinTuyenDung WHERE ID_NhaTuyenDung = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_nha_tuyen_dung);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý tin tuyển dụng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="quanly_tin.php">Kênh Nhà Tuyển Dụng</a>
        <div class="d-flex align-items-center">
            <span class="navbar-text me-3 text-white">
                Xin chào, <strong><?= htmlspecialchars($_SESSION['ho_ten']) ?></strong>
            </span>
            <a href="dangxuat.php" class="btn btn-outline-danger btn-sm">Đăng xuất</a>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Danh sách tin đã đăng</h2>
        <a href="them_tin.php" class="btn btn-success">+ Đăng tin mới</a>
    </div>

    <table class="table table-bordered bg-white shadow-sm">
        <thead class="table-primary">
            <tr>
                <th>ID</th>
                <th>Tiêu đề</th>
                <th>Địa điểm</th>
                <th>Hạn nộp</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['ID'] ?></td>
                        <td><?= htmlspecialchars($row['Tieu_de']) ?></td>
                        <td><?= htmlspecialchars($row['Dia_diem']) ?></td>
                        <td><?= date('d/m/Y', strtotime($row['Han_nop'])) ?></td>
                        <td>
                            <!-- Đã gắn thêm link Xem CV -->
                            <a href="xem_hoso.php?id_tin=<?= $row['ID'] ?>" class="btn btn-info btn-sm text-white">Xem CV</a>
                            <a href="sua_tin.php?id=<?= $row['ID'] ?>" class="btn btn-warning btn-sm">Sửa</a>
                            <a href="xoa_tin.php?id=<?= $row['ID'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa tin này?')">Xóa</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center text-muted">Bạn chưa đăng tin tuyển dụng nào.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
<?php $stmt->close(); ?>