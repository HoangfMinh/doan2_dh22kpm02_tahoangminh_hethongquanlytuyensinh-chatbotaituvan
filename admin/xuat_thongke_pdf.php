<?php
require_once "../config/database.php";
require_once "../tcpdf/tcpdf.php";

/* thống kê */

$sql_nganh = "SELECT COUNT(*) AS tong FROM nganh";
$row_nganh = $conn->query($sql_nganh)->fetch_assoc();

$sql_hoso = "SELECT COUNT(*) AS tong FROM hoso";
$row_hoso = $conn->query($sql_hoso)->fetch_assoc();

$sql_dau = "SELECT COUNT(*) AS tong FROM hoso WHERE trang_thai='Đậu'";
$row_dau = $conn->query($sql_dau)->fetch_assoc();

$sql_rot = "SELECT COUNT(*) AS tong FROM hoso WHERE trang_thai='Rớt'";
$row_rot = $conn->query($sql_rot)->fetch_assoc();

$sql_cho = "SELECT COUNT(*) AS tong FROM hoso WHERE trang_thai='Chờ duyệt'";
$row_cho = $conn->query($sql_cho)->fetch_assoc();


$pdf = new TCPDF();

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor("Hệ thống tuyển sinh");
$pdf->SetTitle("Báo cáo thống kê");

$pdf->AddPage();

$pdf->SetFont("dejavusans","",12);

$html = '

<h2 style="text-align:center">
BÁO CÁO THỐNG KÊ TUYỂN SINH
</h2>

<p>Ngày xuất: '.date("d/m/Y").'</p>

<br>

<table border="1" cellpadding="6">

<tr>
<th width="70%">Nội dung</th>
<th width="30%">Số lượng</th>
</tr>

<tr>
<td>Tổng ngành đào tạo</td>
<td>'.$row_nganh['tong'].'</td>
</tr>

<tr>
<td>Tổng hồ sơ</td>
<td>'.$row_hoso['tong'].'</td>
</tr>

<tr>
<td>Hồ sơ trúng tuyển</td>
<td>'.$row_dau['tong'].'</td>
</tr>

<tr>
<td>Hồ sơ bị từ chối</td>
<td>'.$row_rot['tong'].'</td>
</tr>

<tr>
<td>Hồ sơ chờ duyệt</td>
<td>'.$row_cho['tong'].'</td>
</tr>

</table>

<br><br>

<p style="text-align:right">
HỘI ĐỒNG TUYỂN SINH
<br><br><br>
(Ký và ghi rõ họ tên)
</p>

';

$pdf->writeHTML($html,true,false,true,false,'');

$pdf->Output("baocao_thongke.pdf","I");