<?php
include("../../config/auth_admin.php");
include("../../config/database.php");

if($_SERVER["REQUEST_METHOD"] == "POST"){

$ma_nganh = $_POST["ma_nganh"];
$ten_nganh = $_POST["ten_nganh"];
$chi_tieu = $_POST["chi_tieu"];
$hoc_phi = $_POST["hoc_phi"];
$diem_chuan = $_POST["diem_chuan"];
$mo_ta = $_POST["mo_ta"];


$sql = "INSERT INTO nganh 
(ma_nganh, ten_nganh, chi_tieu, hoc_phi, diem_chuan, mo_ta)
VALUES
('$ma_nganh','$ten_nganh','$chi_tieu','$hoc_phi','$diem_chuan','$mo_ta')";

$conn->query($sql);

$nganh_id = $conn->insert_id;



if(!empty($_POST["tohop"])){

foreach($_POST["tohop"] as $th){

$sql_th = "INSERT INTO nganh_tohop (nganh_id, ma_tohop)
VALUES ('$nganh_id','$th')";

$conn->query($sql_th);

}

}

header("Location: index.php?success=1");
exit();
}

include("../../assets/layout/header.php");

?>

<div class="container mt-4">

<div class="card shadow">

<div class="card-header bg-primary text-white">
<h4 class="mb-0">Thêm ngành tuyển sinh</h4>
</div>

<div class="card-body">

<form method="POST">

<div class="row">

<div class="col-md-6 mb-3">
<label>Mã ngành</label>
<input type="text" name="ma_nganh" class="form-control" required>
</div>

<div class="col-md-6 mb-3">
<label>Tên ngành</label>
<input type="text" name="ten_nganh" class="form-control" required>
</div>

</div>

<div class="row">

<div class="col-md-4 mb-3">
<label>Chỉ tiêu</label>
<input type="number" name="chi_tieu" class="form-control">
</div>

<div class="col-md-4 mb-3">
<label>Học phí</label>
<input type="number" name="hoc_phi" class="form-control">
</div>

<div class="col-md-4 mb-3">
<label>Điểm chuẩn</label>
<input type="number" step="0.1" name="diem_chuan" class="form-control">
</div>

</div>

<div class="mb-3">

<label class="form-label">Tổ hợp xét tuyển</label>

<div class="border rounded p-3">

<?php
$sql = "SELECT * FROM tohop";
$result = $conn->query($sql);

while($row = $result->fetch_assoc()){
?>

<div class="form-check">

<input class="form-check-input"
type="checkbox"
name="tohop[]"
value="<?= $row['ma_tohop'] ?>">

<label class="form-check-label">

<b><?= $row['ma_tohop'] ?></b>
(<?= $row['mon1'] ?> - <?= $row['mon2'] ?> - <?= $row['mon3'] ?>)

</label>

</div>

<?php } ?>

</div>

</div>

<div class="mb-3">
<label>Mô tả ngành</label>
<textarea name="mo_ta" class="form-control" rows="4"></textarea>
</div>

<button class="btn btn-primary">
Thêm ngành
</button>

<a href="index.php" class="btn btn-secondary">
Quay về
</a>

</form>

</div>

</div>

</div>

<?php include("../../assets/layout/footer.php"); ?>