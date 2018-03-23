<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * e.g., it puts together the home page when no home.php file exists.
 *
 * Learn more: {@link https://codex.wordpress.org/Template_Hierarchy}
 *
 * @package WordPress
 * @subpackage EsayWeb_Pro
 * @since EasyWeb Pro 1.0
 */

get_header(); ?>

<?php get_template_part('slider-home'); ?>

<div class="container">
  <!-- Content here -->
  <?php the_content(); ?>
  
</div>
<footer>
	<?php if(is_active_sidebar('widgetized_footer')): ?>
		<?php dynamic_sidebar('widgetized_footer'); ?>
	<?php endif; ?>
</footer>
<?php get_footer(); ?>