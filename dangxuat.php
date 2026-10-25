<?php
session_start();
session_unset(); // Xóa các biến session
session_destroy(); // Hủy session
header("Location: dangnhap.php"); // Trả về trang đăng nhập
exit();
?>