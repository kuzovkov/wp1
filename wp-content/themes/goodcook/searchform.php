<form method="get" action="<?php bloginfo('url'); ?>">
    <input name="s" type="text" class="searchtext" id="s" value="Поиск..." onblur="if (this.value == '') {this.value = 'Поиск...';}" onfocus="if (this.value == 'Поиск...') {this.value = '';}" />
    <input type="submit" src="<?php bloginfo('stylesheet_directory'); ?>/images/menu-spacer.gif" id="searchsubmit" alt="Поиск" value="Поиск" />
</form>