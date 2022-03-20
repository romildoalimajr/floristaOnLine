<?php
@include 'config.php';

session_start();

if (isset($_POST['submit'])) {
    
    $filter_email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    $email = mysqli_real_escape_string($conn, $filter_email);
    $filter_pass = filter_var($_POST['pass'], FILTER_SANITIZE_STRING);
    $pass = mysqli_real_escape_string($conn, md5($filter_pass));

    $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email'") or die('query faliled');

    if (mysqli_num_rows($select_users) > 0) {
       
        $row = mysqli_fetch_assoc($select_users);

        if($row['user_type'] == 'admin'){
            $_SESSION['admin_name'] = $row['name'];
            $_SESSION['admin_email'] = $row['email'];
            $_SESSION['admin_id'] = $row['id'];
            header('location:admin_page.php');

        }elseif($row['user_type'] == 'user'){
            $_SESSION['user_name'] = $row['name'];
            $_SESSION['user_email'] = $row['email'];
            $_SESSION['user_id'] = $row['id'];
            header('location:home.php');
        }
    }else{
        $message[] = 'email ou senha errado!';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" />
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" type="img/x-icon" href="img/kalangos.ico">
</head>
<body>
<section class="form-container">
        <form action="" method="POST">
            <h3>Faça seu login</h3>
            
            <input type="email" name="email" id="" class="box" placeholder="seu email" required>
            <input type="password" name="pass" id="" class="box" placeholder="sua senha" required>
            <input type="submit" value="entrar agora" class="btn" name="submit">
            <p>você não tem uma conta? <a href="register.php">faça aqui!</a></p>
        </form> 
    </section>
</body>
</html>