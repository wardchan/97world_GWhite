<?php /*
2	Template Name: Links
3	*/ ?>


<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>

		<div id="container">
			<div id="content" role="main">
				<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<?php if ( is_front_page() ) { ?>
						<h2 class="entry-title"><?php the_title(); ?></h2>
					<?php } else { ?>
						<h1 class="entry-title"><?php the_title(); ?></h1>
					<?php } ?>
					<div class="entry-content">
						<?php the_content(); ?>
						<div class="page-links">
							<h3>首页/全站链接</h3>
							<ul>
								<?php
								$default_ico ='http://www.google.com.hk/s2/favicons?domain=fredomfeng.com'; //默认 ico 图片位置
								$bookmarks = get_bookmarks('title_li=&categorize=0&category=3'); //全部链接随机输出
								//如果你要输出某个链接分类的链接，更改一下get_bookmarks参数即可
								//如要输出链接分类ID为5的链接 title_li=&categorize=0&category=5&orderby=rand
								if ( !empty($bookmarks) ) {	
									foreach ($bookmarks as $bookmark) {
										echo '<li><img src="', $bookmark->link_url , '/favicon.ico" onerror="javascript:this.src=\'' , $default_ico , '\'" /><a href="' , $bookmark->link_url , '" title="' , $bookmark->link_description , '" target="_blank" >' , $bookmark->link_name , '</a></li>';
									}
								}
								?>
							</ul>
						</div>
        				<div class="page-links">
							<h3>内页链接</h3>
							<ul>
								<?php
								$default_ico ='http://www.google.com.hk/s2/favicons?domain=fredomfeng.com'; //默认 ico 图片位置
								$bookmarks = get_bookmarks('title_li=&categorize=0&category=206'); //全部链接随机输出
								//如果你要输出某个链接分类的链接，更改一下get_bookmarks参数即可
								//如要输出链接分类ID为5的链接 title_li=&categorize=0&category=5&orderby=rand
								if ( !empty($bookmarks) ) {
									foreach ($bookmarks as $bookmark) {
									echo '<li><img src="', $bookmark->link_url , '/favicon.ico" onerror="javascript:this.src=\'' , $default_ico , '\'" /><a href="' , $bookmark->link_url , '" title="' , $bookmark->link_description , '" target="_blank" >' , $bookmark->link_name , '</a></li>';
									}
								}
								?>
							</ul>
						</div>
						<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'twentyten' ), 'after' => '</div>' ) ); ?>
						<?php edit_post_link( __( 'Edit', 'twentyten' ), '<span class="edit-link">', '</span>' ); ?>
					</div><!-- .entry-content -->
				</div><!-- #post-## -->
				<?php comments_template( '', true ); ?>
<?php endwhile; ?>
			</div><!-- #content -->
		</div><!-- #container -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
