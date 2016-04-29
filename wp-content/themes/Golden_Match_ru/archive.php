<?php get_header(); ?>
<!-- container start -->
	<div id="container" class="clearfix">
		<?php get_sidebars(); ?>
<!-- content start -->
		<div id="content" class="clearfix">
		<?php if(have_posts()) : ?>
                 <div class="post_path">Вы находитесь здесь: <a href="<?php bloginfo('url'); ?>">Главная</a> &gt;<?php
		// If this is a category archive
		if (is_category()) {
			printf( __('Архивы рубрики &#8216;<span>%1$s</span>&#8217;', 'Lasvegas-themes'), single_cat_title('', false) );
		// If this is a tag archive
		} elseif (is_tag()) {
			printf( __('Записи с меткой &#8216;<span>%1$s</span>&#8217;', 'Lasvegas-themes'), single_tag_title('', false) );
		// If this is a daily archive
		} elseif (is_day()) {
			printf( __('Архивы за день <span>%1$s</span>', 'Lasvegas-themes'), get_the_time(__('F jS, Y', 'Lasvegas-themes')) );
		// If this is a monthly archive
		} elseif (is_month()) {
			printf( __('Архивы за месяц <span>%1$s</span>', 'Lasvegas-themes'), get_the_time(__('F, Y', 'Lasvegas-themes')) );
		// If this is a yearly archive
		} elseif (is_year()) {
			printf( __('Архивы за год <span>%1$s</span>', 'Lasvegas-themes'), get_the_time(__('Y', 'Lasvegas-themes')) );
		// If this is an author archive
		} elseif (is_author()) {
			_e('Архивы автора', 'Lasvegas-themes');
		// If this is a paged archive
		} elseif (isset($_GET['paged']) && !empty($_GET['paged'])) {
			_e('Архивы блога', 'Lasvegas-themes');
		}
		?></div>
			<?php while(have_posts()) : the_post(); ?>
			<div class="post">
				<h2 class="post-title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
                <div class="postmetadata">Опубликовано в рубрике <?php the_category(', ') ?> | <?php the_time('F jS, Y') ?></div>
                <div class="entry"><?php the_excerpt(); ?></div>
				<div class="endline"></div>
				<?php the_tags('<p class="tags"><strong>Метки:</strong> ', ', ', '</p>'); ?>
<div class="read_comments"><a href="<?php comments_link(); ?>">Комментарии<span><?php comments_number('0','1','%'); ?></span></a></div>
                <?php if ( $user_ID ) : ?>
					<div class="edit_post"><?php edit_post_link(__('Редактировать')); ?> (Вы вошли как <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>)</div>
				<?php endif; ?>
			</div>
			<?php endwhile; ?>
			<?php if(function_exists('wp_pagenavi')) { wp_pagenavi(); } else { ?>
					<div class="wp-pagenavi">
					<div class="alignleft"><?php next_posts_link('&laquo; Предыдущие записи') ?></div>
					<div class="alignright"><?php previous_posts_link('Следующие записи &raquo;') ?></div>
					</div>
					<?php } ?>
		<?php else : ?>
		<div class="notfound"><p>Запись не найдена!</p><p>Пожалуйста, попробуйте еще раз.</p></div>
		<?php endif; ?>
		</div>
<!-- content end -->
	</div>
<!-- container end -->
<?php get_footer(); ?>