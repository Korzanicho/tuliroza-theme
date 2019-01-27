<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package storefront
 */

?>

</div><!-- .col-full -->
</div><!-- #content -->

<?php do_action( 'storefront_before_footer' ); ?>

<footer id="colophon" class="site-footer" role="contentinfo">
    <div class="col-full">

        <?php
        /**
			 * Functions hooked in to storefront_footer action
			 *
			 * @hooked storefront_footer_widgets - 10
			 * @hooked storefront_credit         - 20
			 */
        do_action( 'storefront_footer' );
        ?>

    </div><!-- .col-full -->
    <!--Średni Pasek Widgetów-->
    <div class="sidebar-footermiddle">
        <div class="col-full">
            <?php get_sidebar('footermiddle'); ?>
        </div>
    </div>
    
    <!--Dolny Pasek Widgetów-->
    <div class="sidebar-footerbottom">
        <div class="col-full">
            <?php get_sidebar('footerbottom'); ?>
            <div class="footerbottom-divider"></div>
            <div class="footer-credits">
                <p style="display:inline-block;">Copyright 2018&copy; Tuliróża</p>
                <p style="display:inline-block; float:right;">Create by: Adrian Korzan & Ksybek Artur</p>
            </div>
        </div>
    </div>
</footer><!-- #colophon -->

<?php do_action( 'storefront_after_footer' ); ?>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
