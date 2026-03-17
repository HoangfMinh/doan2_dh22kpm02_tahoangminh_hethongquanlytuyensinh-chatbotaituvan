<?php
include("../../config/auth_admin.php");
include("../../config/database.php");
include("../../assets/layout/header.php");

$id = $_GET["id"];

$sql = "SELECT hoso.*, thisinh.ho_ten, thisinh.ngay_sinh,
thisinh.email, thisinh.so_dien_thoai, thisinh.dia_chi,
nganh.ten_nganh
FROM hoso
JOIN thisinh ON hoso.thisinh_id = thisinh.tai_khoan_id
JOIN nganh ON hoso.nganh_id = nganh.id
WHERE hoso.id = $id";

$result = $conn->query($sql);
$row = $result->fetch_assoc();
?>

<div class="container mt-4">

<h3 class="mb-4">
<i class="fa-solid fa-user"></i>
Chi tiết hồ sơ thí sinh
</h3>

<div class="card shadow mb-4">

<div class="card-header bg-primary text-white">
Thông tin thí sinh
</div>

<div class="card-body">

<p><b>Họ tên:</b> <?= $row['ho_ten'] ?></p>
<p><b>Ngày sinh:</b> <?= $row['ngay_sinh'] ?></p>
<p><b>Email:</b> <?= $row['email'] ?></p>
<p><b>SĐT:</b> <?= $row['so_dien_thoai'] ?></p>
<p><b>Địa chỉ:</b> <?= $row['dia_chi'] ?></p>

</div>

</div>

<div class="card shadow">

<div class="card-header bg-success text-white">
Thông tin xét tuyển
</div>

<div class="card-body">

<p><b>Ngành đăng ký:</b> <?= $row['ten_nganh'] ?></p>

<p><b>Điểm môn:</b> 
<?= $row['diem_mon1'] ?> -
<?= $row['diem_mon2'] ?> -
<?= $row['diem_mon3'] ?>
</p>

<p><b>Tổng điểm:</b> <?= $row['tong_diem'] ?></p>

<p><b>Ngày nộp:</b> <?= $row['ngay_nop'] ?></p>

<p><b>Trạng thái:</b> <?= $row['trang_thai'] ?></p>

<div class="mt-4">

<a href="duyet.php?id=<?= $row['id'] ?>&st=Đậu"
class="btn btn-success">
<i class="fa-solid fa-check"></i> Duyệt hồ sơ
</a>

<a href="duyet.php?id=<?= $row['id'] ?>&st=Rớt"
class="btn btn-danger">
<i class="fa-solid fa-xmark"></i> Từ chối
</a>

<a href="index.php" class="btn btn-secondary">
<i class="fa-solid fa-arrow-left"></i> Quay lại
</a>

</div>

</div>

</div>

</div>

<?php include("../../assets/layout/footer.php"); ?>