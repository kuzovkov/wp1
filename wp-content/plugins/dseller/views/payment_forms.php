<html>
<head>
    <title>Redirecting to payment gateway</title>
    <meta charset="utf-8"/>
</head>
<body>

<img src="<?php echo plugins_url();?>/dseller/img/332.gif"/>

<?php $product = $this->get_product($id);?>

<form name="wm_form" method="POST" action="https://merchant.webmoney.ru/lmi/payment.asp" accept-charset="windows-1251">
    <input type="hidden" name="LMI_PAYMENT_AMOUNT" value="<?php echo round(floatval($product->cost),2);?>">
    <input type="hidden" name="LMI_PAYMENT_DESC" value="Оплата за товар <?php echo $product->name;?>">
    <input type="hidden" name="LMI_PAYMENT_NO" value="<?php echo $product->no;?>">
    <input type="hidden" name="LMI_PAYEE_PURSE" value="<?echo get_option('dseller_purse');?>">
    <input type="hidden" name="LMI_SIM_MODE" value="<?echo get_option('dseller_sim_mode');?>">
</form>


<script type="text/javascript">
    window.setTimeout(submitform,1000);
    function submitform(){
        document.wm_form.submit();
    }
</script>
</body>
</html>