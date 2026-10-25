<?php
// Gọi file kết nối database
require_once 'config.php';

$thong_bao = "";

// Xử lý khi người dùng bấm nút "Đăng ký"
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ho_ten = $_POST['ho_ten'];
    $email = $_POST['email'];
    $mat_khau = $_POST['mat_khau'];
    $vai_tro = $_POST['vai_tro'];

    // Mã hóa mật khẩu bảo mật
    $mat_khau_bam = password_hash($mat_khau, PASSWORD_DEFAULT);

    // Kiểm tra xem Email đã tồn tại chưa bằng Prepared Statement
    $stmt_check = $conn->prepare("SELECT ID FROM NguoiDung WHERE Email = ?");
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        $thong_bao = "<div class='alert alert-danger'>Email này đã được sử dụng! Vui lòng dùng email khác.</div>";
    } else {
        // Thêm tài khoản mới vào database bằng Prepared Statement
        $stmt_insert = $conn->prepare("INSERT INTO NguoiDung (Ho_ten, Email, Mat_khau, Vai_tro) VALUES (?, ?, ?, ?)");
        $stmt_insert->bind_param("ssss", $ho_ten, $email, $mat_khau_bam, $vai_tro);
        
        if ($stmt_insert->execute()) {
            $thong_bao = "<div class='alert alert-success'>Đăng ký thành công! <a href='dangnhap.php'>Bấm vào đây để đăng nhập</a></div>";
        } else {
            $thong_bao = "<div class='alert alert-danger'>Đã có lỗi xảy ra.</div>";
        }
        $stmt_insert->close();
    }
    $stmt_check->close();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký tài khoản</title>
    <!-- Nhúng thư viện Bootstrap để giao diện đẹp mà không cần viết CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 bg-white p-4 border rounded shadow">
            <h3 class="text-center text-primary mb-4">Đăng Ký Tài Khoản</h3>
            
            <!-- Nơi hiển thị thông báo thành công hoặc lỗi -->
            <?= $thong_bao ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label">Họ và Tên</label>
                    <input type="text" name="ho_ten" class="form-control" required placeholder="Nhập họ và tên...">
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required placeholder="example@gmail.com">
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Mật khẩu</label>
                    <input type="password" name="mat_khau" class="form-control" required placeholder="Nhập mật khẩu...">
                </div>
                
                <div class="mb-4">
                    <label class="form-label">Bạn là:</label>
                    <select name="vai_tro" class="form-select" required>
                        <option value="ung_vien">Ứng viên tìm việc</option>
                        <option value="nha_tuyen_dung">Nhà tuyển dụng</option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary w-100">Đăng Ký</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>