<html>
<head>
    <title>Redirecting to payment gateway</title>
    <meta charset="utf-8"/>
</head>
<body>

<img class="preloader" src="<?php echo plugins_url();?>/dseller/img/preloader.gif"/>

<?php
    $product = $this->get_product($id);
    $desc = 'Оплата за продукт ' . $product->name;
?>

<form name="wm_form" method="POST" action="https://merchant.webmoney.ru/lmi/payment.asp" accept-charset="windows-1251">
    <input type="hidden" name="LMI_PAYMENT_AMOUNT" value="<?php echo round(floatval($product->cost),2);?>">
    <input type="hidden" name="LMI_PAYMENT_DESC" value="<?php echo $desc;?>">
    <input type="hidden" name="LMI_PAYMENT_NO" value="<?php echo $this->get_payment_number();?>">
    <input type="hidden" name="LMI_PAYEE_PURSE" value="<?echo get_option('dseller_purse');?>">
    <input type="hidden" name="LMI_SIM_MODE" value="<?echo get_option('dseller_sim_mode');?>">
    <input type="hidden" name="PRODUCT_ID" value="<?echo $product->id;?>">
    <input type="hidden" name="DCODE" value="<?echo $this->random_string(20);?>">
    <input type="hidden" name="CURR_URI" value="<?echo $_SESSION['curr_uri'];?>">
</form>


<script type="text/javascript">
    window.setTimeout(submitform,1000);
    function submitform(){
        document.wm_form.submit();
    }
</script>
</body>
</html>