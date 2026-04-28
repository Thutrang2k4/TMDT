<?php
header('Content-Type: application/json');
require_once '../../db.php';

// Nhận dữ liệu
$region  = isset($_POST['region'])  ? trim($_POST['region'])  : "";
$name    = isset($_POST['name'])    ? trim($_POST['name'])    : "";
$address = isset($_POST['address']) ? trim($_POST['address']) : "";
$phone   = isset($_POST['phone'])   ? trim($_POST['phone'])   : "";
$email   = isset($_POST['email'])   ? trim($_POST['email'])   : "";

// DANH SÁCH REGION ĐÚNG THEO DATABASE THỰC TẾ
$valid_regions = ["hcm", "mien-bac", "mien-trung","ng"];

// Kiểm tra region
if ($region === "" || !in_array($region, $valid_regions)) {
    echo json_encode([
        "status" => "error",
        "message" => "Không tìm thấy khu vực đang chọn!"
    ]);
    exit;
}

// Kiểm tra dữ liệu bắt buộc
if ($name === "" || $address === "") {
    echo json_encode([
        "status" => "error",
        "message" => "Vui lòng nhập đầy đủ tên chi nhánh và địa chỉ!"
    ]);
    exit;
}

// INSERT
$sql = "INSERT INTO branches (region, name, address, phone, email)
        VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $region, $name, $address, $phone, $email);

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "message" => "Thêm chi nhánh thành công!"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Lỗi khi tạo chi nhánh!"
    ]);
}

$stmt->close();
$conn->close();
?>