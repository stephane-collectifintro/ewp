<?php 

function ewp_build_options_page(){  

	$theme_opts = get_option('ewp_opts');
?>
	<div class="wrap">
		<div class="container">
			<?php if(isset($_GET['status']) && $_GET['status'] == 1):?>
				<div class="alert alert-success">Mise à jour ok</div>
			<?php endif;?>
			<h2>Options de mon thème</h2>

			<form id="form-ewp-options" class="form-horizontal" method="post" action="admin-post.php">
				<input type="hidden" name="action" value="ewp_save_options">
				<?php 
				// wp_nonce_field est utilisé pour valider le contenu de la requête de formulaire provenant du site actuel et pas ailleurs. 
				wp_nonce_field('ewp_options_verify'); ?>
				<div class="col-xs-12">
					<h3>Image de la page d'accueil</h3>
					<div class="row">
						<div class="col-lg-5">
							<img class="img-responsive img-thumbnail" alt="" src="<?php echo $theme_opts['legend_01_url'] ?>" style="margin-bottom:20px" id="img_preview_01"></img>
						</div>
						<div class="col-lg-6 col-lg-offset-1">
							<button class="btn btn-primary btn-lg btn-select-img" type="button" id="btn_img_01"> Choisir une image</button>
						</div>
					</div>
					<div class="form-group">
						<label for="ewp_image_01" class="col-sm-2 control-label">Image sauvegardé</label>	
						<div class="col-sm-10">
							<input type="text" width="300" id="ewp_image_01" name="ewp_image_01" 
							value="<?php echo $theme_opts['image_01_url']; ?>" disabled></input>
							<input type="hidden" width="300" id="ewp_image_url_01" name="ewp_image_url_01" 
							value="<?php echo $theme_opts['image_01_url']; ?>"></input>
						</div>
					</div>
				</div>
				<div class="col-xs-12">
					<div class="form-group">
						<label for="ewp_legend_01" class="col-sm-2 control-label">Légende</label>	
						<div class="col-sm-10">
							<input type="text" id="ewp_legend_01" name="ewp_legend_01" 
							value="<?php echo $theme_opts['legend_01']; ?>" ></input>
						</div>
					</div>
				</div>

				<p class="submit">
					<input id="validator" type="submit" class="btn btn-success btn-lg" value="Mettre à jour" />
				</p>
			</form>
		</div>
	</div>
<?php } ?>