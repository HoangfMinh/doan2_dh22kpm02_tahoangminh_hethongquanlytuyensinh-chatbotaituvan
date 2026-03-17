<?php
include("../../config/database.php");
?>

<h2>Xuất PDF</h2>

<br>

<a href="xuat_pdf.php" style="padding:10px 20px;background:#28a745;color:white;text-decoration:none;">
Xuất danh sách trúng tuyển
</a>

<br><br>

<form action="pdf_canhan.php" method="GET">

<label>Nhập ID hồ sơ:</label>
<input type="number" name="id" required>

<button type="submit">
Xuất PDF cá nhân
</button>

</form>