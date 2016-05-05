<?php

    if ($this->is_form_submited('dseller_wm_opt_btn')){
        foreach($this->wm_options as $key => $val){
            update_option($key, $_POST[$key]);
        }
    }

    if ($this->is_form_submited('dseller_mainopt_btn')){
        foreach($this->options as $key => $val){
            update_option($key, $_POST[$key]);
        }
    }

    if($this->is_form_submited('dseller_product_add_btn')){
        $name = $_POST['name'];
        $price = $_POST['price'];
        $url = $_POST['url'];
        $desc = $_POST['description'];
        //var_dump($_POST);
        //var_dump($_FILES);
        if (isset($_FILES[$this->field_file_name])){
            if($filename = $this->upload_file($_FILES[$this->field_file_name])){
                $url =  home_url() . '/' . get_option('dseller_dir') . '/' . $filename;
            }
        }
        $this->add_product($name, $price, $url, $desc);
        $this->add_product_post();

    }


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