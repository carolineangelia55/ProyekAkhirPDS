<?php
    require_once 'koneksi.php';

    session_start();

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $hashed_password = hash("md5", $password);
    $password2 = $_POST['password2'];
    $hashed_password2 = hash("md5", $password2);

    if ($hashed_password2 == $hashed_password) {
    mysqli_query($sambung, "INSERT INTO user (`nama`, `email`, `password`) 
    VALUES ('$name','$email','$hashed_password')");
    header("location: home.php");
    } else {
        echo "<script>alert('Password tidak match!'); window.location.href = 'regist.php';</script>";
    }
    $sambung->close();
?>