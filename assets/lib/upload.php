<?php
session_start();
$root = dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))));
			if (file_exists($root.'/wp-load.php')) {
			require_once($root.'/wp-load.php');
}
if ( ! defined( 'ABSPATH' ) ) exit;  /* direct access prohibited  */

 function GetImageExtension($imagetype)
    {
        if (empty($imagetype))
            return false;
        switch ($imagetype) {
            case 'image/bmp':
                return '.bmp';
            case 'image/gif':
                return '.gif';
            case 'image/jpeg':
                return '.jpg';
            case 'image/png':
                return '.png';
            default:
                return false;
        }
    }
   
if(isset($_FILES['image']['name'])){

        $iWidth = $iHeight = 200; // desired image result dimensions
		$iJpgQuality = 9;	
		$hh=$_POST['h'];
		$ww=$_POST['w'];
		$xx1=$_POST['x1'];
		$yy1=$_POST['y1'];		
        
		$ext = GetImageExtension($_FILES['image']['type']);
		if ($ext != '') {
            $image            = $_FILES['image'];
            $upload_overrides = array(
                'test_form' => false
            );

			if (!function_exists('wp_handle_upload'))
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            $movefile = wp_handle_upload($image, $upload_overrides);	
	
			$explodedfilepath = explode("uploads", $movefile['file']);
            $fronturl         = $explodedfilepath[0];
            $backurl          = $explodedfilepath[1];

            /* thumb variables */
            
            $target_path = $movefile['file'];
            $thumbp      = explode('/', $backurl);
            if(sizeof($thumbp)>2){	

           if(is_multisite() && !is_main_site()){
				
				$image_name  = $thumbp[5];
				$thumb_name  = "/" . $thumbp[3] . "/" . $thumbp[4]."/th_" . $image_name;
				$thumb_path  = $fronturl . "uploads/" . $thumbp[1] . "/" . $thumbp[2] .  "/th_" . $thumbp[3] . "/" . $thumbp[4] ."/" . $thumbp[5];

				  }else{
				$image_name  = $thumbp[3];
				$thumb_name  = "/". $thumbp[1] . "/" . $thumbp[2] ."/th_" . $image_name;
				$thumb_path  = $fronturl . "uploads/" . $thumbp[1] . "/" . $thumbp[2] .  "/th_" . $thumbp[3];
				}
			 }
						   
			else{
				$image_name= $thumbp[1];
				$thumb_name= "/th_" . $thumbp[1];
				$thumb_path  = $fronturl . "uploads/th_" . $thumbp[1];
			}

            $sTempFileName =$thumb_path;
					if (preg_match('/[.](jpg)$/', $movefile['file'])) {
						$vImg = imagecreatefromjpeg($target_path);
					} else if (preg_match('/[.](gif)$/', $movefile['file'])) {
						$vImg = imagecreatefromgif($target_path);
					} else if (preg_match('/[.](png)$/', $movefile['file'])) {
						$vImg = ImageCreateFromPNG($target_path);
					}
	
                    // create a new true color image
                    $vDstImg = @imagecreatetruecolor( $iWidth, $iHeight );

                    // copy and resize part of an image with resampling
					imagecopyresampled($vDstImg, $vImg, 0, 0, (int)$xx1, (int)$yy1, $iWidth, $iHeight, (int)$ww, (int)$hh);
	
                    // define a result image filename
                    $sResultFileName = $sTempFileName;

					// output image to file
					if (preg_match('/[.](jpg)$/', $sResultFileName)) {
						imagejpeg($vDstImg, $thumb_path,$iJpgQuality);
					} else if (preg_match('/[.](gif)$/', $sResultFileName)) {
						imageGif($vDstImg, $thumb_path,$iJpgQuality);
					} else if (preg_match('/[.](png)$/', $sResultFileName)) {
						ImagePng($vDstImg, $thumb_path,$iJpgQuality);
					}	
					unlink($movefile['file']);
					echo $thumb_name;die();
					}
		}	
?>