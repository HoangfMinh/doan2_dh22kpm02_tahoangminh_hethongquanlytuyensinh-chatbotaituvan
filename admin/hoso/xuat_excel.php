<?php
include("../../config/database.php");

header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=danhsach_hoso.xls");
header("Pragma: no-cache");
header("Expires: 0");

echo "\xEF\xBB\xBF"; 

$sql = "SELECT hoso.*, thisinh.ho_ten, thisinh.ngay_sinh,
thisinh.email, thisinh.so_dien_thoai, thisinh.dia_chi,
nganh.ten_nganh
FROM hoso
JOIN thisinh ON hoso.thisinh_id = thisinh.id
JOIN nganh ON hoso.nganh_id = nganh.id
ORDER BY hoso.id DESC";

$result = $conn->query($sql);
?>
<tr>
<td colspan="13" style="text-align:center;font-weight:bold;font-size:18px">
DANH SÁCH HỒ SƠ XÉT TUYỂN
</td>
</tr>
<table border="1">

<tr>
<th>ID</th>
<th>Họ tên</th>
<th>Ngày sinh</th>
<th>Email</th>
<th>SĐT</th>
<th>Địa chỉ</th>
<th>Ngành</th>
<th>Điểm 1</th>
<th>Điểm 2</th>
<th>Điểm 3</th>
<th>Tổng điểm</th>
<th>Ngày nộp</th>
<th>Trạng thái</th>
</tr>

<?php while($row = $result->fetch_assoc()){ ?>

<tr>
<td><?= $row['id'] ?></td>
<td><?= $row['ho_ten'] ?></td>
<td><?= $row['ngay_sinh'] ?></td>
<td><?= $row['email'] ?></td>
<td><?= $row['so_dien_thoai'] ?></td>
<td><?= $row['dia_chi'] ?></td>
<td><?= $row['ten_nganh'] ?></td>
<td><?= $row['diem_mon1'] ?></td>
<td><?= $row['diem_mon2'] ?></td>
<td><?= $row['diem_mon3'] ?></td>
<td><?= $row['tong_diem'] ?></td>
<td><?= $row['ngay_nop'] ?></td>
<td><?= $row['trang_thai'] ?></td>
</tr>

<?php } ?>

</table>
<tr>
<td colspan="13">
Ngày xuất: <?= date("d/m/Y") ?>
</td>
</tr>