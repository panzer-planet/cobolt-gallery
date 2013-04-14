<?php
	/**
	 * ThumbsCreator
	 *
	 * This class is used to create thumbnails of larger images;
	 *
	 * ---------------------------------------------------------------------------
	 * cobolt-gallery : Simple, configurable and easy to deploy php photo gallery 
	 * http://code.google.com/p/cobolt-gallery/
	 * Copyright 2012, Werner Roets and the cobolt-gallery contributors
	 *
	 * Licensed under GNU General Public License v3
	 * Redistributions of files must retain the above copyright notice.
	 *
	 * @author		  Werner Roets (cobolt.exe@gmail.com)
	 * @copyright     Copyright 2012, Werner Roets and the cobolt-gallery contributors
	 * @link          http://code.google.com/p/cobolt-gallery/
	 * @version		  0.1beta
	 * @since         0.1alpha
	 * @license       GNU GPL v3 (http://www.gnu.org/copyleft/gpl.html)
	 */
	class ThumbsCreator{
			
		//These variables will be needed by the refactor method
		private $source_width, $source_height, $scale;
		
		//Specify source location and save directory, then thumbnail and pagesize width
		//returns an array of the refactored images' locations.
		public function makeminimes($source_location, $save_dir, $thumbsize_width, $pagesize_width){

			if(!$source_location){ 
				throw new Exception('source location not set when calling makeminimes(source, save_dir, thumb_widtht, pagesize_width');
			}
			if(!$save_dir){ 
				throw new Exception('save_dir not set when calling makeminimes(source, save_dir, thumb_widtht, pagesize_width');
			}
			$info = getimagesize($source_location);
			$this->source_width = $info[0];
			$this->source_height = $info[1];
			$this->scale = $this->source_width/$this->source_height;
			
			//The location of the final images
			$thumb_loc = $save_dir;
			$pagesize_loc = $save_dir;
			
			//Assume we have to do this at first
			$make_pagesize = true;
			if($pagesize_width >= $this->source_width){
				
				$make_pagesize = false;
				$pagesize_loc = $source_location;
				if($thumbsize_width >= $this->source_width){
					
					//This is trying to scale up which we dont want
					//so return the path of the original to use as thumb and page
					$thumb_loc = $source_location;
					$pagesize_loc = $source_location;
					return array('thumbnail_location' => $thumb_loc,
							 'pagesize_location' => $pagesize_loc);
				}
			}
			if($info['mime'] == 'image/png'){
				$source_image = imagecreatefrompng($source_location);
			}else if($info['mime'] == 'image/jpeg'){
				$source_image = imagecreatefromjpeg($source_location);
			}else if($info['mime'] == 'image/gif'){
				$source_location = imagecreatefromgif($source_location);
			}else{
				echo "Image or filetype not supported";
				return false;
			}
			
			//Only make this reduction on big images (not always needed)
			if($make_pagesize){
				$pagesize_image = $this->refactor($source_image, $pagesize_width);
				$pagesize_loc = $save_dir . 'pagesize_'. basename($source_location);
				if($info['mime'] == 'image/png'){
					imagepng($pagesize_image,$pagesize_loc);
				}else if($info['mime'] == 'image/jpeg'){
					imagejpeg($pagesize_image,$pagesize_loc);
				}else if($info['mime'] == 'image/gif'){
					imagegif($pagesize_image,$pagesize_loc);
				}else{
					return false;
				}
			}
			$thumbnail_image = $this->refactor($source_image, $thumbsize_width);
			$thumb_loc = $save_dir . 'thumb_' . basename($source_location);
			if($info['mime'] == 'image/png'){
				imagepng($thumbnail_image,$thumb_loc);
			}else if($info['mime'] == 'image/jpeg'){
				imagejpeg($thumbnail_image,$thumb_loc);
			}else if($info['mime'] == 'image/gif'){
				imagegif($thumbnail_image,$thumb_loc);
			}else{
				return false;
			}
			//Returns where these files are stored and their names
			return array('thumbnail_loc' => $thumb_loc,
							 'pagesize_loc' => $pagesize_loc);
			
		}
		
		private function refactor($source_image, $width){
			//We specify only width as this is generally the constraint on such a gallery
			$refactored_width = $width;
			$refactored_height = $width/$this->scale;
			
			$refactored = imagecreatetruecolor($refactored_width,$refactored_height);
			imagecopyresampled($refactored, $source_image, 0, 0, 0, 0, $refactored_width, $refactored_height, $this->source_width, $this->source_height);
			return $refactored;
		}
	}
?>
