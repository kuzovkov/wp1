<?php
/*
 Plugin Name: My Widget
 Plugin URI: http://myrysakov.ru
 Description: Show time
 Version: 1.0
 Author: Rysakov
 author URI: http://blog.myrusakov.ru
 */
 
 class my_widget extends WP_Widget{
         
        
        
	function __construct(){
                parent::__construct("my_widget", "Мой виджет", array("description"=>"Пример виджета"));
        } 
        
        public function widget($args, $instance){
                $title = apply_filters("widget_title", $instance['title']);
                echo $args['before_widgets'];
                echo $args['before_title'].$title.$args['after_title'];
                echo '<ul><li>Время на сайте: <span id="myw-time">' . date('H:i:s') . '</span></li></ul>';
		echo $this->getScript();
                echo $args['after_widget'];
        }

        public function form($instnce){
                if(isset($instance['title'])){
                        $title = $instance['title'];
                }else{
                        $title = 'New title';
                }
                ?>
                <p><label for="my_w_title">Заголовок</label>
                <input class="widefat" id="my_w_title" name="<?php echo $this->get_field_name('title'); ?>" />
                </p>
        <?php
        }

        public function update($new_instance, $old_instance){
                $instance = array();
                $instance['title'] = (!empty($new_instance['title']))? $new_instance['title'] : '';
                return $instance;
        }

        private function getScript(){
                $script_url = plugins_url('time.php',__FILE__);
                $script = "<script src='https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js'></script>
                <script>
                        function setTime(){
                                $('#myw-time').load('{$script_url }');
                        }
                        setInterval(setTime, 1000);
                </script>
                ";
                return $script;
        }
         
} 




function myw_load_widget(){
        register_widget('my_widget');
}

add_action('widgets_init', 'myw_load_widget');

?>