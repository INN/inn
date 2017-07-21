<?php
/**
 * The homepage template
 *
 */

/**
 * ======== DO NOT EDIT OR CLONE THIS FILE FOR A CHILD THEME =======
 *
 * Largo comes with a built-in homepage template system, documented in homepages/README.md
 * It's generally better to use that system than to have your child theme use its own home.php template
 * This is modified from Largo's in order to remove the containing div#content
 */

get_header();

/*
 * Collect post IDs in each loop so we can avoid duplicating posts
 * and get the theme option to determine if this is a two column or three column layout
 */
$home_template = largo_get_active_homepage_layout();

global $largo;

largo_render_homepage_layout($home_template);

get_footer();
