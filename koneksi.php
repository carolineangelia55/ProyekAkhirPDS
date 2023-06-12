<?php

$sambung = mysqli_connect("localhost", "root", "", "pds");

if (!$sambung) { // jika tidak terkoneksi maka menampilkan pesan error
    die("Gagal terhubung ke database : " . mysqli_connect_errno());
}