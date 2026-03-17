<?php
include("../../config/database.php");

$id = $_GET['id'];

$sql = "DELETE FROM nganh WHERE id = $id";

$conn->query($sql);

header("Location: index.php?delete=success");
exit;
?>