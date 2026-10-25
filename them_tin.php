<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['vai_tro'] !== 'nha_tuyen_dung') {
    header("Location: dangnhap.php");
    exit();
}

$thong_bao = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tieu_de = $_POST['tieu_de'];
    $mo_ta = $_POST['mo_ta'];
    $yeu_cau = $_POST['yeu_cau'];
    $dia_diem = $_POST['dia_diem'];
    $han_nop = $_POST['han_nop'];
    $id_nha_tuyen_dung = $_SESSION['user_id'];

    // Insert vào database
    $sql = "INSERT INTO TinTuyenDung (Tieu_de, Mo_ta, Yeu_cau, Dia_diem, Han_nop, ID_NhaTuyenDung) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $tieu_de, $mo_ta, $yeu_cau, $dia_diem, $han_nop, $id_nha_tuyen_dung);

    if ($stmt->execute()) {
        $thong_bao = "<div class='alert alert-success'>Đăng tin thành công! <a href='quanly_tin.php'>Quay lại danh sách</a></div>";
    } else {
        $thong_bao = "<div class='alert alert-danger'>Lỗi: " . $stmt->error . "</div>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng tin tuyển dụng mới</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 bg-white p-4 border rounded shadow">
            <h3 class="mb-4 text-success">Đăng Tin Tuyển Dụng Mới</h3>
            
            <?= $thong_bao ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label">Tiêu đề công việc</label>
                    <input type="text" name="tieu_de" class="form-control" required placeholder="VD: Lập trình viên PHP">
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Mô tả công việc</label>
                    <textarea name="mo_ta" class="form-control" rows="3" required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Yêu cầu ứng viên</label>
                    <textarea name="yeu_cau" class="form-control" rows="3" required></textarea>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Địa điểm làm việc</label>
                        <input type="text" name="dia_diem" class="form-control" required placeholder="VD: Cần Thơ">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Hạn nộp hồ sơ</label>
                        <input type="date" name="han_nop" class="form-control" required>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-success w-100">Đăng Tin</button>
                <a href="quanly_tin.php" class="btn btn-secondary w-100 mt-2">Hủy / Quay lại</a>
            </form>
        </div>
    </div>
</div>

</body>
</html>