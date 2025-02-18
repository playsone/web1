<?php
session_start();
include "DB_W1.php"; 

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $std_id = trim($_POST['stdid']);
    $password = trim($_POST['password']); 

    if (!empty($std_id) && !empty($password)) {
        
        $sql =  "SELECT * FROM tb_student WHERE stdid = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $std_id); 
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $stored_password = $row['password']; 

            if (password_needs_rehash($stored_password, PASSWORD_BCRYPT)) {
                if ($password === $stored_password) {
                    $new_hashed_password = password_hash($password, PASSWORD_BCRYPT);
                    $update_sql = "UPDATE tb_student SET password = ? WHERE stdid = ?";
                    $update_stmt = $conn->prepare($update_sql);
                    $update_stmt->bind_param("ss", $new_hashed_password, $std_id);
                    $update_stmt->execute();
                    $update_stmt->close();
                    
                    $_SESSION['stdid'] = $std_id;
                    echo "<script>alert('เข้าสู่ระบบสำเร็จ! (รหัสผ่านได้รับการอัปเดตเป็นแบบเข้ารหัส)'); window.location='view.php';</script>";
                    exit();
                }
            } else {
                if (password_verify($password, $stored_password)) {
                    $_SESSION['stdid'] = $std_id;
                    echo "<script>alert('เข้าสู่ระบบสำเร็จ!'); window.location='view.php';</script>";
                    exit();
                }
            }

            $error_message = "❌ รหัสนิสิตหรือรหัสผ่านไม่ถูกต้อง!";
        } else {
            $error_message = "❌ ไม่พบรหัสนิสิตนี้!";
        }
        
        $stmt->close();
    } else {
        $error_message = "⚠️ กรุณากรอกข้อมูลให้ครบถ้วน!";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-control {
            padding-left: 2.5rem;
        }
        .input-group-text {
            width: 2.5rem;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="login-container">
        <h2 class="text-center mb-4">🔑 เข้าสู่ระบบ</h2>

        <?php if (!empty($error_message)) { ?>
            <div class="alert alert-danger text-center" role="alert">
                <?php echo $error_message; ?>
            </div>
        <?php } ?>

        <form method="post" action="index.php">
            <div class="mb-3">
                <label class="form-label">รหัสนิสิต</label>
                <div class="input-group">
                    <span class="input-group-text">🎓</span>
                    <input type="text" name="stdid" class="form-control" required>
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">รหัสผ่าน</label>
                <div class="input-group">
                    <span class="input-group-text">🔒</span>
                    <input type="password" name="password" class="form-control" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100">เข้าสู่ระบบ</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
