<?php 

function ewp_save_options(){ 

	//verifier si l'utilisateur à les droits
	if(!current_user_can('publish_pages')) wp_die('Get out !');
	//check du formulaire
	check_admin_referer('ewp_options_verify');

	$opts = get_option('ewp_opts');

	//Save
	// clear une chaîne de l'utilisateur ou de la base de données.
	$opts['legend_01'] = sanitize_text_field($_POST["ewp_legend_01"]);
	$opts['legend_01_url'] = sanitize_text_field($_POST["ewp_image_url_01"]);

	// update dans la base de données
	update_option('ewp_opts',$opts);

	//Redirects to another page.
	wp_redirect(admin_url('admin.php?page=ewp_theme_opts&status=1'));
	exit;

} 
