<?php
	function mypl_general_options(){
                if (isset($_POST["submit"])){
                        if (isset($_POST["mypl_text"])){
                                update_option("mypl_text", $_POST["mypl_text"]);
                        }
                }
                $text = get_option("mypl_text");
                
?>

<h1>Мой плагин</h1>
<form method="post" action="">
        <table class="form-table">
                <tr>
                        <th style="width: 269px;">Текст</th>
                        <td><input type="text" name="mypl_text" value="<?php echo $text; ?>"/></td>
                </tr>
                <tr>
                        <td><?php submit_button(); ?></td>
                        <td></td>
                </tr>
        </table>
</form>

<?php } ?> 
