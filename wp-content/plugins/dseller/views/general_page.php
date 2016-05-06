

<h2>Основные настройки</h2>


<form class="opt-form" name="dseller_form" method="post" action="<?php echo $_SERVER['HTTP_SELF']?>?page=dseller-opt&amp;update=true&amp;tab=general">
    <?php if(function_exists('wp_nonce_field')) wp_nonce_field( 'dseller_form' ); ?>
    <div class="form-group"><label for="dir">Каталог загрузки:</label><input id="dir" class="form-control" name="dseller_dir" value="<?php echo get_option('dseller_dir');?>"/></div>
    <div class="form-group"><label for="cat">Категория записей с товарами:</label><input id="cat" class="form-control" name="dseller_category" value="<?php echo get_option('dseller_category');?>"/></div>
    <div class="form-group"><label for="buy">URL обработчика кнопки "Купить":</label><input id="buy" class="form-control" name="dseller_buy_url" value="<?php echo get_option('dseller_buy_url');?>"/></div>
    <div class="form-group"><label for="buy">URL обработчика загрузки товара:</label><input id="download" class="form-control" name="dseller_download_url" value="<?php echo get_option('dseller_download_url');?>"/></div>
    <div class="form-group"><label for="buy">Время жизни ссылки (дней):</label><input type="number" id="livetime" class="form-control" name="dseller_link_timelive" value="<?php echo get_option('dseller_link_timelive');?>"/></div>
    <button name="dseller_mainopt_btn" type="submit" class="btn btn-default">Сохранить</button>
</form>


