<?php
include 'config.php';
$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM sumber_ternak WHERE id = '$id'");
header("Location: index.php?pesan=hapus_sukses");
exit();
?>