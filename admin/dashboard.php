<?php
session_start();
include("../config/database.php");
include("../assets/layout/header.php");

/* ===== THỐNG KÊ ===== */

$sql_nganh = "SELECT COUNT(*) AS tong FROM nganh";
$row_nganh = $conn->query($sql_nganh)->fetch_assoc();

$sql_hoso = "SELECT COUNT(*) AS tong FROM hoso";
$row_hoso = $conn->query($sql_hoso)->fetch_assoc();

$sql_today = "SELECT COUNT(*) AS tong FROM hoso WHERE DATE(hoso_homnay)=CURDATE()";
$row_today = $conn->query($sql_today)->fetch_assoc();

/* ===== TRẠNG THÁI ===== */

$sql_dau = "SELECT COUNT(*) AS tong FROM hoso WHERE trang_thai='Đậu'";
$row_dau = $conn->query($sql_dau)->fetch_assoc();

$sql_rot = "SELECT COUNT(*) AS tong FROM hoso WHERE trang_thai='Rớt'";
$row_rot = $conn->query($sql_rot)->fetch_assoc();

$sql_cho = "SELECT COUNT(*) AS tong FROM hoso WHERE trang_thai='Chờ duyệt'";
$row_cho = $conn->query($sql_cho)->fetch_assoc();

/* ===== TỶ LỆ ĐẬU ===== */

$total = $row_hoso['tong'];
$tyle_dau = 0;

if($total > 0){
$tyle_dau = round(($row_dau['tong'] / $total) * 100);
}

/* ===== HỒ SƠ MỚI ===== */

$sql_new = "SELECT h.id, t.ho_ten, n.ten_nganh, h.tong_diem, h.ngay_nop
FROM hoso h
JOIN thisinh t ON h.thisinh_id = t.id
JOIN nganh n ON h.nganh_id = n.id
ORDER BY h.id DESC
LIMIT 5";

$result_new = $conn->query($sql_new);

/* ===== HỒ SƠ THEO NGÀNH ===== */

$sql_chart = "SELECT n.ten_nganh, COUNT(h.id) AS so_luong
FROM nganh n
LEFT JOIN hoso h ON h.nganh_id = n.id
GROUP BY n.id";

$result_chart = $conn->query($sql_chart);

$ten_nganh = [];
$so_luong = [];

while($row = $result_chart->fetch_assoc()){
$ten_nganh[] = $row['ten_nganh'];
$so_luong[] = $row['so_luong'];
}

/* ===== HỒ SƠ 7 NGÀY ===== */

$sql_7ngay = "SELECT DATE(hoso_homnay) AS ngay, COUNT(*) AS so_luong
FROM hoso
WHERE hoso_homnay >= CURDATE() - INTERVAL 7 DAY
GROUP BY DATE(hoso_homnay)";

$result_7ngay = $conn->query($sql_7ngay);

$ngay = [];
$soluong_ngay = [];

while($row = $result_7ngay->fetch_assoc()){
$ngay[] = $row['ngay'];
$soluong_ngay[] = $row['so_luong'];
}

/* ===== TOP NGÀNH HOT ===== */

$sql_top = "SELECT n.ten_nganh, COUNT(h.id) AS so_luong
FROM nganh n
LEFT JOIN hoso h ON h.nganh_id = n.id
GROUP BY n.id
ORDER BY so_luong DESC
LIMIT 5";

$result_top = $conn->query($sql_top);

/* ===== TOP THÍ SINH ĐIỂM CAO ===== */

$sql_topdiem = "SELECT t.ho_ten, n.ten_nganh, h.tong_diem
FROM hoso h
JOIN thisinh t ON h.thisinh_id = t.id
JOIN nganh n ON h.nganh_id = n.id
ORDER BY h.tong_diem DESC
LIMIT 5";

$result_topdiem = $conn->query($sql_topdiem);

/* ===== BIỂU ĐỒ THEO THÁNG ===== */

$sql_month = "SELECT MONTH(hoso_homnay) AS thang, COUNT(*) AS so_luong
FROM hoso
GROUP BY MONTH(hoso_homnay)
ORDER BY thang";

$result_month = $conn->query($sql_month);

$thang = [];
$hoso_thang = [];

