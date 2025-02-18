<?php
session_start();
if (!isset($_SESSION['stdid'])) {
    header("Location: index.php");
    exit();
}

include "DB_W1.php"; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  
    $std_id = trim($_POST['stdid']);
    $std_prefix = trim($_POST['prefix']);
    $std_fname = trim($_POST['fname']);
    $std_lname = trim($_POST['lname']);
    $std_class = trim($_POST['class']);
    $std_gpa = trim($_POST['gpa']);
    $std_brithday = trim($_POST['brithday']);
    $password = trim($_POST['password']);  

 
    $sql_check = "SELECT * FROM tb_student WHERE stdid = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $std_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        echo "<script>alert('รหัสนิสิตนี้มีอยู่ในระบบแล้ว กรุณากรอกรหัสนิสิตใหม่');</script>";
    } else {
        // เข้ารหัสรหัสผ่านก่อนบันทึกลงฐานข้อมูล
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // เพิ่มข้อมูลเข้า Database
        $sql = "INSERT INTO tb_student (stdid, prefix, fname, lname, class, gpa, brithday, password) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssss", $std_id, $std_prefix, $std_fname, $std_lname, $std_class, $std_gpa, $std_brithday, $hashed_password);

        if ($stmt->execute()) {
            echo "<script>alert('บันทึกข้อมูลสำเร็จ!'); window.location='view.php';</script>";
        } else {
            echo "<script>alert('เกิดข้อผิดพลาดในการบันทึกข้อมูล!');</script>";
        }

        $stmt->close();
    }

    $stmt_check->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มข้อมูลนิสิต</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .form-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

<div class="container">
    <div class="form-container">
        <h2 class="text-center">เพิ่มข้อมูลนิสิต</h2>
        <form method="post" action="add.php">
            <div class="mb-3">
                <label class="form-label">รหัสนิสิต</label>
                <input type="text" name="stdid" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">คำนำหน้า</label>
                <input type="text" name="prefix" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">ชื่อ</label>
                <input type="text" name="fname" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">นามสกุล</label>
                <input type="text" name="lname" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">ชั้นปี</label>
                <input type="text" name="class" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">เกรดเฉลี่ย</label>
                <input type="text" name="gpa" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">วันเกิด</label>
                <input type="date" name="brithday" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">รหัสผ่าน</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success w-100">บันทึกข้อมูล</button>
            <a href="view.php" class="btn btn-secondary w-100 mt-2">กลับ</a>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
