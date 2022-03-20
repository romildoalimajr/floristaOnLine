<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_POST['order'])){

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $number = mysqli_real_escape_string($conn, $_POST['number']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $method = mysqli_real_escape_string($conn, $_POST['method']);
    $address = mysqli_real_escape_string($conn, 'flat no. '. $_POST['flat'].', '. $_POST['street'].', '. $_POST['city'].', '. $_POST['country'].' - '. $_POST['pin_code']);
    $placed_on = date('d-M-Y');

    $cart_total = 0;
    $cart_products[] = '';

    $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
    if(mysqli_num_rows($cart_query) > 0){
        while($cart_item = mysqli_fetch_assoc($cart_query)){
            $cart_products[] = $cart_item['name'].' ('.$cart_item['quantity'].') ';
            $sub_total = ($cart_item['price'] * $cart_item['quantity']);
            $cart_total += $sub_total;
        }
    }

    $total_products = implode(', ',$cart_products);

    $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE name = '$name' AND number = '$number' AND email = '$email' AND method = '$method' AND address = '$address' AND total_products = '$total_products' AND total_price = '$cart_total'") or die('query failed');

    if($cart_total == 0){
        $message[] = 'seu carrinho está vazio!';
    }elseif(mysqli_num_rows($order_query) > 0){
        $message[] = 'pedido já feito!';
    }else{
        mysqli_query($conn, "INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price, placed_on) VALUES('$user_id', '$name', '$number', '$email', '$method', '$address', '$total_products', '$cart_total', '$placed_on')") or die('query failed');
        mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
        $message[] = 'pedido feito com sucesso!';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>checkout</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <link rel="shortcut icon" type="img/x-icon" href="img/kalangos.ico">

</head>
<body>
   
<?php @include 'header.php'; ?>

<section class="heading">
    <h3>checkout do pedido</h3>
    <p> <a href="home.php">home</a> / checkout </p>
</section>

<section class="display-order">
    <?php
        $grand_total = 0;
        $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
        if(mysqli_num_rows($select_cart) > 0){
            while($fetch_cart = mysqli_fetch_assoc($select_cart)){
            $total_price = ($fetch_cart['price'] * $fetch_cart['quantity']);
            $grand_total += $total_price;
    ?>    
    <p> <?php echo $fetch_cart['name'] ?> <span>(<?php echo '$'.$fetch_cart['price'].'/-'.' x '.$fetch_cart['quantity']  ?>)</span> </p>
    <?php
        }
        }else{
            echo '<p class="empty">seu carrinho está vazio</p>';
        }
    ?>
    <div class="grand-total">total geral : <span>R$<?php echo $grand_total; ?>/-</span></div>
</section>

<section class="checkout">

    <form action="" method="POST">

        <h3>faça seu pedido</h3>

        <div class="flex">
            <div class="inputBox">
                <span>seu nome :</span>
                <input type="text" name="name" placeholder="seu nome">
            </div>
            <div class="inputBox">
                <span>seu telefone :</span>
                <input type="number" name="number" min="0" placeholder="seu telefone">
            </div>
            <div class="inputBox">
                <span>seu email :</span>
                <input type="email" name="email" placeholder="seu email">
            </div>
            <div class="inputBox">
                <span>método de pagamento :</span>
                <select name="method">
                    <option value="pagar na entrega">pagar na entrega</option>
                    <option value="cartão de crédito">cartão de crédito</option>
                    <option value="paypal">paypal</option>
                    <option value="paytm">paytm</option>
                </select>
            </div>
            <div class="inputBox">
                <span>rua :</span>
                <input type="text" name="flat" placeholder="rua/avenida">
            </div>
            <div class="inputBox">
                <span>bairro :</span>
                <input type="text" name="street" placeholder="bairro">
            </div>
            <div class="inputBox">
                <span>cidade :</span>
                <input type="text" name="city" placeholder="recife">
            </div>
            <div class="inputBox">
                <span>estado :</span>
                <input type="text" name="state" placeholder="pernambuco">
            </div>
            <div class="inputBox">
                <span>país :</span>
                <input type="text" name="country" placeholder="brasil">
            </div>
            <div class="inputBox">
                <span>cep :</span>
                <input type="number" min="0" name="pin_code" placeholder="00.000-000">
            </div>
        </div>

        <input type="submit" name="order" value="efetuar pedido" class="btn">

    </form>

</section>






<?php @include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>