<?php
// Bắt đầu Session để lưu thông tin đăng nhập
session_start();
require_once 'config.php';

$thong_bao = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $mat_khau = $_POST['mat_khau'];

    // Tìm kiếm user theo email bằng Prepared Statement
    $stmt = $conn->prepare("SELECT ID, Ho_ten, Mat_khau, Vai_tro FROM NguoiDung WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Kiểm tra mật khẩu (giải mã và so sánh với DB)
        if (password_verify($mat_khau, $user['Mat_khau'])) {
            // Lưu thông tin vào Session
            $_SESSION['user_id'] = $user['ID'];
            $_SESSION['ho_ten'] = $user['Ho_ten'];
            $_SESSION['vai_tro'] = $user['Vai_tro'];

            // Điều hướng dựa vào vai trò
            if ($user['Vai_tro'] == 'nha_tuyen_dung') {
                header("Location: quanly_tin.php"); // Nhà tuyển dụng vào trang quản lý
            } else {
                header("Location: index.php"); // Ứng viên vào trang chủ
            }
            exit();
        } else {
            $thong_bao = "<div class='alert alert-danger'>Mật khẩu không chính xác!</div>";
        }
    } else {
        $thong_bao = "<div class='alert alert-danger'>Email không tồn tại!</div>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập hệ thống</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 bg-white p-4 border rounded shadow">
            <h3 class="text-center text-primary mb-4">Đăng Nhập</h3>
            
            <?= $thong_bao ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required placeholder="Nhập email đã đăng ký...">
                </div>
                
                <div class="mb-4">
                    <label class="form-label">Mật khẩu</label>
                    <input type="password" name="mat_khau" class="form-control" required placeholder="Nhập mật khẩu...">
                </div>
                
                <button type="submit" class="btn btn-primary w-100">Đăng Nhập</button>
                
                <div class="mt-3 text-center">
                    <p>Chưa có tài khoản? <a href="dangky.php">Đăng ký ngay</a></p>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>