<?php get_header();?>
    <!--==============================content================================-->
    <div class="inner">
        <div class="main">
            <section id="content">
                <div class="slider">
                    <ul class="items">
                        
                        <?php if ( have_posts() ) :?>
                        <?php while ( have_posts() ) : the_post(); ?>
                        <li>
                            <?php if(function_exists('add_theme_support')) if ( has_post_thumbnail() ) { the_post_thumbnail(array(613,324),'');} ?>
                            <div class="banner"> <strong class="title"> <strong>Hot</strong><em>Recipe</em> </strong>
                                <p><?php the_excerpt(); ?></p>
                                <a class="button-1" href="#">Read More</a>
                            </div>
                        </li>
                        <?php endwhile; endif; ?>
                    </ul>
                    <a class="banner-2" href="#"></a>
                </div>

                <ul class="pags">
                    <li><a href="#">1</a></li>
                    <li><a href="#">2</a></li>
                    <li><a href="#">3</a></li>
                </ul>
                <div class="bg">
                    <div class="padding">
                        <div class="wrapper">
                            <?php if(have_posts()) : $count = 0;?>
                            <?php while(have_posts()): the_post(); ?>
                            <article class="col-<?php $count++; if ($count < 3){$col = 1;}else{$col = 2; $count = 0;} echo $col;?>">
                                <h3><?php the_title(); ?></h3>
                                <p><?php the_excerpt();?></p>
                                <div class="relative"> <a class="button-2" href="#">Read More</a> </div>
                            </article>
                            <?php endwhile; endif; ?>
                        </div>
                    </div>
                </div>
                <div class="padding-2">
                    <div class="indent-top">
                        <div class="wrapper">
                            <article class="col-3">
                                <h4><strong>Welcome</strong> <em>to Our Site!</em></h4>
                                <p class="color-2 p1">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudan tium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo nemo enim ipsam voluptatem quia voluptas:</p>
                                <ul class="list-1">
                                    <li><a href="#">Sit aspernatur aut odit aut fugit quia consequuntur</a></li>
                                    <li><a href="#">Magni dolores eos qui ratione voluptatem sequi nesciunt eque</a></li>
                                    <li><a href="#">Qui dolorem ipsum quia dolor sit amet, consectetur adipisci</a></li>
                                    <li><a href="#">Sed quia non numquam eius modi tempora incidunt</a></li>
                                </ul>
                            </article>
                            <div class="extra-wrap"> <a href="#"><img src="<?php bloginfo('stylesheet_directory');?>/images/banner-1.jpg" alt="" /></a> </div>
                        </div>
                    </div>
                </div>
            </section>
            <div class="block"></div>
        </div>
    </div>
</div>
<?php get_footer();?>

