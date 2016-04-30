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
        'dseller_shop_id' => 'none',
        'dseller_success_url' => 'none',
        'dseller_fail_url' => 'none',
        'dseller_result_url' => 'none',
        'dseller_secret_key' => 'none',
        'dseller_sign' => 'md5',
        'dseller_success_method' => 'get',
        'dseller_fail_method' => 'get',
        'dseller_purse' => 'none',
        'dseller_sim_mode' => '0'

    );

    public $options = array(
        'dseller_dir' => 'upload'
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
        add_menu_page('DSeller Settings', 'DSeller', 8, 'dseller-opt', array($this,'show_main_page'), plugins_url( 'dseller/img/webmoney.jpg' ));
        add_submenu_page( 'dseller-opt', 'WebMoney', 'WebMoney', 8, 'dseller-wm-opt', array($this,'show_wm_opt_page'));
        add_submenu_page( 'dseller-opt', 'Товары', 'Товары', 8, 'dseller-products-opt', array($this,'show_products_page') );
        add_submenu_page( 'dseller-opt', 'Платежи', 'Платежи', 8, 'dseller-payments-opt', array($this,'show_payments_page') );
    }


    public function run(){

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
            `cost` varchar(250) NOT NULL,
            `url` varchar(250) NOT NULL,
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

    public function show_main_page(){
        require('views/main_page.php');
    }

    public function show_products_page(){
        require('views/products_page.php');
    }

    public function show_wm_opt_page(){
        require('views/wm_opt_page.php');
    }

    public function show_payments_page(){
        require('views/payments_page.php');
    }


    /**
     * @param $id ID продукта
     * @return mixed
     */
    public function get_product($id){
        global $wpdb;
        $table_products = $wpdb->prefix . $this->table_product;
        $product = $wpdb->get_row(
            $wpdb->prepare("SELECT * FROM $table_products WHERE id=$id")
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
    public function add_product($name, $price, $url){
        global $wpdb;
        $table_products = $wpdb->prefix . $this->table_product;
        $wpdb->insert(
            $table_products,
            array('name' => $name, 'cost'=> $price, 'url' => $url),
            array('%s', '%s', '%s')
        );
    }
    
    public function delete_product($id){
        global $wpdb;
        $table_products = $wpdb->prefix . $this->table_product;
        $wpdb->query("DELETE FROM $table_products WHERE id=$id");
        $this->delete_lost_files();
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

    public function show_wm_payment_form($id){
        require('views/wm_payment_form.php');
    }


}


$dseller = new DSeller();



