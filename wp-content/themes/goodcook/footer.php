<!--==============================footer=================================-->
<footer>
    <div class="padding">
        <div class="main">
            <div class="wrapper">
                <div class="fleft footer-text"> <strong>Copyright &copy; goodcook, 2016</strong>
                    <!-- {%FOOTER_LINK} -->
                </div>
                <ul class="list-services">
                    <li>Link to Us:</li>
                    <li><a class="tooltips" href="#"></a></li>
                    <li class="item-1"><a class="tooltips" href="#"></a></li>
                    <li class="item-2"><a class="tooltips" href="#"></a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>
<script type="text/javascript">Cufon.now();</script>
<script type="text/javascript">
    $(window).load(function () {
        $('.slider')._TMS({
            duration: 1000,
            easing: 'easeOutQuart',
            preset: 'simpleFade',
            slideshow: 10000,
            banners: 'fade',
            pauseOnHover: true,
            waitBannerAnimation: false,
            pagination: '.pags'
        });
    });
</script>
<script type="text/javascript">
    $('.alignleft > a').addClass('button-2');
    $('.alignright > a').addClass('button-2');
    $('a.read-more').addClass('button-2');
    $('a.read-more1').addClass('button-1');
</script>
<?php wp_footer();?>
</body>
</html>