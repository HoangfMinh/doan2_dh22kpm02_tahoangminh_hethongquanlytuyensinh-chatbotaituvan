<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../tcpdf/tcpdf.php';

$sql = "SELECT hoso.*, thisinh.ho_ten, thisinh.ngay_sinh,
thisinh.email, thisinh.so_dien_thoai,
nganh.ten_nganh
FROM hoso
JOIN thisinh ON hoso.thisinh_id = thisinh.id
JOIN nganh ON hoso.nganh_id = nganh.id
WHERE hoso.trang_thai='Đậu'";

$result = mysqli_query($conn,$sql);

$pdf = new TCPDF();
$pdf->SetFont('dejavusans','',12);

$logo = __DIR__ . '/../../assets/images/channels4_profile.jpg';

while($row = mysqli_fetch_assoc($result)){

$pdf->AddPage();

if(file_exists($logo)){
$pdf->Image($logo,15,10,30);
}

$html='

<h2 style="text-align:center;">GIẤY BÁO TRÚNG TUYỂN</h2>

<br><br>

<table border="1" cellpadding="6">

<tr>
<td width="40%">Họ tên</td>
<td>'.$row['ho_ten'].'</td>
</tr>

<tr>
<td>Ngày sinh</td>
<td>'.$row['ngay_sinh'].'</td>
</tr>

<tr>
<td>Email</td>
<td>'.$row['email'].'</td>
</tr>

<tr>
<td>Số điện thoại</td>
<td>'.$row['so_dien_thoai'].'</td>
</tr>

<tr>
<td>Ngành trúng tuyển</td>
<td>'.$row['ten_nganh'].'</td>
</tr>

<tr>
<td>Tổng điểm</td>
<td>'.$row['tong_diem'].'</td>
</tr>

</table>

<br><br>

<p>Chúc mừng bạn đã trúng tuyển vào <b>Trường Đại học Nam Cần Thơ</b>.</p>

<br><br><br>

<p style="text-align:right;">
<b>HỘI ĐỒNG TUYỂN SINH</b>
<br><br><br>
(Ký tên)
</p>

';

$pdf->writeHTML($html);

}

$pdf->Output('giaybao_trungtuyen.pdf','I');
?>