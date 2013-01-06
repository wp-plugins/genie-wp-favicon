<?php

/*
 * Created on Jan 5, 2013
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

class GwpfSetupModel {
	
	function GwpfSetupModel() {

	}
	function getFaviconState() {
		global $wpdb;
		$favicon = array();
		$faviconName = get_option('gwpf_favicon');
		if(isset($faviconName) && $faviconName != "") {
			$favicon['state'] = 1 ;
			$favicon['name'] = $faviconName ;
		}
		return $favicon ;
	}

	function savePhotoToUploadFolder($photo) {

		$photoId = "favicon";
		$errorOccured = false; 
		$errorMsg = "" ;
		if ($photo["type"] == "image/gif") {
			$photoEXT = ".gif";
			$pType = 0;
		}
		elseif ($photo["type"] == "image/x-icon") {
			$photoEXT = ".ico";
			$pType = 1;
		}
		elseif ($photo["type"] == "image/png") {
			$photoEXT = ".png";
			$pType = 2;
		}
		list ($width, $height) = getimagesize($photo["tmp_name"]);
		if (!isset ($pType)) {
			$errorMsg .= "Upload of profile photo failed: Image object is empty or invalid image type (Only .ico, .png, .png allowed).  " ;
			$errorOccured = true;
		} else if($photo["size"] > (100 * 1024)) {
			$errorMsg .=  "Upload of profile photo failed: Image size greater than 100 Kb. " ;
			$errorOccured = true;
		} else if($width > 128 || $height > 128) {
			$errorMsg .=  "Upload of profile photo failed: Image width or height greater than 128. " ;
			$errorOccured = true;
		} else {
			if ($photo["error"] > 0) {
				$errorMsg .= ("Upload of favicon failed. Error Code: " . $photo["error"]);
				$errorOccured = true;
			} else {
				$uploadURL = GWPF_FAVICON_DIR . URL_S ;
				$photo["name"] = $photoId . $photoEXT;

				if (!is_dir(WP_CONTENT_DIR . URL_S . 'uploads')) {
					mkdir(WP_CONTENT_DIR . URL_S . 'uploads', 0755);
				}

				if (!is_dir(GWPF_FAVICON_DIR)) {
					mkdir(GWPF_FAVICON_DIR, 0755);
				}

				if (!is_dir($uploadURL)) {
					mkdir($uploadURL, 0755);
				}

				if (file_exists($uploadURL . $photoId . ".gif")) {
					unlink($uploadURL . $photoId . ".gif");
				}
				if (file_exists($uploadURL . $photoId . ".ico")) {
					unlink($uploadURL . $photoId . ".ico");
				}
				if (file_exists($uploadURL . $photoId . ".png")) {
					unlink($uploadURL . $photoId . ".png");
				}
				
				move_uploaded_file($photo["tmp_name"], $uploadURL . $photo["name"]);
				update_option('gwpf_favicon', $photo["name"]);
				unset ($photo["tmp_name"]);
			}
		}
		
		if($errorOccured) {
			echo '<div class="error"><p>' . $errorMsg . '</p></div>' ;
		}
		
		return $photo;
	}
	
	function removeGWPFDetails() {
		delete_option("gwpf_favicon") ;
	}
}