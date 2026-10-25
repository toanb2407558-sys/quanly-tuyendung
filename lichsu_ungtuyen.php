<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['vai_tro'] !== 'ung_vien') {
    header("Location: dangnhap.php");
    exit();
}

$id_ung_vien = $_SESSION['user_id'];

// Lấy danh sách hồ sơ mà ứng viên này đã nộp (kết hợp bảng HoSoUngTuyen và TinTuyenDung)
$sql = "SELECT hs.Ngay_nop, hs.Trang_thai, hs.Duong_dan_CV, t.Tieu_de, t.Dia_diem 
        FROM HoSoUngTuyen hs 
        JOIN TinTuyenDung t ON hs.ID_TinTuyenDung = t.ID 
        WHERE hs.ID_UngVien = ? 
        ORDER BY hs.Ngay_nop DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_ung_vien);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Lịch sử ứng tuyển</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="index.php">Tuyển Dụng CT428</a>
        <div class="d-flex align-items-center">
            <a href="index.php" class="btn btn-outline-light btn-sm me-3">Về trang chủ</a>
            <a href="dangxuat.php" class="btn btn-outline-danger btn-sm">Đăng xuất</a>
        </div>
    </div>
</nav>

<div class="container">
    <h3 class="text-primary mb-4">Công việc bạn đã ứng tuyển</h3>
    
    <div class="bg-white p-4 shadow-sm rounded border">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Công việc</th>
                    <th>Ngày nộp</th>
                    <th>File CV của bạn</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td class="fw-bold text-success"><?= htmlspecialchars($row['Tieu_de']) ?></td>
                            <td><?= date('H:i d/m/Y', strtotime($row['Ngay_nop'])) ?></td>
                            <td>
                                <!-- Mở file CV trong tab mới -->
                                <a href="<?= htmlspecialchars($row['Duong_dan_CV']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary">Xem CV đã nộp</a>
                            </td>
                            <td>
                                <?php 
                                    if ($row['Trang_thai'] == 'Đang chờ duyệt') {
                                        echo '<span class="badge bg-warning text-dark">Đang chờ duyệt</span>';
                                    } elseif ($row['Trang_thai'] == 'Đã trúng tuyển') {
                                        echo '<span class="badge bg-success">Đã trúng tuyển</span>';
                                    } else {
                                        echo '<span class="badge bg-danger">Từ chối</span>';
                                    }
                                ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted">Bạn chưa ứng tuyển công việc nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
<?php $stmt->close(); ?>