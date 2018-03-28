<?php get_header(); ?>

<section>
	<div class="container">
		<?php if(have_posts()): ?>
			<?php while(have_posts()): the_post(); ?>
					<?php get_template_part('content'); ?>
			<?php endwhile; ?>
		<?php else: ?>
				<div class="row">
					<div class="col-xs-12">
						<p>pas de résultats</p>
					</div>
				</div> 
		<?php endif; ?>
	</div>
</section>
<?php get_footer(); ?>