<?php include('_header.php'); ?>
<?php

    if($this->is_form_submited('dseller_product_add_btn')){
        $name = $_POST['name'];
        $price = $_POST['price'];
        $url = $_POST['url'];

        //var_dump($_POST);
        //var_dump($_FILES);
        if (isset($_FILES[$this->field_file_name])){
            if($filename = $this->upload_file($_FILES[$this->field_file_name])){
                $url =  home_url() . '/' . get_option('dseller_dir') . '/' . $filename;
            }
        }
        $this->add_product($name,$price, $url);

    }

    if($this->is_form_submited('dseller_product_update_btn')){
        $name = $_POST['name'];
        $price = $_POST['price'];
        $url = $_POST['url'];
        $id = $_POST['id'];
        $this->update_product($id, $name, $price, $url);
    }

    if($this->is_form_submited('dseller_product_del_btn')){
        $id = intval($_POST['id']);
        $this->delete_product($id);
    }

    $products = $this->get_products();
?>



<h2>Добавить товар</h2>
<form class="opt-form" name="dseller_form" method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['HTTP_SELF']?>?page=dseller-products-opt&amp;update=true">
    <?php if(function_exists('wp_nonce_field')) wp_nonce_field( 'dseller_form' ); ?>
    <div class="form-group"><label for="name">Название:</label><input id="name" class="form-control" name="name" value=""/></div>
    <div class="form-group"><label for="price">Цена:</label><input id="price" class="form-control" name="price" value=""/></div>
    <div class="form-group"><label for="url">URL:</label><input class="form-control" id=url name="url" value=""/></div>
    <div class="form-group"><label for="file">File:</label><input type="file" class="form-control" id=file name="file" value=""/></div>
    <button name="dseller_product_add_btn" type="submit" class="btn btn-default">Добавить</button>
</form>

<h2>Список товаров</h2>

<?php if ($products): ?>
<?php foreach($products as $item):?>
    <p>ID: <?php echo $item->id?></p>
    <form class="opt-form" name="dseller_form" method="post" action="<?php echo $_SERVER['HTTP_SELF']?>?page=dseller-products-opt&amp;update=true">
        <?php if(function_exists('wp_nonce_field')) wp_nonce_field( 'dseller_form' ); ?>
        <div class="form-group"><label for="name">Название:</label><input id="name" class="form-control" name="name" value="<?php echo $item->name;?>"/></div>
        <div class="form-group"><label for="price">Цена:</label><input id="price" class="form-control" name="price" value="<?php echo $item->cost;?>"/></div>
        <div class="form-group"><label for="url">URL:</label><input class="form-control" id="url" name="url" value="<?php echo $item->url;?>"/></div>
        <input type="hidden" name="id" value="<?php echo $item->id;?>"/>
        <button name="dseller_product_update_btn" type="submit" class="btn btn-default">Сохранить</button>
        <button name="dseller_product_del_btn" type="submit" class="btn btn-default">Удалить</button>
    </form>
    <hr/>

<?php endforeach;?>
<?php endif; ?>


<?php include('_footer.php');?>
