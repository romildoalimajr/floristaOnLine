<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>orders</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <link rel="shortcut icon" type="img/x-icon" href="img/kalangos.ico">

</head>
<body>
   
<?php @include 'header.php'; ?>

<section class="heading">
    <h3>seus pedidos</h3>
    <p> <a href="home.php">home</a> / pedido </p>
</section>

<section class="placed-orders">

    <h1 class="title">pedidos efetuados</h1>

    <div class="box-container">

    <?php
        $select_orders = mysqli_query($conn, "SELECT * FROM `orders` WHERE user_id = '$user_id'") or die('query failed');
        if(mysqli_num_rows($select_orders) > 0){
            while($fetch_orders = mysqli_fetch_assoc($select_orders)){
    ?>
    <div class="box">
        <p> data do pedido : <span><?php echo $fetch_orders['placed_on']; ?></span> </p>
        <p> nome : <span><?php echo $fetch_orders['name']; ?></span> </p>
        <p> telefone : <span><?php echo $fetch_orders['number']; ?></span> </p>
        <p> email : <span><?php echo $fetch_orders['email']; ?></span> </p>
        <p> endereço : <span><?php echo $fetch_orders['address']; ?></span> </p>
        <p> metódo de pagamento : <span><?php echo $fetch_orders['method']; ?></span> </p>
        <p> seus pedidos : <span><?php echo $fetch_orders['total_products']; ?></span> </p>
        <p> valor total : <span>R$<?php echo $fetch_orders['total_price']; ?>/-</span> </p>
        <p> status do pagamento : <span style="color:<?php if($fetch_orders['payment_status'] == 'pending'){echo 'tomato'; }else{echo 'green';} ?>"><?php echo $fetch_orders['payment_status']; ?></span> </p>
    </div>
    <?php
        }
    }else{
        echo '<p class="empty">nenhum pedido efetuado!</p>';
    }
    ?>
    </div>

</section>







<?php @include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>