while($row = $result_month->fetch_assoc()){
$thang[] = "Tháng ".$row['thang'];
$hoso_thang[] = $row['so_luong'];
}

?>

<div class="container mt-4">

<div class="d-flex justify-content-between mb-3">

<h2>📊 Trang quản trị</h2>

<div>

<a href="xuat_dashboard_pdf.php" class="btn btn-danger">
Xuất PDF
</a>

<a href="../index.php" class="btn btn-secondary">
Quay về
</a>

</div>

</div>

<div class="row">

<div class="col-md-3 mb-3">
<div class="card text-center shadow">
<div class="card-body">
<h5>Ngành</h5>
<h2><?= $row_nganh['tong'] ?></h2>
</div>
</div>
</div>

<div class="col-md-3 mb-3">
<div class="card text-center shadow">
<div class="card-body">
<h5>Tổng hồ sơ</h5>
<h2><?= $row_hoso['tong'] ?></h2>
</div>
</div>
</div>

<div class="col-md-3 mb-3">
<div class="card text-center shadow">
<div class="card-body">
<h5>Hồ sơ hôm nay</h5>
<h2><?= $row_today['tong'] ?></h2>
</div>
</div>
</div>

<div class="col-md-3 mb-3">
<div class="card text-center shadow">
<div class="card-body">
<h5>Tỷ lệ đậu</h5>
<h2 class="text-success"><?= $tyle_dau ?>%</h2>
</div>
</div>
</div>

</div>

<div class="row">

<div class="col-md-6">
<div class="card shadow">
<div class="card-body">

<h5>📊 Hồ sơ theo ngành</h5>

<canvas id="chartNganh"></canvas>

</div>
</div>
</div>

<div class="col-md-6">
<div class="card shadow">
<div class="card-body">

<h5>📊 Trạng thái hồ sơ</h5>

<canvas id="chartTrangThai"></canvas>

</div>
</div>
</div>

</div>

<div class="row mt-4">

<div class="col-md-6">

<div class="card shadow">
<div class="card-body">

<h5>🔥 Top ngành đăng ký</h5>

<table class="table">

<tr>
<th>Ngành</th>
<th>Số hồ sơ</th>
</tr>

<?php while($row = $result_top->fetch_assoc()){ ?>

<tr>
<td><?= $row['ten_nganh'] ?></td>
<td><?= $row['so_luong'] ?></td>
</tr>

<?php } ?>

</table>

</div>
</div>

</div>

<div class="col-md-6">

<div class="card shadow">
<div class="card-body">

<h5>🎯 Top thí sinh điểm cao</h5>

<table class="table">

<tr>
<th>Họ tên</th>
<th>Ngành</th>
<th>Điểm</th>
</tr>

<?php while($row = $result_topdiem->fetch_assoc()){ ?>

<tr>
<td><?= $row['ho_ten'] ?></td>
<td><?= $row['ten_nganh'] ?></td>
<td><?= $row['tong_diem'] ?></td>
</tr>

<?php } ?>

</table>

</div>
</div>

</div>

</div>

<div class="card mt-4 shadow">

<div class="card-body">

<h5>📈 Tăng trưởng hồ sơ theo tháng</h5>

<canvas id="chartMonth"></canvas>

</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

new Chart(document.getElementById("chartNganh"),{

type:"bar",

data:{
labels: <?= json_encode($ten_nganh) ?>,
datasets:[{
label:"Số hồ sơ",
data: <?= json_encode($so_luong) ?>
}]
}

});

new Chart(document.getElementById("chartTrangThai"),{

type:"doughnut",

data:{
labels:["Đậu","Rớt","Chờ duyệt"],
datasets:[{
data:[
<?= $row_dau['tong'] ?>,
<?= $row_rot['tong'] ?>,
<?= $row_cho['tong'] ?>
]
}]
}

});

new Chart(document.getElementById("chartMonth"),{

type:"line",

data:{
labels: <?= json_encode($thang) ?>,
datasets:[{
label:"Hồ sơ",
data: <?= json_encode($hoso_thang) ?>,
borderColor:"#007bff",
fill:false
}]
}

});

</script>
<?php include("../assets/layout/footer.php"); ?>