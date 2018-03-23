<?php
/**
 * Enqueue scripts and styles.
 *
 * @since EasyWebPro 1.0
 */


/**
* CHARGEMENT DES SCRIPTS ET STYLES
**/

function ewp_scripts() {

	// Add stylesheets.
	wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/vendor/bootstrap/css/bootstrap.min.css', array(), '4.0','all' );
	wp_enqueue_style( 'fontawesome', get_template_directory_uri() . '/vendor/fontawesome/css/fontawesome-all.min.css', array(), '5.0','all');
	wp_enqueue_style( 'ewp_custom', get_template_directory_uri() . '/css/styles.css', array('fontawesome','bootstrap'), '1.0','all' );
	// Add scripts.
	wp_enqueue_script( 'bootstrap-script', get_template_directory_uri() . '/vendor/bootstrap/js/bootstrap.min.js', array( 'jquery' ), '1.0', true );
	wp_enqueue_script( 'ewp-script', get_template_directory_uri() . '/js/custom.js', array( 'jquery','bootstrap-script' ), '4	.0', true );

}
add_action( 'wp_enqueue_scripts', 'ewp_scripts' );


function ewp_setup() {
	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	// Créer format d'image
	add_image_size('front-slider',1140,420,true);
	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * See: https://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 825, 510, true );

	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'primary' => 'Principal',
		'social'  => 'Réseaux sociaux',
	) );

}// ewp_setup
add_action( 'after_setup_theme', 'ewp_setup' );

// Initialisation de l'admin
function ewp_admin_init(){
	//Chargement des styles et scripts dans le theme admin
	function ewp_admin_scripts(){
		wp_enqueue_style('boostrap', get_template_directory_uri().'/vendor/bootstrap/css/bootstrap.min.css',array(),'4.0');
		//Ajout du script media worpdress
		wp_enqueue_media();
		wp_enqueue_script('ewp-admin-init',get_template_directory_uri() . '/js/admin-options.js', array('jquery'),'1.0',true);

	}
	add_action('admin_enqueue_scripts','ewp_admin_scripts');

	include('inc/save-options-page.php'); // function pour save les options 
	add_action('admin_post_ewp_save_options','ewp_save_options');
};
add_action('admin_init', 'ewp_admin_init');


//Activation des otptions
function ewp_activ_options(){
	$theme_opts = get_option('ewp_opts');

	if(!$theme_opts){
		$opts = array(
			'image_01_url'	=>	'',
			'legend_01' 	=>	''
		);
		add_action('ewp_opts',$opts);
	}
}
add_action('after_switch_theme','ewp_activ_options');

/* Menu Options du Thème
* add_menu_page( string $page_title, string $menu_title, string $capability, string $menu_slug, callable $function = '', string $icon_url = '', int $position = null )
* capability (role de l'utilisateur) https://codex.wordpress.org/Roles_and_Capabilities
*/
function ewp_admin_menu(){
	add_menu_page(
		'Options',
		'Options du thème',
		'publish_pages',
		'ewp_theme_opts',
		'ewp_build_options_page'
	);
	include('inc/build-options-page.php'); //Contient la construciton de l'option
}
add_action('admin_menu','ewp_admin_menu');

/*
* Ajout des widget dans l'admin
*/

function ewp_widgets_init() {
    register_sidebar( array(
        'name' 			=>  'Footer Widget zone',
        'id' 			=> 'widgetized_footer',
        'description' 	=> 'Widgets affichés dans le footer: 4 au max ',
        'before_widget' => '<div id="%1$s" class="col-xs-3 %2$s"><div class="inside-widget"',
		'after_widget'  => '</div></div>',
		'before_title'  => '<h2 class="h3 text-center">',
		'after_title'   => '</h2>',
    ) );

}
add_action( 'widgets_init', 'ewp_widgets_init' );

/*
*	Custom Post-type -> creation d'un module carrousel dans l'admin
*	https://codex.wordpress.org/Function_Reference/register_post_type
*/
function ewp_slider_init(){
	$labels = array(
		'name'=>'Image Carousel Accueil',
		'singular_name'=>' Image accueil',
		'add_new'=>'Ajouter une image',
		'add_new_item'=>'Ajouter une image accueil',
		'new_item'=>'Nouveau',
		'all_items'=>'Voir la liste',
		'view_item'=>'voir l\'élément',
		'search_item'=>'chercher une image accueuil',
		'not_found'=>'aucun élémement trouvé',
		'not_found_in_trash'=>'Aucun élément dans la corbeille',
		'menu_name'=>'Slider frontal',
	);

	$args = array(
		'labels'=>$labels,
		'public'=>true,
		'public_queryable'=>true,
		'show_ui'=>true,
		'show_in_menu'=>true,
		'query_var'=>true,
		'rewrite'=>true,
		'capability_type'=>'post',
		'has_archive'=>false,
		'hierachical'=>false,
		'menu_position'=>20,
		'menu_icon'=> get_stylesheet_directory_uri().'/assets/camera_16.png',
		'publicly_queryable'=>false,
		'exclude_from_search'=>true,
		'supports'=>array('title','page-attributes','thumbnail')
	);

	register_post_type('ewp_slider',$args);

}
add_action('init', 'ewp_slider_init');

/* Ajout de l'image et ordre sans la colonne admin pour le slider
*	format du hook: manage_edit-{custom-post-type}_columns
*/

add_filter('manage_edit-ewp_slider_columns','ewp_col_change');

function ewp_col_change($columns){
	$columns['ewp_slider_image_order'] = "Ordre";
	$columns['ewp_slider_image'] = "Image affichée";

	return $columns;
}
/*
*	manage posts custom column
*	HOOK: manage_{custom-post-type}_posts_custom_column
*	https://codex.wordpress.org/Plugin_API/Action_Reference/manage_posts_custom_column
*/
add_action('manage_ewp_slider_posts_custom_column','ewp_content_show',10,2);
function ewp_content_show($column, $post_id){
	global $post;
	switch ($column) {
		case 'ewp_slider_image':
			echo the_post_thumbnail(array(100,100));
			break;
		
		case 'ewp_slider_image_order':
			echo $post->menu_order;
			break;
	}
}

add_filter('manage_edit-ewp_slider_sortable_columns','my_sortable_slider_column');

function my_sortable_slider_column($column){
	$columns['ewp_slider_image_order'] = 'menu_order';
	return $columns; 
}

?>