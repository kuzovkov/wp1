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
        add_submenu_page( 'dseller-opt', 'Товары', 'Товары', 8, 'dseller-goods-opt', array($this,'show_goods_page') );
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

    public function show_goods_page(){
        require('views/goods_page.php');
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

    public function show_wm_payment_form($id){
        require('views/wm_payment_form.php');
    }


}


$dseller = new DSeller();



