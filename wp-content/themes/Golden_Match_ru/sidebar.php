<!-- sidebar start -->
		<div id="sidebar">
        	<div id="searchform"><?php include(TEMPLATEPATH . '/searchform.php'); ?></div>
			<div id="welcome"><p><?php include(TEMPLATEPATH . '/welcome.php'); ?></p></div>
			<div id="sidebar_main" class="clearfix">
            <ul>
            <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Sidebar') ) : ?>
                <li>
                    <h2>Рубрики</h2>
                    <ul>
                        <?php wp_list_categories('hierarchical=0&title_li='); ?>
                    </ul>
                </li>
                <li>
                    <h2>Архивы</h2>
                    <ul>
                        <?php wp_get_archives('type=monthly'); ?>
                    </ul>
                </li>
              
             <?php endif; ?>
             </ul>
			 </div>
		</div>
<!-- sidebar end -->