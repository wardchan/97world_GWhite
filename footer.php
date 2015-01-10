<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content
 * after.  Calls sidebar-footer.php for bottom widgets.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
?>
	</div><!-- #main -->

	<div id="footer" role="contentinfo">
		<div id="colophon">

<?php
	/* A sidebar in the footer? Yep. You can can customize
	 * your footer with four columns of widgets.
	 */
	get_sidebar( 'footer' );
?>

			<div id="site-info">
				<span class="wp_credit"><a href="<?php echo esc_url( __('http://wordpress.org/', 'twentyten') ); ?>" title="<?php esc_attr_e('Semantic Personal Publishing Platform', 'twentyten'); ?>" rel="generator"><?php _e('Powered By Wordpress', 'Twenty Ten' ); ?></a></span> Copyright &copy; 2010 <a href="<?php echo home_url( '/' ) ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>. Theme G-White by Alan Ouyang.
				<script src="http://s9.cnzz.com/stat.php?id=3827267&web_id=3827267" language="JavaScript"></script>.
				<script type="text/javascript">var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3Fcfe25940b12793e62f3a5b168285e0bc' type='text/javascript'%3E%3C/script%3E"));</script>.
				<a href="http://www.97world.com/sitemap.html" target="_blank">&#x7F51;&#x7AD9;&#x5730;&#x56FE;</a>.
			</div><!-- #site-info -->

			<div id="site-generator">
				<a href="#header" rel="nofollow" title="Go to the header">Go Top</a>
			</div><!-- #site-generator -->

		</div><!-- #colophon -->
	</div><!-- #footer -->

</div><!-- #wrapper -->

<?php
	/* Always have wp_footer() just before the closing </body>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to reference JavaScript files.
	 */

	wp_footer();
?>
</body>
</html>
