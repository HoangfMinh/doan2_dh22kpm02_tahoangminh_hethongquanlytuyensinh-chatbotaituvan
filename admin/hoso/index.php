<?php
include("../../config/auth_admin.php");
include("../../config/database.php");
include("../../assets/layout/header.php");

$nganh_list = $conn->query("SELECT * FROM nganh");

$keyword = $_GET['keyword'] ?? "";
$nganh_id = $_GET['nganh'] ?? "";
$trang_thai = $_GET['trang_thai'] ?? "";

$where = [];

if($keyword != ""){
    $where[] = "(thisinh.ho_ten LIKE '%$keyword%' OR thisinh.email LIKE '%$keyword%')";
}

if($nganh_id != ""){
    $where[] = "hoso.nganh_id = '$nganh_id'";
}

if($trang_thai != ""){
    $where[] = "hoso.trang_thai = '$trang_thai'";
}

$where_sql = "";

if(count($where) > 0){
    $where_sql = "WHERE " . implode(" AND ", $where);
}
$sql = "SELECT hoso.*, thisinh.ho_ten, thisinh.ngay_sinh,
thisinh.email, thisinh.so_dien_thoai, thisinh.dia_chi,
nganh.ten_nganh

FROM hoso
JOIN thisinh ON hoso.thisinh_id = thisinh.tai_khoan_id
JOIN nganh ON hoso.nganh_id = nganh.id

$where_sql

ORDER BY hoso.id DESC";

$result = $conn->query($sql);
?>

<div class="container mt-4">

<div class="d-flex justify-content-between align-items-center mb-3">

<h3>
<i class="fa-solid fa-folder-open"></i>
Quản lý hồ sơ xét tuyển
</h3>

<div>

<a href="xuat_excel.php" class="btn btn-success">
<i class="fa-solid fa-file-excel"></i> Excel
</a>

<div class="dropdown d-inline">

<button class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown">
<i class="fa-solid fa-file-pdf"></i> Xuất PDF
</button>

<ul class="dropdown-menu">

<li>
<a class="dropdown-item" href="xuat_pdf.php">
📄 Xuất danh sách trúng tuyển
</a>
</li>

<li>
<a class="dropdown-item" href="pdf_hangloat.php">
📑 Xuất giấy báo hàng loạt
</a>
</li>

<li>
<hr class="dropdown-divider">
</li>

<li class="px-3">

<form action="pdf_canhan.php" method="GET">

<input type="number"
name="id"
class="form-control mb-2"
placeholder="Nhập ID hồ sơ"
required>

<button class="btn btn-sm btn-danger w-100">
📃 Xuất PDF cá nhân
</button>

</form>

</li>

</ul>

</div>

<a href="../../index.php" class="btn btn-secondary">
<i class="fa-solid fa-arrow-left"></i> Quay về
</a>

</div>

</div>

<div class="mb-3">

<form method="GET" class="d-flex gap-2" style="max-width:500px;">

<input type="text"
name="keyword"
value="<?= $keyword ?>"
class="form-control"
placeholder="Tìm thí sinh...">

<select name="nganh" class="form-select" style="max-width:200px;">

<option value="">🎓 Lọc ngành</option>

<?php while($nganh = $nganh_list->fetch_assoc()){ ?>

<option value="<?= $nganh['id'] ?>"
<?= ($nganh_id == $nganh['id']) ? "selected" : "" ?>>

<?= $nganh['ten_nganh'] ?>

</option>

<?php } ?>

</select>
<select name="trang_thai" class="form-select" style="max-width:180px;">

<option value="">📋 Trạng thái</option>

<option value="Chờ duyệt"
<?= ($trang_thai == "Chờ duyệt") ? "selected" : "" ?>>
Chờ duyệt
</option>

<option value="Đậu"
<?= ($trang_thai == "Đậu") ? "selected" : "" ?>>
Đã duyệt 
</option>


</select>
<button class="btn btn-primary">
<i class="fa-solid fa-search"></i>
</button>

</form>

</div>

<div class="table-responsive">

<table class="table table-bordered table-hover align-middle">

<thead class="table-primary">

<tr>
<th>ID</th>
<th>Họ tên</th>

<th>Email</th>


<th>Ngành</th>
<th>Điểm</th>
<th>Tổng</th>
<th>Ngày nộp</th>
<th>Trạng thái</th>
<th width="160">Hành động</th>
</tr>

</thead>

<tbody>

<?php if($result && $result->num_rows > 0){ ?>

<?php while($row = $result->fetch_assoc()){ 

$status = $row['trang_thai'];
$color = "bg-warning";

if($status == "Đậu") $color = "bg-success";
if($status == "Rớt") $color = "bg-danger";

?>

<tr>

<td><?= $row['id'] ?></td>

<td><?= $row['ho_ten'] ?></td>



<td><?= $row['email'] ?></td>





<td><?= $row['ten_nganh'] ?></td>

<td>
<?= $row['diem_mon1'] ?> -
<?= $row['diem_mon2'] ?> -
<?= $row['diem_mon3'] ?>
</td>

<td>
<strong><?= $row['tong_diem'] ?></strong>
</td>

<td><?= $row['ngay_nop'] ?></td>

<td>
<span class="badge <?= $color ?>">
<?= $status ?>
</span>
</td>

<td>

<a href="chitiet.php?id=<?= $row['id'] ?>"
class="btn btn-info btn-sm">
<i class="fa-solid fa-eye"></i>
</a>

<a href="duyet.php?id=<?= $row['id'] ?>&st=Đậu"
class="btn btn-success btn-sm"
onclick="return confirm('Duyệt hồ sơ này?')">

<i class="fa-solid fa-check"></i>

</a>

<a href="duyet.php?id=<?= $row['id'] ?>&st=Rớt"
class="btn btn-danger btn-sm"
onclick="return confirm('Từ chối hồ sơ này?')">

<i class="fa-solid fa-xmark"></i>

</a>
<a href="pdf_canhan.php?id=<?= $row['id'] ?>"
class="btn btn-danger btn-sm">

<i class="fa-solid fa-file-pdf"></i>

</a>
</td>

</tr>

<?php } ?>

<?php } else { ?>

<tr>
<td colspan="12" class="text-center">
Không có dữ liệu
</td>
</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

<?php include("../../assets/layout/footer.php"); ?>