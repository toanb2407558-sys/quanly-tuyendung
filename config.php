<?php
$host = "localhost";
$username = "root";
$password = ""; // XAMPP mặc định mật khẩu để trống
$dbname = "quanly_tuyendung"; // Tên database bạn vừa tạo

// Khởi tạo kết nối
$conn = new mysqli($host, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Đặt bảng mã utf8mb4 để không bị lỗi font tiếng Việt
$conn->set_charset("utf8mb4");
?>