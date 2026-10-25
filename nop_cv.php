<?php
session_start();
require_once 'config.php';

// Kiểm tra quyền ứng viên
if (!isset($_SESSION['user_id']) || $_SESSION['vai_tro'] !== 'ung_vien') {
    header("Location: dangnhap.php");
    exit();
}

$thong_bao = "";
$id_tin = $_GET['id_tin'] ?? 0;

// Xử lý khi ứng viên bấm nút Nộp CV
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Kiểm tra xem có file tải lên không và không có lỗi
    if (isset($_FILES['file_cv']) && $_FILES['file_cv']['error'] == 0) {
        $file_tmp = $_FILES['file_cv']['tmp_name'];
        // Thêm timestamp vào tên file để tránh trùng lặp
        $file_name = time() . "_" . basename($_FILES['file_cv']['name']); 
        $upload_dir = 'uploads/';
        
        // Tự động tạo thư mục uploads nếu chưa tồn tại
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_path = $upload_dir . $file_name;
        
        // Chỉ cho phép định dạng PDF, DOC, DOCX
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed = ['pdf', 'doc', 'docx'];
        
        if (in_array($file_ext, $allowed)) {
            // Di chuyển file từ bộ nhớ tạm vào thư mục uploads
            if (move_uploaded_file($file_tmp, $file_path)) {
                // Lưu vào cơ sở dữ liệu bằng Prepared Statements
                $id_ung_vien = $_SESSION['user_id'];
                $sql_nop = "INSERT INTO HoSoUngTuyen (ID_UngVien, ID_TinTuyenDung, Duong_dan_CV) VALUES (?, ?, ?)";
                $stmt_nop = $conn->prepare($sql_nop);
                $stmt_nop->bind_param("iis", $id_ung_vien, $id_tin, $file_path);
                
                if ($stmt_nop->execute()) {
                    $thong_bao = "<div class='alert alert-success'>Nộp CV thành công! <a href='index.php'>Quay lại trang chủ</a></div>";
                } else {
                    $thong_bao = "<div class='alert alert-danger'>Lỗi cơ sở dữ liệu: " . $stmt_nop->error . "</div>";
                }
                $stmt_nop->close();
            } else {
                $thong_bao = "<div class='alert alert-danger'>Lỗi khi lưu file lên máy chủ.</div>";
            }
        } else {
            $thong_bao = "<div class='alert alert-danger'>Chỉ chấp nhận file định dạng PDF, DOC, hoặc DOCX.</div>";
        }
    } else {
        $thong_bao = "<div class='alert alert-danger'>Vui lòng chọn file CV để tải lên.</div>";
    }
}

// Lấy thông tin chi tiết của tin tuyển dụng để hiển thị
$sql_tin = "SELECT * FROM TinTuyenDung WHERE ID = ?";
$stmt_tin = $conn->prepare($sql_tin);
$stmt_tin->bind_param("i", $id_tin);
$stmt_tin->execute();
$result_tin = $stmt_tin->get_result();

if ($result_tin->num_rows == 0) {
    die("Không tìm thấy tin tuyển dụng.");
}
$tin = $result_tin->fetch_assoc();
$stmt_tin->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Nộp CV - <?= htmlspecialchars($tin['Tieu_de']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row">
        <!-- Cột hiển thị thông tin công việc -->
        <div class="col-md-7 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h3 class="text-success fw-bold mb-3"><?= htmlspecialchars($tin['Tieu_de']) ?></h3>
                    <p>📍 <strong>Địa điểm:</strong> <?= htmlspecialchars($tin['Dia_diem']) ?></p>
                    <p>⏳ <strong>Hạn nộp:</strong> <?= date('d/m/Y', strtotime($tin['Han_nop'])) ?></p>
                    <hr>
                    <h5 class="fw-bold">Mô tả công việc:</h5>
                    <p style="white-space: pre-line;"><?= htmlspecialchars($tin['Mo_ta']) ?></p>
                    <h5 class="fw-bold mt-3">Yêu cầu ứng viên:</h5>
                    <p style="white-space: pre-line;"><?= htmlspecialchars($tin['Yeu_cau']) ?></p>
                </div>
            </div>
        </div>

        <!-- Cột hiển thị Form nộp CV -->
        <div class="col-md-5">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h4 class="text-primary text-center mb-4">Ứng Tuyển Ngay</h4>
                    <?= $thong_bao ?>
                    
                    <!-- Bắt buộc phải có enctype="multipart/form-data" mới upload được file -->
                    <form method="POST" action="" enctype="multipart/form-data">
                        <div class="mb-4">
                            <label class="form-label fw-bold">Tải lên CV của bạn (PDF, DOC, DOCX):</label>
                            <input type="file" name="file_cv" class="form-control" accept=".pdf,.doc,.docx" required>
                            <div class="form-text">Dung lượng tối đa 5MB.</div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-2">Gửi Hồ Sơ Ứng Tuyển</button>
                        <a href="index.php" class="btn btn-outline-secondary w-100 mt-2">Quay lại</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>