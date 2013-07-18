<?php
/**
 * Template for membership archive
 */
get_header();

?>

<div id="content" class="stories span12" role="main">
	<header class="archive-background clearfix">
		<h1 class="page-title">Our Members</h1>
		<div class="archive-description">
			<p><?php	_e('All INN members are nonprofit journalism organizations producing non-partisan news in the public interest.', 'inn'); ?></p>
			<?php
				wp_nav_menu( array(
					'theme_location' => 'membership',
					'container' => false,
					'menu_class' => 'members-menu',
					'depth' => 1)
				);
			?>
		</div>
	</header>

		<?php
			//alphabet, abstracted in inn_members.php
			inn_member_alpha_links();

			//map, abstracted in inn_members.php
			inn_member_map();
		?>

		<?php if ( have_posts() ) {

			while ( have_posts() ) : the_post();
				get_template_part( 'content', 'member' );
			endwhile;

			//largo_content_nav( 'nav-below' );
			?>
			<nav id="nav-below" class="pager post-nav">
				<div class="next"><?php next_posts_link( __( 'Next Page &rarr;', 'largo' ) ); ?></div>
				<div class="previous"><?php previous_posts_link( __( '&larr; Previous Page', 'largo' ) ); ?></div>

			</nav><!-- .post-nav -->

			<?php
		}

		?>

</div><!--#content-->
<?php get_footer(); ?>