<?php
session_start();
require_once 'config.php';

// Kiểm tra quyền: Chỉ nhà tuyển dụng mới được duyệt
if (!isset($_SESSION['user_id']) || $_SESSION['vai_tro'] !== 'nha_tuyen_dung') {
    header("Location: dangnhap.php");
    exit();
}

// Kiểm tra xem đã nhận đủ dữ liệu từ URL chưa
if (isset($_GET['id_hoso']) && isset($_GET['trang_thai']) && isset($_GET['id_tin'])) {
    $id_hoso = $_GET['id_hoso'];
    $trang_thai = $_GET['trang_thai'];
    $id_tin = $_GET['id_tin'];

    // Cập nhật trạng thái hồ sơ vào database
    $sql = "UPDATE HoSoUngTuyen SET Trang_thai = ? WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $trang_thai, $id_hoso);
    
    if ($stmt->execute()) {
        // Thành công thì chuyển hướng về lại trang danh sách ứng viên
        header("Location: xem_hoso.php?id_tin=" . $id_tin);
        exit();
    } else {
        echo "Có lỗi xảy ra: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "Thiếu dữ liệu để xử lý.";
}
?>