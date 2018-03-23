/**
* admin Scritpt
**/
jQuery(document).ready(function($){

	// Ajout d'une image
   	var frame = wp.media({
      title: 'Selectionner une image',
      button: {
        text: 'Utiliser ce média'
      },
      multiple: false  // Set to true to allow multiple files to be selected
    });

	$('#form-ewp-options #btn_img_01').click(function(e){
		e.preventDefault();
		frame.open();

	 });
	// When an image is selected in the media frame...
    frame.on( 'select', function() {
      
      // Get media attachment details from the frame state
      var objImg = frame.state().get('selection').first().toJSON();
       console.log(objImg);
       var url = objImg.sizes.medium.url;
   
      

      $("img#img_preview_01").attr('src', url);
      $("input#ewp_image_01").attr('value',url);
      $("input#ewp_image_url_01").attr('value',url);

    });

}); // end ready