<?php include('_header.php'); ?>
<h2>Основные настройки</h2>
<?php

if ($this->is_form_submited('dseller_mainopt_btn')){
    foreach($this->options as $key => $val){
        update_option($key, $_POST[$key]);
    }
}

?>

<form class="opt-form" name="dseller_form" method="post" action="<?php echo $_SERVER['HTTP_SELF']?>?page=dseller-opt&amp;update=true">
    <?php if(function_exists('wp_nonce_field')) wp_nonce_field( 'dseller_form' ); ?>
    <div class="form-group"><label for="dir">Каталог загрузки:</label><input id="dir" class="form-control" name="dseller_dir" value="<?php echo get_option('dseller_dir');?>"/></div>
    <div class="form-group"><label for="cat">Категория записей с товарами:</label><input id="cat" class="form-control" name="dseller_category" value="<?php echo get_option('dseller_category');?>"/></div>
    <div class="form-group"><label for="buy">URL обоаботчика кнопки "Купить":</label><input id="игн" class="form-control" name="dseller_buy_url" value="<?php echo get_option('dseller_buy_url');?>"/></div>
    <button name="dseller_mainopt_btn" type="submit" class="btn btn-default">Сохранить</button>
</form>

<?php include('_footer.php');?>
