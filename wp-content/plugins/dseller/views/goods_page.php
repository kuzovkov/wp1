<?php include('_header.php'); ?>
<?php
    global $wpdb;
    $dseller_table_products = $wpdb->prefix .'dseller_products';
    $dseller_products = $wpdb->get_results("SELECT * FROM $table_products");

    function dseller_add_good($name, $price, $url){
        global $wpdb;
        $dseller_table_products = $wpdb->prefix .'dseller_products';
        $wpdb->insert(
            $dseller_table_products,
            array('name' => $name, 'price'=> $price, 'url' => $url),
            array('%s', '%s', '%s')
            );
    }

    
    
?>



<h2>Добавить товар</h2>
<form class="opt-form" name="dseller_form" method="post" action="<?php echo $_SERVER['HTTP_SELF']?>?page=dseller-goods-opt&amp;update=true">
    <div class="form-group"><label for="name">Название:</label><input id="name" class="form-control" name="name" value=""/></div>
    <div class="form-group"><label for="price">Цена:</label><input id="price" class="form-control" name="price" value=""/></div>
    <div class="form-group"><label for="url">URL:</label><input class="form-control" id=url name="url" value=""/></div>
    <div class="form-group"><label for="file">File:</label><input type="file" class="form-control" id=file name="file" value=""/></div>
    <button name="dseller_goodadd_btn" type="submit" class="btn btn-default">Добавить</button>
</form>

<h2>Список товаров</h2>

<?php if ($dseller_products): ?>
<?php foreach($dseller_products as $item):?>
    <p>ID: <?php echo $item->id?></p>
    <form class="opt-form" name="dseller_form" method="post" action="<?php echo $_SERVER['HTTP_SELF']?>?page=dseller-goods-opt&amp;update=true">
        <div class="form-group"><label for="name">Название:</label><input id="name" class="form-control" name="name" value=""/></div>
        <div class="form-group"><label for="price">Цена:</label><input id="price" class="form-control" name="price" value=""/></div>
        <div class="form-group"><label for="url">URL:</label><input class="form-control" id="url" name="url" value=""/></div>
        <input type="hidden" name="id" value="<?php echo $item->id;?>"/>
        <button name="dseller_goodsave_btn" type="submit" class="btn btn-default">Сохранить</button>
        <button name="dseller_gooddel_btn" type="submit" class="btn btn-default">Удалить</button>
    </form>
    <hr/>

<?php endforeach;?>
<?php endif; ?>


<?php include('_footer.php');?>
