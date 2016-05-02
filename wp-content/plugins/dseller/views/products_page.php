<?php include('_header.php'); ?>
<?php

    if($this->is_form_submited('dseller_product_update_btn')){
        $name = $_POST['name'];
        $price = $_POST['price'];
        $url = $_POST['url'];
        $id = $_POST['id'];
        $desc = $_POST['desc'];
        $this->update_product($id, $name, $price, $url);
    }

    if($this->is_form_submited('dseller_product_del_btn')){
        $id = intval($_POST['id']);
        $this->delete_product($id);
    }

    $products = $this->get_products();
?>


<h2>Список товаров</h2>

<?php if ($products): ?>
    <table class="table">
        <tr>
            <th>ID</th>
            <th>Название</th>
            <th>Цена</th>
            <th>URL</th>
            <th>Описание</th>
            <th></th>
            <th></th>
        </tr>
<?php foreach($products as $item):?>
    <form class="opt-form" name="dseller_form" method="post" action="<?php echo $_SERVER['HTTP_SELF']?>?page=dseller-products-opt&amp;update=true">
        <?php if(function_exists('wp_nonce_field')) wp_nonce_field( 'dseller_form' ); ?>
        <tr>
            <td><?php echo $item->id?></td>
            <td><input id="name" class="form-control" name="name" value="<?php echo $item->name;?>"/></td>
            <td><input id="price" class="form-control" name="price" value="<?php echo $item->cost;?>"/></td>
            <td><input class="form-control" id="url" name="url" value="<?php echo $item->url;?>"/></td>
            <td><textarea class="form-control" id="desc" name="description"><?php echo $item->description;?></textarea></td>
            <input type="hidden" name="id" value="<?php echo $item->id;?>"/>
            <td><button name="dseller_product_update_btn" type="submit" class="btn btn-default">Сохранить</button></td>
            <td><button name="dseller_product_del_btn" type="submit" class="btn btn-default">Удалить</button></td>
        </tr>
    </form>

<?php endforeach;?>
    </table> 
<?php endif; ?>


<?php include('_footer.php');?>
<?php include ('_tinymce.php');?>
