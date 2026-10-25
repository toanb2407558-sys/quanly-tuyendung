<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['vai_tro'] !== 'nha_tuyen_dung') {
    header("Location: dangnhap.php");
    exit();
}

$id_tin = $_GET['id'];
$id_nha_tuyen_dung = $_SESSION['user_id'];
$thong_bao = "";

// Xử lý khi bấm nút Cập nhật
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tieu_de = $_POST['tieu_de'];
    $mo_ta = $_POST['mo_ta'];
    $yeu_cau = $_POST['yeu_cau'];
    $dia_diem = $_POST['dia_diem'];
    $han_nop = $_POST['han_nop'];

    $sql_update = "UPDATE TinTuyenDung SET Tieu_de=?, Mo_ta=?, Yeu_cau=?, Dia_diem=?, Han_nop=? WHERE ID=? AND ID_NhaTuyenDung=?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("sssssii", $tieu_de, $mo_ta, $yeu_cau, $dia_diem, $han_nop, $id_tin, $id_nha_tuyen_dung);
    
    if ($stmt_update->execute()) {
        $thong_bao = "<div class='alert alert-success'>Cập nhật thành công! <a href='quanly_tin.php'>Quay lại danh sách</a></div>";
    }
    $stmt_update->close();
}

// Lấy dữ liệu cũ để hiển thị vào form
$sql = "SELECT * FROM TinTuyenDung WHERE ID = ? AND ID_NhaTuyenDung = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id_tin, $id_nha_tuyen_dung);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Không tìm thấy tin hoặc bạn không có quyền sửa tin này.");
}
$tin = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa tin tuyển dụng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 bg-white p-4 border rounded shadow">
            <h3 class="mb-4 text-warning">Sửa Tin Tuyển Dụng</h3>
            <?= $thong_bao ?>
            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label">Tiêu đề công việc</label>
                    <input type="text" name="tieu_de" class="form-control" value="<?= htmlspecialchars($tin['Tieu_de']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Mô tả công việc</label>
                    <textarea name="mo_ta" class="form-control" rows="3" required><?= htmlspecialchars($tin['Mo_ta']) ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Yêu cầu ứng viên</label>
                    <textarea name="yeu_cau" class="form-control" rows="3" required><?= htmlspecialchars($tin['Yeu_cau']) ?></textarea>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Địa điểm làm việc</label>
                        <input type="text" name="dia_diem" class="form-control" value="<?= htmlspecialchars($tin['Dia_diem']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Hạn nộp hồ sơ</label>
                        <input type="date" name="han_nop" class="form-control" value="<?= $tin['Han_nop'] ?>" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-warning w-100">Cập nhật Tin</button>
                <a href="quanly_tin.php" class="btn btn-secondary w-100 mt-2">Hủy / Quay lại</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>