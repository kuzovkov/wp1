<!-- sidebar start -->
		<div id="sidebar">
			<div id="welcome"><p><?php include(TEMPLATEPATH . '/welcome.php'); ?></p></div>
            <div id="searchform"><?php include(TEMPLATEPATH . '/searchform.php'); ?></div>
			<div id="sidebar_main" class="clearfix">
            <ul>
            <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar1') ) : ?>
                <li>
                    <h2>Рубрики</h2>
                    <div class="sidebar_icon"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/sidebar_icon.jpg" /></div>
                    <ul>
                        <?php wp_list_cats('sort_column=name&optioncount=0&hierarchical=0'); ?>
                    </ul>
                </li>
                <li>
                    <h2>Архивы</h2>
                    <div class="sidebar_icon"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/sidebar_icon.jpg" /></div>
                    <ul>
                        <?php wp_get_archives('type=monthly'); ?>
                    </ul>
                </li>
                <li>
                    <h2>Мета</h2>
                    <div class="sidebar_icon"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/sidebar_icon.jpg" /></div>
                    <ul>
                        <?php wp_register(); ?>
                        <li><?php wp_loginout(); ?></li>
                        <?php wp_meta(); ?>
                    </ul>
                </li>
             <?php endif; ?>
             </ul>
			 </div>
			 
		</div>
<!-- sidebar end -->
