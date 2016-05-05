<?php
/*
    Plugin Name: DSeller
    Plugin URI: http://kuzovkov12.ru
    Description: Plugin for seller digital goods
    Armstrong: Digital Seller
    Version: 0.1
    Author URI: kuzovkov12.ru
*/


class DSeller {


    public $wm_options = array(
        'dseller_shop_id' => 'VideoService',
        'dseller_success_url' => 'dseller_wm_success',
        'dseller_fail_url' => 'dseller_wm_fail',
        'dseller_result_url' => 'dseller_wm_result',
        'dseller_secret_key' => 'Sekret_Merchant',
        'dseller_sign' => 'md5',
        'dseller_success_method' => 'post',
        'dseller_fail_method' => 'post',
        'dseller_purse' => 'R425889686600',
        'dseller_sim_mode' => '0'

    );

    public $options = array(
        'dseller_dir' => 'upload',
        'dseller_category' => 'digit_products',
        'dseller_buy_url' => 'dseller_buy'
    );

    public $table_product = 'dseller_products';
    public $table_downloadcodes = 'dseller_downloadcodes';
    public $field_file_name = 'file';

    public function __construct(){
        global $wpdb;
        add_action('admin_menu', array($this,'add_admin_pages'));
        add_action('init', array($this,'run'));
        register_activation_hook(__FILE__, array($this,'install'));
        register_deactivation_hook(__FILE__, array($this,'uninstall'));
    }

    public function add_admin_pages(){
        add_menu_page('DSeller Settings', 'DSeller', 8, 'dseller-opt', array($this,'show_settings_page'), plugins_url( 'dseller/img/webmoney.jpg' ));
    }


    public function run(){
        $real_uri = $_SERVER['REQUEST_URI'];
        if (($p = strpos($real_uri, '?')) === false){
            $uri = substr($real_uri, 1);
        }else{
            $uri = substr($real_uri, 1, strpos($real_uri, '?'));
        }

        if ($uri == get_option('dseller_buy_url')){
            $id = (isset($_POST['id']))? intval($_POST['id']) : null;
            if ($id !== null){
                $this->send_payment_request($id);
            }else{
                wp_redirect( '/', 302 );
            }
            exit();
        }elseif ($uri == get_option('dseller_success_url')){

        }elseif($uri == get_option('dseller_fail_url')){

        }elseif($uri == get_option('dseller_result_url')){
            $result = $_POST['LMI_PAYEE_PURSE'].$_POST['LMI_PAYMENT_AMOUNT'].$_POST['LMI_PAYMENT_NO'].$_POST['LMI_MODE'].$_POST['LMI_SYS_INVS_NO'].$_POST['LMI_SYS_TRANS_NO'].$_POST['LMI_SYS_TRANS_DATE'].get_option('dseller_secret_key').$_POST['LMI_PAYER_PURSE'].$_POST['LMI_PAYER_WM'];
            $md5res = strtoupper(md5($result));
            if ($_POST['LMI_HASH'] == $md5res) {
                $dcode = $this->random_stirng(20);
                $ctime = time();
                $product_id = $_POST['PRODUCT_ID'];
                global $wpdb;
                $table_downloadcodes = $wpdb->prefix . $this->table_downloadcodes;
                $wpdb->insert(
                    $table_downloadcodes,
                    array('download_code' => $dcode, 'ctime'=> $ctime, 'product_id' => $product_id),
                    array('%s', '%d', '%d')
                );

            }

        }
    }

    public function add_options(){
        foreach($this->options as $key => $val){
            add_option($key, $val);
        }

        foreach($this->wm_options as $key => $val){
            add_option($key, $val);
        }
    }

    public function delete_options(){
        foreach($this->options as $key => $val){
            delete_option($key);
        }

        foreach($this->wm_options as $key => $val){
            delete_option($key, $val);
        }
    }

    /**
     * проверка получены ли данные от формы
     * @param $name имя параметра POST коорый должен быть
     * @return bool
     */
    public function is_form_submited($name){
        if (isset($_POST[$name])){

            if(function_exists('current_user_can') && !current_user_can('manage_options')){
                die(_e('Access restrict','dseller'));                        }

            if(function_exists('check_admin_referer')){
                check_admin_referer('dseller_form');
            }

            return true;
        }
        return false;
    }

    public function install(){
        global $wpdb;

        $this->add_options();

        $table_products = $wpdb->prefix . $this->table_product;
        $table_downloadcodes = $wpdb->prefix . $this->table_downloadcodes;

        $sql1 = "CREATE TABLE IF NOT EXISTS `". $table_downloadcodes ."` 
        (
            `id` INT(10) NOT NULL AUTO_INCREMENT,
            `download_code` varchar(64) NOT NULL,
            `product_id` INT(11) NOT NULL,
            `ctime` INT(11) NOT NULL,
            PRIMARY KEY (`id`)
        )ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        $sql2 = "CREATE TABLE IF NOT EXISTS `". $table_products ."` 
        (
            `id` INT(10) NOT NULL AUTO_INCREMENT,
            `name` varchar(250) NOT NULL,
            `description` TEXT,
            `cost` varchar(250) NOT NULL,
            `url` varchar(250) NOT NULL,
            `post_id` INT(10) DEFAULT 0,
            PRIMARY KEY (`id`)
        )ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        $wpdb->query($sql1);
        $wpdb->query($sql2);
    }

