-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 25, 2026 lúc 04:23 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `quanly_tuyendung`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hosoungtuyen`
--

CREATE TABLE `hosoungtuyen` (
  `ID` int(11) NOT NULL,
  `ID_UngVien` int(11) NOT NULL,
  `ID_TinTuyenDung` int(11) NOT NULL,
  `Duong_dan_CV` varchar(255) NOT NULL,
  `Ngay_nop` datetime DEFAULT current_timestamp(),
  `Trang_thai` varchar(50) DEFAULT 'Đang chờ duyệt'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nguoidung`
--

CREATE TABLE `nguoidung` (
  `ID` int(11) NOT NULL,
  `Ho_ten` varchar(255) NOT NULL,
  `Email` varchar(150) NOT NULL,
  `Mat_khau` varchar(255) NOT NULL,
  `Vai_tro` enum('ung_vien','nha_tuyen_dung') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tintuyendung`
--

CREATE TABLE `tintuyendung` (
  `ID` int(11) NOT NULL,
  `Tieu_de` varchar(255) NOT NULL,
  `Mo_ta` text NOT NULL,
  `Yeu_cau` text NOT NULL,
  `Dia_diem` varchar(255) NOT NULL,
  `Han_nop` date NOT NULL,
  `ID_NhaTuyenDung` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `hosoungtuyen`
--
ALTER TABLE `hosoungtuyen`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID_UngVien` (`ID_UngVien`),
  ADD KEY `ID_TinTuyenDung` (`ID_TinTuyenDung`);

--
-- Chỉ mục cho bảng `nguoidung`
--
ALTER TABLE `nguoidung`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Chỉ mục cho bảng `tintuyendung`
--
ALTER TABLE `tintuyendung`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID_NhaTuyenDung` (`ID_NhaTuyenDung`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `hosoungtuyen`
--
ALTER TABLE `hosoungtuyen`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `nguoidung`
--
ALTER TABLE `nguoidung`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `tintuyendung`
--
ALTER TABLE `tintuyendung`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `hosoungtuyen`
--
ALTER TABLE `hosoungtuyen`
  ADD CONSTRAINT `hosoungtuyen_ibfk_1` FOREIGN KEY (`ID_UngVien`) REFERENCES `nguoidung` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `hosoungtuyen_ibfk_2` FOREIGN KEY (`ID_TinTuyenDung`) REFERENCES `tintuyendung` (`ID`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `tintuyendung`
--
ALTER TABLE `tintuyendung`
  ADD CONSTRAINT `tintuyendung_ibfk_1` FOREIGN KEY (`ID_NhaTuyenDung`) REFERENCES `nguoidung` (`ID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
