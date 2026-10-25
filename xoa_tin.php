<?php
session_start();
require_once 'config.php';

// Kiểm tra quyền nhà tuyển dụng
if (!isset($_SESSION['user_id']) || $_SESSION['vai_tro'] !== 'nha_tuyen_dung') {
    header("Location: dangnhap.php");
    exit();
}

if (isset($_GET['id'])) {
    $id_tin = $_GET['id'];
    $id_nha_tuyen_dung = $_SESSION['user_id'];

    // Dùng Prepared Statement để xóa đúng tin của nhà tuyển dụng này
    $sql = "DELETE FROM TinTuyenDung WHERE ID = ? AND ID_NhaTuyenDung = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id_tin, $id_nha_tuyen_dung);
    $stmt->execute();
    $stmt->close();
}

// Xóa xong tự động quay về trang quản lý
header("Location: quanly_tin.php");
exit();
?>