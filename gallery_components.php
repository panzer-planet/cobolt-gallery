<?php
	/**
	 * GalleryComponents
	 *
	 * Similar to a controller, this class contains the functionality (verbs) of the gallery.
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
	require_once 'lib/upload_handler.php';
	require_once 'lib/thumbs_creator.php';
	require_once 'gallery_images.php';
	require_once 'lib/thumbs_viewer.php';
	require_once 'settings/icon_manager.php';
	
	class GalleryComponents {
		//database and file handler classes
		private $gallery_settings_manager, $upload_handler, $gallery_images, $thumbs_viewer, $icon_manager, $thumbs_creator;
		//Where the images are stored
		private $images_storage_location, $images_url_base;
		
		function __construct() {
			//We initialise GallerySettingsManager so we can get and set these easily
			$this -> gallery_settings_manager = new GallerySettingsManager();
			//We initialise UploadHandler so we can easily upload files
			$this -> upload_handler = new UploadHandler();
			//Set where we want to store the images
			$this -> images_url_base = 'gallery_images/';//Not sure this is safe
			$this -> images_storage_location = $this->images_url_base;
			
			$this -> gallery_images = new GalleryImages();
			$this -> thumbs_viewer = new ThumbsViewer();
			$this -> thumbs_creator = new ThumbsCreator();
			$this -> icon_manager = new IconManager('glyph');//icon set should be stored in db
                        $this -> thumbs_creator = new ThumbsCreator();
		}
	
		//The show functions create the content for the renderer
		function show_home($page_number) {
			if($page_number == null){
				$page_number = 0;
			}
			return $this->thumbs_viewer->generate_page($page_number);// . " " . $this->gallery_images->get_next_image_by_current(67);
		}
		
		//Show the user an image in a mode
		function show_image($image_id, $view_mode) {
			$row = $this->gallery_images->get_image_by_id($image_id);
			if(isset($row)){
				switch($view_mode){
					case 'fullsize':
						//DEPRECATED
					break;
					/* Displays when the image is view_mode is pagesize which is the state
					 * after a thumbnail is clicked
					 */
					case 'pagesize':
						//URL for full sized image
						$fullsize_url = $this->images_url_base . $row['fullsize_file_name'];
						//Pagesize url
						$pagesize_url = $this->images_url_base . $row['pagesize_file_name'];
						//The page of thumbnails we came from. 0 if unknown.
						$from_page = 0;
						if(isset($_GET['from_thumbs_page'])){
							$from_page = $_GET['from_thumbs_page'];
						}
						//Back to thumbs link
						$link_back = "<a title='Back' class='icon' href='{$_SERVER['PHP_SELF']}?action=gallery_home&page={$from_page}'><img alt='Back' src='{$this->icon_manager->view_thumbnails}'/></a>";
						
						// NEXT / PREVIOUS links
						$prev_id = $this->gallery_images->get_previous_id_by_current($image_id);
						$next_id = $this->gallery_images->get_next_id_by_current($image_id);
						
						$prev_icon = $this->icon_manager->previous_arrow;
						$next_icon = $this->icon_manager->next_arrow;
						//Ensure we don't show a next or previous button on the last or first image
						if(!$prev_id){
							$prev_link = '';
						}else{
							$prev_link = "<a class='icon' href='{$_SERVER['PHP_SELF']}?action=view&id={$prev_id}&view_mode=pagesize&from_thumbs_page={$from_page}'><img title='Previous' src='{$prev_icon}'/></a>";
						}
						if(!$next_id){
							$next_link = '';
						}else{
							$next_link = "<a class ='icon' href='{$_SERVER['PHP_SELF']}?action=view&id={$next_id}&view_mode=pagesize&from_thumbs_page={$from_page}'><img title='Next' src='{$next_icon}'/></a>";
						}
						//The layout
						return "<div>
								{$prev_link} {$link_back} {$next_link}<br/>
								<a href='{$fullsize_url}'><img src='{$pagesize_url}'/></a><br/>
								{$prev_link} {$link_back} {$next_link}<br/>
							</div>
					";
					//print_r($_SERVER);
						break;
				}
			}else{
				return "error not found";
			}
		}
	
		function show_admin($loggedin) {
			//Get the latest values of the settings from the database
			$x_thumbs = $this -> gallery_settings_manager -> get_setting('x_thumbs');
			$y_thumbs = $this -> gallery_settings_manager -> get_setting('y_thumbs');
			$max_file_size = $this -> gallery_settings_manager -> get_setting('max_file_size');
			$max_file_size_kb = $max_file_size/1024;
			$thumbs_width = $this -> gallery_settings_manager -> get_setting('thumbs_width');
			$pagesize_width = $this -> gallery_settings_manager -> get_setting('pagesize_width');
			$content = '';
			if ($loggedin == true) {
				//We can show the admin settings
				$x_thumbs_options = '';
				//We are hard coding max to 12 for now
				for($i = 1; $i<=12;$i++){
					if($i == $x_thumbs){
						$x_thumbs_options .= "<option selected='selected'>{$i}</option>";
					}else{
						$x_thumbs_options .= "<option>{$i}</option>";
					}
				}
				$y_thumbs_options = '';
				//We are hard coding max to 12 for now
				for($i = 1; $i<=12;$i++){
					if($i == $y_thumbs){
						$y_thumbs_options .= "<option selected='selected'>{$i}</option>";
					}else{
						$y_thumbs_options .= "<option>{$i}</option>";
					}
				}
				$js_toggle = "javascript:toggleSettings(\"settings\");toggleValue(\"toggleSettings\");";
				$content = "
				<div id='cobolt-gallery_admin' name='adminDiv'>
					<fieldset id='imageUpload'>
						<legend>Add Image to Gallery</legend>
						<form name='upload_form' enctype='multipart/form-data' action='{$_SERVER['PHP_SELF']}' method='POST'>
							<input type='hidden' name='action' value='upload'/>
							<input type='hidden' name='MAX_FILE_SIZE' value='{$max_file_size}'/>
							<table>
								<tr>
									<td>Choose file</td><td> <input type='file' name='filename' id='filename'/></td>
								</tr>
							</table>
							<input style='float: right' type='submit' value='Upload now'/>
						</form>
					</fieldset>
					
					<input name='toggleSettings' id='toggleSettings' type='button' value='Show Advanced' onclick='{$js_toggle}'/>
					
					<fieldset id='settings'>
						<legend>Advanced Settings</legend>
						<form name='settings_form' type='submit' action='{$_SERVER['PHP_SELF']}' method='get'>
							<input type='hidden' name='action' value='save_settings'/>
							<table>
								<tr>
									<th>Layout settings</th>
								</tr>
								<tr>
									<td><i>Thumbnail grid: </i></td>
									<td>
									Width: 
										<select name='x_thumbs'>".
										$x_thumbs_options
										."</select>
									Height:
										<select name='y_thumbs'>".
											$y_thumbs_options
										. "</select>
									</td>
								</tr>
								<tr>
									<td><i>Thumbnail width (px):</i></td>
									<td>
										<input type='text' name='thumbs_width' value='{$thumbs_width}'/>
									</td> 
								</tr>
								<tr>
									<td><i>Pagesize width (px):</i></td>
									<td>
										<input type='text' name='pagesize_width' value='{$pagesize_width}'/>
									</td>
								<tr>
									<th>Upload settings</th>
								</tr>
								<tr>
									<td><i>Max upload size (KB): </i></td>
									<td><input type='text' name='max_file_size' value='{$max_file_size_kb}'/></td>
								</tr>
							</table>
							<input style='float:right' type='submit' value='Save settings'/>
						</form>
					</fieldset>
				</div>
				";
				return $content;
			} else {
				//Request that user logs in to admin view
				$content = "
					<div id='gallery_login_form'>
						<fieldset><legend>Administrator login</legend>
							<form name='login' action='{$_SERVER['PHP_SELF']}' method='post'>
								<input type='hidden' name='action' value='login'/> 
								<table>
									<tr>
										<td>Enter the password</td>
									</tr>
									<tr>
										<td>
											<input title='Enter the password' name='password' type='password'/>
										</td>
									</tr>
									<tr>
										<td>
											<input type='submit' value='Login' />
										</td>
									</tr>
								</table>
							</form>
						</fieldset>
					</div>";
			}
			return $content;
		}
	
		function upload($uploaded_file) {
			if(!$this->upload_handler->set_allowed_types(array('image'))){
				$this->upload_handler->show_error();
				return false;
			}else{
				$details = $this->upload_handler->save_file($uploaded_file, $this->images_storage_location);
				echo $details['file_loc'];exit;
				if(!isset($details) || !isset($details['file_loc'])){
					 $this->upload_handler->show_error();
					 return false; 
				}
				else{
					//We must store the info in the db
					if($details['file_type'] == 'zip'){
						//TODO unzip
					}else{
						if($refactored = $this->thumbs_creator->makeminimes($details['file_loc'],
																	$this->images_storage_location,
																	$this->gallery_settings_manager->get_setting('thumbs_width'),
																	$this->gallery_settings_manager->get_setting('pagesize_width'))){
							$this->gallery_images = new GalleryImages();
							if($this->gallery_images->add_image($details['file_loc'], $refactored['pagesize_loc'], $refactored['thumbnail_loc'])){
								return true;
							} else {
								return false;
							}
							
						} else {
							echo 'Error refactoring image';
							return false;
						}
					}
					//return true;	
				}
			}
		}
		
		/**
		 * Creates the header for cobolt-gallery_header id and returns to the view
		 * @param string action: what action is being called during this header
		 * @param string message: the flash message for cobolt_gallery_message
		 */
		function make_header($action,$message){
			$header_html = "<div id='cobolt-gallery_header'>";
			if($action == 'admin'){
				if($_SESSION['loggedin'] == 'true'){
					$header_html .= "<a class='gallery_logout_button' href='" . $_SERVER['PHP_SELF'] . "?action=logout'><img title='Logout' alt='Logout' src='{$this->icon_manager->log_out}'/></a>";
				}
				$header_html .= "<a class='icon' href='{$_SERVER['PHP_SELF']}?action=gallery_home'><img title='View gallery' alt='View Thumbnails' src='{$this->icon_manager->view_thumbnails}'/></a>";
			}else{
				$header_html .= "<a class='icon' href='{$_SERVER['PHP_SELF']}?action=admin'><img title='Login' alt='Admin' src='{$this->icon_manager->admin_button}'/></a>";
			}
			$header_html .= "<span class='cobolt-gallery_message'>{$message}</span><hr/>";
			$header_html .= "</div>";
			return $header_html;
		}
		
		function save_settings() {
			//Reverse and pop to remove the action. Now we only have settings in the array
			$changed = 0;
			$r_get = array_reverse($_GET);
			array_pop($r_get);
			//print_r($r_get);
			foreach ($r_get as $setting => $value) {
				if($setting == 'max_file_size'){
					if($this -> gallery_settings_manager -> change_setting('max_file_size', $value*1024)){
						$changed++;
					}
				}else{
					if($this -> gallery_settings_manager -> change_setting($setting, $value)){
						$changed++;	
					}
				}
			}
			return $changed;
		}
	}
?>
