<form method="get" action="<?php bloginfo('url'); ?>">
					<input name="s" type="text" class="searchtext" id="s" value="Поиск..." onblur="if (this.value == '') {this.value = 'Поиск...';}" onfocus="if (this.value == 'Поиск...') {this.value = '';}" />
					<input type="image" src="<?php bloginfo('stylesheet_directory'); ?>/images/spacer.gif" id="searchsubmit" alt="Поиск" value="" />
				</form>