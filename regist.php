<?php
    require_once 'koneksi.php';
    session_start();
    if(isset($_SESSION['email'])){
      header("location: home.php");
      exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register</title>
 
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/page.css">
    
    <style media="screen">
            *,
        *:before,
        *:after{
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }
        body{
            background-color: rgb(255, 179, 153);
        }
        .background{
            width: 430px;
            height: 520px;
            position: absolute;
            transform: translate(-50%,-50%);
            left: 50%;
            top: 50%;
        }

        form{
            height: 660px;
            width: 400px;
            background-color: rgba(255,255,255,0.13);
            position: absolute;
            transform: translate(-50%,-50%);
            top: 50%;
            left: 50%;
            border-radius: 10px;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255,255,255,0.1);
            box-shadow: 0 0 40px rgba(8,7,16,0.6);
            padding: 50px 35px;
        }
        form *{
            font-family: 'Poppins',sans-serif;
            color: #080710;
            letter-spacing: 0.5px;
            outline: none;
            border: none;
        }
        form h3{
            font-size: 32px;
            font-weight: 500;
            line-height: 42px;
            text-align: center;
        }

        label{
            display: block;
            margin-top: 30px;
            font-size: 16px;
            font-weight: 500;
        }
        input{
            display: block;
            height: 50px;
            width: 100%;
            background-color: rgba(67, 60, 60, 0.07);
            border-radius: 3px;
            padding: 0 10px;
            margin-top: 8px;
            font-size: 14px;
            font-weight: 300;
        }
        ::placeholder{
            color: #080710;
        }
        button{
            margin-top: 15px;
            width: 100%;
            background-color: #847d7d;
            color: #080710;
            padding: 15px 0;
            font-size: 18px;
            font-weight: 600;
            border-radius: 5px;
            cursor: pointer;
        }
        .social{
        margin-top: 30px;
        display: flex;
        }
        .social div{
        background: red;
        width: 150px;
        border-radius: 3px;
        padding: 5px 10px 10px 5px;
        background-color: rgba(67, 60, 60,0.27);
        color: #080710;
        text-align: center;
        }
        .social div:hover{
        background-color: rgba(67, 60, 60,0.47);
        }
        .social .fb{
        margin-left: 25px;
        }
        .social i{
        margin-right: 4px;
        }

    </style>
</head>
<body>
    <div class="background">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    <form action="registproses.php" method="post">
        <h3>Register Here</h3>

        <label for="name">Name</label>
        <input type="text" placeholder="Name" id="name" name="name">

        <label for="email">Email</label>
        <input type="text" placeholder="Email" id="email" name="email">

        <label for="password">Password</label>
        <input type="password" placeholder="Password" id="password" name="password">

        <label for="password2">Confirm Password</label>
        <input type="password" placeholder="Confirm Password" id="password2" name="password2">

        <button type="submit" value="login">Register Now</button>
        <br>
        <a href="login.php">Sudah memiliki akun? Login disini!</a>
    </form>
</body>
</html>
