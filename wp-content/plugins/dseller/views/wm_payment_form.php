<?php $product = $this->get_product($id)?>


<form method="POST" action="https://merchant.webmoney.ru/lmi/payment.asp" accept-charset="windows-1251">
    <input type="hidden" name="LMI_PAYMENT_AMOUNT" value="<?php echo $product->amount;?>">
    <input type="hidden" name="LMI_PAYMENT_DESC" value="<?php echo $product->desc;?>">
    <input type="hidden" name="LMI_PAYMENT_NO" value="<?php echo $product->no;?>">
    <input type="hidden" name="LMI_PAYEE_PURSE" value="<?echo get_option('dseller_purse');?>">
    <input type="hidden" name="LMI_SIM_MODE" value="<?echo get_option('dseller_sim_mode');?>">
    <button type="submit" class="btn btn-default">Оплатить</button>
</form>;

