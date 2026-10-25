<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['vai_tro'] !== 'nha_tuyen_dung') {
    header("Location: dangnhap.php");
    exit();
}

$id_tin = $_GET['id_tin'];

// Lấy danh sách hồ sơ kèm tên và email của ứng viên
$sql = "SELECT hs.ID, hs.Ngay_nop, hs.Duong_dan_CV, hs.Trang_thai, nd.Ho_ten, nd.Email 
        FROM HoSoUngTuyen hs 
        JOIN NguoiDung nd ON hs.ID_UngVien = nd.ID 
        WHERE hs.ID_TinTuyenDung = ? 
        ORDER BY hs.Ngay_nop DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_tin);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách ứng viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Danh sách Hồ Sơ Ứng Viên</h3>
        <a href="quanly_tin.php" class="btn btn-secondary">Quay lại danh sách tin</a>
    </div>

    <table class="table table-bordered bg-white shadow-sm">
        <thead class="table-primary">
            <tr>
                <th>Tên Ứng viên</th>
                <th>Email</th>
                <th>Ngày nộp</th>
                <th>File CV</th>
                <th>Trạng thái hiện tại</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td class="fw-bold"><?= htmlspecialchars($row['Ho_ten']) ?></td>
                        <td><?= htmlspecialchars($row['Email']) ?></td>
                        <td><?= date('H:i d/m/Y', strtotime($row['Ngay_nop'])) ?></td>
                        <td><a href="<?= htmlspecialchars($row['Duong_dan_CV']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">Tải / Xem CV</a></td>
                        <td><span class="badge bg-secondary"><?= $row['Trang_thai'] ?></span></td>
                        <td>
                            <!-- Các nút thay đổi trạng thái gọi đến file capnhat_trangthai.php -->
                            <a href="capnhat_trangthai.php?id_hoso=<?= $row['ID'] ?>&id_tin=<?= $id_tin ?>&trang_thai=Đã trúng tuyển" class="btn btn-success btn-sm">Duyệt</a>
                            <a href="capnhat_trangthai.php?id_hoso=<?= $row['ID'] ?>&id_tin=<?= $id_tin ?>&trang_thai=Từ chối" class="btn btn-danger btn-sm">Từ chối</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="6" class="text-center text-muted">Chưa có ứng viên nào nộp hồ sơ.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
<?php $stmt->close(); ?>