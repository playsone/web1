<?php
session_start();
if (!isset($_SESSION['stdid'])) {
    header("Location: index.php");
    exit();
}
?>

<?php
include "DB_W1.php"; 

if (isset($_GET['stdid'])) {
    $std_id = $_GET['stdid'];
    $sql = "DELETE FROM tb_student WHERE stdid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $std_id);
    
    if ($stmt->execute()) {
        echo "<script>alert('ลบข้อมูลสำเร็จ!'); window.location='view.php';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาดในการลบข้อมูล!'); window.location='view.php';</script>";
    }
    
    $stmt->close();
}
$conn->close();
?>
