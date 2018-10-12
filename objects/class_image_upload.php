<?php
class appointment_image_upload
{
    
    /* Object property Image Name */
    public $image_obj_name;
    
    /* Object property Image Temp Name */
    public $image_obj_tmp_name;
    
    /* Object property Image Type */
    public $image_obj_type;
    
    /* Object property Image Target Path */
    public $image_traget_path;
    
    /* Object property Image Thumb Height */
    public $image_thumb_height;
    
    /* Object property Image Thumb Width */
    public $image_thumb_width;
    
    /* Object property Image Name */
    public $image_name;
    
    /* Object property Image Type */
    public $image_type;
    
    
    /**
     * Get Image File Extension
     * @param Image Type (extension)
     * @return (.bmp,.gif,.jpg,.png) default - false
     */
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
    
    
    /**
     * Image Upload
     * @return Full Image Name
     */
    function image_upload()
    {
        if (!function_exists('wp_handle_upload'))
            require_once(ABSPATH . 'wp-admin/includes/file.php');
			$imgtype = $this->image_obj_type;
			$ext     = $this->GetImageExtension($imgtype);
        if ($ext != '') {
            $image            = $this->image_obj_name;
            $upload_overrides = array(
                'test_form' => false
            );
            $movefile         = wp_handle_upload($image, $upload_overrides);
            $explodedfilepath = explode("uploads", $movefile['file']);
            $fronturl         = $explodedfilepath[0];
            $backurl          = $explodedfilepath[1];
            
            
            $image_type      = $image['type'];
            $image_temp_name = $image['tmp_name'];
            /* thumb variables */
            
            $target_path = $movefile['file'];
            $thumbp      = explode('/', $backurl);
            if(sizeof($thumbp)>2){	

           if(is_multisite() && !is_main_site()){
				
				$image_name  = $thumbp[5];
				$thumb_name  = "/" . $thumbp[3] . "/" . $thumbp[4]."/th_" . $image_name;
				$thumb_path  = $fronturl . "uploads/" . $thumbp[1] . "/" . $thumbp[2] .  "/" . $thumbp[3] . "/" . $thumbp[4] ."/th_" . $thumbp[5];

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
            
            
            if ($movefile) {
                /* Creating thumb for provider  */
                if (preg_match('/[.](jpg)$/', $movefile['file'])) {
                    $im = imagecreatefromjpeg($target_path);
                } else if (preg_match('/[.](gif)$/', $movefile['file'])) {
                    $im = imagecreatefromgif($target_path);
                } else if (preg_match('/[.](png)$/', $movefile['file'])) {
                    $im = ImageCreateFromPNG($target_path);
                }
                
                $ox = imagesx($im);
                $oy = imagesy($im);
                $nx = 80;
                $ny = floor($oy * (80 / $ox));
                $nm = imagecreatetruecolor($nx, $ny);
                imagealphablending($nm, false);
                imagesavealpha($nm, true);
                $trans_layer_overlay = imagecolorallocatealpha($nm, 220, 220, 220, 127);
                imagefill($nm, 0, 0, $trans_layer_overlay);
                
                imagecopyresized($nm, $im, 0, 0, 0, 0, $nx, $ny, $ox, $oy);
                
                
                if (preg_match('/[.](jpg)$/', $image_name)) {
                    imagejpeg($nm, $thumb_path);
                } else if (preg_match('/[.](gif)$/', $image_name)) {
                    imageGif($nm, $thumb_path);
                } else if (preg_match('/[.](png)$/', $image_name)) {
                    ImagePng($nm, $thumb_path);
                }
				
                return $thumb_name;
            }
        } else {
            return "apt_image_error";
        }
    }
    
}