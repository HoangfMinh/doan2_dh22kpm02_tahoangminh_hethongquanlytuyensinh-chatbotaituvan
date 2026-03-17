<?php
include("../../config/auth_admin.php");
include("../../config/database.php");
include("../../config/mail.php");



$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$st = isset($_GET['st']) ? $_GET['st'] : "";

if($id <= 0){
    die("ID hồ sơ không hợp lệ");
}

if($st != "Đậu" && $st != "Rớt"){
    die("Trạng thái không hợp lệ");
}



$sql = "UPDATE hoso SET trang_thai='$st' WHERE id=$id";

if(!$conn->query($sql)){
    die("Lỗi cập nhật trạng thái");
}



$sql2 = "SELECT t.email, t.ho_ten, n.ten_nganh
FROM hoso h
JOIN thisinh t ON h.thisinh_id = t.tai_khoan_id
JOIN nganh n ON h.nganh_id = n.id
WHERE h.id=$id";

$result = $conn->query($sql2);

if(!$result || $result->num_rows == 0){
    die("Không tìm thấy thông tin thí sinh");
}

$row = $result->fetch_assoc();

$email = $row['email'];
$ten   = $row['ho_ten'];
$nganh = $row['ten_nganh'];



if($st == "Đậu"){
    $ketqua = "<span style='color:green;font-size:20px'>
                <b>CHÚC MỪNG! BẠN ĐÃ TRÚNG TUYỂN</b>
               </span>";
}else{
    $ketqua = "<span style='color:red;font-size:20px'>
                <b>RẤT TIẾC BẠN CHƯA TRÚNG TUYỂN</b>
               </span>";
}

$email_body = "
<div style='font-family:Arial;padding:20px'>

<h2 style='color:#0d6efd'>
Thông báo kết quả xét tuyển
</h2>

<p>Xin chào <b>$ten</b>,</p>

<p>
Hồ sơ đăng ký ngành <b>$nganh</b> của bạn đã được xét.
</p>

<p>Kết quả:</p>

<p>$ketqua</p>

<p>
Vui lòng đăng nhập hệ thống tuyển sinh để xem chi tiết.
</p>

<br>

<p>Trân trọng</p>

<p><b>$mail_name</b></p>

</div>
";


require '../../vendor/PHPMailer/src/PHPMailer.php';
require '../../vendor/PHPMailer/src/SMTP.php';
require '../../vendor/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);
$mail->CharSet = 'UTF-8';
$mail->Encoding = 'base64';
try{

    $mail->isSMTP();
    $mail->Host       = $mail_host;
    $mail->SMTPAuth   = true;
    $mail->Username   = $mail_user;
    $mail->Password   = $mail_pass;
    $mail->SMTPSecure = 'tls';
    $mail->Port       = $mail_port;

    $mail->setFrom($mail_user, $mail_name);

    $mail->addAddress($email, $ten);

    $mail->isHTML(true);

    $mail->Subject = "Kết quả xét tuyển ngành $nganh";

    $mail->Body = $email_body;

    $mail->send();

}catch(Exception $e){

    echo "Không gửi được email. Lỗi: {$mail->ErrorInfo}";

}


header("Location: index.php");
exit;

?>