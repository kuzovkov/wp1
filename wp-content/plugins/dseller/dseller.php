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
        'dseller_buy_url' => 'dseller_buy',
        'dseller_download_url' => 'dseller_download',
        'dseller_link_timelive' => 10
    );

    public $table_product = 'dseller_products';
    public $table_downloadcodes = 'dseller_downloadcodes';
    public $table_payments = 'dseller_payments';
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
            $arr = (isset($_POST['LMI_PAYMENT_NO']))? $_POST : $_GET;
            $this->show_wm_success($arr);
            exit();
        }elseif($uri == get_option('dseller_fail_url')){
            $arr = (isset($_POST['LMI_PAYMENT_NO']))? $_POST : $_GET;
            $this->show_wm_fail($arr);
            exit();
        }elseif($uri == get_option('dseller_result_url')){
            if ($this->wm_check_result()) {
                $this->add_download_code($_POST);
            }
            exit();
        }elseif($uri == get_option('dseller_download_url')){
            $this->start_download();
            exit();
        }
    }

    public function wm_check_result(){
        $result = $_POST['LMI_PAYEE_PURSE'].$_POST['LMI_PAYMENT_AMOUNT'].$_POST['LMI_PAYMENT_NO'].$_POST['LMI_MODE'].$_POST['LMI_SYS_INVS_NO'].$_POST['LMI_SYS_TRANS_NO'].$_POST['LMI_SYS_TRANS_DATE'].get_option('dseller_secret_key').$_POST['LMI_PAYER_PURSE'].$_POST['LMI_PAYER_WM'];
        $hash = strtoupper(hash(get_option('dseller_sign'), $result));
        return ($_POST['LMI_HASH'] == $hash)? true : false;
    }


    public function show_wm_success($arr){
        echo "<h2>success</h2>";
        $timelive = get_option('dseller_link_timelive');
        $dcode = $arr['DCODE'];
        $link = home_url() . '/' . get_option('dseller_download_url') . '?dcode=' . $dcode;
        echo "<p>Ваша ссылка на скачивание: $link (действительна $timelive дней)</p>";
        if (is_array($arr)){
            foreach($arr as $key => $val){
                echo "<p>$key => $val</p>";
            }
        }
    }

    public function show_wm_fail($arr){
        echo "<h2>fail</h2>";
        if (is_array($arr)){
            foreach($arr as $key => $val){
                echo "<p>$key => $val</p>";
            }
        }
    }


    /**
     * создание опций по умолчанию
     */
    public function add_options(){
        foreach($this->options as $key => $val){
            add_option($key, $val);
        }

        foreach($this->wm_options as $key => $val){
            add_option($key, $val);
        }
    }

    /**
     * удаление опций по умолчанию
     */
    public function delete_options(){
        foreach($this->options as $key => $val){
            delete_option($key);
        }

        foreach($this->wm_options as $key => $val){
            delete_option($key, $val);
        }
    }


    /**
     * определение по коду загрузки активна ли ссылка на загрузку
     * и если активна то старт отгрузки пользователю соответвующего файла
     */
    public function start_download(){
        global $wpdb;
        $this->delete_expired_codes();
        if (isset($_GET['dcode'])){
            $dcode = $_GET['dcode'];
            $table_downloadcodes = $wpdb->prefix . $this->table_downloadcodes;
            $table_products = $wpdb->prefix . $this->table_product;
            $code_product = $wpdb->get_row(
                $wpdb->prepare("SELECT * FROM $table_downloadcodes WHERE download_code = %s", $dcode)
            );

            if ($code_product){
                $product_code_id = $code_product->product_id;
                $product = $wpdb->get_row(
                    $wpdb->prepare("SELECT * FROM $table_products WHERE id = %d", $product_code_id)
                );
                $url = $product->url;
                $this->download_file($url);
            }else{
                echo "Ссылка не активна";
            }
        }

    }


    /**
     * отгрузка файла пользователю
     * @param $url настоящий URL файла
     */
    public function download_file($url){
        $filename = basename($url);
        header('Content-Disposition: attachment; filename=' . $filename);
        header('Content-Length: ' . filesize($url));
        header('Keep-Alive: timeout=5; max=100');
        header('Connection: Keep-Alive');
        header('Content-Type: coter-stream');
        readfile($url);
        exit();
    }

    /**
     * удаление просроченных кодов
     */
    public function delete_expired_codes(){
        global $wpdb;
        $final_time = time() - intval(get_option('dseller_link_timelive')) * 3600 * 24;
        $table_downloadcodes = $wpdb->prefix . $this->table_downloadcodes;
        $wpdb->query(
            $wpdb->prepare("DELETE DROM $table_downloadcodes WHERE ctime < %d", $final_time)
        );
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
        $table_payments = $wpdb->prefix .$this->table_payments;

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

        $sql3 = "CREATE TABLE IF NOT EXISTS `". $table_payments ."` 
        (
            `id` INT(10) NOT NULL AUTO_INCREMENT,
            `PAYMENT_NO` int(12) NOT NULL,
            `PAYMENT_AMOUNT` FLOAT(10),
            `PAYEE_PURSE` varchar(25) NOT NULL,
            `SYS_TRANS_NO` varchar(250) NOT NULL,
            `PAYER_PURSE` varchar(25) NOT NULL,
            `PAYER_WM` varchar(25) NOT NULL,
            `SYS_TRANS_DATE` DATETIME NOT NULL,
            `PAYMENT_DESC` TEXT DEFAULT '',
            PRIMARY KEY (`id`)
        )ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        $wpdb->query($sql1);
        $wpdb->query($sql2);
        $wpdb->query($sql3);
    }

    public function uninstall(){
        global $wpdb;
        $table_products = $wpdb->prefix . $this->table_product;
        $table_downloadcodes = $wpdb->prefix . $this->table_downloadcodes;
        $table_payments = $wpdb->prefix .$this->table_payments;
        $this->delete_options();

        $sql1 = "DROP TABLE IF EXISTS `". $table_products ."`;";
        $sql2 = "DROP TABLE IF EXISTS `". $table_downloadcodes ."`;";
        $sql3 = "DROP TABLE IF EXISTS `". $table_payments ."`;";

        $wpdb->query($sql1);
        $wpdb->query($sql2);
        $wpdb->query($sql3);
    }


    public function add_payment($post){
        global $wpdb;
        $table_payments = $wpdb->prefix .$this->table_payments;
        $wpdb->insert(
            $table_payments,
            array(
                'PAYMENT_NO' => intval($post['LMI_PAYMENT_NO']),
                'PAYMENT_AMOUNT' => floatval($post['LMI_PAYMENT_AMOUNT']),
                'PAYEE_PURSE' => strval($post['LMI_PAYEE_PURSE']),
                'SYS_TRANS_NO' => strval($post['LMI_SYS_TRANS_NO']),
                'PAYER_PURSE' => strval($post['LMI_PAYER_PURSE']),
                'PAYER_WM' => strval($post['LMI_PAYER_WM']),
                'SYS_TRANS_DATE' => new DateTime($post['LMI_SYS_TRANS_DATE']),
                'PAYMENT_DESC' => strval($post['LMI_PAYMENT_DESC']),
            ),
            array('%d', '%f', '%s', '%s', '%s', '%s', '%s', '%s')
        );

    }


    public function get_payments(){
        global $wpdb;
        $table_payments = $wpdb->prefix .$this->table_payments;
        $payments = $wpdb->get_results("SELECT * FROM $table_payments");
        return $payments;
    }
    
    public function add_download_code($post){
        $dcode = $_POST['DCODE'];
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

    /**
     * отображение страниц настроек в админке
     */
    public function show_settings_page(){
        require('views/settings_page.php');
    }


    /**
     * генерация кода для формы Покупки
     * @param $product
     * @return string
     */
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


    /**
     * добавляем пост с описанием товара
     */
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
    }

    /**
     * возвр номер покупки
     * @return int
     */
    public function get_payment_number(){
        return time();
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

    public function random_string($n){
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



