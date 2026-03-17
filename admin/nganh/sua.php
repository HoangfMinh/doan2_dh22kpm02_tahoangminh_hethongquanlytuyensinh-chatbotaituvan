```php
<?php
include("../../config/auth_admin.php");
include("../../config/database.php");

$id = $_GET['id'];

$page_title = "✏️ Sửa ngành tuyển sinh";

// xử lý cập nhật
if(isset($_POST['ma_nganh'])){

$ma_nganh = $_POST['ma_nganh'];
$ten_nganh = $_POST['ten_nganh'];
$chi_tieu = $_POST['chi_tieu'];
$hoc_phi = $_POST['hoc_phi'];
$diem_chuan = $_POST['diem_chuan'];
$mo_ta = $_POST['mo_ta'];

$conn->query("UPDATE nganh SET
ma_nganh='$ma_nganh',
ten_nganh='$ten_nganh',
chi_tieu='$chi_tieu',
hoc_phi='$hoc_phi',
diem_chuan='$diem_chuan',
mo_ta='$mo_ta'
WHERE id=$id");

/* XÓA TỔ HỢP CŨ */
$conn->query("DELETE FROM nganh_tohop WHERE nganh_id=$id");

/* THÊM TỔ HỢP MỚI */
if(isset($_POST['tohop'])){
foreach($_POST['tohop'] as $th){

$conn->query("INSERT INTO nganh_tohop
(nganh_id,ma_tohop)
VALUES
($id,'$th')");

}
}

header("Location: index.php?update=success");
exit;

}

$sql = "SELECT * FROM nganh WHERE id=$id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

$sql_th = "SELECT * FROM tohop";
$result_th = $conn->query($sql_th);

$selected = [];

$sql_nt = "SELECT ma_tohop FROM nganh_tohop WHERE nganh_id=$id";
$result_nt = $conn->query($sql_nt);

while($r = $result_nt->fetch_assoc()){
$selected[] = $r['ma_tohop'];
}

include("../../assets/layout/header.php");
?>

<div class="container mt-4">

<a href="index.php" class="btn btn-light border mb-3">
⬅ Quay lại quản lý ngành
</a>

<h3 class="mb-4">
<i class="bi bi-pencil-square"></i> <?= $page_title ?>
</h3>

<div class="card shadow">

<div class="card-body">

<form method="POST">

<div class="mb-3">
<label>Mã ngành</label>
<input type="text" name="ma_nganh"
value="<?= $row['ma_nganh'] ?>"
class="form-control">
</div>

<div class="mb-3">
<label>Tên ngành</label>
<input type="text" name="ten_nganh"
value="<?= $row['ten_nganh'] ?>"
class="form-control">
</div>

<div class="mb-3">
<label>Chỉ tiêu</label>
<input type="number" name="chi_tieu"
value="<?= $row['chi_tieu'] ?>"
class="form-control">
</div>

<div class="mb-3">
<label>Học phí</label>
<input type="number" name="hoc_phi"
value="<?= $row['hoc_phi'] ?>"
class="form-control">
</div>

<div class="mb-3">
<label>Điểm chuẩn</label>
<input type="number" step="0.01"
name="diem_chuan"
value="<?= $row['diem_chuan'] ?>"
class="form-control">
</div>

<h5 class="mt-4">Tổ hợp xét tuyển</h5>

<?php while($th = $result_th->fetch_assoc()) { ?>

<div class="form-check">

<input
class="form-check-input"
type="checkbox"
name="tohop[]"
value="<?= $th['ma_tohop'] ?>"
<?= in_array($th['ma_tohop'],$selected) ? 'checked' : '' ?>
>

<label class="form-check-label">

<?= $th['ma_tohop'] ?>
(<?= $th['mon1'] ?> - <?= $th['mon2'] ?> - <?= $th['mon3'] ?>)

</label>

</div>

<?php } ?>

<div class="mb-3 mt-3">
<label>Mô tả ngành</label>

<textarea
name="mo_ta"
class="form-control"
rows="3"
><?= $row['mo_ta'] ?></textarea>

</div>

<button class="btn btn-primary">
<i class="bi bi-save"></i> Cập nhật
</button>

<a href="index.php" class="btn btn-secondary">
Hủy
</a>

</form>

</div>

</div>

</div>

<?php include("../../assets/layout/footer.php"); ?>
```
