<?php
    require_once 'koneksi.php';

    session_start();

    $email = $_POST['email'];
    $password = $_POST['password'];
    $hashed_password = hash("md5", $password);

    $query = "SELECT * FROM user WHERE email = '$email' AND password = '$hashed_password'";
    
    $result = $sambung->query($query);

    if($result->num_rows>0){
        $_SESSION['email'] = $email;
        foreach ($result as $data) {
            $_SESSION['nama'] = $data['nama'];
        }
        header("location: home.php");
    } else {
        echo "<script>alert('Username atau password salah'); window.location.href = 'login.php';</script>";
    }

    $sambung->close();
?>