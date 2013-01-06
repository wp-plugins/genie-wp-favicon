<div class="wrap">
<?php
if (!function_exists('is_admin')) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit ();
}

screen_icon("themes");
echo "<h2>" . __('Favicon Configuration', 'genie_wp_favicon') . "</h2>";

$gwpfSetupModel = new GwpfSetupModel();
$state = null;

if (isset($_POST['plugin_submitted']) && $_POST['plugin_submitted'] == 'Y') {
	$photoObj = $_FILES["gwpf_favicon_img"] ;
	if( $photoObj["size"] != 0 ) {
		$value = $gwpfSetupModel->savePhotoToUploadFolder($photoObj);
		echo '<br /><div class="updated"><p>Favicon updated successfully.</p></div>' ;
	}
	$state = 2 ;
}

$faviconSetup = $gwpfSetupModel->getFaviconState() ;

?>
<br />
<strong>The Genie WP Favicon does saves the images 'as is' what ever you upload. It does not process the images in any sort. <br />
The idle size for the Favicon icon is 16x16 or 32x32, with a max of 128x128. Make sure to process the image before uploading.<br /></strong>
<form name="gwpf_form" method="post" enctype="multipart/form-data"
			 action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" >
	<p><?php    echo "<h4>" . __( 'Select Favicon: ', 'genie_wp_favicon' ) . "</h4>"; ?>
	<input type="file" name="gwpf_favicon_img" id="gwpf_favicon_img" /> &nbsp; <strong>(.ico, .png, .gif - 100Kb Max)</strong>
	<input type="hidden" name="plugin_submitted" id="plugin_submitted" value="Y">
	</p>
	<?php
	 if(isset($faviconSetup) && isset($faviconSetup['state']) && $faviconSetup['state'] == 1 && $state != 2) {
	 	echo "<br /><p>Note: You already have a Favicon configured. Upadating the setup will replace the older Favicon.<br />" .
	 			"<br />Current Favicon: <br /><img src='" . GWPF_FAVICON_URL . URL_S . $faviconSetup['name'] . "' alt='Favicon' /></p>";
	 } else if( isset($faviconSetup) && $state) {
	 	echo "<br /><p>Updated Favicon: <br /><img src='" . GWPF_FAVICON_URL . URL_S . $faviconSetup['name'] . "' alt='Favicon' /></p>" ;
	 }
	?>
	<p class="submit">
	<input type="submit" name="SubmitForm" value="<?php _e('Update Favicon', 'genie_wp_favicon' ) ?>" />
	</p>
</form>

</div>