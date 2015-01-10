<?php
/*
Template Name: Tags
*/
?>
<?php get_header(); ?>
<!-- Container -->

<div id="container"><div id="content">
	<h1 class="entry-title">Tags</h3>
	<div id="tag_page" style="margin: 15px 0 0;">
		<?php wp_tag_cloud( 'number=0' ); ?>
	</div>
</div></div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
