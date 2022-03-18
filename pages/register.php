<?php
@include 'config.php';

if (isset($_POST['submit'])) {
    $filter_name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $name = mysqli_real_escape_string($conn, $filter_name);
    $filter_email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    $email = mysqli_real_escape_string($conn, $filter_email);
    $filter_pass = filter_var($_POST['pass'], FILTER_SANITIZE_STRING);
    $pass = mysqli_real_escape_string($conn, md5($filter_pass));
    $filter_cpass = filter_var($_POST['cpass'], FILTER_SANITIZE_STRING);
    $cpass = mysqli_real_escape_string($conn, md5($filter_cpass));

    $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email'") or die('query faliled');

    if (mysqli_num_rows($select_users) > 0) {
        $message[] = 'usuário já existe!!!';
    } else {
        if ($pass != $cpass) {
            $message[] = 'senha não confere!';
        } else {
            mysqli_query($conn, "INSERT INTO `users` (name, email, password)
            VALUES ('$name','$email','$pass')") or die('query falhou ao registrar');
            $message[] = 'Usuário cadastrado com sucesso!';
            header('location:login.php');
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" />
    <link rel="stylesheet" href="../css/style.css">
    <link rel="shortcut icon" type="img/x-icon" href="../img/kalangos.ico">
</head>

<body>

    <?php
    if (isset($message)) {
        foreach ($message as $message) {
            echo '
            <div class="message">
                <span>' . $message . '</span>
                <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
            </div>
            ';
        }
    }
    ?>
    <section class="form-container">
        <form action="" method="POST">
            <h3>Cadastre-se Agora</h3>
            <input type="text" name="name" id="" class="box" placeholder="seu usuário" required>
            <input type="email" name="email" id="" class="box" placeholder="seu email" required>
            <input type="password" name="pass" id="" class="box" placeholder="sua senha" required>
            <input type="password" name="cpass" id="" class="box" placeholder="confirme sua senha" required>
            <input type="submit" value="registre agora" class="btn" name="submit">
            <p>já tem uma conta? <a href="login.php">login aqui!</a></p>
        </form>
    </section>
</body>

</html>