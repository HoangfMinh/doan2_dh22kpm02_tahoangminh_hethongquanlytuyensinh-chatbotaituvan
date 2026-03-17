<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../tcpdf/tcpdf.php';

$sql = "SELECT hoso.*, thisinh.ho_ten, thisinh.ngay_sinh,
thisinh.email, thisinh.so_dien_thoai,
nganh.ten_nganh
FROM hoso
JOIN thisinh ON hoso.thisinh_id = thisinh.id
JOIN nganh ON hoso.nganh_id = nganh.id
WHERE hoso.trang_thai='Đậu'
ORDER BY hoso.id DESC";

$result = mysqli_query($conn, $sql);

$pdf = new TCPDF();

/* thông tin file */
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Hệ thống tuyển sinh');
$pdf->SetTitle('Danh sách trúng tuyển');

/* margin */
$pdf->SetMargins(15, 20, 15);
$pdf->SetAutoPageBreak(TRUE,15);

/* trang ngang để bảng rộng hơn */
$pdf->AddPage('L');

/* font tiếng Việt */
$pdf->SetFont('dejavusans','',9);

/* logo */
$logo = __DIR__ . '/../../assets/images/channels4_profile.jpg';
if(file_exists($logo)){
    $pdf->Image($logo, 15, 15, 25);
}

$html = '

<h3 style="text-align:center;">
TRƯỜNG ĐẠI HỌC NAM CẦN THƠ
</h3>

<h2 style="text-align:center;">
DANH SÁCH THÍ SINH TRÚNG TUYỂN
</h2>

<p style="text-align:right;">
Ngày xuất: '.date("d/m/Y").'
</p>

<br>

<table border="1" cellpadding="4">
<tr style="background-color:#f2f2f2;">
<th width="30"><b>STT</b></th>
<th width="90"><b>Họ tên</b></th>
<th width="60"><b>Ngày sinh</b></th>
<th width="130"><b>Email</b></th>
<th width="80"><b>SĐT</b></th>
<th width="120"><b>Ngành</b></th>
<th width="60"><b>Tổng điểm</b></th>
</tr>
';

$stt = 1;

while($row = mysqli_fetch_assoc($result)){

$html .= '
<tr>
<td>'.$stt++.'</td>
<td>'.$row['ho_ten'].'</td>
<td>'.$row['ngay_sinh'].'</td>
<td>'.$row['email'].'</td>
<td>'.$row['so_dien_thoai'].'</td>
<td>'.$row['ten_nganh'].'</td>
<td>'.$row['tong_diem'].'</td>
</tr>
';

}

$total = $stt - 1;

$html .= '
</table>

<br>
<b>Tổng số thí sinh trúng tuyển: '.$total.'</b>

<br><br><br>

<table width="100%">
<tr>
<td width="60%"></td>
<td width="40%" style="text-align:center;">
<b>HỘI ĐỒNG TUYỂN SINH</b>
<br><br><br><br>
(Ký và ghi rõ họ tên)
</td>
</tr>
</table>
';

$pdf->writeHTML($html,true,false,true,false,'');

/* Footer số trang */
$pdf->SetFont('dejavusans','',8);
$pdf->setFooterMargin(10);
$pdf->setPrintFooter(true);
$pdf->setFooterData();
$pdf->setFooterFont(array('dejavusans','',8));
$pdf->setPrintFooter(true);
$pdf->Output('danhsach_trungtuyen.pdf','I');
?>