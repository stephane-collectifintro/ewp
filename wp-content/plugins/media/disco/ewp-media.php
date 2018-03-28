<?php 

// ==========================================================================
// Custom post type ewp_media
// ==========================================================================

function ewp_media_init(){

	// On rentre les différentes dénominations de notre custom post type qui seront affichées dans l'administration
	$labels = array(
		// Le nom au pluriel
		'name'                => ( 'Discotheque'),
		// Le nom au singulier
		'singular_name'       => ( 'CD'),
		// Le libellé affiché dans le menu
		'menu_name'           => __( 'Discotheque'),
		// Les différents libellés de l'administration
		'all_items'           => __( 'Voir la liste'),
		'view_item'           => __( 'Voir l\élément'),
		'add_new_item'        => __( 'Ajouter un élément à la disco'),
		'add_new'             => __( 'Ajouter un élément'),
		'edit_item'           => __( 'Modifier la disco'),
		'search_items'        => __( 'chercher un media'),
		'not_found'           => __( 'Aucun élément trouvé'),
		'not_found_in_trash'  => __( 'Aucun média dans la corbeille'),
	);
	
	// On peut définir ici d'autres options pour notre custom post type
	
	$args = array(
		'labels'=>$labels,
		'public'=>true,
		'public_queryable'=>true,
		'show_ui'=>true,
		'show_in_menu'=>true,
		'query_var'=>true,
		'rewrite' => array( 'slug' => 'disc'),
		'capability_type'=>'post',
		'has_archive'=>true,
		'hierachical'=>false,
		'menu_position'=>20,
		'menu_icon'=> get_stylesheet_directory_uri().'/assets/disc_16.png',
		'publicly_queryable'=>true,
		'exclude_from_search'=>false,
		//'taxonomies'=>array('post_tag','category'),
		'supports'=>array('title','editor','thumbnail')
	);


	register_post_type('ewp_media',$args);
}

// Action hook to initialize the custom post type ewp_media
add_action('init','ewp_media_init');


// ==========================================================================
// meta boxes pour custom post type ewp_media
// ==========================================================================

// https://developer.wordpress.org/reference/functions/add_meta_box/
function ewp_media_register_meta_box(){
	 add_meta_box( 
        'ewp_media_meta',
        'Références du CD',
        'ewp_media_meta_building',
        'ewp_media',
        'normal',
        'high'
    );
}

// Création de mon formulaire
function ewp_media_meta_building($post){

	$ewp_meta_an = get_post_meta($post->ID,'_media_meta_an',true);
	$ewp_meta_editeur = get_post_meta($post->ID,'_media_meta_editeur',true);


	wp_nonce_field('ewp_media_meta_box_saving','ewp_25896');

	$ewp_years = array();
	$ewp_years[0] = 'compil';
	for($i=1970; $i<2000; $i++) $ewp_years[] = $i;

	echo '<div>';
	echo '<p><label for="media_detail_an"> Année -&gt;</label>';
	echo '<select id="media_detail_an" name="media_detail_an">';
		foreach ($ewp_years as $ewp_year):
			echo '<option value="'.$ewp_year.'">'.$ewp_year.'</option>';
			echo '<option value="'.$ewp_year.'"'.selected($ewp_meta_an,$ewp_year,false).'>'.$ewp_year.'</option>';
		endforeach;
	echo '</select></p>';

	echo '<p><label for="media_detail_editor">Editeur -&gt; </label>';
	echo '<input type="text" size="30" value="'.$ewp_meta_editeur.'" id="media_detail_editeur" name="media_detail_editeur"></p>';

	echo '</div>';
}

add_action('add_meta_boxes','ewp_media_register_meta_box');

// ==========================================================================
// Sauvegarde meta boxes pour custom post type ewp_media
// ==========================================================================

function ewp_media_save_meta_box($post_id){
	if(get_post_type($post_id) == 'ewp_media' && isset($_POST['media_detail_an'])){

		//empeche l'autosave de sauvegarder notre valeur
		if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE){return;}
		//Verification du formulaire
		check_admin_referer('ewp_media_meta_box_saving','ewp_25896');
		//sauvegarde dans la BDD champ wp_postmeta
		update_post_meta($post_id,'_media_meta_an', sanitize_text_field($_POST['media_detail_an']));
		update_post_meta($post_id,'_media_meta_editeur', sanitize_text_field($_POST['media_detail_editeur']));
	}
}

add_action('save_post','ewp_media_save_meta_box');


// ==========================================================================
// Ajout de l'image et année dans la colonne admin pour le ewp_media
// ==========================================================================

/* Ajout de l'image et ordre sans la colonne admin pour le slider
*	format du hook: manage_edit-{custom-post-type}_columns
*/

add_filter('manage_edit-ewp_media_columns','ewp_col_change2');

function ewp_col_change2($columns){
	$columns['ewp_media_annee']   = "Année";
	$columns['ewp_media_editeur'] = "Editeur";
	$columns['ewp_media_image']   = "Image affichée";

	return $columns;
}
// ==========================================================================
// 	manage posts custom column
//	HOOK: manage_{custom-post-type}_posts_custom_column
//	https://codex.wordpress.org/Plugin_API/Action_Reference/manage_posts_custom_column
// ==========================================================================

add_action('manage_ewp_media_posts_custom_column','ewp_content_show2',10,2);
function ewp_content_show2($column, $post_id){
	global $post;
	switch ($column) {
		case 'ewp_media_image':
			echo the_post_thumbnail(array(120,120));
			break;
		
		case 'ewp_media_annee':
			echo $ewp_meta_annee = get_post_meta($post_id, '_media_meta_an',true);
			break;
		case 'ewp_media_editeur':
			echo $ewp_meta_annee = get_post_meta($post_id, '_media_meta_editeur',true);
			break;
	}
}

// ==========================================================================
// custom taxonomy ewp-media
// ==========================================================================


function ewp_define_taxonomy_disco() {
	// Add new taxonomy, make it hierarchical (like categories)
	$labels = array(
		'name'              => 'style musicaux',
		'singular_name'     => 'style',
		'search_items'      => 'rechercher les styles' ,
		'all_items'         => 'tous les styles',
		'edit_item'         => 'modifier le style',
		'update_item'       => 'Umettre à jour le style',
		'add_new_item'      => 'ajouter un style',
		'new_item_name'     => 'nouveau nom du style',
		'menu_name'         => 'styles des disques',
	);

	$args = array(
		'labels'            => $labels,
		'public'			=>true,
		'public_queryable'  =>true,
		'hierarchical'      => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'style' ),
		'show_admin_column' => true,
	);

	register_taxonomy( 'genre_mus','ewp_media', $args );
}

add_action( 'init', 'ewp_define_taxonomy_disco');