    public function uninstall(){
        global $wpdb;
        $table_products = $wpdb->prefix . $this->table_product;
        $table_downloadcodes = $wpdb->prefix . $this->table_downloadcodes;
        $this->delete_options();

        $sql1 = "DROP TABLE IF EXISTS `". $table_products ."`;";
        $sql2 = "DROP TABLE IF EXISTS `". $table_downloadcodes ."`;";

        $wpdb->query($sql1);
        $wpdb->query($sql2);
    }

    public function show_settings_page(){
        require('views/settings_page.php');
    }

    public function get_buy_button($product){
        $form = "<form method='post' action='/". get_option('dseller_buy_url') ."'>
            <input type='hidden' name='id' value='{$product->id}'/>
            <button>Купить</button>
        </form>";
        return $form;
    }

    public function send_payment_request($id){
        require('views/payment_forms.php');
    }

    public function add_product_post(){
        global $wpdb;
        $table_products = $wpdb->prefix . $this->table_product;
        $row = $wpdb->get_row("SELECT MAX(id) AS id FROM $table_products");
        $product = $this->get_product(intval($row->id));
        $category = get_category_by_slug( get_option('dseller_category') );
        $category_id = $category->cat_ID;
        $post_data = array(
            'post_title'    => wp_strip_all_tags( $product->name ),
            'post_content'  => $product->description . $this->get_buy_button($product),
            'post_status'   => 'publish',
            'post_author'   => 1,
            'post_category' => array( $category_id )
        );
        $post_id = wp_insert_post( $post_data );
        $wpdb->update($table_products,
            array('post_id' => $post_id),
            array('id' => $product->id),
            array('%s'),
            array('%d')
        );
        return;

    }


    /**
     * @param $id ID продукта
     * @return mixed
     */
    public function get_product($id){
        global $wpdb;
        $table_products = $wpdb->prefix . $this->table_product;
        $product = $wpdb->get_row(
            $wpdb->prepare("SELECT * FROM $table_products WHERE id=%d", $id)
        );
        return $product;
    }

    /**
     * @return mixed
     */
    public function get_products(){
        global $wpdb;
        $table_products = $wpdb->prefix . $this->table_product;
        $products = $wpdb->get_results("SELECT * FROM $table_products");
        return $products;
    }

    /**
     * @param $name
     * @param $price
     * @param $url
     */
    public function add_product($name, $price, $url, $desc){
        global $wpdb;
        $table_products = $wpdb->prefix . $this->table_product;
        $wpdb->insert(
            $table_products,
            array('name' => $name, 'cost'=> $price, 'url' => $url, 'description' => $desc),
            array('%s', '%s', '%s', '%s')
        );
    }
    
    public function delete_product($id){
        global $wpdb;
        $table_products = $wpdb->prefix . $this->table_product;
        $product = $this->get_product($id);
        $post_id = $product->post_id;
        $wpdb->query("DELETE FROM $table_products WHERE id=$id");
        $this->delete_lost_files();
        wp_delete_post($post_id);
    }

    public function update_product($id, $name, $price, $url){
        global $wpdb;
        $table_products = $wpdb->prefix . $this->table_product;
        $wpdb->update($table_products,
                array('name' => $name, 'cost' => $price, 'url' => $url),
                array('id' => $id),
                array('%s', '%s', '%s'),
                array('%d')
            );
        $this->delete_lost_files();
    }

    public function delete_lost_files(){
        $products = $this->get_products();
        $files = array();
        foreach($products as $product){
            $files[] = basename($product->url);
        }
        if (file_exists(ABSPATH . get_option('dseller_dir')) && is_dir(ABSPATH . get_option('dseller_dir'))){
            foreach(scandir(ABSPATH . get_option('dseller_dir')) as $file){
                if ($file == '.' || $file == '..') continue;
                if (!in_array($file, $files)){
                    unlink(ABSPATH . get_option('dseller_dir') .'/' . $file);
                }
            }
        }

    }

    public function upload_file($files){
        if (isset($files['error']) && $files['error'] == 0){
            if ($this->check_upload_dir()){
                $tmp_name = $files['tmp_name'];
                $name = $files['name'];
                return (move_uploaded_file($tmp_name, ABSPATH . get_option('dseller_dir') . '/' . $name))? $name : false;
            }
        }
    }

    public function check_upload_dir(){
        $upload_dir = ABSPATH . get_option('dseller_dir');
        if (file_exists($upload_dir) && is_dir($upload_dir)){
            return true;
        }else{
            return mkdir($upload_dir);
        }
    }

    public function random_stirng($n){
        $str = 'wertyuiopasdfghjklzxcvbnm1234567890QWERTYUIOPASDFGHJKLZXCVBNM';
        $arr = str_split($str);
        $pass= '';
        for ($i = 0; $i < $n; $i++){
            $index = rand(0, count($arr) - 1);
            $pass .= $arr[$index];
        }
        return $pass;
    }

    public function change_content($content){

    }


    
}


$dseller = new DSeller();



