<?php
	/**
	 * ThumbsViewer
	 *
	 * ThumbsViewer is used to generate an interface to click through thumbnails.
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

	 require_once 'settings/gallery_settings_manager.php';
	 require_once 'gallery_images.php';
	 require_once 'lib/image.php';
	 require_once 'settings/icon_manager.php';
	 class ThumbsViewer{
	 	private $gallery_settings_manager, $gallery_images, $image_storage_location, $icon_manager;
		private $across, $down, $total_pages, $current_page;
	 	/**
		 * Constructor
		 * 
		 */
	 	public function __construct(){
	 		$this->gallery_settings_manager = new GallerySettingsManager();
			$this->across = $this->gallery_settings_manager->get_setting('x_thumbs');
			$this->down = $this->gallery_settings_manager->get_setting('y_thumbs');
			$this->image_storage_location = $this->gallery_settings_manager->get_setting('image_storage_location');
			$this->icon_manager = new IconManager('glyph');
			$this->gallery_images = new GalleryImages();
	 	}
		
		private function generate_pages(){
			$pages = array();
			$per_page = $this->across * $this->down;
			$thumbs_on_page = array();
			$all_images = $this->gallery_images->get_all_images();
			
			if($all_images == false){
				return false;
			}
			for($j = 0; $j < count($all_images)-1; $j++){
				//echo "j: $j<hr/>";
				//echo "<b>thumbs_on_page COUNT " . count($thumbs_on_page) ."</b><br/>";
				$image = new Image($all_images[$j]['fullsize_file_name'],$all_images[$j]['pagesize_file_name'],$all_images[$j]['thumbnail_file_name']);
				$image->id = $all_images[$j]['id'];
				//echo "thumb url: $thumb_url<br/>";
				$images_on_page[] = $image; 
				if(count($images_on_page) == $per_page){
					//echo "Per page reached thumbs on page reset<br/>";
					$pages[] = $images_on_page;
					$images_on_page = null;
				}else if(count($images_on_page) != 0 && $j == count($all_images)-2){
					$pages[] = $images_on_page;
				}
				
			}
			return $pages;
			
			
		}

		public function t_render(){
			$pages = $this->generate_pages();
				print_r($pages);
			}
		
		public function generate_page($page_number){
			$pages = $this->generate_pages();
			//In the event that there are no pages
			if($pages == false){
				return "<p>Gallery is empty!</p>";
			}
			$page = '';
			if($page_number != 0){
				//back button
				$previous_page_no = $page_number-1;
				$back = "{$_SERVER['PHP_SELF']}?action=gallery_home&page={$previous_page_no}";
				$page .= "<a href='{$back}'><img alt='Back' src='{$this->icon_manager->previous_page}'/></a>";
			}
			
			$img_no = 0;
			$page .= "<table>";		
			for($i = 0; $i < $this->down; $i++){
				$page.= "<tr>";
				for($j = 0; $j < $this->across; $j++){
					if(!isset($pages[$page_number][$img_no])){
						$img = '';
						$link = '';
					}else{
						$img = $this->image_storage_location . basename($pages[$page_number][$img_no]->thumbnail_loc);
						$link = "{$_SERVER['PHP_SELF']}?action=view&id={$pages[$page_number][$img_no]->id}&view_mode=pagesize&from_thumbs_page={$page_number}";
						$page .= "<td><a class='thumbnail' href='{$link}'><img alt='{$i},{$j}' src='{$img}'/></a></td>";
					}
					
					$img_no++;
				}
				$page .= "</tr>";
			}
			$page .= "</table>";
			if($page_number != count($pages)-1){
				//forward button
				$next_page_no = $page_number+1;
				$forward = "{$_SERVER['PHP_SELF']}?action=gallery_home&page={$next_page_no}";
				$page .= "<a href='{$forward}'><img alt='Forward' src='{$this->icon_manager->next_page}'/></a>";
			}
			return $page;
		}
	 }
?>	
