
<?php
include("../../config/auth_admin.php");
include("../../config/database.php");

$page_title = " Quản lý ngành tuyển sinh";

include("../../assets/layout/header.php");

$keyword = $_GET['keyword'] ?? '';

$limit = 5;
$page = $_GET['page'] ?? 1;
$start = ($page - 1) * $limit;

$sql = "SELECT * FROM nganh 
        WHERE ten_nganh LIKE '%$keyword%' 
        OR ma_nganh LIKE '%$keyword%'
        LIMIT $start,$limit";

$result = $conn->query($sql);

$total_sql = "SELECT COUNT(*) as total FROM nganh 
              WHERE ten_nganh LIKE '%$keyword%' 
              OR ma_nganh LIKE '%$keyword%'";

$total_result = $conn->query($total_sql);
$total_row = $total_result->fetch_assoc();

$total_pages = ceil($total_row['total'] / $limit);
?>

<div class="container mt-4">

<a href="../../index.php" class="btn btn-light border mb-3">
⬅ Quay về 
</a>

<h3 class="mb-3">
<i class="bi bi-mortarboard"></i> <?= $page_title ?>
</h3>

<?php if(isset($_GET['success'])){ ?>
<div class="alert alert-success">
Thêm ngành thành công!
</div>
<?php } ?>

<?php if(isset($_GET['update'])){ ?>
<div class="alert alert-success">
Cập nhật ngành thành công!
</div>
<?php } ?>

<?php if(isset($_GET['delete'])){ ?>
<div class="alert alert-danger">
Ngành đã được xóa!
</div>
<?php } ?>

<form method="GET" class="mb-3">

<div class="row">

<div class="col-md-4">
<input type="text" name="keyword" class="form-control"
placeholder="Tìm theo tên hoặc mã ngành"
value="<?= $keyword ?>">
</div>

<div class="col-md-2">
<button class="btn btn-primary">
<i class="bi bi-search"></i> Tìm
</button>
</div>

</div>

</form>

<a href="them.php" class="btn btn-success mb-3">
<i class="bi bi-plus-circle"></i> Thêm ngành
</a>

<table class="table table-bordered table-hover">

<tr class="table-primary">
<th>ID</th>
<th>Mã ngành</th>
<th>Tên ngành</th>
<th>Chỉ tiêu</th>
<th>Học phí</th>
<th>Điểm chuẩn</th>
<th>Mô tả</th>
<th>Tổ hợp xét tuyển</th>
<th>Hành động</th>
</tr>

<?php while($row = $result->fetch_assoc()) { ?>

<tr>

<td><?= $row['id'] ?></td>
<td><?= $row['ma_nganh'] ?></td>
<td><?= $row['ten_nganh'] ?></td>
<td><?= $row['chi_tieu'] ?></td>
<td><?= number_format($row['hoc_phi']) ?></td>
<td><?= $row['diem_chuan'] ?></td>

<td 
data-bs-toggle="tooltip"
title="<?= $row['mo_ta'] ?>"
>
<?= mb_strimwidth($row['mo_ta'],0,50,"...") ?>
</td>

<td>

<?php

$sql_th = "SELECT ma_tohop 
           FROM nganh_tohop 
           WHERE nganh_id = ".$row['id'];

$result_th = $conn->query($sql_th);

while($th = $result_th->fetch_assoc()){
echo '<span class="badge bg-primary me-1">'.$th['ma_tohop'].'</span>';
}

?>

</td>

<td>

<a href="sua.php?id=<?= $row['id'] ?>" 
class="btn btn-warning btn-sm me-1">
<i class="bi bi-pencil-square"></i>
</a>

<a href="xoa.php?id=<?= $row['id'] ?>" 
class="btn btn-danger btn-sm"
onclick="return confirm('Bạn chắc chắn muốn xóa ngành này?')">
<i class="bi bi-trash"></i>
</a>

</td>

</tr>

<?php } ?>

</table>

<nav>

<ul class="pagination">

<?php for($i=1;$i<=$total_pages;$i++){ ?>

<li class="page-item <?= ($i==$page)?'active':'' ?>">

<a class="page-link" href="?page=<?= $i ?>&keyword=<?= $keyword ?>">
<?= $i ?>
</a>

</li>

<?php } ?>

</ul>

</nav>

</div>

<script>

var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))

var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
return new bootstrap.Tooltip(tooltipTriggerEl)
})

</script>

<?php include("../../assets/layout/footer.php"); ?>
