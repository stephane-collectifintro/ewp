<?php 

	//requete creation du slider

	$args = array(
		'post_type' => 'ewp_slider',
		'posts_per_page' => -1,
		'orderby' => 'menu_order',
		'order' => 'ASC'
	);

	$slider_query = new WP_Query($args);
?>


<?php if($slider_query->have_posts()): ?>
<section>
	<div class="container">
		<div id="slider01" class="carousel slide" data-ride="carousel">
		  <ol class="carousel-indicators">
		  	<?php 
		  		$indicator = 0;
		  		while ( $slider_query->have_posts() ): 
		  			$slider_query->the_post();
		  			echo '<li data-target="#slider'.$indicator.'" data-slide-to="'.$indicator.'" class="'.($indicator == 0 ? "active" : "").'"></li>';
		  			$indicator++;
		  		endwhile;
		  	?>	
		  </ol>
		  <?php rewind_posts(); ?>
		  <div class="carousel-inner">

		  	<?php 
		  		
			  	$active = true;
			  	while ( $slider_query->have_posts() ): 
				  	$slider_query->the_post(); 
				  	$thumbnail_html = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'front-slider');
		  			$thumbnail_src = $thumbnail_html['0'];
				  	if($active): 
				  		$classActive = 'active';
				  	else: 
				  		$classActive = "";
				  	endif;
		  	?>
		    <div class="carousel-item <?php echo $classActive; ?>">
		      <img class="d-block w-100" src="<?php echo $thumbnail_src; ?>" alt="First slide">
		    </div>
		    <?php 
		    	$active = false;
		    	endwhile; 
		    	wp_reset_postdata(); ?>
		  </div>


		  <a class="carousel-control-prev" href="#slider01" role="button" data-slide="prev">
		    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
		    <span class="sr-only">Previous</span>
		  </a>
		  <a class="carousel-control-next" href="#slider01" role="button" data-slide="next">
		    <span class="carousel-control-next-icon" aria-hidden="true"></span>
		    <span class="sr-only">Next</span>
		  </a>
		</div>
	</div>
</section>
<?php endif; ?>