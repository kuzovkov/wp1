<?php ?>


<form method="POST" action="https://merchant.webmoney.ru/lmi/payment.asp" accept-charset="windows-1251">
    <input type="hidden" name="LMI_PAYMENT_AMOUNT" value="'. $amount .'">
    <input type="hidden" name="LMI_PAYMENT_DESC" value="'. $desc .'">
    <input type="hidden" name="LMI_PAYMENT_NO" value="'. $no .'">
    <input type="hidden" name="LMI_PAYEE_PURSE" value="' . get_option('dseller_purse') . '">
    <input type="hidden" name="LMI_SIM_MODE" value="'. $test_mode .'">
    <button type="submit" class="btn btn-default">Оплатить</button>
</form>;

