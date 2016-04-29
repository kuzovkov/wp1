<?php get_header(); ?>
<!-- container start -->
	<div id="container" class="clearfix">
		<?php get_sidebar(); ?>
<!-- content start -->
		<div id="content" class="clearfix">
		<?php if(have_posts()) : ?>
			<?php while(have_posts()) : the_post(); ?>
			<div class="post">
				<div class="post_header_bg"><h2 class="post-title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2></div>
				<div class="post_icon"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/post_icon.jpg" /></div>
                <div class="postmetadata">Опубликовано в <?php the_category(', ') ?> | <?php the_time('F jS, Y') ?></div>
                <div class="entry"><?php the_content("Узнать рецепт..."); ?></div>
				<div class="endline"></div>
				<?php the_tags('<p class="tags"><strong>Метки:</strong> ', ', ', '</p>'); ?>
				<div class="bookmark"><?php bookmark(get_the_ID(), get_permalink()); ?></div>
                <div class="read_comments"><a href="<?php comments_link(); ?>">Читать комментарии: <span>(<?php comments_number('0','1','%'); ?>)</span></a></div>
                <?php if ( $user_ID ) : ?>
					<div class="edit_post"><?php edit_post_link(__('[Правка]')); ?> (Вы вошли как <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>)</div>
				<?php endif; ?>
			</div>
			<?php endwhile; ?>
			<?php if(function_exists('wp_pagenavi')) { wp_pagenavi(); } else { ?>
					<div class="wp-pagenavi">
					<div class="alignleft"><?php next_posts_link('&laquo; Предыдущие') ?></div>
					<div class="alignright"><?php previous_posts_link('Следующие &raquo;') ?></div>
					</div>
					<?php } ?>
		<?php else : ?>
		<div class="notfound"><p>Ничего не найдено!</p><p>Вернитесь назад.</p></div>
		<?php endif; ?>
		</div>
<!-- content end -->
	</div>
<!-- container end -->
<?php get_footer(); ?>